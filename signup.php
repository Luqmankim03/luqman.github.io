<?php
session_start();

$host = "localhost:3308";
$user = "root";
$password = "";
$db = "SWC2363";

$conn = mysqli_connect($host, $user, $password, $db);

if (isset($_POST['Username'])) {

  $name = $_POST['FullName'];
  $username = $_POST['Username'];
  $phone = $_POST['Phone'];
  $password = $_POST['Password'];

  $check_query = "SELECT * FROM user WHERE User = ?";
  $check_stmt = mysqli_prepare($conn, $check_query);
  mysqli_stmt_bind_param($check_stmt, "s", $username);
  mysqli_stmt_execute($check_stmt);
  $check_result = mysqli_stmt_get_result($check_stmt);

  if (mysqli_num_rows($check_result) == 0) {
    $sql = "INSERT INTO user (FullName, User, PhoneNo, Pass) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
      die("Error creating statement: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "ssss", $name, $username, $phone, $password);
    mysqli_stmt_execute($stmt);

    if (mysqli_affected_rows($conn) == 1) {
      $_SESSION['Username'] = $username;
      header("Location: homepage.php");
      exit();
    } else {
      $error = "Failed to create account";
    }
  } else {
    echo "<script>alert('Duplicate user found. Please choose another username.');</script>";
  }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <link rel="icon" href="Logo.png" type="image/icon type">
  <meta charset="utf-8">
  <title>Sign Up Page</title>
  <link rel="stylesheet" href="signup.css">
</head>

<body>
  <div class="center">
    <h1>Sign Up</h1>
    <form method="post">
      <div class="txt_field">
        <input type="text" name="FullName" required>
        <span></span>
        <label>Full Name</label>
      </div>
      <div class="txt_field">
        <input type="text" name="Username" required>
        <span></span>
        <label>Username</label>
      </div>
      <div class="txt_field">
        <input type="tel" name="Phone" required pattern="[0-9]{3}-[0-9]{7}" title="Please enter a phone number in the format XXX-XXXXXXX">
        <span></span>
        <label>Phone Number</label>
      </div>
      <div class="txt_field">
        <input type="password" name="Password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+]).{8,}" title="Password must contain at least one number, one uppercase and lowercase letter, one symbol, and be at least 8 characters long">
        <span></span>
        <label>Password</label>
      </div>
      <input type="submit" value="Sign Up">
      <div class="login_link">
        Already have an account? <a href="login.php">Login</a>
      </div>
    </form>
  </div>
</body>
</html>

