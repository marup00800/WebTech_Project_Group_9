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