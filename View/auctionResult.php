<?php
include "../Model/db.php";
include "../Model/ResultModel.php";
session_start();

$isLoggedIn = $_SESSION["isLoggedIn"] ?? false;
$name = $_SESSION["name"] ?? "";
$role = $_SESSION["role"] ?? "";

if (!$isLoggedIn) {
    Header("Location: login.php");
    exit();
}

if ($role != "admin") {
    Header("Location: auctionList.php");
    exit();
}

$db = new db();
$connection = $db->openConnection();
$resultModel = new ResultModel();

$expiredListings = $resultModel->getExpiredActiveListings($connection, "listings");
if ($expiredListings->num_rows > 0) {
    while ($expiredRow = $expiredListings->fetch_assoc()) {
        $expiredId = $expiredRow["id"];
        $highestBidResult = $resultModel->getHighestBidByListing($connection, "bids", $expiredId);
        if ($highestBidResult->num_rows > 0) {
            $highestBidRow = $highestBidResult->fetch_assoc();
            $resultModel->closeAuction($connection, "listings", $expiredId, $highestBidRow["id"]);
        } else {
            $resultModel->closeAuctionNoWinner($connection, "listings", $expiredId);
        }
    }
}
?>