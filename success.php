<?php
include('connect.php'); // Ensure this file connects properly to your DB
session_start();

// Include the database connection (replace with actual DB connection details)
$host = "localhost";
$username = "root";
$password = "";
$dbname = "tempahan"; // Replace with your database name

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

//! PHP TO CHECK IF USER exists in db and logged in
if (!isset($_SESSION['idpelanggan'])) {
  echo '<script>alert("Sila Log masuk/Daftar!");
                window.location.href = "signup.php";
        </script>';
  exit();
} else {
  $idpelanggan = $_SESSION['idpelanggan'];

  $query = "SELECT idpelanggan FROM pelanggan WHERE idpelanggan = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $idpelanggan);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    //? The user exists in db and logged in!
  } else {
    //? The user does not exists in db and not logged in!
    echo '<script>alert("Sila Log masuk/Daftar!");
                  window.location.href = "signup.php";
          </script>';
  }

  // Close the statement
  $stmt->close();
}

require 'vendor/autoload.php';

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
  $total_amount = $session->amount_total / 100;
  $idpelanggan = $_SESSION['idpelanggan'] ?? 'DefaultUser';
  $orders = $_SESSION['order'] ?? [];

  if (empty($orders)) {
    die('No order data available.');
  }

  // Database connection
  $mysqli = new mysqli('localhost', 'root', '', 'tempahan');
  if ($mysqli->connect_error) {
    die('Database connection failed: ' . $mysqli->connect_error);
  }

  // Process each order item
  foreach ($orders as $order) {
    $foodName = $order['name'];
    $quantity = $order['quantity'];
    $notes = $order['notes'];

    if ($notes === "Notes: undefined") {
      $notes = "";
    } else {
      $notes = substr($notes, 8); // Remove "Notes: " prefix
    }

    // Set timezone to Malaysia
    date_default_timezone_set('Asia/Kuala_Lumpur');

    // Get the current date and time
    $tarikh = date('Y-m-d H:i:s'); // Format: 2024-12-28 14:30:00

    // Check if tarikh is in correct format
    if (!$tarikh || !strtotime($tarikh)) {
      die('Invalid date format.');
    }

    // Fetch `idmakanan` by name
    $stmt = $mysqli->prepare("SELECT idmakanan FROM makanan WHERE namamakanan = ?");
    if (!$stmt) {
      error_log("Preparation failed: " . $mysqli->error);
      die("Database error occurred. Please contact admin.");
    }

    $stmt->bind_param('s', $foodName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $idmakanan = $row['idmakanan'];

      // Insert into `tempahan`
      $insert_stmt = $mysqli->prepare("INSERT INTO tempahan (idpelanggan, idmakanan, tarikh, bilangan, nota) VALUES (?, ?, ?, ?, ?)");
      if (!$insert_stmt) {
        error_log("Insert preparation failed: " . $mysqli->error);
        continue;
      }

      $insert_stmt->bind_param('sssis', $idpelanggan, $idmakanan, $tarikh, $quantity, $notes);
      if (!$insert_stmt->execute()) {
        error_log("Insert execution failed: " . $insert_stmt->error);
        continue;
      }

      $insert_stmt->close();
    } else {
      error_log("Food not found in database: $foodName");
      echo "<script>
        alert('Food not found in database: " . addslashes($foodName) . "');
        window.location.href = 'user-menu.php';
      </script>";
    }

    $stmt->close();
  }

  $mysqli->close();

  // Database connection
  $conn = new mysqli("localhost", "root", "", "tempahan");

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  error_log(print_r($_SESSION['customer_details'], true));
  error_log($_SESSION['idpelanggan']);

  // Check if the session contains the required data
  if (isset($_SESSION["customer_details"]) && isset($_SESSION["idpelanggan"])) {
    // Extract session details
    $customerDetails = $_SESSION["customer_details"];
    $idpelanggan = $_SESSION["idpelanggan"];

    $deliveryAddress = $customerDetails->deliveryAddress;
    $additionalNotes = $customerDetails->additionalNotes;
    $subtotal = $customerDetails->subtotal;
    $tax = $customerDetails->tax;
    $totalItems = $customerDetails->totalItems;

    $currentDateTime = date("Y-m-d H:i:s"); // Get current date and time
    $order_status = 'Pending';
    $subtotal = 'RM' . number_format($subtotal, 2);
    $tax = 'RM' . number_format($tax, 2);
    $total_amount = 'RM' . number_format($total_amount, 2);

    // Insert into maklumat_pesanan table
    $query = "INSERT INTO maklumat_pesanan (idpelanggan, tarikh, nota_pesanan, status_pesanan, jumlah_item, subtotal, tax, total) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssisss", $idpelanggan, $currentDateTime, $additionalNotes, $order_status, $totalItems, $subtotal, $tax, $total_amount);

    // Insert into pelanggan table
    $query = "UPDATE pelanggan SET alamat_pelanggan = ? WHERE idpelanggan =  ?";
    $stmtPelanggan = $conn->prepare($query);
    $stmtPelanggan->bind_param("ss", $deliveryAddress, $idpelanggan);

    if ($stmtPelanggan->execute()) {
    } else {
      echo "Error: " . $stmtPelanggan->error;
    }

    // Store data in session to use for displaying in another page
    $_SESSION['payment_data'] = [
      'tarikh' => $tarikh,
      'orders' => $orders,
      'total_amount' => $total_amount,
      'subtotal' => $subtotal,
      'tax' => $tax,
      'totalItems' => $totalItems,
    ];

    if ($stmt->execute()) {
    } else {
      echo "Error: " . $stmt->error;
    }

    $stmt->close();
  } else {
    echo "Customer details or ID not found in session!";
  }

  $conn->close();

  // Redirect user to another page to display order summary
  header('Location: user-order-summary.php');
  exit();
} catch (Exception $e) {
  die('Error retrieving payment session: ' . htmlspecialchars($e->getMessage()));
}
