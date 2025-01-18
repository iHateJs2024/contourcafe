<?php
session_start();
//! PHP TO ADD A NEW CATEGORY AND FETCH IT FROM DATABASE
include_once(__DIR__ . '/../connect.php');
$conn = getConnection();

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

// Handle form submission for adding kategori
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = $_POST['nama']; // Get kategori name from form

  // Handle image upload
  $target_dir = "uploads/"; // Directory for storing images
  $target_file = $target_dir . basename($_FILES["gambar"]["name"]);
  $upload_ok = true;

  // Validate file type
  $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
  $allowed_types = ["jpg", "jpeg", "png", "gif"];

  if (!in_array($image_file_type, $allowed_types)) {
    echo '<script>alert("Hanya JPG, JPEG, PNG, dan GIF yang diizinkan!");
                    window.location.href = "admin-manage-category.php";
          </script>';
    $upload_ok = false;
  }

  // Get the next auto-increment ID
  $idkategori = getNextKategoriId($conn);

  // If validation passed, upload the file and save to the database
  if ($upload_ok) {
    if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
      $sql = "INSERT INTO kategori (idkategori, namakategori, gambar_kategori) VALUES (?, ?, ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sss", $idkategori, $nama, $target_file);
      if ($stmt->execute()) {
        // Redirect to the same page to prevent resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
      } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }
    } else {
      echo "Error uploading file.";
    }
  }
}

//! Fetch all kategori to display
$kategori_data = [];
$sql = "SELECT idkategori, namakategori, gambar_kategori FROM kategori";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $kategori_data[] = $row;
  }
}

//! Fetch total count of kategori
$total_kategori = 0;
$sql_count = "SELECT COUNT(*) AS total FROM kategori";
$result_count = $conn->query($sql_count);

if ($result_count->num_rows > 0) {
  $total_kategori = $result_count->fetch_assoc()['total'];
}

//! Fetch item counts for each kategori
$kategori_item_counts = [];
foreach ($kategori_data as $kategori) {
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
    $kategori_item_counts[$kategori['idkategori']] = $result_item_count->fetch_assoc()['item_count'];
  } else {
    // If no rows are found, default the count to 0
    $kategori_item_counts[$kategori['idkategori']] = 0;
  }
}

$conn->close();
?>

<?php
//! PHP TO DELETE CATEGRY FROM kategori TABLE AND kategori_makanan TABLE
include_once(__DIR__ . '/../connect.php');
$conn = getConnection();

// Handle the Delete Request
if (isset($_POST['delete'])) {
  $idkategori = $_POST['idkategori']; // Get the idkategori to delete

  // Start a transaction
  $conn->begin_transaction();

  try {
    // First delete the items from the kategori_makanan table that belong to this kategori
    $sql_delete_items = "DELETE FROM kategori_makanan WHERE idkategori = ?";
    $stmt_items = $conn->prepare($sql_delete_items);
    $stmt_items->bind_param("s", $idkategori);
    $stmt_items->execute();
    $stmt_items->close();

    // Then delete the category from the kategori table
    $sql_delete_category = "DELETE FROM kategori WHERE idkategori = ?";
    $stmt_category = $conn->prepare($sql_delete_category);
    $stmt_category->bind_param("s", $idkategori);
    $stmt_category->execute();
    $stmt_category->close();

    // Commit the transaction
    $conn->commit();

    // Redirect back to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
  } catch (Exception $e) {
    // Rollback the transaction if there was an error
    $conn->rollback();
    echo "Error: " . $e->getMessage();
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
        <p class="main-title">Senarai Kategori</p>
        <button class="add-category-button">Tambah Kategori</button>
      </div>

      <dialog id="add-category-dialog" class="food-preview-modal">
        <!--First Vertical Layout-->
        <div class="modal-title-and-close-button">
          <div class="modal-title">
            Tambah Kategori
          </div>
          <button
            class="close-button-food-preview-modal"
            id="close-dialog-button">
            x
          </button>
        </div>

        <!-- Main content -->
        <form id="add-category-form" class="form-dialog" action="" method="post" enctype="multipart/form-data">
          <div class="modal-scrollable-content">
            <p class="input-text">
              Kategori Name:
            </p>
            <br>
            <div class="text-box">
              <input class="input-category-name" type="text" name="nama" id="nama" placeholder="Sila masukkan nama kategori" required>
            </div>
            <br>
            <p class="input-text">
              Kategori Image:
            </p>
            <br>
            <img id="image_preview" src="" alt="Image Preview" style="display:none; max-width: 200px;">
            <input class="input-upload-image" type="file" name="gambar" id="image" accept="image/*" required>
            <label class="upload-image-label" for="image">Muat Naik Gambar</label>
            <br>
            <p class="error-message" id="error-message"></p>
          </div>
        </form>

        <!-- Add button layout -->
        <div
          class="
              modal-food-preview-add-to-basket-button add-button-container
          ">
          <button form="add-category-form" type="submit" class="add-button-dialog">Tambah Kategori</button>
        </div>
      </dialog>

      <div class="total-sections-counter-container">
        <p class="total-sections-counter">
          Jumlah: <?php echo $total_kategori; ?>
        </p>
      </div>

      <div class="category-container">
        <?php if (!empty($kategori_data)): ?>
          <?php foreach ($kategori_data as $kategori): ?>
            <div class="kategori-item">
              <img
                class="category-image"
                src="<?= $kategori['gambar_kategori']; ?>"
                alt="<?= $kategori['namakategori']; ?>">
              <p class="category-name">
                <?= $kategori['namakategori']; ?>
              </p>
              <p class="total-items-category">
                Total Items:
                <?= isset($kategori_item_counts[$kategori['idkategori']]) ? $kategori_item_counts[$kategori['idkategori']] : 0; ?>
              </p>
              <div class="category-buttons-container">
                <a href="admin-edit-category.php?idkategori=<?= $kategori['idkategori']; ?>" class="edit-category-button">
                  Edit
                </a>
                <form class="delete-button-form"
                  action="admin-manage-category.php"
                  method="POST"
                  onsubmit="
                    return confirm('Are you sure you want to delete this category?');
                  ">
                  <input
                    type="hidden"
                    name="idkategori"
                    value="<?= $kategori['idkategori']; ?>
                  ">
                  <button
                    class="delete-category-button"
                    type="submit"
                    name="delete"
                    value="delete
                  ">
                    Buang
                  </button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="font-size: 32px;">Tiada Kategori</p>
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

  <!--Link the JS file-->
  <script>
    // JavaScript function to show image preview when an image is chosen
    document.getElementById("image").addEventListener("change", function(event) {
      let image = document.getElementById("image_preview"); // The img tag for preview
      let file = event.target.files[0]; // The file object selected by the user

      if (file) {
        let reader = new FileReader();

        // When the file is read, set the image source to the result (image data URL)
        reader.onload = function(e) {
          image.src = e.target.result;
          image.style.display = "block"; // Show the image preview
        }

        reader.readAsDataURL(file); // Read the file as a data URL
      } else {
        image.style.display = "none"; // Hide the image preview if no file is chosen
      }
    });

    const addButton = document.querySelector('.add-button-dialog');
    const closeButton = document.querySelector('.close-button-food-preview-modal');
    const inputUploadImage = document.querySelector('.input-upload-image');
    const inputCategoryName = document.querySelector('.input-category-name');

    addButton.addEventListener('click', () => {
      if (inputCategoryName.checkValidity()) {
        if (inputUploadImage.files.length === 0) {
          document.getElementById('error-message').textContent = 'Sila pilih gambar!';
        }
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
  <script src="js/admin-manage-category.js"></script>
</body>

</html>