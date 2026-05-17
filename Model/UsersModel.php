<?php
class UsersModel {

    function registerUser($connection, $tableName, $name, $email, $phone, $bio, $password_hash) {
        $sql = "INSERT INTO $tableName (name, email, phone, bio, password_hash, role, seller_verified, created_at) VALUES (?, ?, ?, ?, ?, 'buyer', 0, NOW())";
        $statement = $connection->prepare($sql);
        $statement->bind_param("sssss", $name, $email, $phone, $bio, $password_hash);
        $statement->execute();
        $result = $statement->affected_rows;
        return $result;
    }

    function getUserByEmail($connection, $tableName, $email) {
        $sql = "SELECT * FROM $tableName WHERE email = ?";
        $statement = $connection->prepare($sql);
        $statement->bind_param("s", $email);
        $statement->execute();
        $result = $statement->get_result();
        return $result;
    }

    function checkExistingEmail($connection, $tableName, $email) {
        $sql = "SELECT * FROM $tableName WHERE email = ?";
        $statement = $connection->prepare($sql);
        $statement->bind_param("s", $email);
        $statement->execute();
        $result = $statement->get_result();
        return $result;
    }

}
?>
