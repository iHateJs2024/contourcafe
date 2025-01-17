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

// Check if idmakanan and idkategori are provided in the URL
if (isset($_GET['idmakanan']) && isset($_GET['idkategori'])) {
  $idmakanan = $_GET['idmakanan'];
  $idkategori = $_GET['idkategori'];

  // Fetch the item's current data
  $sql = "SELECT * FROM makanan WHERE idmakanan = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $idmakanan);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $item = $result->fetch_assoc();

    // Remove the 'RM' prefix from the prices for editing
    $item['harga'] = number_format((float)str_replace("RM", "", $item['harga']), 2, '.', '');
    $item['harga_pembungkusan'] = number_format((float)str_replace("RM", "", $item['harga_pembungkusan']), 2, '.', '');
  } else {
    echo "Item not found.";
    exit();
  }

  // Fetch all categories for the dropdown
  $sql_categories = "SELECT idkategori, namakategori FROM kategori";
  $result_categories = $conn->query($sql_categories);

  $categories = [];
  while ($row = $result_categories->fetch_assoc()) {
    $categories[] = $row;
  }
} else {
  echo "Missing item or category ID.";
  exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $namamakanan = $_POST['namamakanan'];
  $maklumat_makanan = $_POST['maklumat_makanan'];
  $harga = $_POST['harga'];
  $harga_pembungkusan = $_POST['harga_pembungkusan'];
  $status_makanan = $_POST['status_makanan'];
  $idkategori_new = $_POST['idkategori'];

  // Add 'RM' prefix and format to two decimal places
  $harga = "RM" . number_format((float)$harga, 2, '.', '');
  $harga_pembungkusan = "RM" . number_format((float)$harga_pembungkusan, 2, '.', '');

  // Handle file upload
  if (!empty($_FILES['gambar_makanan']['name'])) {
    $file_name = $_FILES['gambar_makanan']['name'];
    $file_tmp = $_FILES['gambar_makanan']['tmp_name'];

    // Save the file in the Food preview folder
    $gambar_makanan = "Food preview/" . $file_name;

    // Ensure directory exists
    if (!is_dir("Food preview")) {
      mkdir("Food preview", 0777, true);
    }

    // Move file to folder
    move_uploaded_file($file_tmp, $gambar_makanan);
  } else {
    $gambar_makanan = $item['gambar_makanan']; // Keep the existing image if none is uploaded
  }

  // Update the item's details in the database
  $sql_update = "UPDATE makanan SET namamakanan = ?, maklumat_makanan = ?, harga = ?, harga_pembungkusan = ?, status_makanan = ?, gambar_makanan = ? WHERE idmakanan = ?";
  $stmt_update = $conn->prepare($sql_update);
  $stmt_update->bind_param("sssssss", $namamakanan, $maklumat_makanan, $harga, $harga_pembungkusan, $status_makanan, $gambar_makanan, $idmakanan);

  if ($stmt_update->execute()) {
    // Update kategori_makanan table to reflect new kategori
    $sql_update_kategori = "UPDATE kategori_makanan SET idkategori = ? WHERE idmakanan = ?";
    $stmt_update_kategori = $conn->prepare($sql_update_kategori);
    $stmt_update_kategori->bind_param("ss", $idkategori_new, $idmakanan);
    $stmt_update_kategori->execute();

    // Redirect back to the show category items page
    header("Location: admin-show-category-items.php?idkategori=" . $idkategori);
    exit();
  } else {
    echo "Error updating item.";
  }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <!-- Important to make website responsive-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contour Cafe'</title>

  <!--Barlow fonts-->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700&display=swap"
    rel="stylesheet" />

  <!--Link the CSS file-->
  <link rel="stylesheet" href="../css/menu.css" />
  <link rel="stylesheet" href="../css/general.css" />
  <link rel="stylesheet" href="../css/basket-items.css" />
  <link rel="stylesheet" href="../css/modal-food-preview.css" />
  <link rel="stylesheet" href="../css/scrollbar.css" />
  <link rel="stylesheet" href="css/admin-menu.css" />
  <link rel="stylesheet" href="css/admin-manage-category.css" />
  <link rel="stylesheet" href="css/admin-edit-category.css" />
  <link rel="stylesheet" href="css/admin-show-category-items.css" />
  <link rel="stylesheet" href="css/admin-edit-item.css" />
  <link rel="stylesheet" href="../css/mobile-navigation.css" />
  <link rel="stylesheet" href="css/admin-mobile-navigation-bar.css" />
  <link rel="icon" type="image/jpg" href="../Logo image/Contour Cafe’.jpg" />

  <!--Link the icons file-->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
</head>

<body id="body">
  <!--Navigation bar starts here-->
  <header>
    <div class="main-header">
      <!--Middle section starts here-->
      <div class="middle-section">
        <!--Logo section-->
        <a class="logo-link" href="admin-index.php">
          <img
            class="contour-cafe-logo"
            src="../Logo image/Contour Cafe’.jpg"
            alt="" />
        </a>
        <!--Links section-->
        <nav class="links-section">
          <ul id="ul-header" class="ul-header"></ul>
        </nav>

        <!-- Mobile version menu button -->
        <button class="navbar-toggler" id="navbar-toggler">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="undefined">
            <path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z" />
          </svg>
        </button>

      </div>
      <!--Middle section ends here-->
    </div>
  </header>
  <!--Navigation bar ends here-->

  <!-- Mobile version nav bar starts here! -->
  <div class="nav-bar-mobile">
    <div>
      <button class="nav-bar-mobile-close-button">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="undefined">
          <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z" />
        </svg>
      </button>
    </div>
    <nav class="nav-bar-mobile-links-section">
      <ul id="js-ul__mobile" class="ul__mobile"></ul>
    </nav>
  </div>
  <!-- Mobile version nav bar ends here! -->

  <!-- Main content starts here! -->
  <main class="main">
    <section class="main-section">
      <div class="title-and-add-category-button">
        <p class="main-title">
          <a href="admin-show-category-items.php?idkategori=<?php echo $idkategori; ?>">
            <img src="../icons/popup-back-icon.svg" alt="">
          </a>
          Edit Item
        </p>
        <button class="delete-button" onclick="confirmDelete('<?php echo $idmakanan; ?>', '<?php echo $idkategori; ?>')">Buang Item</button>
      </div>

      <form id="form-edit-item" class="form-edit-item" action="" method="POST" enctype="multipart/form-data">
        <div class="left-item-details-container">
          <p>Nama Item:</p>
          <input type="text" id="namamakanan" name="namamakanan" value="<?php echo $item['namamakanan']; ?>" required><br>

          <p>Maklumat Makanan:</p>
          <textarea id="maklumat_makanan" name="maklumat_makanan"><?php echo $item['maklumat_makanan']; ?></textarea><br>

          <p>Harga (RM):</p>
          <input type="number" id="harga" name="harga" step="0.01" value="<?php echo $item['harga']; ?>" min="0" required><br>

          <p>Harga Pembungkusan (RM):</p>
          <input type="number" id="harga_pembungkusan" name="harga_pembungkusan" step="0.01" value="<?php echo $item['harga_pembungkusan']; ?>" min="0" required><br>

          <p for="idkategori">Kategori:</p>
          <select id="idkategori" name="idkategori" required>
            <!-- Preselect the current kategori -->
            <?php
            // Find the current kategori name
            $current_kategori_name = "";
            foreach ($categories as $category) {
              if ($category['idkategori'] === $idkategori) {
                $current_kategori_name = $category['namakategori'];
                break;
              }
            }
            ?>
            <option value="<?php echo $idkategori; ?>" selected>
              Sekarang: <?php echo $current_kategori_name; ?>
            </option>
            <!-- List other categories -->
            <?php foreach ($categories as $category): ?>
              <?php if ($category['idkategori'] !== $idkategori): ?>
                <option value="<?php echo $category['idkategori']; ?>">
                  <?php echo $category['namakategori']; ?>
                </option>
              <?php endif; ?>
            <?php endforeach; ?>
          </select><br>

          <p>Status:</p>
          <select id="status_makanan" name="status_makanan" required>
            <option value="Ada" <?php echo $item['status_makanan'] === 'Ada' ? 'selected' : ''; ?>>Ada</option>
            <option value="Habis" <?php echo $item['status_makanan'] === 'Habis' ? 'selected' : ''; ?>>Habis</option>
            <option value="Istimewa" <?php echo $item['status_makanan'] === 'Istimewa' ? 'selected' : ''; ?>>Istimewa</option>
          </select>
        </div>

        <div class="right-item-details-container">
          <p>Gambar Makanan:</p>
          <div class="image-preview-container">
            <img
              class="image_preview"
              id="image_preview"
              src="<?= $item['gambar_makanan']; ?>"
              alt="Image preview">
            <br>
            <input style="display: none;" type="file" id="gambar_makanan" name="gambar_makanan" accept="image/*">
            <label class="upload-image-label" for="gambar_makanan">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path d="M288 109.3L288 352c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-242.7-73.4 73.4c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l128-128c12.5-12.5 32.8-12.5 45.3 0l128 128c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L288 109.3zM64 352l128 0c0 35.3 28.7 64 64 64s64-28.7 64-64l128 0c35.3 0 64 28.7 64 64l0 32c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64l0-32c0-35.3 28.7-64 64-64zM432 456a24 24 0 1 0 0-48 24 24 0 1 0 0 48z" />
              </svg>
              Muat Naik Gambar
            </label>
          </div>
        </div>
      </form>
      <div class="submit-button-container">
        <button form="form-edit-item" class="submit-button" type="submit">Save Changes</button>
      </div>
    </section>
  </main>
  <!-- Main content ends here! -->

  <!--Footer starts here!-->
  <footer>
    <div class="footer">
      <div style="display: flex; justify-content: center">
        <div class="logo-and-socialmedia-icons-div">
          <div>
            <a href="admin-index.php">
              <img
                class="logo-image-footer"
                src="../Logo image/Contour Cafe’.jpg"
                alt="" />
            </a>
          </div>
          <div class="socialmedia-icons-div">
            <div class="socialmedia-icons-subdiv">
              <a
                class="footer-icon-link"
                href="https://www.instagram.com/contour.pj/?hl=en"
                target="_blank">
                <img class="insta-icon" src="../icons/insta.svg" alt="" />
              </a>
              <a
                class="footer-icon-link"
                href="https://www.facebook.com/p/ContourPJ-61551751387405/"
                target="_blank">
                <img class="fb-icon" src="../icons/fb.svg" alt="" />
              </a>
            </div>
          </div>
        </div>
      </div>

      <hr class="footer-hr" style="width: 1103.6px" />

      <div class="terms-and-conditions-join-us-div">
        <div class="terms-and-conditions-join-us-subdiv">
          <div>
            <p>Sah</p>
            <a href="admin-privacy-policy.html" class="a-footer-link">Dasar Privasi</a>
            <a href="admin-terms-and-conditions.html" class="a-footer-link">Syarat Penggunaan</a>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!--Footer ends here!-->

  <!--Link the JS file-->
  <script>
    //! Function to show image preview when an image is chosen
    document.getElementById("gambar_makanan").addEventListener("change", function(event) {
      let image = document.getElementById("image_preview"); // The img tag for preview
      let file = event.target.files[0]; // The file object selected by the user

      if (file) {
        let reader = new FileReader();

        // When the file is read, set the image source to the result (image data URL)
        reader.onload = function(e) {
          image.src = e.target.result;
        }

        reader.readAsDataURL(file); // Read the file as a data URL
      }
    });

    //! FUNCTION TO Confirm delete and redirect to delete handler
    function confirmDelete(idmakanan, idkategori) {
      if (confirm("Are you sure you want to delete this item?")) {
        // Redirect to the PHP file that processes deletion with idmakanan as query parameter
        window.location.href = "process-delete-item.php?idmakanan=" + idmakanan + "&idkategori=" + idkategori;
      }
    }
  </script>
  <script src="data/HeaderLinksDataAdmin.js"></script>
  <script src="js/generateHeaderLinksAdmin.js"></script>
  <script src="data/mobileHeaderLinksDataAdmin.js"></script>
  <script src="js/generateMobileHeaderLinksAdmin.js"></script>
  <script src="js/admin-mobile-navigation-bar.js"></script>
</body>

</html>