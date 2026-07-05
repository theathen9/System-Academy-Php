<?php

namespace App\Repositories;

use App\Models\Employee;

class EmployeeRepository
{
    public function all()
    {
        return Employee::all();
    }

    public function create(array $data)
    {
        return Employee::create($data);
    }

    public function update(int $id, array $data)
    {
        return Employee::updateById($id, $data);
    }

    public function delete(int $id)
    {
        return Employee::delete($id);
    }

    public function totalSalary()
    {
        $pdo = \App\Core\Database::connection();

        $stmt = $pdo->query("SELECT SUM(salary) as total FROM tblEmployees");

        return (float) $stmt->fetch()['total'];
    }
}