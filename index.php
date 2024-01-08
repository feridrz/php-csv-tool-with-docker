<?php
require_once 'controllers/DataController.php';
require_once 'utils/CSVReader.php';

$controller = new DataController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['csvFile'])) {
    if ($_FILES['csvFile']['error'] === UPLOAD_ERR_OK) {
        $fileType = mime_content_type($_FILES['csvFile']['tmp_name']);
        if ($fileType == 'text/csv') {
            $csvReader = new CSVReader();
            $data = $csvReader->readCSV($_FILES['csvFile']['tmp_name']);
            if(!empty($data)){
                $controller->insertData($data);
                echo "Data imported successfully!";
            } else {
                echo "No data found in the file.";
            }
        } else {
            echo "Invalid file type.";
        }
    } else {
        echo "Error during file upload: " . $_FILES['csvFile']['error'];
    }
}
if (isset($_GET['export'])) {
    $filters = [
        'category' => $_GET['category'] ?? null,
        'gender' => $_GET['gender'] ?? null,
        'birthDate' => $_GET['birthDate'] ?? null,
        'age' => $_GET['age'] ?? null,
        'ageRange' => $_GET['ageRange'] ?? null
    ];

    $controller->exportFilteredData($filters);
} else {
    // Handle filters
    $filters = [
        'category' => $_GET['category'] ?? null,
        'gender' => $_GET['gender'] ?? null,
        'birthDate' => $_GET['birthDate'] ?? null,
        'age' => $_GET['age'] ?? null,
        'ageRange' => $_GET['ageRange'] ?? null
    ];

    // Pagination
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    // Fetch and display data
    $controller->displayDataWithFilters($page, $filters);
}
