<?php
include "../Model/db.php";
include "../Model/ResultModel.php";
session_start();

$isLoggedIn = $_SESSION["isLoggedIn"] ?? false;
$name = $_SESSION["name"] ?? "";
$role = $_SESSION["role"] ?? "";

if (!$isLoggedIn) {
    Header("Location: login.php");
    exit();
}

if ($role != "admin") {
    Header("Location: auctionList.php");
    exit();
}

$db = new db();
$connection = $db->openConnection();
$resultModel = new ResultModel();

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

$statsResult = $resultModel->getAdminStats($connection);
$stats = $statsResult->fetch_assoc();

$topCategories = [];
$categoriesResult = $resultModel->getTop5CategoriesByEndedAuctions($connection);
while ($row = $categoriesResult->fetch_assoc()) {
    $topCategories[] = $row;
}
?>
<html>
<head>
    <title>Auction Results</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>Hello, <?php echo $name; ?></h2>
    <a href="dashboard.php">Dashboard</a> |
    <a href="../Controller/logout.php">Logout</a>

    <h3>Admin Analytics Dashboard</h3>
    <table border="1">
        <tr>
            <th>Total Active Auctions</th>
            <th>Total Ended Auctions</th>
            <th>Total Bids Placed</th>
            <th>Highest Value Sale</th>
        </tr>
        <tr>
            <td><?php echo $stats["total_active"]; ?></td>
            <td><?php echo $stats["total_ended"]; ?></td>
            <td><?php echo $stats["total_bids"]; ?></td>
            <td><?php echo $stats["highest_sale"]; ?></td>
        </tr>
    </table>

    <h3>Top 5 Categories by Completed Auctions</h3>
    <canvas id="categoryChart" width="600" height="300"></canvas>

    <script>
        var labels = <?php echo json_encode(array_column($topCategories, "category_name")); ?>;
        var data = <?php echo json_encode(array_column($topCategories, "total")); ?>;
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
</body>
</html>