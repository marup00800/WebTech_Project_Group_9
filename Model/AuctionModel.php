<?php
class AuctionModel {

    function getAllCategories($connection, $tableName) {
        $sql = "SELECT * FROM $tableName";
        $result = $connection->query($sql);
        return $result;
    }

    function createListing($connection, $tableName, $seller_id, $category_id, $title, $description, $starting_price, $reserve_price, $image_path, $end_datetime) {
        $sql = "INSERT INTO $tableName (seller_id, category_id, title, description, starting_price, reserve_price, current_bid, image_path, end_datetime, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'active', NOW())";
        $statement = $connection->prepare($sql);
        $statement->bind_param("iissdddss", $seller_id, $category_id, $title, $description, $starting_price, $reserve_price, $starting_price, $image_path, $end_datetime);
        $statement->execute();
        $result = $statement->affected_rows;
        return $result;
    }

}