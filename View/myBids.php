<?php
include "../Model/db.php";
include "../Model/BidModel.php";
include "../Model/ResultModel.php";
session_start();

$isLoggedIn = $_SESSION["isLoggedIn"] ?? false;
$name = $_SESSION["name"] ?? "";
$user_id = $_SESSION["user_id"] ?? "";

if (!$isLoggedIn) {
    Header("Location: login.php");
    exit();
}

$db = new db();
$connection = $db->openConnection();
$resultModel = new ResultModel();

// close expired auctions
$expiredListings = $resultModel->getExpiredActiveListings($connection, "listings");
if ($expiredListings->num_rows > 0) {
    while ($expiredRow = $expiredListings->fetch_assoc()) {
        $expiredId = $expiredRow["id"];
        $highestBidResult = $resultModel->getHighestBidByListing($connection, "bids", $expiredId);
        if ($highestBidResult->num_rows > 0) {
            $highestBidRow = $highestBidResult->fetch_assoc();
            $resultModel->closeAuction($connection, "listings", $expiredId, $highestBidRow["id"]);
        } else {
            $resultModel->closeAuctionNoWinner($connection, "listings", $expiredId);
        }
    }
}

$bidModel = new BidModel();
$myBidsResult = $bidModel->getMyBids($connection, "bids", $user_id);
?>
<html>
<head>
    <title>My Bids</title>
</head>
<body>
    <h2>Hello, <?php echo $name; ?></h2>
    <a href="auctionList.php">Auction List</a> |
    <a href="dashboard.php">Dashboard</a> |
    <a href="../Controller/logout.php">Logout</a>

    <h3>My Bids</h3>
    <table border="1">
        <tr>
            <th>Auction Title</th>
            <th>My Highest Bid</th>
            <th>Current Leading Bid</th>
            <th>Status</th>
        </tr>
        <?php
        if ($myBidsResult->num_rows > 0) {
            while ($row = $myBidsResult->fetch_assoc()) {
                $listing_title = $row["title"];
                $my_highest_bid = $row["my_highest_bid"];
                $current_bid = $row["current_bid"];
                $status = $row["status"];
                $winner_bid_id = $row["winner_bid_id"];
                $bid_id = $row["bid_id"];
                $reserve_price = $row["reserve_price"];
                $listing_id = $row["listing_id"];

                if ($status == "active") {
                    if ($my_highest_bid == $current_bid) {
                        $badge = "<span style='color:green;'>Leading</span>";
                    } else {
                        $badge = "<span style='color:orange;'>Outbid</span>";
                    }
                } else if ($status == "ended") {
                    if ($winner_bid_id == $bid_id && $current_bid >= $reserve_price) {
                        $badge = "<span style='color:green;'>You Won!</span>";
                    } else if ($current_bid < $reserve_price) {
                        $badge = "<span style='color:red;'></span>";
                    } else {
                        $badge = "<span style='color:red;'>Lost</span>";
                    }
                } else {
                    $badge = "<span>$status</span>";
                }

                echo "<tr>
                    <td><a href='bidNow.php?listing_id=$listing_id'>$listing_title</a></td>
                    <td>$my_highest_bid</td>
                    <td>$current_bid</td>
                    <td>$badge</td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>You have not placed any bids yet.</td></tr>";
        }
        ?>
    </table>
</body>
</html>