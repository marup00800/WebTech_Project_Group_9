<?php
include "../Model/db.php";
include "../Model/UsersModel.php";
session_start();

$email = $_POST["email"] ?? "";
$password = $_POST["password"] ?? "";

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
} else {
    $db = new db();
    $connection = $db->openConnection();
    $usersModel = new UsersModel();

    $result = $usersModel->getUserByEmail($connection, "users", $email); 
    if ($result->num_rows == 1) {
        while ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row["password_hash"])) {
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["name"] = $row["name"];
                $_SESSION["role"] = $row["role"];
                $_SESSION["seller_verified"] = $row["seller_verified"];
                $_SESSION["isLoggedIn"] = true;

                if ($row["role"] == "admin") {
                    Header("Location: ../View/dashboard.php");
                } else {
                    Header("Location: ../View/auctionList.php");
                }
            } else {
                $_SESSION["loginErr"] = "Email or password doesn't match. Please try again.";
                Header("Location: ../View/login.php");
            }
        }
    } else {
        $_SESSION["loginErr"] = "Email or password doesn't match. Please try again.";
        Header("Location: ../View/login.php");
    }
}
?>
