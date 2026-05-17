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
    
    </table>

    <h3>Top 5 Categories by Completed Auctions</h3>
    <canvas id="categoryChart" width="600" height="300"></canvas>

    <script>
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
