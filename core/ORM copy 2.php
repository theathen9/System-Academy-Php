<?php

class ORM
{
    private $db;
    private $table;

    private $select = "*";
    private $joins = [];
    private $where = [];
    private $params = [];
    private $types = "";
    private $order = "";
    private $limit = "";

    // role system
    private $role;
    private $userId;

    // cache
    private $cacheEnabled = false;
    private $cacheTime = 60;
    private $cacheKey = null;

    public function __construct($db, $table)
    {
        $this->db = $db;
        $this->table = $table;
    }

    // =====================
    // SELECT
    // =====================
    public function select($columns)
    {
        $this->select = $columns;
        return $this;
    }

    // =====================
    // WHERE
    // =====================
    public function where($column, $operator, $value)
    {
        $this->where[] = "$column $operator ?";
        $this->params[] = $value;
        $this->types .= $this->type($value);
        return $this;
    }

    public function whereLike($column, $value)
    {
        $this->where[] = "$column LIKE ?";
        $this->params[] = "%$value%";
        $this->types .= "s";
        return $this;
    }

    // =====================
    // JOIN
    // =====================
    public function join($table, $first, $operator, $second, $type = "INNER")
    {
        $this->joins[] = "$type JOIN $table ON $first $operator $second";
        return $this;
    }

    // =====================
    // ORDER / LIMIT
    // =====================
    public function orderBy($column, $dir = "DESC")
    {
        $this->order = " ORDER BY $column $dir";
        return $this;
    }

    public function limit($limit, $offset = 0)
    {
        $this->limit = " LIMIT $limit OFFSET $offset";
        return $this;
    }

    // =====================
    // ROLE SYSTEM (SAFE)
    // =====================
    public function setRole($role, $userId = null)
    {
        $this->role = $role;
        $this->userId = $userId;
        return $this;
    }

    private function applyRole()
    {
        if (!$this->role || !$this->userId) return;

        $map = [
            "teacher" => "teacher_id",
            "student" => "student_id",
            "admin"   => "employee_id",
            "account" => "employee_id"
        ];

        if (isset($map[$this->role])) {
            $this->where($map[$this->role], "=", $this->userId);
        }
    }

    // =====================
    // CACHE
    // =====================
    public function cache($seconds = 60, $key = null)
    {
        $this->cacheEnabled = true;
        $this->cacheTime = $seconds;
        $this->cacheKey = $key;
        return $this;
    }

    private function cachePath()
    {
        $path = __DIR__ . "/../storage/cache/";
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        return $path;
    }

    private function cacheKey($sql)
    {
        return md5($sql . serialize($this->params) . $this->types . $this->role . $this->userId);
    }

    // =====================
    // GET
    // =====================
    public function get()
    {
        $this->applyRole();

        $sql = "SELECT {$this->select} FROM {$this->table}";

        if ($this->joins) {
            $sql .= " " . implode(" ", $this->joins);
        }

        if ($this->where) {
            $sql .= " WHERE " . implode(" AND ", $this->where);
        }

        $sql .= $this->order;
        $sql .= $this->limit;

        // cache read
        if ($this->cacheEnabled) {
            $key = $this->cacheKey($sql);
            $file = $this->cachePath() . $key . ".json";

            if (file_exists($file) && (time() - filemtime($file) < $this->cacheTime)) {
                return json_decode(file_get_contents($file), true);
            }
        }

        // execute
        $result = $this->db->select($sql, $this->types, $this->params);
        $rows = $result->fetch_all(MYSQLI_ASSOC);

        // cache write
        if ($this->cacheEnabled) {
            $key = $this->cacheKey($sql);
            $file = $this->cachePath() . $key . ".json";
            file_put_contents($file, json_encode($rows));
        }

        $this->reset();

        return $rows;
    }

    // =====================
    // FIRST ROW (FIXED)
    // =====================
    public function first()
    {
        $clone = clone $this;
        $clone->limit(1);
        $result = $clone->get();
        return $result[0] ?? null;
    }

    // =====================
    // INSERT
    // =====================
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

        $this->reset();
        return $this->db->insert($sql, $types, $values);
    }

    // =====================
    // UPDATE
    // =====================
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

    // =====================
    // DELETE
    // =====================
    public function delete()
    {
        $sql = "DELETE FROM {$this->table}";

        if ($this->where) {
            $sql .= " WHERE " . implode(" AND ", $this->where);
        }

        $this->reset();
        return $this->db->delete($sql, $this->types, $this->params);
    }

    // =====================
    // SEARCH
    // =====================
    public function search($value, $columns)
    {
        if (!$value || !$columns) return $this;

        $group = [];

        foreach ($columns as $col) {
            $group[] = "$col LIKE ?";
            $this->params[] = "%$value%";
            $this->types .= "s";
        }

        $this->where[] = "(" . implode(" OR ", $group) . ")";
        return $this;
    }

    // =====================
    // TYPE DETECTION
    // =====================
    private function type($val)
    {
        if (is_int($val)) return "i";
        if (is_float($val)) return "d";
        return "s";
    }

    // =====================
    // RESET (SAFE)
    // =====================
    public function reset()
    {
        $this->select = "*";
        $this->joins = [];
        $this->where = [];
        $this->params = [];
        $this->types = "";
        $this->order = "";
        $this->limit = "";

        $this->cacheEnabled = false;
        $this->cacheKey = null;

        return $this;
    }
}