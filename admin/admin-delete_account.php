<?php
// Start the session
session_start();

// Check if 'idpelanggan' exists in the session
if (isset($_SESSION['idjurujual'])) {
  // Get idpelanggan from session
  $idjurujual = $_SESSION['idjurujual'];

  // Database connection (Replace with your DB credentials)
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "tempahan";

  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check the connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Start transaction
  $conn->begin_transaction();

  try {
    //! Delete from 'pelanggan' table
    $stmt = $conn->prepare("DELETE FROM jurujual WHERE idjurujual = ?");
    $stmt->bind_param("s", $idjurujual);
    $stmt->execute();

    // Commit the transaction
    $conn->commit();

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Clear all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to another page
    header("Location: ../index.php");
    exit();
  } catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();

    // Close the connection
    $conn->close();

    // Optionally show an error message
    die("Error deleting account: " . $e->getMessage());
  }
} else {
  // Redirect to login or error page if 'idpelanggan' is not set
  header("Location: ../signup.php");
  exit();
}
