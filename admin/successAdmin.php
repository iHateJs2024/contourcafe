<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "tempahan";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

//! PHP TO CHECK IF USER exists in db and logged in
if (!isset($_SESSION['idjurujual'])) {
  //? The user does not exists in db and not logged in!
  echo '<script>alert("Sila Log masuk/Daftar!");
                window.location.href = "../signup.php";
        </script>';
  exit();
} else {
  $idjurujual = $_SESSION['idjurujual'];

  $query = "SELECT idjurujual FROM jurujual WHERE idjurujual = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $idjurujual);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    //? The user exists in db and logged in!
  } else {
    //? The user does not exists in db!
    echo '<script>alert("Sila Log masuk/Daftar!");
                window.location.href = "../signup.php";
        </script>';
  }

  // Close the statement
  $stmt->close();
}

require '../vendor/autoload.php';

// Stripe secret key
\Stripe\Stripe::setApiKey('sk_test_51QaSfSInQZmbpKG5NqiLHw71WDrGhzUXtKtvGYzM7w1U7xbrXyHbE7t1mCIx2W7coPKCoyomOSEoIUwHDXuzXz4q00BOyyLkCt');

// Get session_id from the query string
$session_id = filter_input(INPUT_GET, 'session_id', FILTER_SANITIZE_STRING);

if (!$session_id) {
  die('Session ID is missing.');
}

try {
  // Retrieve Stripe session
  $session = \Stripe\Checkout\Session::retrieve($session_id);
  $total_amount = $session->amount_total / 100; // Convert total to MYR
  $orders = $_SESSION['order'] ?? [];

  if (empty($orders)) {
    die('No order data available.');
  }

  // Set timezone to Malaysia
  date_default_timezone_set('Asia/Kuala_Lumpur');

  // Get the current date and time
  $tarikh = date('Y-m-d H:i:s'); // Format: 2024-12-28 14:30:00

  // Check if tarikh is in correct format
  if (!$tarikh || !strtotime($tarikh)) {
    die('Invalid date format.');
  }

  // Store data in session to use for displaying in another page
  $_SESSION['payment_data'] = [
    'tarikh' => $tarikh,
    'orders' => $orders,
    'total_amount' => $total_amount
  ];

  // Redirect user to another page to display order summary
  header('Location: admin-order-summary.php');
  exit();
} catch (Exception $e) {
  die('Error retrieving payment session: ' . htmlspecialchars($e->getMessage()));
}
