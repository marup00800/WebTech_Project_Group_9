<?php
include "../Model/db.php";
include "../Model/BidModel.php";
include "../Model/ResultModel.php";
session_start();

header("Content-Type: application/json");

$isLoggedIn = $_SESSION["isLoggedIn"] ?? false;

if (!$isLoggedIn) {
    echo json_encode(["ok" => false, "error" => "You must be logged in to place a bid."]);
    exit();
}

$listing_id = $_POST["listing_id"] ?? "";
$amount = $_POST["amount"] ?? "";
$buyer_id = $_SESSION["user_id"];

if (!$listing_id || !$amount) {
    echo json_encode(["ok" => false, "error" => "Listing ID and amount are required."]);
    exit();
}

?>
