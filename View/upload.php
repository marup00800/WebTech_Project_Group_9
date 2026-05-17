<html>
<head>
    <title>Upload Image</title>
</head>
<body>
    <h2>Upload Image</h2>
    <a href="dashboard.php">Back to Dashboard</a>

    <

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

    
        <p>Uploaded Image:</p>
       
</body>
</html>
