<?php
include "../Model/db.php";
include "../Model/AuctionModel.php";
session_start();

$file = $_FILES["image"] ?? null;
$listing_id = $_POST["listing_id"] ?? "";

if ($file) {
    $allowedMimeTypes = ["image/jpeg", "image/png"];
    $maxSize = 3 * 1024 * 1024;

    $fileMimeType = mime_content_type($file["tmp_name"]);
    $fileSize = $file["size"];

    if (!in_array($fileMimeType, $allowedMimeTypes)) {
        $_SESSION["uploadErr"] = "Only JPEG and PNG files are allowed.";
        Header("Location: ../View/upload.php");
    } else if ($fileSize > $maxSize) {
        $_SESSION["uploadErr"] = "File size must be less than 3MB.";
        Header("Location: ../View/upload.php");
    } else {
        $uploadDirectory = "../uploads/";
        $fileName = time() . "_" . basename($file["name"]);
        $path = $uploadDirectory . $fileName;
        $res = move_uploaded_file($file["tmp_name"], $path);

        if ($res) {
            $_SESSION["uploadSuccess"] = "File uploaded successfully.";
            $_SESSION["uploaded_image_path"] = $path;
            Header("Location: ../View/upload.php");
        } else {
            $_SESSION["uploadErr"] = "File upload failed. Please try again.";
            Header("Location: ../View/upload.php");
        }
    }
} else {
    $_SESSION["uploadErr"] = "No file selected.";
    Header("Location: ../View/upload.php");
}
?>
