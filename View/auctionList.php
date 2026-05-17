<?php
include "../Model/db.php";
include "../Model/AuctionModel.php";
include "../Model/ResultModel.php";
session_start();

$isLoggedIn = $_SESSION["isLoggedIn"] ?? false;
$name = $_SESSION["name"] ?? "";

if (!$isLoggedIn) {
    Header("Location: login.php");
    exit();
}

$db = new db();
$connection = $db->openConnection();

$auctionModel = new AuctionModel();
$categories = $auctionModel->getAllCategories($connection, "categories");
?>
<html>
<head>
    <title>Auction List</title>
</head>
<body>
    <h2>Hello, <?php echo $name; ?></h2>
    <a href="dashboard.php">Dashboard</a> |
    <a href="bidNow.php">My Bids</a> |
    <a href="../Controller/logout.php">Logout</a>

    <h3>Active Auctions</h3>

    <label>Filter by Category: </label>
    <select id="categoryFilter" onchange="filterByCategory()">
        <option value="">All Categories</option>
        <?php
        while ($row = $categories->fetch_assoc()) {
            echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
        }
        ?>
    </select>

    <input type="text" id="searchInput" placeholder="Search auctions..." onkeyup="searchListings()"/>
    <p id="searchResponse"></p>

    <div id="listingsContainer">
        <table border="1" id="listingsTable">
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Current Bid</th>
                <th>Bid Count</th>
                <th>Time Remaining</th>
                <th>Action</th>
            </tr>
        </table>
    </div>

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

        function filterByCategory() {
            var category_id = document.getElementById("categoryFilter").value;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var response = JSON.parse(this.responseText);
                    var table = "<table border='1'><tr><th>Image</th><th>Title</th><th>Current Bid</th><th>Bid Count</th><th>Time Remaining</th><th>Action</th></tr>";
                    if (response.listings.length > 0) {
                        response.listings.forEach(function(listing) {
                            table += "<tr><td><img src='" + listing.image_path + "' height='50px' width='50px'/></td><td>" + listing.title + "</td><td>" + listing.current_bid + "</td><td>" + listing.bid_count + "</td><td><span class='countdown' data-end='" + listing.end_datetime + "'></span></td><td><a href='bidNow.php?listing_id=" + listing.id + "'>Bid Now</a></td></tr>";
                        });
                    } else {
                        table += "<tr><td colspan='6'>No active auctions found.</td></tr>";
                    }
                    table += "</table>";
                    document.getElementById("listingsContainer").innerHTML = table;
                    countdown();
                }
            };
            xhttp.open("GET", "../Controller/AuctionController.php?action=filter&category_id=" + category_id, true);
            xhttp.send();
        }

        function searchListings() {
            var q = document.getElementById("searchInput").value;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var response = JSON.parse(this.responseText);
                    var table = "<table border='1'><tr><th>Image</th><th>Title</th><th>Current Bid</th><th>Bid Count</th><th>Time Remaining</th><th>Action</th></tr>";
                    if (response.listings.length > 0) {
                        response.listings.forEach(function(listing) {
                            table += "<tr><td><img src='" + listing.image_path + "' height='50px' width='50px'/></td><td>" + listing.title + "</td><td>" + listing.current_bid + "</td><td>" + listing.bid_count + "</td><td><span class='countdown' data-end='" + listing.end_datetime + "'></span></td><td><a href='bidNow.php?listing_id=" + listing.id + "'>Bid Now</a></td></tr>";
                        });
                    } else {
                        table += "<tr><td colspan='6'>No results found.</td></tr>";
                    }
                    table += "</table>";
                    document.getElementById("listingsContainer").innerHTML = table;
                    countdown();
                }
            };
            xhttp.open("GET", "../Controller/AuctionController.php?action=search&q=" + q, true);
            xhttp.send();
        }
    </script>
</body>
</html>
