<?php
session_start();

// Initialize database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "butterfly_db1";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
  $user_id = $_SESSION['user_id'];
  $otp = mysqli_real_escape_string($conn, $_POST['otp']);

  // Check if OTP is valid
  $sql = "SELECT * FROM verification_status WHERE user_id=$user_id AND otp='$otp' AND status=0 AND created_at >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    // Update verification statu

    // Update user status
    $sql = "UPDATE user SET status=1 WHERE id=$user_id";
    mysqli_query($conn, $sql);

    echo "Verification successful.";
  } else {
    echo "Invalid OTP or OTP has expired.";
  }
} else if (isset($_POST['otp'])) {
  $user_otp = $_POST['otp'];
  if ($user_otp == $_SESSION['otp']) {
    // Update the verification status in the database
    $user_id = $_SESSION['user_id'];
   
    // Update the user status in the database
    $sql = "UPDATE user SET verification_status=1 WHERE id=$user_id";
    mysqli_query($conn, $sql);
    
    echo 'OTP verification successful!';
    // Add code to log the user in or redirect them to a new page
  } else {
    echo 'Invalid OTP code. Please try again.';
  }
}

// Close database connection
mysqli_close($conn);
?>
