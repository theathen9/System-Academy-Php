<?php
function resizeImage($source, $destination, $maxWidth = 300) {

    list($width, $height) = getimagesize($source);

    $ratio = $width / $height;
    $newWidth = $maxWidth;
    $newHeight = $maxWidth / $ratio;

    $srcImage = imagecreatefromstring(file_get_contents($source));
    $newImage = imagecreatetruecolor($newWidth, $newHeight);

    imagecopyresampled(
        $newImage, $srcImage,
        0, 0, 0, 0,
        $newWidth, $newHeight,
        $width, $height
    );

    imagejpeg($newImage, $destination, 85);

    imagedestroy($srcImage, $newImage);
}

function uploadProfileImage($file, $folder = 'employees')
{
    if ($file['error'] !== 0) {
        throw new Exception("Upload failed.");
    }

    // 1️⃣ Size limit (2MB)
    if ($file['size'] > 2 * 1024 * 1024) {
        throw new Exception("File too large.");
    }

    // 2️⃣ Allowed types
    $allowed = ['image/jpeg', 'image/png', 'image/webp'];
    $mime = mime_content_type($file['tmp_name']);

    if (!in_array($mime, $allowed)) {
        throw new Exception("Invalid image type.");
    }

    // 3️⃣ Extension
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);

    // 4️⃣ Secure filename
    $filename = uniqid($folder . '_', true) . '.' . $ext;

    $uploadDir = __DIR__ . "/../public/uploads/$folder/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $destination = $uploadDir . $filename;

    // Resize before saving
    resizeImage($file['tmp_name'], $destination);

    return "uploads/$folder/" . $filename;
}

// \
function selectDepartments($conn){
        $sql = "SELECT * FROM tblDepartment";

    return $conn->query($sql);
}



?>