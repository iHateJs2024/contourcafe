<?php
//! PHP TO EDIT CATEGORY
include_once(__DIR__ . '/../connect.php');
$conn = getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idkategori'])) {
    $idkategori = $_POST['idkategori'];
    $kategori_name = $_POST['namakategori'];
    $image_path = "";

    // Handle file upload if an image is provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $image_name = basename($_FILES['image']['name']);
        $target_file = $upload_dir . $image_name;

        // Move the uploaded file
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Save the full path to the database
            $image_path = $upload_dir . $image_name;
        } else {
            echo "Error uploading the image.";
            exit;
        }
    }

    // Update the category in the database
    if ($image_path) {
        $sql = "UPDATE kategori SET namakategori = ?, gambar_kategori = ? WHERE idkategori = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $kategori_name, $image_path, $idkategori);
    } else {
        $sql = "UPDATE kategori SET namakategori = ? WHERE idkategori = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $kategori_name, $idkategori);
    }

    if ($stmt->execute()) {
        echo "Category updated successfully!";
        header("Location: http://contourcafe.great-site.net/admin/admin-manage-category.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
