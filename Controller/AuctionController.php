<?php
include "../Model/db.php";
include "../Model/AuctionModel.php";
session_start();

$isLoggedIn = $_SESSION["isLoggedIn"] ?? false;
$seller_verified = $_SESSION["seller_verified"] ?? 0;

if (!$isLoggedIn) {
    Header("Location: ../View/login.php");
    exit();
}

if ($seller_verified != 1) {
    $_SESSION["auctionErr"] = "You must be a verified seller to create a listing.";
    Header("Location: ../View/dashboard.php");
    exit();
}

$title = $_POST["title"] ?? "";
$description = $_POST["description"] ?? "";
$category_id = $_POST["category_id"] ?? "";
$starting_price = $_POST["starting_price"] ?? "";
$reserve_price = $_POST["reserve_price"] ?? "";
$end_datetime = $_POST["end_datetime"] ?? "";
$file = $_FILES["image"] ?? null;

$hasTitleError = false;
$hasDescriptionError = false;
$hasCategoryError = false;
$hasStartingPriceError = false;
$hasEndDatetimeError = false;
$hasImageError = false;



if (!$title) {
    $_SESSION["titleErr"] = "Title is required";
    $hasTitleError = true;
} else {
    unset($_SESSION["titleErr"]);
    $hasTitleError = false;
}

if (!$description) {
    $_SESSION["descriptionErr"] = "Description is required";
    $hasDescriptionError = true;
} else {
    unset($_SESSION["descriptionErr"]);
    $hasDescriptionError = false;
}

if (!$category_id) {
    $_SESSION["categoryErr"] = "Category is required";
    $hasCategoryError = true;
} else {
    unset($_SESSION["categoryErr"]);
    $hasCategoryError = false;
}

if (!$starting_price || $starting_price <= 0) {
    $_SESSION["startingPriceErr"] = "Starting price must be a positive number";
    $hasStartingPriceError = true;
} else {
    unset($_SESSION["startingPriceErr"]);
    $hasStartingPriceError = false;
}

if ($reserve_price && $reserve_price < $starting_price) {
    $_SESSION["reservePriceErr"] = "Reserve price must be greater than or equal to starting price";
    Header("Location: ../View/createAuction.php");
    exit();
} else {
    unset($_SESSION["reservePriceErr"]);
}

if (!$end_datetime) {
    $_SESSION["endDatetimeErr"] = "End date and time is required";
    $hasEndDatetimeError = true;
} else {
    $endTime = strtotime($end_datetime);
    $minTime = time() + 3600;
    if ($endTime < $minTime) {
        $_SESSION["endDatetimeErr"] = "End date must be at least 1 hour from now";
        $hasEndDatetimeError = true;
    } else {
        unset($_SESSION["endDatetimeErr"]);
        $hasEndDatetimeError = false;
    }
}

if (!$file || $file["error"] != 0) {
    $_SESSION["imageErr"] = "Image is required";
    $hasImageError = true;
} else {
    $allowedMimeTypes = ["image/jpeg", "image/png"];
    $maxSize = 3 * 1024 * 1024;
    $fileMimeType = mime_content_type($file["tmp_name"]);
    $fileSize = $file["size"];

    if (!in_array($fileMimeType, $allowedMimeTypes)) {
        $_SESSION["imageErr"] = "Only JPEG and PNG files are allowed.";
        $hasImageError = true;
    } else if ($fileSize > $maxSize) {
        $_SESSION["imageErr"] = "Image size must be less than 3MB.";
        $hasImageError = true;
    } else {
        unset($_SESSION["imageErr"]);
        $hasImageError = false;
    }
}

if ($hasTitleError || $hasDescriptionError || $hasCategoryError || $hasStartingPriceError || $hasEndDatetimeError || $hasImageError) {
    $_SESSION["title"] = $title;
    $_SESSION["description"] = $description;
    $_SESSION["category_id"] = $category_id;
    $_SESSION["starting_price"] = $starting_price;
    $_SESSION["reserve_price"] = $reserve_price;
    $_SESSION["end_datetime"] = $end_datetime;
    Header("Location: ../View/createAuction.php");
} else {
    $uploadDirectory = "../uploads/";
    $fileName = time() . "_" . basename($file["name"]);
    $image_path = $uploadDirectory . $fileName;
    move_uploaded_file($file["tmp_name"], $image_path);

    $db = new db();
    $connection = $db->openConnection();
    $auctionModel = new AuctionModel();

    $seller_id = $_SESSION["user_id"];
    $result = $auctionModel->createListing($connection, "listings", $seller_id, $category_id, $title, $description, $starting_price, $reserve_price, $image_path, $end_datetime);

    if ($result) {
        $_SESSION["auctionSuccess"] = "Auction listing created successfully.";
        Header("Location: ../View/dashboard.php");
    } else {
        $_SESSION["auctionErr"] = "Failed to create listing. Please try again.";
        Header("Location: ../View/createAuction.php");
    }
}
?>
