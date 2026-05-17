<?php
class db {
    function openConnection() {
        $db_host = "localhost";
        $db_username = "root";
        $db_password = "";
        $db_name = "auction_db";
        $connection = new mysqli($db_host, $db_username, $db_password, $db_name);

        if ($connection->connect_error) {
            die("Could not connect to the database. Please try again. Original Error: " . $connection->connect_error);
        }
        return $connection;
    }

    function closeConnection($connection) {
        $connection->close();
    }
}
?>
