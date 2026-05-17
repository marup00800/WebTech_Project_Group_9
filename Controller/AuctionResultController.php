<?php
include "../Model/db.php";
include "../Model/ResultModel.php";
session_start();

header("Content-Type: application/json");

$isLoggedIn = $_SESSION["isLoggedIn"] ?? false;
$role = $_SESSION["role"] ?? "";

if (!$isLoggedIn || $role != "admin") {
    echo json_encode(["ok" => false, "error" => "Unauthorized access."]);
    exit();
}

?>