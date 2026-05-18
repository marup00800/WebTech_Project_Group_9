<?php
session_start();

$emailError = $_SESSION["emailErr"] ?? "";
$passwordError = $_SESSION["passwordErr"] ?? "";
$loginErr = $_SESSION["loginErr"] ?? "";
$email = $_SESSION["email"] ?? "";
$isLoggedIn = $_SESSION["isLoggedIn"] ?? false;

if ($isLoggedIn) {
    Header("Location: auctionList.php");
    exit();
}

unset($_SESSION["emailErr"]);
unset($_SESSION["passwordErr"]);
unset($_SESSION["loginErr"]);
unset($_SESSION["email"]);
?>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post" action="../Controller/loginValidation.php">
        <table>
            <tr>
                <td>Email</td>
                <td><input type="text" name="email" placeholder="Enter email" value="<?php echo $email; ?>"/></td>
                <td><p style='color:red;'><?php echo $emailError; ?></p></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="password" name="password" placeholder="Enter password"/></td>
                <td><p style='color:red;'><?php echo $passwordError; ?></p></td>
            </tr>
            <tr>
                <td></td>
                <td><p style='color:red;'><?php echo $loginErr; ?></p></td>
            </tr>
            <tr>
                <td></td>
                <td>Don't have an account? <a href='registration.php'>Sign Up</a> Here</td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="submit"/></td>
            </tr>
        </table>
    </form>
</body>
</html>