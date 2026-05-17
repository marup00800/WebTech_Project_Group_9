<?php
class ResultModel {

    function getExpiredActiveListings($connection, $tableName) {
        $sql = "SELECT id FROM $tableName WHERE status = 'active' AND end_datetime <= NOW()";
        $result = $connection->query($sql);
        return $result;
    }

    function getHighestBidByListing($connection, $tableName, $listing_id) {
        $sql = "SELECT id FROM $tableName WHERE listing_id = ? ORDER BY amount DESC LIMIT 1";
        $statement = $connection->prepare($sql);
        $statement->bind_param("i", $listing_id);
        $statement->execute();
        $result = $statement->get_result();
        return $result;
    }

    function closeAuction($connection, $tableName, $listing_id, $winner_bid_id) {
        $sql = "UPDATE $tableName SET status = 'ended', winner_bid_id = ? WHERE id = ?";
        $statement = $connection->prepare($sql);
        $statement->bind_param("ii", $winner_bid_id, $listing_id);
        $statement->execute();
        $result = $statement->affected_rows;
        return $result;
    }


}
?>
