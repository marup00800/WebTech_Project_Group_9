<?php
class UsersModel {

    function registerUser($connection, $tableName, $name, $email, $phone, $bio, $password_hash) {
        $sql = "INSERT INTO $tableName (name, email, phone, bio, password_hash, role, seller_verified, created_at)
                VALUES ('$name', '$email', '$phone', '$bio', '$password_hash', 'buyer', 0, NOW())";
        return $connection->query($sql);
    }

    function getUserByEmail($connection, $tableName, $email) {
        $sql = "SELECT * FROM $tableName
                WHERE email='$email'";
        return $connection->query($sql);
    }

    function checkExistingEmail($connection, $tableName, $email) {
        $sql = "SELECT * FROM $tableName
                WHERE email='$email'";
        return $connection->query($sql);
    }

    function getAllUsers($connection, $tableName) {
        $sql = "SELECT * FROM $tableName";
        return $connection->query($sql);
    }

    function checkExistingName($connection, $tableName, $name) {
    $sql = "SELECT * FROM $tableName
            WHERE name='$name'";
    return $connection->query($sql);
}

}