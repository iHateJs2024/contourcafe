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

$conn->close();
?>

<?php
//! PHP TO DELETE CATEGRY FROM kategori TABLE AND kategori_makanan TABLE
// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$database = "tempahan";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

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
  <link rel="stylesheet" href="css/admin-edit-category.css" />
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

  <!--Main content starts here! -->
  <main class="main">
    <section class="main-section">
      <div class="title-and-add-category-button">
        <p class="main-title"><?= $kategori['namakategori']; ?></p>
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
            Buang Kategori
          </button>
        </form>
      </div>
      <div class="main-content-container">
        <div class="main-content">
          <div class="return-container">
            <a href="admin-manage-category.php">
              <img class="return-icon" src="../icons/popup-back-icon.svg" alt="" />
              Kembali
            </a>
          </div>
          <div class="edit-category-and-show-items-container">
            <button class="edit-category-button" id="edit-category-button">
              Edit Kategori
            </button>

            <dialog id="edit-category-dialog" class="food-preview-modal">
              <!--First Vertical Layout-->
              <div class="modal-title-and-close-button">
                <div class="modal-title">
                  Edit Kategori
                </div>
                <button
                  class="close-button-food-preview-modal"
                  id="close-dialog-button">
                  x
                </button>
              </div>

              <!-- Main content -->
              <form id="edit-category-form" class="form-dialog" action="process-edit-category.php" method="post" enctype="multipart/form-data">
                <!-- Hidden field to keep track of idkategori -->
                <input type="hidden" name="idkategori" value="<?= $kategori['idkategori']; ?>">

                <div class="modal-scrollable-content">
                  <p class="input-text">
                    Kategori Name:
                  </p>
                  <br>
                  <div class="text-box">
                    <input
                      class="input-category-name"
                      type="text"
                      name="namakategori"
                      id="kategori_name"
                      placeholder="Sila masukkan nama kategori"
                      value="<?= htmlspecialchars($kategori['namakategori']); ?>"
                      required>
                  </div>
                  <br>
                  <p class="input-text">
                    Kategori Image:
                  </p>
                  <br>
                  <input class="input-upload-image" type="file" name="image" id="image" accept="image/*" required>
                  <img
                    id="image_preview"
                    src="<?= $kategori['gambar_kategori']; ?>"
                    alt="Image preview"
                    style="max-width: 200px;">
                  <br>
                  <br>
                  <label class="input-upload-image-label" for="image">Muat naik gambar</label>
                </div>
              </form>

              <!-- Save Changes button layout -->
              <div
                class="
                  modal-food-preview-add-to-basket-button add-button-container
              ">
                <button
                  form="edit-category-form"
                  type="submit"
                  class="add-button-dialog"
                  name="edit_category
                ">
                  Simpan Perubahan
                </button>
              </div>
            </dialog>

            <a href="admin-show-category-items.php?idkategori=<?= $idkategori ?>">Tunjuk Item</a>
          </div>
        </div>
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
    document.getElementById("image").addEventListener("change", function(event) {
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

    //! Reset the input field when close dialog button is clicked
    let closeDialogButton = document.getElementById('close-dialog-button');
    const form = document.querySelector('.form-dialog');

    closeDialogButton.addEventListener('click', () => {
      let inputCategoryName = document.getElementById('kategori_name');
      inputCategoryName.value = "<?= htmlspecialchars($kategori['namakategori']); ?>";
      let imagePreview = document.getElementById('image_preview');
      imagePreview.src = "<?= $kategori['gambar_kategori']; ?>";
      form.reset();
    });

    const addButton = document.querySelector('.add-button-dialog');
    addButton.addEventListener('click', () => {
      form.submit();
    });
  </script>
  <script src="data/HeaderLinksDataAdmin.js"></script>
  <script src="js/generateHeaderLinksAdmin.js"></script>
  <script src="data/mobileHeaderLinksDataAdmin.js"></script>
  <script src="js/generateMobileHeaderLinksAdmin.js"></script>
  <script src="js/admin-mobile-navigation-bar.js"></script>
  <script src="js/admin-edit-category.js"></script>
</body>

</html>