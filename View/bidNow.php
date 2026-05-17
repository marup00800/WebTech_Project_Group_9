<?php

session_start();

$usernameError = $_SESSION["usernameErr"] ?? "";
$passwordError = $_SESSION["passwordErr"] ?? "";

$username = $_SESSION["username"] ?? "";

$loginErr = $_SESSION["loginErr"] ?? "";

$isLoggedIn = $_SESSION["isLoggedIn"] ?? false;

if($isLoggedIn){

    Header("Location: dashboard.php");
    exit();
}

unset($_SESSION["usernameErr"]);
unset($_SESSION["passwordErr"]);
unset($_SESSION["username"]);
unset($_SESSION["loginErr"]);

?>