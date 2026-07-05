<?php

namespace App\Repositories;

use App\Models\Student;

class StudentRepository
{
    public function all()
    {
        return Student::all();
    }

    public function find(int $id)
    {
        return Student::find($id);
    }

    public function create(array $data)
    {
        return Student::create($data);
    }

    public function update(int $id, array $data)
    {
        return Student::updateById($id, $data);
    }

    public function delete(int $id)
    {
        return Student::delete($id);
    }

    public function count()
    {
        return Student::query()->count();
    }

    public function search(string $keyword)
    {
        return Student::query()
            ->where('name', 'LIKE', "%$keyword%")
            ->get();
    }
}