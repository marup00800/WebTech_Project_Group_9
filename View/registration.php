<?php
session_start();

$nameError = $_SESSION["nameErr"] ?? "";
$emailError = $_SESSION["emailErr"] ?? "";
$phoneError = $_SESSION["phoneErr"] ?? "";
$passwordError = $_SESSION["passwordErr"] ?? "";

$name = $_SESSION["name"] ?? "";
$email = $_SESSION["email"] ?? "";
$phone = $_SESSION["phone"] ?? "";
$bio = $_SESSION["bio"] ?? "";

$isLoggedIn = $_SESSION["isLoggedIn"] ?? false;

if ($isLoggedIn) {
    Header("Location: auctionList.php");
    exit();
}

unset($_SESSION["nameErr"]);
unset($_SESSION["emailErr"]);
unset($_SESSION["phoneErr"]);
unset($_SESSION["passwordErr"]);
unset($_SESSION["name"]);
unset($_SESSION["email"]);
unset($_SESSION["phone"]);
unset($_SESSION["bio"]);
?>
<html>
<head>
    <title>Register</title>
    <script src="../Controller/JS/checkEmail.js"></script>
    <script src="../Controller/JS/checkUsername.js"></script>
    <script src="../Controller/JS/checkPassword.js"></script>
</head>
<body>
    <h2>Register</h2>
    <form method="post" action="../Controller/registrationValidation.php">
        <table>
            <tr>
                <td>Name</td>
                <td><input type="text" name="name" id="name" placeholder="Enter name" value="<?php echo $name; ?>" onkeyup="checkUsername()"/></td>
                <td><p style='color:red;'><?php echo $nameError; ?></p>
                    <p id="nameCheckResponse"></p></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type="text" name="email" id="email" placeholder="Enter email" value="<?php echo $email; ?>" onkeyup="checkEmail()"/></td>
                <td>
                    <p style='color:red;'><?php echo $emailError; ?></p>
                    <p id="emailCheckResponse"></p>
                </td>
            </tr>
                <td>Phone</td>
                <td><input type="text" name="phone" id="phone" placeholder="Enter phone" value="<?php echo $phone; ?>"/></td>
                <td><p style='color:red;'><?php echo $phoneError; ?></p></td>
            </tr>
            <tr>
                <td>Bio</td>
                <td><textarea name="bio" placeholder="Enter bio"><?php echo $bio; ?></textarea></td>
                <td></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="password" name="password" id="password" placeholder="Enter password (min 8 chars)" onkeyup="checkPassword()"/></td>
                <td>
                    <p style='color:red;'><?php echo $passwordError; ?></p>
                    <p id="passwordCheckResponse"></p>
                </td>
            <tr>
                <td></td>
                <td>Already have an account? <a href='login.php'>Login</a> Here</td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="submit"/></td>
            </tr>
        </table>
    </form>
</body>
</html>
