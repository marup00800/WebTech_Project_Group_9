<?php

class DatabaseConnection {

    function openConnection() {

        $connection = new mysqli(
            "localhost",
            "root",
            "",
            "auction_db"
        );

        if ($connection->connect_error) {
            die("Connection Failed: " . $connection->connect_error);
        }

        return $connection;
    }

    function checkEmail($connection, $tableName, $email) {

    $sql = "SELECT * FROM $tableName
            WHERE email='$email'";

    return $connection->query($sql);
}

    function CreateUser($connection, $tableName, $name, $email, $phone, $bio, $password_hash) {

        $sql = "INSERT INTO $tableName (name, email, phone, bio, password)
                VALUES ('$name', '$email', '$phone', '$bio', '$password_hash')";

        return $connection->query($sql);
    }
}
?>