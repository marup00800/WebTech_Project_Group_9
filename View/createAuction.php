<?php
include "../Model/db.php";
include "../Model/AuctionModel.php";
session_start();

$isLoggedIn = $_SESSION["isLoggedIn"] ?? false;
$seller_verified = $_SESSION["seller_verified"] ?? 0;

if (!$isLoggedIn) {
    Header("Location: login.php");
    exit();
}

if ($seller_verified != 1) {
    Header("Location: dashboard.php");
    exit();
}

$titleError = $_SESSION["titleErr"] ?? "";
$descriptionError = $_SESSION["descriptionErr"] ?? "";
$categoryError = $_SESSION["categoryErr"] ?? "";
$startingPriceError = $_SESSION["startingPriceErr"] ?? "";
$reservePriceError = $_SESSION["reservePriceErr"] ?? "";
$endDatetimeError = $_SESSION["endDatetimeErr"] ?? "";
$imageError = $_SESSION["imageErr"] ?? "";

$title = $_SESSION["title"] ?? "";
$description = $_SESSION["description"] ?? "";
$category_id = $_SESSION["category_id"] ?? "";
$starting_price = $_SESSION["starting_price"] ?? "";
$reserve_price = $_SESSION["reserve_price"] ?? "";
$end_datetime = $_SESSION["end_datetime"] ?? "";

unset($_SESSION["titleErr"]);
unset($_SESSION["descriptionErr"]);
unset($_SESSION["categoryErr"]);
unset($_SESSION["startingPriceErr"]);
unset($_SESSION["reservePriceErr"]);
unset($_SESSION["endDatetimeErr"]);
unset($_SESSION["imageErr"]);
unset($_SESSION["title"]);
unset($_SESSION["description"]);
unset($_SESSION["category_id"]);
unset($_SESSION["starting_price"]);
unset($_SESSION["reserve_price"]);
unset($_SESSION["end_datetime"]);

$db = new db();
$connection = $db->openConnection();
$auctionModel = new AuctionModel();
$categories = $auctionModel->getAllCategories($connection, "categories");
?>