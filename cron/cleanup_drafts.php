<?php

require_once __DIR__ . '/../config/bootstrap.php';

echo "Cleanup started...\n";

$conn->begin_transaction();

try {

    // 1. Delete payments
    $conn->query("
        DELETE p
        FROM tblPayments p
        JOIN tblInvoices i ON p.invoice_id = i.invoice_id
        JOIN tblStudents s ON i.student_id = s.student_id
        WHERE s.status = 'draft'
        AND s.created_at < NOW() - INTERVAL 24 HOUR
    ");

    // 2. Delete invoice items
    $conn->query("
        DELETE ii
        FROM tblInvoiceItems ii
        JOIN tblInvoices i ON ii.invoice_id = i.invoice_id
        JOIN tblStudents s ON i.student_id = s.student_id
        WHERE s.status = 'draft'
        AND s.created_at < NOW() - INTERVAL 24 HOUR
    ");

    // 3. Delete invoices
    $conn->query("
        DELETE i
        FROM tblInvoices i
        JOIN tblStudents s ON i.student_id = s.student_id
        WHERE s.status = 'draft'
        AND s.created_at < NOW() - INTERVAL 24 HOUR
    ");

    // 4. Delete enrollments
    $conn->query("
        DELETE e
        FROM tblEnrollments e
        JOIN tblStudents s ON e.student_id = s.student_id
        WHERE s.status = 'draft'
        AND s.created_at < NOW() - INTERVAL 24 HOUR
    ");

    // 5. Delete students
    $conn->query("
        DELETE FROM tblStudents
        WHERE status = 'draft'
        AND created_at < NOW() - INTERVAL 24 HOUR
    ");

    $conn->commit();

    echo "Cleanup completed";

} catch (Exception $e) {

    $conn->rollback();

    echo "Cleanup failed: " . $e->getMessage();
}