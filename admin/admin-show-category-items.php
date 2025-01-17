<?php
session_start();
//! PHP TO GET ALL THE COLUMNS FROM kategori table based on idkategori
// Database connection setup
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

// Get idkategori from URL
if (isset($_GET['idkategori'])) {
  $idkategori = $_GET['idkategori'];

  // Fetch the category's details
  $sql = "SELECT * FROM kategori WHERE idkategori = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $idkategori);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $kategori = $result->fetch_assoc();
  } else {
    echo "Category not found!";
    exit;
  }
} else {
  echo "No category selected!";
  exit;
}

//! Fetch item counts for each kategori
$kategori_item_count = 0;
// Prepare the SQL query to count the items in kategori_makanan for each idkategori
$sql_item_count = "SELECT COUNT(*) AS item_count FROM kategori_makanan WHERE idkategori = ?";

// Prepare the statement
$stmt = $conn->prepare($sql_item_count);

// Bind the idkategori parameter to the prepared statement
$stmt->bind_param("s", $kategori['idkategori']);

// Execute the query
$stmt->execute();

// Get the result
$result_item_count = $stmt->get_result();

// Check if there are any rows in the result
if ($result_item_count->num_rows > 0) {
  // Fetch the item count
  $kategori_item_count = $result_item_count->fetch_assoc()['item_count'];
} else {
  // If no rows are found, default the count to 0
  $kategori_item_count = 0;
}

$conn->close();
?>

<?php
//! PHP TO GET ALL THE ITEMS IN KATEGORI
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "tempahan";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get idkategori from the URL
if (isset($_GET['idkategori'])) {
  $idkategori = $_GET['idkategori'];

  // Fetch idmakanan for the specified idkategori
  $sql_kategori_makanan = "SELECT idmakanan FROM kategori_makanan WHERE idkategori = ?";
  $stmt_kategori_makanan = $conn->prepare($sql_kategori_makanan);
  $stmt_kategori_makanan->bind_param("s", $idkategori); // 's' since idkategori is a string
  $stmt_kategori_makanan->execute();
  $result_kategori_makanan = $stmt_kategori_makanan->get_result();

  $items = [];
  if ($result_kategori_makanan->num_rows > 0) {
    while ($row = $result_kategori_makanan->fetch_assoc()) {
      $items[] = $row['idmakanan'];
    }
  }

  // If items found, fetch details from makanan table
  if (!empty($items)) {
    // Convert the array to a string of placeholders for the query
    $placeholders = implode(",", array_fill(0, count($items), "?"));

    $sql_makanan = "SELECT gambar_makanan, namamakanan, maklumat_makanan, harga, harga_pembungkusan, status_makanan, idmakanan 
                        FROM makanan WHERE idmakanan IN ($placeholders)";
    $stmt_makanan = $conn->prepare($sql_makanan);
    $stmt_makanan->bind_param(str_repeat("s", count($items)), ...$items); // 's' for strings

    $stmt_makanan->execute();
    $result_makanan = $stmt_makanan->get_result();
  } else {
  }
} else {
  echo "No category selected.";
  exit;
}
?>

<?php
//! PHP TO GET ALL THE CATEGORIES FOR THE DROPDOWN INPUT FIELD
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "tempahan";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get idkategori from the URL
$idkategori = isset($_GET['idkategori']) ? $_GET['idkategori'] : '';

// Fetch categories for the dropdown
$sql_kategori = "SELECT idkategori, namakategori FROM kategori";
$result_kategori = $conn->query($sql_kategori);

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
          <a href="admin-manage-category.php"><img src="../icons/popup-back-icon.svg" alt=""></a>
          <?= $kategori['namakategori']; ?>
        </p>
        <button id="add-item-button" class="add-item-button">Tambah item</button>

        <dialog id="add-item-dialog" class="food-preview-modal">
          <!--First Vertical Layout-->
          <div class="modal-title-and-close-button">
            <div class="modal-title">
              Tambah Item
            </div>
            <button
              class="close-button-food-preview-modal"
              id="close-dialog-button">
              x
            </button>
          </div>

          <!-- Main content -->
          <form id="add-item-form" class="form-dialog" action="process-add-item.php" method="post" enctype="multipart/form-data">
            <div class="modal-scrollable-content">
              <p>Nama Item:</p>
              <input type="text" id="namamakanan" name="namamakanan" required><br><br>

              <p>Maklumat Makanan:</p>
              <textarea id="maklumat_makanan" name="maklumat_makanan" required></textarea><br><br>

              <p>Harga (RM):</p>
              <input type="number" id="harga" name="harga" step="0.01" required><br><br>

              <p>Harga Pembungkusan (RM):</p>
              <input type="number" id="harga_pembungkusan" name="harga_pembungkusan" step="0.01" required><br><br>

              <p>Status <img title="Item status istimewa akan dipaparkan di laman utama" src="icons/tooltip.png" alt=""> :</p>
              <select id="status_makanan" name="status_makanan" required>
                <option value="Ada">Ada</option>
                <option value="Habis">Habis</option>
                <option value="Istimewa">Istimewa</option>
              </select><br><br>

              <p>Gambar Makanan:</p>
              <input class="input-item-image" type="file" id="gambar_makanan" name="gambar_makanan" accept="image/*" required>
              <img
                class="input-item-image-preview"
                id="image_preview"
                src=""
                alt="Image preview"
                style=" display: none; max-width: 100px;">
              <br>
              <label class="input-item-image-label" for="gambar_makanan">Pilih gambar makanan</label>
              <p class="error-message" id="error-message"></p>
              <br>

              <p>Kategori:</p>
              <select name="idkategori" required>
                <?php
                // Generate options
                while ($row = $result_kategori->fetch_assoc()) {
                  $selected = ($row['idkategori'] == $idkategori) ? "selected" : ""; // Select current kategori
                  echo "<option value='" . $row['idkategori'] . "' $selected>" . $row['namakategori'] . "</option>";
                }
                ?>
              </select>
            </div>
          </form>
          <!-- Add button layout -->
          <div
            class="
              modal-food-preview-add-to-basket-button add-button-container
            ">
            <button form="add-item-form" type="submit" class="add-button-dialog">Tambah Item</button>
          </div>
        </dialog>

      </div>
      <p class="total-items-count">
        Jumlah item: <?= $kategori_item_count; ?>
      </p>

      <div class="category-item-container">
        <?php if ($result_makanan && $result_makanan->num_rows > 0): ?>
          <?php while ($row = $result_makanan->fetch_assoc()): ?>
            <div class="item-container">
              <div>
                <img class="item-image" src="<?= htmlspecialchars($row['gambar_makanan']); ?>" alt="<?= htmlspecialchars($row['namamakanan']); ?>">
                <p class="item-name"><?= htmlspecialchars($row['namamakanan']); ?></p>
                <p class="item-description"><?= htmlspecialchars($row['maklumat_makanan']); ?></p>
              </div>
              <div>
                <div class="item-price-and-status-container">
                  <p class="item-price"><?= htmlspecialchars($row['harga']); ?></p>
                  <p>Status: <?= htmlspecialchars($row['status_makanan']); ?></p>
                </div>
                <div class="edit-and-delete-button-container">
                  <a class="edit-link" href="admin-edit-item.php?idmakanan=<?= $row['idmakanan']; ?>&idkategori=<?= $idkategori; ?>">Edit</a>
                  <button class="delete-button" onclick="confirmDelete('<?php echo $row['idmakanan']; ?>', '<?php echo $idkategori; ?>')">Buang</button>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p>No items available for this category.</p>
          <script>
            document.querySelector('.category-item-container').innerHTML = "Tiada Item dalam Kategori";
            document.querySelector('.category-item-container').style.fontSize = "28px";
          </script>
        <?php endif; ?>
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
</body>

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
        image.style.display = 'block';
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

  const addButton = document.querySelector('.add-button-dialog');
  const closeButton = document.querySelector('.close-button-food-preview-modal');
  const inputUploadImage = document.querySelector('.input-item-image');
  addButton.addEventListener('click', () => {
    if (inputUploadImage.files.length === 0) {
      document.getElementById('error-message').textContent = 'Sila pilih gambar!';
    }
  });
  closeButton.addEventListener('click', () => {
    document.getElementById('error-message').textContent = '';
  });
</script>
<script src="data/HeaderLinksDataAdmin.js"></script>
<script src="js/generateHeaderLinksAdmin.js"></script>
<script src="data/mobileHeaderLinksDataAdmin.js"></script>
<script src="js/generateMobileHeaderLinksAdmin.js"></script>
<script src="js/admin-mobile-navigation-bar.js"></script>
<script src="js/admin-show-category-items.js"></script>

</html>