<?php
include "../Model/db.php";
include "../Model/AuctionModel.php";
include "../Model/BidModel.php";
session_start();

$isLoggedIn = $_SESSION["isLoggedIn"] ?? false;
$name = $_SESSION["name"] ?? "";
$user_id = $_SESSION["user_id"] ?? "";

if (!$isLoggedIn) {
    Header("Location: login.php");
    exit();
}

$listing_id = $_GET["listing_id"] ?? "";

if (!$listing_id) {
    Header("Location: auctionList.php");
    exit();
}

$db = new db();
$connection = $db->openConnection();
$resultModel = new ResultModel();

$auctionModel = new AuctionModel();
$bidModel = new BidModel();

$listingResult = $auctionModel->getListingWithSellerById($connection, "listings", $listing_id);

if ($listingResult->num_rows == 0) {
    Header("Location: auctionList.php");
    exit();
}

$listing = $listingResult->fetch_assoc();
?>
<html>
<head>
    <title>Bid Now - <?php echo $listing["title"]; ?></title>
</head>
<body>
    <h2>Hello, <?php echo $name; ?></h2>
    <a href="auctionList.php">Back to Auctions</a> |
    <a href="../Controller/logout.php">Logout</a>

    <h3><?php echo $listing["title"]; ?></h3>
    <p><?php echo $listing["description"]; ?></p>
    <p>Seller: <?php echo $listing["seller_name"]; ?></p>
    <img src="<?php echo $listing["image_path"]; ?>" height="200px" width="200px"/>

    <p>Current Bid: <strong><span id="currentBid"><?php echo $listing["current_bid"]; ?></span></strong></p>
    <p>Time Remaining: <span class="countdown" data-end="<?php echo $listing["end_datetime"]; ?>"></span></p>

    <?php if ($listing["status"] == "ended") { ?>
        <?php if ($listing["current_bid"] >= $listing["reserve_price"]) { ?>
            <p style='color:green;'>Auction Ended. Winner: <?php echo $listing["seller_name"]; ?></p>
        <?php } else { ?>
            <p style='color:red;'>Auction Ended. Reserve Not Met.</p>
        <?php } ?>
    <?php } else { ?>
        <input type="number" id="bidAmount" placeholder="Enter bid amount" step="0.01" min="0"/>
        <button onclick="placeBid()">Place Bid</button>
        <p id="bidError" style='color:red;'></p>
    <?php } ?>

    <h3>Bid History (Last 10)</h3>
    <table border="1" id="bidHistoryTable">
        <tr>
            <th>Bidder</th>
            <th>Amount</th>
            <th>Time</th>
        </tr>
       
    </table>

    <h3>My Bids</h3>
    <table border="1">
        <tr>
            <th>Auction Title</th>
            <th>My Highest Bid</th>
            <th>Current Leading Bid</th>
            <th>Status</th>
        </tr>
        <?php
    
               
        
        ?>
    </table>

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

        function placeBid() {
            var amount = document.getElementById("bidAmount").value;
            var listing_id = <?php echo $listing_id; ?>;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var response = JSON.parse(this.responseText);
                    if (response.ok) {
                        document.getElementById("currentBid").innerHTML = response.new_bid;
                        document.getElementById("bidError").innerHTML = "";
                        var table = document.getElementById("bidHistoryTable");
                        var newRow = table.insertRow(1);
                        newRow.innerHTML = "<td><?php echo $name; ?></td><td>" + response.new_bid + "</td><td>Just now</td>";
                    } else {
                        document.getElementById("bidError").innerHTML = response.error;
                    }
                }
            };
            xhttp.open("POST", "../Controller/BidController.php", true);
            xhttp.setRequestHeader("content-type", "application/x-www-form-urlencoded");
            xhttp.send("listing_id=" + listing_id + "&amount=" + amount);
        }
    </script>
</body>
</html>
