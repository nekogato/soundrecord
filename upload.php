<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['audio']) && $_FILES['audio']['error'] === UPLOAD_ERR_OK) {
        // Define the directory to save uploaded files
        $uploadDir = 'uploads/';
        
        // Ensure the uploads directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Generate a unique file name based on the timestamp
        $fileName = 'recording_' . time() . '.mp3';
        $uploadFilePath = $uploadDir . $fileName;
        
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['audio']['tmp_name'], $uploadFilePath)) {
            echo json_encode(['filePath' => $uploadFilePath]);
        } else {
            echo json_encode(['error' => 'Failed to move uploaded file']);
        }
    } else {
        echo json_encode(['error' => 'No file uploaded or upload error']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>
