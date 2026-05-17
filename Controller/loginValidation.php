<?php
session_start();

$email = $_POST["email"] ?? "";
$password = $_POST["password"] ?? "";

$hasEmailError = false;
$hasPasswordError = false;

if (!$email) {
    $_SESSION["emailErr"] = "Email is required";
    $hasEmailError = true;
} else {
    unset($_SESSION["emailErr"]);
    $hasEmailError = false;
}

if (!$password) {
    $_SESSION["passwordErr"] = "Password is required";
    $hasPasswordError = true;
} else {
    unset($_SESSION["passwordErr"]);
    $hasPasswordError = false;
}

if ($hasEmailError || $hasPasswordError) {
    $_SESSION["email"] = $email;
    Header("Location: ../View/login.php");
    exit();
}