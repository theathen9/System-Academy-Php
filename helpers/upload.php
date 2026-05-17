<?php

if (!function_exists('uploadPhoto')) {

    function uploadPhoto($fileInputName, $uploadDir = __DIR__ . '/../uploads/photos/')
    {
        if (!isset($_FILES[$fileInputName]) || $_FILES[$fileInputName]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileTmp = $_FILES[$fileInputName]['tmp_name'];
        $fileName = uniqid() . '_' . basename($_FILES[$fileInputName]['name']);
        $filePath = $uploadDir . $fileName;

        return move_uploaded_file($fileTmp, $filePath) ? $fileName : null;
    }
}