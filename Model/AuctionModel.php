<?php

include "../Model/db.php";

include "../Model/AuctionModel.php";

session_start();

$isLoggedIn = $_SESSION["isLoggedIn"] ?? false;

if (!$isLoggedIn) {

    Header("Location: ../View/login.php");

    exit();

}

?>