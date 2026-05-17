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

$db = new db();
$connection = $db->openConnection();
$resultModel = new ResultModel();

$statsResult = $resultModel->getAdminStats($connection);
$stats = $statsResult->fetch_assoc();

$categoriesResult = $resultModel->getTop5CategoriesByEndedAuctions($connection);
$categories = [];
while ($row = $categoriesResult->fetch_assoc()) {
    $categories[] = $row;
}

echo json_encode([
    "ok" => true,
    "total_active" => $stats["total_active"],
    "total_ended" => $stats["total_ended"],
    "total_bids" => $stats["total_bids"],
    "highest_sale" => $stats["highest_sale"],
    "top_categories" => $categories
]);
?>
