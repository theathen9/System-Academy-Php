<?php

namespace App\Repositories;

use App\Models\Payment;
use App\Core\Database;

class PaymentRepository
{
    public function createPayment(array $data)
    {
        return Payment::create($data);
    }

    public function totalRevenue()
    {
        $pdo = Database::connection();

        $stmt = $pdo->query("SELECT SUM(amount) as total FROM tblPayments");

        return (float) $stmt->fetch()['total'];
    }

    public function getByStudent(int $studentId)
    {
        return Payment::query()
            ->where('student_id', '=', $studentId)
            ->get();
    }
}