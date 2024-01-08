<?php
require_once './database/DatabaseConnection.php';

class DataModel extends DatabaseConnection {
    private function prepareAndExecute($query, $queryParams = [], $types = "") {



        $stmt = $this->connection->prepare($query);


        if (!empty($queryParams)) {
            $types = $types ?: str_repeat("s", count($queryParams));
            $stmt->bind_param($types, ...$queryParams);
        }
        $stmt->execute();
        return $stmt;
    }

    public function fetchData($limit, $offset) {
        $query = "SELECT * FROM users LIMIT ?, ?";
        $stmt = $this->prepareAndExecute($query, [$limit, $offset], "ii");
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function insertData($data) {
        // Batch size
        $batchSize = 100;
        // Skip first row if it's header
        $start = (isset($data[0]) && is_array($data[0]) && $data[0][0] === 'category') ? 1 : 0;
        for ($i = $start; $i < count($data); $i += $batchSize) {
            $batchData = array_slice($data, $i, $batchSize);
            $this->insertBatch($batchData);
        }
    }

    private function insertBatch($batchData) {
        $placeholders = [];
        $values = [];
        foreach ($batchData as $row) {
            $placeholders[] = "(?, ?, ?, ?, ?, ?)";
            $values = array_merge($values, array_values($row));
        }
        $stmt = $this->prepareAndExecute("INSERT INTO users (category, firstname, lastname, email, gender, birthDate) VALUES " . implode(', ', $placeholders), $values, str_repeat("ssssss", count($batchData)));
    }

    public function fetchDataWithFilters($limit, $offset, $filters) {
        $queryParams = $this->constructFilterParams($filters);
        array_push($queryParams, $offset, $limit); // Adding pagination parameters
        $query = "SELECT * FROM users WHERE 1=1" . $this->constructFilterQuery($filters) . " LIMIT ?, ?";
        return $this->prepareAndExecute($query, $queryParams, str_repeat("s", count($queryParams) - 2) . "ii")->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function countFilteredData($filters) {
        $queryParams = $this->constructFilterParams($filters);
        $query = "SELECT COUNT(*) FROM users WHERE 1=1" . $this->constructFilterQuery($filters);
        return $this->prepareAndExecute($query, $queryParams)->get_result()->fetch_row()[0];
    }

    private function constructFilterQuery($filters) {
        $query = "";
        if (!empty($filters['category'])) { $query .= " AND category = ?"; }
        if (!empty($filters['gender'])) { $query .= " AND gender = ?"; }
        if (!empty($filters['birthDate'])) { $query .= " AND birthDate = ?"; }
        if (!empty($filters['age'])) { $query .= " AND TIMESTAMPDIFF(YEAR, birthDate, CURDATE()) = ?"; }
        if (!empty($filters['ageRange'])) { $query .= " AND TIMESTAMPDIFF(YEAR, birthDate, CURDATE()) BETWEEN ? AND ?"; }
        return $query;
    }

    private function constructFilterParams($filters) {
        $queryParams = [];
        if (!empty($filters['category'])) { $queryParams[] = $filters['category']; }
        if (!empty($filters['gender'])) { $queryParams[] = $filters['gender']; }
        if (!empty($filters['birthDate'])) { $queryParams[] = $filters['birthDate']; }
        if (!empty($filters['age'])) { $queryParams[] = $filters['age']; }
        if (!empty($filters['ageRange'])) {
            list($ageStart, $ageEnd) = explode('-', $filters['ageRange']);
            $queryParams[] = $ageStart;
            $queryParams[] = $ageEnd;
        }
        return $queryParams;
    }

    public function fetchFilteredDataForExport($filters) {
        $queryParams = $this->constructFilterParams($filters);
        $query = "SELECT * FROM users WHERE 1=1" . $this->constructFilterQuery($filters);
        return $this->prepareAndExecute($query, $queryParams)->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
