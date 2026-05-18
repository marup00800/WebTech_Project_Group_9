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

    function getListingsBySeller($connection, $tableName, $seller_id) {
        $sql = "SELECT l.*, COUNT(b.id) as bid_count FROM $tableName l LEFT JOIN bids b ON l.id = b.listing_id WHERE l.seller_id = ? GROUP BY l.id";
        $statement = $connection->prepare($sql);
        $statement->bind_param("i", $seller_id);
        $statement->execute();
        $result = $statement->get_result();
        return $result;
    }

    function getListingById($connection, $tableName, $listing_id) {
        $sql = "SELECT l.*, COUNT(b.id) as bid_count FROM $tableName l LEFT JOIN bids b ON l.id = b.listing_id WHERE l.id = ? GROUP BY l.id";
        $statement = $connection->prepare($sql);
        $statement->bind_param("i", $listing_id);
        $statement->execute();
        $result = $statement->get_result();
        return $result;
    }

    function updateListing($connection, $tableName, $listing_id, $title, $description, $image_path) {
        $sql = "UPDATE $tableName SET title = ?, description = ?, image_path = ? WHERE id = ?";
        $statement = $connection->prepare($sql);
        $statement->bind_param("sssi", $title, $description, $image_path, $listing_id);
        $statement->execute();
        $result = $statement->affected_rows;
        return $result;
    }

    function cancelListing($connection, $tableName, $listing_id) {
        $sql = "UPDATE $tableName SET status = 'cancelled' WHERE id = ? AND (SELECT COUNT(*) FROM bids WHERE listing_id = ?) = 0";
        $statement = $connection->prepare($sql);
        $statement->bind_param("ii", $listing_id, $listing_id);
        $statement->execute();
        $result = $statement->affected_rows;
        return $result;
    }

    function getActiveListings($connection, $tableName) {
        $sql = "SELECT l.*, COUNT(b.id) as bid_count FROM $tableName l LEFT JOIN bids b ON l.id = b.listing_id WHERE l.status = 'active' AND l.end_datetime > NOW() GROUP BY l.id";
        $result = $connection->query($sql);
        return $result;
    }

    function getActiveListingsByCategory($connection, $tableName, $category_id) {
        $sql = "SELECT l.*, COUNT(b.id) as bid_count FROM $tableName l LEFT JOIN bids b ON l.id = b.listing_id WHERE l.status = 'active' AND l.end_datetime > NOW() AND l.category_id = ? GROUP BY l.id";
        $statement = $connection->prepare($sql);
        $statement->bind_param("i", $category_id);
        $statement->execute();
        $result = $statement->get_result();
        return $result;
    }

    function searchListings($connection, $tableName, $keyword) {
        $keyword = "%" . $keyword . "%";
        $sql = "SELECT l.*, COUNT(b.id) as bid_count FROM $tableName l LEFT JOIN bids b ON l.id = b.listing_id WHERE l.status = 'active' AND l.end_datetime > NOW() AND l.title LIKE ? GROUP BY l.id";
        $statement = $connection->prepare($sql);
        $statement->bind_param("s", $keyword);
        $statement->execute();
        $result = $statement->get_result();
        return $result;
    }

    function getListingWithSellerById($connection, $tableName, $listing_id) {
        $sql = "SELECT l.*, u.name as seller_name, u.email as seller_email FROM $tableName l JOIN users u ON l.seller_id = u.id WHERE l.id = ?";
        $statement = $connection->prepare($sql);
        $statement->bind_param("i", $listing_id);
        $statement->execute();
        $result = $statement->get_result();
        return $result;
    }

}
?>
