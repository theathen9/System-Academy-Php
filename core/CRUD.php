<?php
// ./core/CRUD.php

class CRUD
{
    private $db;
    private $table;
    private $primaryKey;

    public function __construct($db, $table, $primaryKey = 'id')
    {
        $this->db = $db;
        $this->table = $table;
        $this->primaryKey = $primaryKey;
    }

    // 📄 GET ALL (search + pagination)
    public function getAll($columns = "*", $where = "", $searchCols = [], $search = "", $limit = null, $offset = 0)
    {
        $sql = "SELECT $columns FROM {$this->table}";
        $params = [];
        $types = "";

        // 🔍 SEARCH
        if (!empty($search) && !empty($searchCols)) {
            $like = "%$search%";
            $conditions = [];

            foreach ($searchCols as $col) {
                $conditions[] = "$col LIKE ?";
                $params[] = $like;
                $types .= "s";
            }

            $sql .= " WHERE (" . implode(" OR ", $conditions) . ")";
        }

        // 📌 WHERE
        if (!empty($where)) {
            $sql .= (!empty($search) ? " AND " : " WHERE ") . $where;
        }

        // 📊 ORDER + PAGINATION
        $sql .= " ORDER BY {$this->primaryKey} DESC";

        if ($limit !== null) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
            $types .= "ii";
        }

        return $this->db->select($sql, $types, $params);
    }

    // 🔢 COUNT
    public function count($where = "", $searchCols = [], $search = "")
    {
        $sql = "SELECT COUNT(*) AS total FROM {$this->table}";
        $params = [];
        $types = "";

        if (!empty($search) && !empty($searchCols)) {
            $like = "%$search%";
            $conditions = [];

            foreach ($searchCols as $col) {
                $conditions[] = "$col LIKE ?";
                $params[] = $like;
                $types .= "s";
            }

            $sql .= " WHERE (" . implode(" OR ", $conditions) . ")";
        }

        if (!empty($where)) {
            $sql .= (!empty($search) ? " AND " : " WHERE ") . $where;
        }

        $row = $this->db->selectOne($sql, $types, $params);
        return $row['total'] ?? 0;
    }

    // 🔍 GET BY ID
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return $this->db->selectOne($sql, "i", [$id]);
    }

    // ➕ CREATE
    public function create($data)
    {
        $fields = array_keys($data);
        $placeholders = implode(",", array_fill(0, count($fields), "?"));

        $sql = "INSERT INTO {$this->table} (" . implode(",", $fields) . ")
                VALUES ($placeholders)";

        $types = "";
        $values = [];

        foreach ($data as $val) {
            $values[] = $val;

            if (is_int($val)) $types .= "i";
            elseif (is_float($val)) $types .= "d";
            else $types .= "s";
        }

        return $this->db->execute($sql, $types, $values);
    }

    // ✏️ UPDATE
    public function updateById($id, $data)
    {
        $set = [];
        $types = "";
        $params = [];

        foreach ($data as $key => $val) {
            $set[] = "$key = ?";
            $params[] = $val;

            if (is_int($val)) $types .= "i";
            elseif (is_float($val)) $types .= "d";
            else $types .= "s";
        }

        $params[] = $id;
        $types .= "i";

        $sql = "UPDATE {$this->table}
                SET " . implode(",", $set) . "
                WHERE {$this->primaryKey} = ?";

        return $this->db->execute($sql, $types, $params);
    }

    // ❌ DELETE
    public function deleteById($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return $this->db->execute($sql, "i", [$id]);
    }

    // 🔥 RAW QUERY (JOIN, REPORTS, COMPLEX SQL)
    public function query($sql, $types = "", $params = [])
    {
        return $this->db->select($sql, $types, $params);
    }
}