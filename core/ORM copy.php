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

    // 🔐 ROLE SYSTEM
    private $role = null;
    private $userId = null;

    // ⚡ CACHE SYSTEM
    private $cacheEnabled = false;
    private $cacheTTL = 60;
    private $cacheKey = null;

    public function __construct($db, $table)
    {
        $this->db = $db;
        $this->table = $table;
    }

    // =========================
    // 📄 SELECT
    // =========================
    public function select($columns)
    {
        $this->select = $columns;
        return $this;
    }

    // =========================
    // 🔍 WHERE
    // =========================
    public function where($column, $operator, $value)
    {
        $this->where[] = "$column $operator ?";
        $this->params[] = $value;
        $this->types .= $this->detectType($value);
        return $this;
    }

    public function whereLike($column, $value)
    {
        $this->where[] = "$column LIKE ?";
        $this->params[] = "%$value%";
        $this->types .= "s";
        return $this;
    }

    // =========================
    // 🔗 JOIN
    // =========================
    public function join($table, $first, $operator, $second, $type = "INNER")
    {
        $this->joins[] = "$type JOIN $table ON $first $operator $second";
        return $this;
    }

    // =========================
    // 📊 ORDER / LIMIT
    // =========================
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

    // =========================
    // 🔐 ROLE SYSTEM
    // =========================
    public function setRole($role, $userId = null)
    {
        $this->role = $role;
        $this->userId = $userId;
        return $this;
    }

    private function applyRoleFilter()
    {
        if (!$this->role || !$this->userId) return;

        // SAFE: avoid duplicate filters
        switch ($this->role) {

            case "teacher":
                $this->where("teacher_id", "=", $this->userId);
                break;

            case "student":
                $this->where("student_id", "=", $this->userId);
                break;

            case "account":
            case "admin":
                $this->where("employee_id", "=", $this->userId);
                break;
        }
    }

    // =========================
    // ⚡ CACHE SYSTEM
    // =========================
    public function cache($ttl  = 60, $key = null)
    {
        $this->cacheEnabled = true;
        $this->cacheTTL = $ttl;
        $this->cacheKey = $key;
        return $this;
    }

    private function buildCacheKey($sql)
    {
        // FULL JOIN + PARAM SAFE CACHE KEY
        return md5(
            $sql .
                $this->types .
                serialize($this->params) .
                $this->role .
                $this->userId
        );
    }

    private function cachePath()
    {
        $path = __DIR__ . "/../storage/cache/";
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        return $path;
    }

    // =========================
    // 🔍 GET DATA
    // =========================
    public function get()
    {
        $this->applyRoleFilter();

        $sql = "SELECT {$this->select} FROM {$this->table}";

        if (!empty($this->joins)) {
            $sql .= " " . implode(" ", $this->joins);
        }

        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(" AND ", $this->where);
        }

        $sql .= $this->order;
        $sql .= $this->limit;

        // =========================
        // ⚡ CACHE READ
        // =========================
        if ($this->cacheEnabled) {

            $key = $this->cacheKey ?? $this->buildCacheKey($sql);
            $file = $this->cachePath() . $key . ".json";

            if (
                file_exists($file) &&
                (time() - filemtime($file) < $this->cacheTime)
            ) {

                return json_decode(file_get_contents($file), true);
            }
        }

        // =========================
        // DB EXECUTION
        // =========================
        $result = $this->db->select($sql, $this->types, $this->params);
        $rows = $result->fetch_all(MYSQLI_ASSOC);

        // =========================
        // CACHE WRITE
        // =========================
        if ($this->cacheEnabled) {

            $key = $this->cacheKey ?? $this->buildCacheKey($sql);
            $file = $this->cachePath() . $key . ".json";

            file_put_contents($file, json_encode($rows));
        }

        $this->reset(); // 🔥 AUTO CLEAN AFTER EVERY SELECT

        return $rows;
    }

    // =========================
    // 🔍 FIRST ROW
    // =========================
    public function first()
    {
        $clone = clone $this;
        $clone->limit(1);
        $result = $clone->get();
        return $result[0] ?? null;
    }

    // =========================
    // ➕ INSERT
    // =========================
    public function insert($data)
    {
        $fields = array_keys($data);
        $placeholders = implode(",", array_fill(0, count($fields), "?"));

        $sql = "INSERT INTO {$this->table} (" . implode(",", $fields) . ")
                VALUES ($placeholders)";

        $types = "";
        $values = [];

        foreach ($data as $val) {
            $values[] = $val;
            $types .= $this->detectType($val);
        }

        $result = $this->db->insert($sql, $types, $values);

        $this->reset();

        return $result;
    }

    // =========================
    // ✏️ UPDATE
    // =========================
    public function update($data)
    {
        $set = [];
        $setParams = [];
        $setTypes = "";

        foreach ($data as $key => $val) {
            $set[] = "$key = ?";
            $setParams[] = $val;
            $setTypes .= $this->detectType($val);
        }

        $sql = "UPDATE {$this->table} SET " . implode(",", $set);

        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(" AND ", $this->where);
        }

        $params = array_merge($setParams, $this->params);
        $types  = $setTypes . $this->types;

        $result = $this->db->update($sql, $types, $params);

        $this->reset(); // 🔥 IMPORTANT

        return $result;
    }

    // =========================
    // ❌ DELETE
    // =========================
    public function delete()
    {
        $sql = "DELETE FROM {$this->table}";

        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(" AND ", $this->where);
        }

        $result = $this->db->delete($sql, $this->types, $this->params);

        $this->reset();

        return $result;
    }

    // =========================
    // 🔍 SEARCH
    // =========================
    public function search($value, $columns)
    {
        if (!$value || !is_array($columns) || empty($columns)) {
            return $this;
        }

        $orGroup = [];
        $params = [];

        foreach ($columns as $col) {
            $orGroup[] = "$col LIKE ?";
            $params[] = "%$value%";
        }

        // wrap OR conditions properly
        $this->where[] = "(" . implode(" OR ", $orGroup) . ")";

        // merge params safely AFTER grouping
        foreach ($params as $p) {
            $this->params[] = $p;
            $this->types .= "s";
        }

        return $this;
    }

    // =========================
    // 🧠 TYPE DETECTION
    // =========================
    private function detectType($val)
    {
        if (is_int($val)) return "i";
        if (is_float($val)) return "d";
        return "s";
    }

    // =========================
    // 🔄 RESET (IMPORTANT FIX)
    // =========================
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
        $this->cacheTTL = 60;
        $this->cacheKey = null;

        return $this;
    }
}
