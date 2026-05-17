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

    Header("Location: ../View/dashboard.php");

    exit();

}

?>