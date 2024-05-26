<?php
$csvFile = 'recordings.csv';
$latestOnly = isset($_GET['latest']) && $_GET['latest'] == '1';

if (file_exists($csvFile)) {
    $fileHandle = fopen($csvFile, 'r');
    if ($fileHandle) {
        $html = '';
        $lastRow = null;

        while (($row = fgetcsv($fileHandle)) !== false) {
            $lastRow = $row;
            if (!$latestOnly) {
                $name = htmlspecialchars($row[0]);
                $filePath = htmlspecialchars($row[1]);
                $html .= "<li><strong>$name:</strong><br><audio controls><source src='$filePath' type='audio/mp3'>Your browser does not support the audio element.</audio></li>";
            }
        }

        if ($latestOnly && $lastRow) {
            $name = htmlspecialchars($lastRow[0]);
            $filePath = htmlspecialchars($lastRow[1]);
            $html .= "<li><strong>$name:</strong><br><audio controls><source src='$filePath' type='audio/mp3'>Your browser does not support the audio element.</audio></li>";
        }

        fclose($fileHandle);
        echo $html;
    } else {
        echo 'Failed to open CSV file';
    }
} else {
    echo 'No recordings found';
}
?>
