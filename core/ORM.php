<?php

class ORM
{
    private $db;
    private $table;

    private $select = "*";
    private $joins = [];
    private $where = [];
    private $orWhere = [];
    private $rawWhere = [];
    private $params = [];
    private $types = "";
    private $order = "";
    private $group = "";
    private $limit = "";
    private $offset = "";

    private $relations = [];

    private $primaryKey;

    public function __construct($db, $table, $primaryKey = "id")
    {
        $this->db = $db;
        $this->table = $table;
        $this->primaryKey = $primaryKey;
    }

    // =========================
    // SELECT
    // =========================
    public function select($columns = "*")
    {
        $this->select = $columns;
        return $this;
    }


    public function count()
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";

        if ($this->where) {
            $sql .= " WHERE " . implode(" AND ", $this->where);
        }

        $result = $this->db->select($sql, $this->types, $this->params);

        if (!$result) {
            $this->reset();
            return 0;
        }

        $row = $result->fetch_assoc();

        $this->reset();

        return (int)$row['total'];
    }

    // =========================
    // WHERE
    // =========================
    public function where($column, $operator, $value)
    {
        $this->where[] = "$column $operator ?";
        $this->bind($value);
        return $this;
    }

    public function orWhere($column, $operator, $value)
    {
        $this->orWhere[] = "$column $operator ?";
        $this->bind($value);
        return $this;
    }

    public function whereRaw($condition)
    {
        $this->rawWhere[] = $condition;
        return $this;
    }

    // =========================
    // JOIN
    // =========================
    // public function join($table, $first, $operator, $second, $type = "INNER")
    // {
    //     $this->joins[] = "$type JOIN $table ON $first $operator $second";
    //     return $this;
    // }

    public function join($table, $condition, $type = "INNER")
    {
        $this->joins[] = "$type JOIN $table ON $condition";
        return $this;
    }

    public function from($table)
    {
        $this->table = $table;
        return $this;
    }

    // =========================
    // ORDER / GROUP
    // =========================
    public function orderBy($column, $dir = "ASC")
    {
        $this->order = " ORDER BY $column $dir";
        return $this;
    }

    public function groupBy($column)
    {
        $this->group = " GROUP BY $column";
        return $this;
    }

    // =========================
    // LIMIT / OFFSET
    // =========================
    public function limit($limit)
    {
        $this->limit = " LIMIT $limit";
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = " OFFSET $offset";
        return $this;
    }

    // =========================
    // RELATIONSHIP (BASIC)
    // =========================
    public function with($relation)
    {
        $this->relations[] = $relation;
        return $this;
    }

    // =========================
    // BUILD SQL
    // =========================
    private function buildSQL()
    {
        $sql = "SELECT {$this->select} FROM {$this->table}";

        if ($this->joins) {
            $sql .= " " . implode(" ", $this->joins);
        }

        if ($this->where || $this->orWhere) {
            $conditions = [];

            if ($this->where) {
                $conditions[] = implode(" AND ", $this->where);
            }

            if ($this->orWhere) {
                $conditions[] = "(" . implode(" OR ", $this->orWhere) . ")";
            }

            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        if ($this->group) {
            $sql .= $this->group;
        }

        if ($this->order) {
            $sql .= $this->order;
        }

        if ($this->limit) {
            $sql .= $this->limit;
        }

        if ($this->offset) {
            $sql .= $this->offset;
        }

        return $sql;
    }

    // =========================
    // GET
    // =========================
    public function get()
    {
        $sql = $this->buildSQL();

        $result = $this->db->select($sql, $this->types, $this->params);

        if (!$result) {
            $this->reset();
            return [];
        }

        $rows = $result->fetch_all(MYSQLI_ASSOC);

        $this->reset();

        return $rows;
    }

    // =========================
    // FIRST
    // =========================
    public function first()
    {
        $data = $this->limit(1)->get();

        return !empty($data) ? $data[0] : null;
    }
    // =========================
    // increment
    // =========================
    public function increment($column, $amount = 1)
    {
        $sql = "UPDATE {$this->table} SET {$column} = {$column} + ?";

        if ($this->where) {
            $sql .= " WHERE " . implode(" AND ", $this->where);
        }

        $params = array_merge([$amount], $this->params);
        $types = $this->type($amount) . $this->types;

        $this->reset();

        return $this->db->update($sql, $types, $params);
    }

    // =========================
    // PAGINATION
    // =========================
    public function paginate($perPage, $page = 1)
    {
        $offset = ($page - 1) * $perPage;

        $data = $this->limit($perPage)
            ->offset($offset)
            ->get();

        return [
            "data" => $data,
            "page" => $page,
            "per_page" => $perPage
        ];
    }

    public function find($id)
    {
        $sql = "SELECT {$this->select} 
            FROM {$this->table} 
            WHERE {$this->primaryKey} = ? 
            LIMIT 1";

        $result = $this->db->select($sql, "i", [$id]);

        if (!$result) {
            $this->reset();
            return null;
        }

        $row = $result->fetch_assoc();

        $this->reset();

        return $row ?: null;
    }

    // =========================
    // INSERT
    // =========================
    public function insert($data)
    {
        $fields = array_keys($data);
        $placeholders = implode(",", array_fill(0, count($fields), "?"));

        $sql = "INSERT INTO {$this->table} (" . implode(",", $fields) . ")
                VALUES ($placeholders)";

        $types = "";
        $values = [];

        foreach ($data as $v) {
            $values[] = $v;
            $types .= $this->type($v);
        }

        return $this->db->insert($sql, $types, $values);
    }

    // =========================
    // UPDATE
    // =========================
    public function update($data)
    {
        $set = [];
        $params = [];
        $types = "";

        foreach ($data as $k => $v) {
            $set[] = "$k = ?";
            $params[] = $v;
            $types .= $this->type($v);
        }

        $sql = "UPDATE {$this->table} SET " . implode(",", $set);

        if ($this->where) {
            $sql .= " WHERE " . implode(" AND ", $this->where);
        }

        $params = array_merge($params, $this->params);
        $types .= $this->types;

        $this->reset();

        return $this->db->update($sql, $types, $params);
    }

    // =========================
    // DELETE
    // =========================
    public function delete()
    {
        $sql = "DELETE FROM {$this->table}";

        if ($this->where) {
            $sql .= " WHERE " . implode(" AND ", $this->where);
        }

        $types = $this->types;
        $params = $this->params;

        $this->reset();

        return $this->db->delete($sql, $types, $params);
    }

    // =========================
    // SEARCH
    // =========================
    public function search($value, $columns)
    {
        $group = [];

        foreach ($columns as $col) {
            $group[] = "$col LIKE ?";
            $this->bind("%$value%");
        }

        $this->where[] = "(" . implode(" OR ", $group) . ")";
        return $this;
    }

    // =========================
    // BIND HELPERS
    // =========================
    private function bind($value)
    {
        $this->params[] = $value;
        $this->types .= $this->type($value);
    }

    private function type($val)
    {
        if (is_int($val)) return "i";
        if (is_float($val)) return "d";
        return "s";
    }

    // =========================
    // DEBUG SQL (Laravel style)
    // =========================
    public function toSql()
    {
        return $this->buildSQL();
    }

    // =========================
    // RESET
    // =========================
    public function reset()
    {
        $this->select = "*";
        $this->joins = [];
        $this->where = [];
        $this->orWhere = [];
        $this->params = [];
        $this->types = "";
        $this->order = "";
        $this->group = "";
        $this->limit = "";
        $this->offset = "";
        $this->relations = [];

        return $this;
    }
}
