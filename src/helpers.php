<?php
// helpers.php

function redirect($url) {
    header("Location: $url");
    exit();
}

function sanitize($data) {
    return htmlspecialchars(strip_tags($data));
}

function uploadImage($file) {
    $targetDir = __DIR__ . '/../public/uploads/';
    $targetFile = $targetDir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
    // Check if image file is a actual image or fake image
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        return "File is not an image.";
    }

    // Check file size (limit to 5MB)
    if ($file["size"] > 5000000) {
        return "Sorry, your file is too large.";
    }

    // Allow certain file formats
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        return "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    }

    // Try to upload file
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return $targetFile;
    } else {
        return "Sorry, there was an error uploading your file.";
    }
}

function deleteImage($filePath) {
    if (file_exists($filePath)) {
        unlink($filePath);
        return true;
    }
    return false;
}

function getImages() {
    global $db; // Assuming $db is your database connection
    $stmt = $db->prepare("SELECT * FROM images");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>