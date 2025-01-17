<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "tempahan";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if idmakanan is set and valid
if (isset($_GET['idmakanan'])) {
    $idmakanan = $_GET['idmakanan'];

    // Delete related entries in kategori_makanan table
    $sql_kategori_makanan = "DELETE FROM kategori_makanan WHERE idmakanan = ?";
    $stmt_kategori_makanan = $conn->prepare($sql_kategori_makanan);
    $stmt_kategori_makanan->bind_param("s", $idmakanan);
    if (!$stmt_kategori_makanan->execute()) {
        echo "Error deleting from kategori_makanan table.";
        exit();
    }

    // Now, delete the item itself from the makanan table
    $sql_makanan = "DELETE FROM makanan WHERE idmakanan = ?";
    $stmt_makanan = $conn->prepare($sql_makanan);
    $stmt_makanan->bind_param("s", $idmakanan);
    if ($stmt_makanan->execute()) {
        // Optionally, redirect back to the category items page after successful deletion.
        header("Location: admin-show-category-items.php?idkategori=" . $_GET['idkategori']);
        exit();
    } else {
        echo "Error deleting item from makanan table.";
    }
}

$conn->close();
