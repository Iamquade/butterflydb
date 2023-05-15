<?php
// Connect to database
$host = "localhost";
$username = "root";
$password = "";
$dbname = "butterfly_db1";

$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Process registration form
if (isset($_POST['submit'])) {
  // Get user inputs
  $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
  $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
  $business_name = mysqli_real_escape_string($conn, $_POST['business_name']);
  $address = mysqli_real_escape_string($conn, $_POST['address']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);
  $repeat_password = mysqli_real_escape_string($conn, $_POST['repeat_password']);
  $farm_permit = mysqli_real_escape_string($conn, $_POST['farm_permit']);
  $collector_permit = mysqli_real_escape_string($conn, $_POST['collector_permit']);

  // Check if passwords match
  if ($password !== $repeat_password) {
    echo "Passwords do not match.";
    exit();
  }

  // Check if username already exists
  $sql = "SELECT * FROM user WHERE username='$username'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
    echo "Username already taken.";
    exit();
  }

  // Hash password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Insert user into database
 // Insert user into database
$sql = "INSERT INTO user (first_name, last_name, business_name, address, email, contact_number, username, password, farm_permit, collector_permit)
VALUES ('$first_name', '$last_name', '$business_name', '$address', '$email', '$contact_number', '$username', '$hashed_password', '$farm_permit', '$collector_permit')";

if (mysqli_query($conn, $sql)) {
// Retrieve the user ID
$user_id = mysqli_insert_id($conn);

session_start();
$_SESSION['user_id'] = $user_id;

echo "Registration successful.";
} else {
echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
}
// Include PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php';

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

// Generate random OTP code
$otp = mt_rand(100000, 999999);

// Store OTP code in session variable
session_start();
$_SESSION['otp'] = $otp;

// Send OTP code to user's email address
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'butterflyonetwo3@gmail.com'; // Your Gmail email address
    $mail->Password = 'yopcnwloqzdwnala'; // Your Gmail password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('butterflynetwo3@gmail.com', 'Your Name'); // Your Name and your Gmail email address
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'OTP Verification';
    $mail->Body = 'Your OTP code is: ' . $otp;

    $mail->send();
    echo 'An OTP has been sent to your email address.';
    header("Location: verify_form.php");

} catch (Exception $e) {
    echo 'Unable to send OTP. Please try again later.';

}
// Close database connection
mysqli_close($conn);
?>
