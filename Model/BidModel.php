<?php
class BidModel {

    function placeBid($connection, $tableName, $listing_id, $buyer_id, $amount) {
        $sql = "INSERT INTO $tableName (listing_id, buyer_id, amount, created_at) VALUES (?, ?, ?, NOW())";
        $statement = $connection->prepare($sql);
        $statement->bind_param("iid", $listing_id, $buyer_id, $amount);
        $statement->execute();
        $result = $statement->affected_rows;
        return $result;
    }

    function updateCurrentBid($connection, $tableName, $listing_id, $amount) {
        $sql = "UPDATE $tableName SET current_bid = ? WHERE id = ?";
        $statement = $connection->prepare($sql);
        $statement->bind_param("di", $amount, $listing_id);
        $statement->execute();
        $result = $statement->affected_rows;
        return $result;
    }

    function getBidCountByListing($connection, $tableName, $listing_id) {
        $sql = "SELECT COUNT(*) as bid_count FROM $tableName WHERE listing_id = ?";
        $statement = $connection->prepare($sql);
        $statement->bind_param("i", $listing_id);
        $statement->execute();
        $result = $statement->get_result();
        return $result;
    }

    function getLastTenBids($connection, $tableName, $listing_id) {
        $sql = "SELECT b.*, u.name as bidder_name FROM $tableName b JOIN users u ON b.buyer_id = u.id WHERE b.listing_id = ? ORDER BY b.amount DESC LIMIT 10";
        $statement = $connection->prepare($sql);
        $statement->bind_param("i", $listing_id);
        $statement->execute();
        $result = $statement->get_result();
        return $result;
    }

    function getMyBids($connection, $tableName, $buyer_id) {
        $sql = "SELECT l.id as listing_id, l.title, l.current_bid, l.status, l.reserve_price, l.winner_bid_id, MAX(b.amount) as my_highest_bid, b.id as bid_id FROM $tableName b JOIN listings l ON b.listing_id = l.id WHERE b.buyer_id = ? GROUP BY l.id";
        $statement = $connection->prepare($sql);
        $statement->bind_param("i", $buyer_id);
        $statement->execute();
        $result = $statement->get_result();
        return $result;
    }

    function getListingCurrentBid($connection, $tableName, $listing_id) {
        $sql = "SELECT current_bid, status, end_datetime, seller_id FROM $tableName WHERE id = ?";
        $statement = $connection->prepare($sql);
        $statement->bind_param("i", $listing_id);
        $statement->execute();
        $result = $statement->get_result();
        return $result;
    }

}
?>
