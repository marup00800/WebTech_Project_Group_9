<?php
include "../Model/db.php";
include "../Model/AuctionModel.php";
include "../Model/ResultModel.php";
session_start();

$isLoggedIn = $_SESSION["isLoggedIn"] ?? false;
$name = $_SESSION["name"] ?? "";
$role = $_SESSION["role"] ?? "";
$seller_verified = $_SESSION["seller_verified"] ?? 0;
$user_id = $_SESSION["user_id"] ?? "";

if (!$isLoggedIn) {
    Header("Location: login.php");
    exit();
}

$db = new db();
$connection = $db->openConnection();
$resultModel = new ResultModel();


$auctionModel = new AuctionModel();

$auctionSuccess = $_SESSION["auctionSuccess"] ?? "";
$auctionErr = $_SESSION["auctionErr"] ?? "";
unset($_SESSION["auctionSuccess"]);
unset($_SESSION["auctionErr"]);

$listings = null;
$endedListings = null;



?>
<html>
<head>
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>Hello, <?php echo $name; ?></h2>
    <a href="auctionList.php">Auction List</a> |
    <a href="../Controller/logout.php">Logout</a>

    <?php if ($auctionSuccess) { echo "<p style='color:green;'>$auctionSuccess</p>"; } ?>
    <?php if ($auctionErr) { echo "<p style='color:red;'>$auctionErr</p>"; } ?>

    <?php if ($role == "admin") { ?>
        <h3>Admin Analytics</h3>
        <table border="1">
            <tr>
                <th>Total Active Auctions</th>
                <th>Total Ended Auctions</th>
                <th>Total Bids</th>
                <th>Highest Sale</th>
            </tr>
            <tr>
                
            </tr>
        </table>

        <h3>Top 5 Categories by Completed Auctions</h3>
        <canvas id="categoryChart" width="600" height="300"></canvas>
        <script>
            var ctx = document.getElementById("categoryChart").getContext("2d");
            new Chart(ctx, {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Completed Auctions",
                        data: data,
                        backgroundColor: "rgba(54, 162, 235, 0.6)"
                    }]
                },
                options: {
                    indexAxis: "y"
                }
            });
        </script>
    <?php } ?>

    <?php if ($seller_verified == 1) { ?>
        <h3>My Listings</h3>
        <a href="createAuction.php">+ Create New Listing</a>
        <table border="1">
            <tr>
                <th>Title</th>
                <th>Starting Price</th>
                <th>Current Bid</th>
                <th>Bid Count</th>
                <th>Status</th>
                <th>Time Remaining</th>
                <th>Action</th>
            </tr>
            <?php
            if ($listings && $listings->num_rows > 0) {
                while ($row = $listings->fetch_assoc()) {
                    $id = $row["id"];
                    $title = $row["title"];
                    $starting_price = $row["starting_price"];
                    $current_bid = $row["current_bid"];
                    $bid_count = $row["bid_count"];
                    $status = $row["status"];
                    $end_datetime = $row["end_datetime"];
                    echo "<tr>
                        <td>$title</td>
                        <td>$starting_price</td>
                        <td>$current_bid</td>
                        <td>$bid_count</td>
                        <td><span id='status_$id'>$status</span></td>
                        <td><span class='countdown' data-end='$end_datetime'></span></td>
                        <td>
                            <a href='createAuction.php?edit=$id'>Edit</a> |
                            <button onclick='cancelListing($id)'>Cancel</button>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No listings found.</td></tr>";
            }
            ?>
        </table>

        <h3>Ended Listings</h3>
        <table border="1">
            <tr>
                <th>Title</th>
                <th>Winning Bid</th>
                <th>Winner Name</th>
                <th>Winner Email</th>
                <th>Reserve</th>
            </tr>
            <?php
            if ($endedListings && $endedListings->num_rows > 0) {
                while ($row = $endedListings->fetch_assoc()) {
                    $title = $row["title"];
                    $winning_amount = $row["winning_amount"] ?? "N/A";
                    $winner_name = $row["winner_name"] ?? "N/A";
                    $winner_email = $row["winner_email"] ?? "N/A";
                    $reserve_met = ($row["current_bid"] >= $row["reserve_price"]) ? "Reserve Met" : "Reserve Not Met";
                    echo "<tr>
                        <td>$title</td>
                        <td>$winning_amount</td>
                        <td>$winner_name</td>
                        <td>$winner_email</td>
                        <td>$reserve_met</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No ended listings.</td></tr>";
            }
            ?>
        </table>

    <?php } else { ?>
        <p style='color:red;'>You are not a verified seller.</p>
        <p>Contact admin to get verified as a seller.</p>
    <?php } ?>

    <script>
        function countdown() {
            var elements = document.querySelectorAll(".countdown");
            elements.forEach(function(el) {
                var endTime = new Date(el.getAttribute("data-end")).getTime();
                var now = new Date().getTime();
                var diff = endTime - now;
                if (diff <= 0) {
                    el.innerHTML = "Ended";
                } else {
                    var days = Math.floor(diff / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((diff % (1000 * 60)) / 1000);
                    el.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s";
                }
            });
        }
        setInterval(countdown, 1000);
        countdown();

        function cancelListing(listing_id) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var response = JSON.parse(this.responseText);
                    if (response.ok) {
                        document.getElementById("status_" + listing_id).innerHTML = "cancelled";
                    } else {
                        alert(response.error);
                    }
                }
            };
            xhttp.open("POST", "../Controller/AuctionController.php?action=cancel", true);
            xhttp.setRequestHeader("content-type", "application/x-www-form-urlencoded");
            xhttp.send("listing_id=" + listing_id);
        }
    </script>
</body>
</html>