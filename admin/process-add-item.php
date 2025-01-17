<?php
//! PHP TO ADD ITEM INTO KATEGORI
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "tempahan";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $namamakanan = $_POST['namamakanan'];
    $maklumat_makanan = $_POST['maklumat_makanan'];
    $harga = $_POST['harga'];
    $harga_pembungkusan = $_POST['harga_pembungkusan'];
    $status_makanan = $_POST['status_makanan'];
    $idkategori = $_POST['idkategori'];
    $gambar_makanan = "";

    // Retrieve input
    $harga = $_POST['harga'];
    $harga_pembungkusan = $_POST['harga_pembungkusan'];

    // Check for negative values
    if ($harga < 0 || $harga_pembungkusan < 0) {
        die("Harga and Harga Pembungkusan cannot be negative.");
    }

    // Generate the next idmakanan
    $idmakanan = "";
    $result_last_id = $conn->query("SELECT idmakanan FROM makanan ORDER BY idmakanan DESC LIMIT 1");
    if ($result_last_id->num_rows > 0) {
        $last_row = $result_last_id->fetch_assoc();
        $last_id = $last_row['idmakanan'];
        // Extract numeric part and increment
        $num_part = (int) substr($last_id, 1); // Assume format is "M01", "M02", etc.
        $idmakanan = "M" . str_pad($num_part + 1, 2, "0", STR_PAD_LEFT);
    } else {
        $idmakanan = "M01"; // Start with M01 if no rows exist
    }

    // Handle image upload
    if (isset($_FILES['gambar_makanan']) && $_FILES['gambar_makanan']['error'] === UPLOAD_ERR_OK) {
        // Define both upload directories
        $admin_upload_dir = 'Food image/';
        $main_upload_dir = '../Food image/';
        $image_name = basename($_FILES['gambar_makanan']['name']);

        // Set the path that will be stored in database (using admin path)
        $gambar_makanan = $admin_upload_dir . $image_name;

        // Create directories if they don't exist
        if (!is_dir($admin_upload_dir)) {
            mkdir($admin_upload_dir, 0777, true);
        }
        if (!is_dir($main_upload_dir)) {
            mkdir($main_upload_dir, 0777, true);
        }

        // Copy file to both locations
        if (move_uploaded_file($_FILES['gambar_makanan']['tmp_name'], $admin_upload_dir . $image_name)) {
            // Copy the file to the main directory
            if (!copy($admin_upload_dir . $image_name, $main_upload_dir . $image_name)) {
                die("Error copying the image to main directory.");
            }
        } else {
            die("Error uploading the image.");
        }
    } else {
        die("Image upload failed.");
    }

    // Format harga and harga_pembungkusan
    function format_price($price)
    {
        return "RM" . number_format((float) $price, 2, '.', '');
    }

    $harga = format_price($harga);
    $harga_pembungkusan = format_price($harga_pembungkusan);

    // Insert into makanan table
    $sql_makanan = "INSERT INTO makanan (idmakanan, namamakanan, maklumat_makanan, harga, harga_pembungkusan, status_makanan, gambar_makanan) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_makanan = $conn->prepare($sql_makanan);
    $stmt_makanan->bind_param("sssssss", $idmakanan, $namamakanan, $maklumat_makanan, $harga, $harga_pembungkusan, $status_makanan, $gambar_makanan);

    if ($stmt_makanan->execute()) {
        // Insert into kategori_makanan table
        $sql_kategori_makanan = "INSERT INTO kategori_makanan (idkategori, idmakanan) VALUES (?, ?)";
        $stmt_kategori_makanan = $conn->prepare($sql_kategori_makanan);
        $stmt_kategori_makanan->bind_param("ss", $idkategori, $idmakanan);

        if ($stmt_kategori_makanan->execute()) {
            echo "<script>
                    alert('Berjaya Tambah Item!');
                    window.location.href = 'admin-show-category-items.php?idkategori=$idkategori';
                </script>";
            exit();  // It's important to call exit after header to stop further execution
        } else {
            echo "Failed to add item to kategori_makanan.";
        }
    } else {
        echo "Failed to add item to makanan.";
    }
}

$conn->close();
