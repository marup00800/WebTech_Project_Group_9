<?php
include "../Model/db.php";
include "../Model/UsersModel.php";

$email = $_POST["email"] ?? "";

if (!$email) {
    echo "";
} else {
    $db = new db();
    $connection = $db->openConnection();
    $usersModel = new UsersModel();

    $result = $usersModel->checkExistingEmail($connection, "users", $email);
    if ($result->num_rows > 0) {
        echo "<span style='color:red;'>Email already taken</span>";
    } else {
        echo "<span style='color:green;'>Email is available</span>";
    }
}
?>