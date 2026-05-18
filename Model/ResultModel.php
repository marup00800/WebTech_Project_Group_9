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

    function closeAuctionNoWinner($connection, $tableName, $listing_id) {
        $sql = "UPDATE $tableName SET status = 'ended' WHERE id = ?";
        $statement = $connection->prepare($sql);
        $statement->bind_param("i", $listing_id);
        $statement->execute();
        $result = $statement->affected_rows;
        return $result;
    }

    function getWinnerInfoByBidId($connection, $tableName, $bid_id) {
        $sql = "SELECT b.*, u.name as winner_name, u.email as winner_email FROM $tableName b JOIN users u ON b.buyer_id = u.id WHERE b.id = ?";
        $statement = $connection->prepare($sql);
        $statement->bind_param("i", $bid_id);
        $statement->execute();
        $result = $statement->get_result();
        return $result;
    }

    function getAdminStats($connection) {
        $sql = "SELECT 
                    (SELECT COUNT(*) FROM listings WHERE status = 'active') as total_active,
                    (SELECT COUNT(*) FROM listings WHERE status = 'ended') as total_ended,
                    (SELECT COUNT(*) FROM bids) as total_bids,
                    (SELECT MAX(amount) FROM bids) as highest_sale";
        $result = $connection->query($sql);
        return $result;
    }

    function getTop5CategoriesByEndedAuctions($connection) {
        $sql = "SELECT c.name as category_name, COUNT(l.id) as total FROM listings l JOIN categories c ON l.category_id = c.id WHERE l.status = 'ended' GROUP BY l.category_id ORDER BY total DESC LIMIT 5";
        $result = $connection->query($sql);
        return $result;
    }

    function getEndedListingsBySeller($connection, $tableName, $seller_id) {
        $sql = "SELECT l.*, b.amount as winning_amount, u.name as winner_name, u.email as winner_email FROM $tableName l LEFT JOIN bids b ON l.winner_bid_id = b.id LEFT JOIN users u ON b.buyer_id = u.id WHERE l.seller_id = ? AND l.status = 'ended'";
        $statement = $connection->prepare($sql);
        $statement->bind_param("i", $seller_id);
        $statement->execute();
        $result = $statement->get_result();
        return $result;
    }

}
?>
