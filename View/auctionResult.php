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

?>