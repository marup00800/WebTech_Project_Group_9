<?php
include "../Model/db.php";
include "../Model/UsersModel.php";
session_start();

$name = $_POST["name"] ?? "";
$email = $_POST["email"] ?? "";
$phone = $_POST["phone"] ?? "";
$bio = $_POST["bio"] ?? "";
$password = $_POST["password"] ?? "";

$hasNameError = false;
$hasEmailError = false;
$hasPhoneError = false;
$hasPasswordError = false;

if (!$name) {
    $_SESSION["nameErr"] = "Name is required";
    $hasNameError = true;
} else {
    unset($_SESSION["nameErr"]);
    $hasNameError = false;
}

if (!$email) {
    $_SESSION["emailErr"] = "Email is required";
    $hasEmailError = true;
} else {
    unset($_SESSION["emailErr"]);
    $hasEmailError = false;
}

if (!$phone) {
    $_SESSION["phoneErr"] = "Phone is required";
    $hasPhoneError = true;
} else {
    unset($_SESSION["phoneErr"]);
    $hasPhoneError = false;
}

if (!$password) {
    $_SESSION["passwordErr"] = "Password is required";
    $hasPasswordError = true;
} else if (strlen($password) < 8) {
    $_SESSION["passwordErr"] = "Password must be at least 8 characters";
    $hasPasswordError = true;
} else {
    unset($_SESSION["passwordErr"]);
    $hasPasswordError = false;
}

if ($hasNameError || $hasEmailError || $hasPhoneError || $hasPasswordError) {
    $_SESSION["name"] = $name;
    $_SESSION["email"] = $email;
    $_SESSION["phone"] = $phone;
    $_SESSION["bio"] = $bio;
    Header("Location: ../View/registration.php");
} else {
    $db = new db();
    $connection = $db->openConnection();
    $usersModel = new UsersModel();

    $existingUser = $usersModel->checkExistingEmail($connection, "users", $email);
    if ($existingUser->num_rows > 0) {
        $_SESSION["emailErr"] = "Email already registered. Please use another email.";
        $_SESSION["name"] = $name;
        $_SESSION["email"] = $email;
        $_SESSION["phone"] = $phone;
        $_SESSION["bio"] = $bio;
        Header("Location: ../View/registration.php");
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $result = $usersModel->registerUser($connection, "users", $name, $email, $phone, $bio, $password_hash);
        if ($result) {
            Header("Location: ../View/login.php");
        }
    }
}
?>
