<?php

// ./app/api/v1/generate_invoice.php

require_once __DIR__ . '/../../../config/bootstrap.php';
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../app/services/InvoicePdfService.php';

use App\Services\InvoicePdfService;

if (!isset($_COOKIE['refresh_token']) || empty($_COOKIE['refresh_token'])) {

    http_response_code(401);
    // echo json_encode(["error" => "Access token expired"]);
    exit;
}



// -----------------------------
// 1. Validate input
// -----------------------------
$invoiceId = $_GET['id'] ?? null;

if (!$invoiceId) {
    http_response_code(400);
    exit("Missing invoice ID");
}

// -----------------------------
// 2. Init ORM
// -----------------------------
$db = new DB($conn);

$invoiceCRUD      = new ORM($db, 'tblInvoices', 'invoice_id');
$studentCRUD      = new ORM($db, 'tblStudents', 'student_id');
$classCRUD        = new ORM($db, 'tblClasses', 'class_id');
$paymentCRUD      = new ORM($db, 'tblPayments', 'payment_id');
$invoiceItemCRUD  = new ORM($db, 'tblInvoiceItems', 'invoice_item_id');
$enrollmentCRUD   = new ORM($db, 'tblEnrollments', 'enrollment_id');

// -----------------------------
// 3. Fetch invoice
// -----------------------------
$invoice = $invoiceCRUD->find($invoiceId);

if (!$invoice) {
    http_response_code(404);
    exit("Invoice not found");
}

// -----------------------------
// 4. Fetch student
// -----------------------------
$student = $studentCRUD->find($invoice['student_id']);

if (!$student) {
    http_response_code(404);
    exit("Student not found");
}

// -----------------------------
// 5. Fetch invoice items
// -----------------------------
$invoiceItems = $invoiceItemCRUD
    ->where('invoice_id', '=', $invoiceId)
    ->get();

// -----------------------------
// 6. Build classes (FIXED LOGIC)
// invoice_items → enrollment → class
// -----------------------------
$classes = [];

foreach ($invoiceItems as $item) {

    $enroll = $enrollmentCRUD->find($item['enrollment_id']);
    if (!$enroll) continue;

    $class = $classCRUD
        ->select("
        tblClasses.class_code,
        tblClasses.class_name,
        CONCAT(t.first_name_kh, ' ', t.last_name_kh) AS teacher_name,
        CONCAT('Room ', tblClasses.room_id) AS room,
        CONCAT('Slot ', tblClasses.slot_id) AS slot
    ")
        ->join('tblEmployees t', 't.employee_id = tblClasses.teacher_id')
        ->where('tblClasses.class_id', '=', $enroll['class_id'])
        ->first();
    if (!$class) continue;

    $classes[] = [
        "name"    => $class['class_name'] ?? 'N/A',
        "teacher" => $class['teacher_name'] ?? 'N/A',
        "price"   => (float) $item['amount'] ?? 0
    ];
}

// -----------------------------
// 7. Fetch payments (SAFE SUM)
// -----------------------------
$payments = $paymentCRUD
    ->where('invoice_id', '=', $invoiceId)
    ->get();

$totalPaid = array_sum(array_column($payments, 'amount'));

// -----------------------------
// 8. Prepare payment object
// -----------------------------
$payment = [
    "total" => (float) $invoice['total_amount'],
    "paid"  => $totalPaid
];

// -----------------------------
// 9. Generate PDF
// -----------------------------
try {
    InvoicePdfService::generate(
        $student,
        $classes,
        $payment
    );
} catch (Exception $e) {
    http_response_code(500);
    exit("Failed to generate invoice: " . $e->getMessage());
}
