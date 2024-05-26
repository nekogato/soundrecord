<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $filePath = $_POST['filePath'];
    
    $csvFile = 'recordings.csv';
    $fileHandle = fopen($csvFile, 'a');
    
    if ($fileHandle) {
        fputcsv($fileHandle, [$name, $filePath]);
        fclose($fileHandle);
        echo 'Info saved successfully';
    } else {
        echo 'Failed to open CSV file';
    }
} else {
    echo 'Invalid request method';
}
?>
