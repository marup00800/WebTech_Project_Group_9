<?php
include "../Model/db.php";
include "../Model/AuctionModel.php";
session_start();

$isLoggedIn = $_SESSION["isLoggedIn"] ?? false;
$seller_verified = $_SESSION["seller_verified"] ?? 0;

if (!$isLoggedIn) {
    Header("Location: login.php");
    exit();
}

if ($seller_verified != 1) {
    Header("Location: dashboard.php");
    exit();
}

$titleError = $_SESSION["titleErr"] ?? "";
$descriptionError = $_SESSION["descriptionErr"] ?? "";
$categoryError = $_SESSION["categoryErr"] ?? "";
$startingPriceError = $_SESSION["startingPriceErr"] ?? "";
$reservePriceError = $_SESSION["reservePriceErr"] ?? "";
$endDatetimeError = $_SESSION["endDatetimeErr"] ?? "";
$imageError = $_SESSION["imageErr"] ?? "";

$title = $_SESSION["title"] ?? "";
$description = $_SESSION["description"] ?? "";
$category_id = $_SESSION["category_id"] ?? "";
$starting_price = $_SESSION["starting_price"] ?? "";
$reserve_price = $_SESSION["reserve_price"] ?? "";
$end_datetime = $_SESSION["end_datetime"] ?? "";

unset($_SESSION["titleErr"]);
unset($_SESSION["descriptionErr"]);
unset($_SESSION["categoryErr"]);
unset($_SESSION["startingPriceErr"]);
unset($_SESSION["reservePriceErr"]);
unset($_SESSION["endDatetimeErr"]);
unset($_SESSION["imageErr"]);
unset($_SESSION["title"]);
unset($_SESSION["description"]);
unset($_SESSION["category_id"]);
unset($_SESSION["starting_price"]);
unset($_SESSION["reserve_price"]);
unset($_SESSION["end_datetime"]);

$db = new db();
$connection = $db->openConnection();
$auctionModel = new AuctionModel();
$categories = $auctionModel->getAllCategories($connection, "categories");
?>
<html>
<head>
    <title>Create Auction</title>
</head>
<body>
    <h2>Create Auction Listing</h2>
    <a href="dashboard.php">Back to Dashboard</a> |
    <a href="../Controller/logout.php">Logout</a>

    <form method="post" action="../Controller/AuctionController.php" enctype="multipart/form-data">
        <table>
            <tr>
                <td>Title</td>
                <td><input type="text" name="title" placeholder="Enter title" value="<?php echo $title; ?>"/></td>
                <td><p style='color:red;'><?php echo $titleError; ?></p></td>
            </tr>
            <tr>
                <td>Description</td>
                <td><textarea name="description" placeholder="Enter description"><?php echo $description; ?></textarea></td>
                <td><p style='color:red;'><?php echo $descriptionError; ?></p></td>
            </tr>
            <tr>
                <td>Category</td>
                <td>
                    <select name="category_id">
                        <option value="">Select Category</option>
                        <?php
                        while ($row = $categories->fetch_assoc()) {
                            $selected = ($row["id"] == $category_id) ? "selected" : "";
                            echo "<option value='" . $row["id"] . "' $selected>" . $row["name"] . "</option>";
                        }
                        ?>
                    </select>
                </td>
                <td><p style='color:red;'><?php echo $categoryError; ?></p></td>
            </tr>
            <tr>
                <td>Starting Price</td>
                <td><input type="number" name="starting_price" placeholder="Enter starting price" value="<?php echo $starting_price; ?>" step="0.01" min="0"/></td>
                <td><p style='color:red;'><?php echo $startingPriceError; ?></p></td>
            </tr>
            <tr>
                <td>Reserve Price (Optional)</td>
                <td><input type="number" name="reserve_price" placeholder="Enter reserve price" value="<?php echo $reserve_price; ?>" step="0.01" min="0"/></td>
                <td><p style='color:red;'><?php echo $reservePriceError; ?></p></td>
            </tr>
            <tr>
                <td>End Date & Time</td>
                <td><input type="datetime-local" name="end_datetime" value="<?php echo $end_datetime; ?>"/></td>
                <td><p style='color:red;'><?php echo $endDatetimeError; ?></p></td>
            </tr>
            <tr>
                <td>Image (JPEG/PNG max 3MB)</td>
                <td><input type="file" name="image"/></td>
                <td><p style='color:red;'><?php echo $imageError; ?></p></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="submit" value="Create Listing"/></td>
            </tr>
        </table>
    </form>
</body>
</html>