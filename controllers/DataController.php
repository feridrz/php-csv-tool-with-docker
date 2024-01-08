<?php
require_once 'models/DataModel.php';
require_once 'utils/DataExporter.php';

class DataController {
    private $model;

    public function __construct() {
        $this->model = new DataModel();
    }

    public function insertData($data) {
        $this->model->insertData($data);
    }

    public function displayDataWithFilters($page, $filters) {
        $limit = 100;
        $offset = ($page - 1) * $limit;

        $data = $this->model->fetchDataWithFilters($limit, $offset, $filters);
        $totalRecords = $this->model->countFilteredData($filters);
        $totalPages = ceil($totalRecords / $limit);
        include 'views/dataTableView.php';
    }

    public function exportFilteredData($filters) {
        $data = $this->model->fetchFilteredDataForExport($filters);
        $exporter = new DataExporter();
        $exporter->exportToCSV($data);
    }

}
?>
