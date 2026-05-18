<?php
session_start();

$isLoggedIn = $_SESSION["isLoggedIn"] ?? false;

if (!$isLoggedIn) {
    Header("Location: login.php");
    exit();
}

$uploadErr = $_SESSION["uploadErr"] ?? "";
$uploadSuccess = $_SESSION["uploadSuccess"] ?? "";
$uploaded_image_path = $_SESSION["uploaded_image_path"] ?? "";

unset($_SESSION["uploadErr"]);
unset($_SESSION["uploadSuccess"]);
unset($_SESSION["uploaded_image_path"]);
?>
<html>
<head>
    <title>Upload Image</title>
</head>
<body>
    <h2>Upload Image</h2>
    <a href="dashboard.php">Back to Dashboard</a>

    <?php if ($uploadSuccess) { echo "<p style='color:green;'>$uploadSuccess</p>"; } ?>
    <?php if ($uploadErr) { echo "<p style='color:red;'>$uploadErr</p>"; } ?>

    <form method="post" action="../Controller/UploadHandler.php" enctype="multipart/form-data">
        <table>
            <tr>
                <td>Upload Image (JPEG/PNG max 3MB)</td>
                <td><input type="file" name="image"/></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="submit" value="Upload"/></td>
            </tr>
        </table>
    </form>

    <?php if ($uploaded_image_path) { ?>
        <p>Uploaded Image:</p>
        <img src="<?php echo $uploaded_image_path; ?>" height="200px" width="200px"/>
    <?php } ?>
</body>
</html>