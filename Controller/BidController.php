<?php
include "../Model/db.php";
include "../Model/BidModel.php";
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

$db = new db();
$connection = $db->openConnection();
$bidModel = new BidModel();
$resultModel = new ResultModel();


$listingResult = $bidModel->getListingCurrentBid($connection, "listings", $listing_id);

if ($listingResult->num_rows == 0) {
    echo json_encode(["ok" => false, "error" => "Listing not found."]);
    exit();
}

$listing = $listingResult->fetch_assoc();

if ($listing["status"] != "active") {
    echo json_encode(["ok" => false, "error" => "This auction is no longer active."]);
    exit();
}

if (strtotime($listing["end_datetime"]) <= time()) {
    echo json_encode(["ok" => false, "error" => "This auction has already ended."]);
    exit();
}

if ($listing["seller_id"] == $buyer_id) {
    echo json_encode(["ok" => false, "error" => "You cannot bid on your own auction."]);
    exit();
}

if ($amount <= $listing["current_bid"]) {
    echo json_encode(["ok" => false, "error" => "Your bid must be higher than the current bid of " . $listing["current_bid"] . "."]);
    exit();
}

$bidResult = $bidModel->placeBid($connection, "bids", $listing_id, $buyer_id, $amount);

if ($bidResult) {
    $bidModel->updateCurrentBid($connection, "listings", $listing_id, $amount);
    $bidCountResult = $bidModel->getBidCountByListing($connection, "bids", $listing_id);
    $bidCountRow = $bidCountResult->fetch_assoc();
    $bid_count = $bidCountRow["bid_count"];

    echo json_encode(["ok" => true, "new_bid" => $amount, "bid_count" => $bid_count]);
} else {
    echo json_encode(["ok" => false, "error" => "Failed to place bid. Please try again."]);
}
?>
