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

}
?>
