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
</body>
</html>
