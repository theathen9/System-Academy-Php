<?php

namespace App\Core;

class QueryBuilder
{
    private string $table;
    private array $wheres = [];
    private array $bindings = [];
    private string $select = '*';

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function select(string $columns = '*'): self
    {
        $this->select = $columns;
        return $this;
    }

    public function where(string $column, string $operator, mixed $value): self
    {
        $this->wheres[] = "$column $operator ?";
        $this->bindings[] = $value;
        return $this;
    }

    public function get(): array
    {
        $sql = "SELECT {$this->select} FROM {$this->table}";

        if ($this->wheres) {
            $sql .= " WHERE " . implode(" AND ", $this->wheres);
        }

        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($this->bindings);

        return $stmt->fetchAll();
    }

    public function first(): ?array
    {
        $data = $this->get();
        return $data[0] ?? null;
    }

    public function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";

        if ($this->wheres) {
            $sql .= " WHERE " . implode(" AND ", $this->wheres);
        }

        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($this->bindings);

        return (int) $stmt->fetch()['total'];
    }
}