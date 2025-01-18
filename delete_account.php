<?php
// Start the session
session_start();

// Check if 'idpelanggan' exists in the session
if (isset($_SESSION['idpelanggan'])) {
  // Get idpelanggan from session
  $idpelanggan = $_SESSION['idpelanggan'];

  include_once(__DIR__ . '/connect.php');
  $conn = getConnection();

  // Start transaction
  $conn->begin_transaction();

  try {
    //! Delete from 'pelanggan' table
    $stmt = $conn->prepare("DELETE FROM pelanggan WHERE idpelanggan = ?");
    $stmt->bind_param("s", $idpelanggan);
    $stmt->execute();

    //! Delete from 'tempahan' table
    $stmt = $conn->prepare("DELETE FROM tempahan WHERE idpelanggan = ?");
    $stmt->bind_param("s", $idpelanggan);
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
    header("Location: index.php");
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
  header("Location: signup.php");
  exit();
}
