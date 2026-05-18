<?php
include "../Model/db.php";
include "../Model/UsersModel.php";

$email = $_POST["email"] ?? "";
$name = $_POST["name"] ?? "";

$db = new db();
$connection = $db->openConnection();
$usersModel = new UsersModel();

if ($email) {
    $result = $usersModel->checkExistingEmail($connection, "users", $email);
    if ($result->num_rows > 0) {
        echo "<span style='color:red;'>Email already taken</span>";
    } else {
        echo "<span style='color:green;'>Email is available</span>";
    }
} else if ($name) {
    $result = $usersModel->checkExistingName($connection, "users", $name);
    if ($result->num_rows > 0) {
        echo "<span style='color:red;'>Name already taken</span>";
    } else {
        echo "<span style='color:green;'>Name is available</span>";
    }
}
?>