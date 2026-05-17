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
    <h3>Auction Results</h3>

</body>
</html>