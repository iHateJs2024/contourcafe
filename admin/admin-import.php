<?php
session_start();
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

if (isset($_POST['upload'])) {
  $table = $_POST['table'];
  $fileName = $_FILES['csv_file']['tmp_name'];

  if ($_FILES['csv_file']['size'] > 0) {
    $file = fopen($fileName, "r");

    // Skip the first line (header)
    fgetcsv($file);

    try {
      while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
        switch ($table) {
          case 'pelanggan':
            if (count($column) < 6) throw new Exception("Incorrect Data");
            $idpelanggan = $column[0];
            $namapelanggan = $column[1];
            $password_pelanggan = $column[2];
            $nohp = $column[3];
            $email_pelanggan = $column[4];
            $alamat_pelanggan = $column[5];

            $query = "INSERT INTO pelanggan (idpelanggan, namapelanggan, password_pelanggan, nohp, email_pelanggan, alamat_pelanggan) 
                                    VALUES ('$idpelanggan', '$namapelanggan', '$password_pelanggan', '$nohp', '$email_pelanggan', '$alamat_pelanggan')";
            break;

          case 'jurujual':
            if (count($column) < 5) throw new Exception("Incorrect Data");
            $idjurujual = $column[0];
            $namajurujual = $column[1];
            $password_jurujual = $column[2];
            $nohp = $column[3];
            $email_jurujual = $column[4];

            $query = "INSERT INTO jurujual (idjurujual, namajurujual, password_jurujual, nohp, email_jurujual) 
                                    VALUES ('$idjurujual', '$namajurujual', '$password_jurujual', '$nohp', '$email_jurujual')";
            break;

          case 'makanan':
            if (count($column) < 7) throw new Exception("Incorrect Data");
            $idmakanan = $column[0];
            $namamakanan = $column[1];
            $harga = $column[2];
            $harga_pembungkusan = $column[3];
            $maklumat_makanan = $column[4];
            $status_makanan = $column[5];
            $idjurujual = $column[6];

            $query = "INSERT INTO makanan (idmakanan, namamakanan, harga, harga_pembungkusan, maklumat_makanan, status_makanan, idjurujual) 
                                    VALUES ('$idmakanan', '$namamakanan', '$harga', '$harga_pembungkusan', '$maklumat_makanan', '$status_makanan', '$idjurujual')";
            break;

          case 'kategori':
            if (count($column) < 3) throw new Exception("Incorrect Data");
            $idkategori = $column[0];
            $namakategori = $column[1];
            $gambar_kategori = $column[2];

            $query = "INSERT INTO kategori (idkategori, namakategori, gambar_kategori) 
                                    VALUES ('$idkategori', '$namakategori', '$gambar_kategori')";
            break;

          case 'kategori_makanan':
            if (count($column) < 2) throw new Exception("Incorrect Data");
            $idkategori = $column[0];
            $idmakanan = $column[1];

            $query = "INSERT INTO kategori_makanan (idkategori, idmakanan) 
                                    VALUES ('$idkategori', '$idmakanan')";
            break;

          default:
            $query = "";
            break;
        }

        if (!empty($query)) {
          $result = mysqli_query($conn, $query);

          if (!$result) {
            throw new Exception("Error importing data: " . mysqli_error($conn));
          }
        }
      }

      echo "<script>alert('Berjaya import data!'); window.location.href=window.location.href;</script>";
    } catch (Exception $e) {
      echo "<script>alert('Sila import data yang betul!'); window.location.href=window.location.href;</script>";
    }

    fclose($file);
  }
}

// Close the database connection
mysqli_close($conn);
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
  <link rel="stylesheet" href="../css/signup.css" />
  <link rel="stylesheet" href="css/admin-manage-user.css" />
  <link rel="stylesheet" href="css/admin-manage-admin.css" />
  <link rel="stylesheet" href="css/admin-import.css" />
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

      <!-- Title -->
      <div class="title-and-add-category-button">
        <p class="main-title">Import Data</p>
      </div>

      <!-- Import data form -->
      <section class="main-container-section">
        <div id="main-container">
          <p class="import-form-title">Pilih file untuk diimport (Format CSV sahaja)</p>
          <form action="" method="post" enctype="multipart/form-data">
            <div class="select-container">
              <label class="select-label" for="">Pilih Jadual:</label>
              <div id="select-table-container" class="select-body">
                <select name="table" id="select-table" required>
                  <option value="pelanggan">Pelanggan</option>
                  <option value="jurujual">Jurujual</option>
                  <option value="makanan">Makanan</option>
                  <option value="kategori">Kategori</option>
                  <option value="kategori_makanan">Kategori Makanan</option>
                </select>
                <div class="select-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                    <path d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z" />
                  </svg>
                </div>
              </div>
            </div>
            <div class="select-file-container">
              <p>CSV File:</p>
              <input type="file" name="csv_file" id="csv_file" accept=".csv" onchange="previewCSV(this)" required>
              <label for="csv_file">Pilih CSV File</label>
              <p>Pratonton:</p>
              <div class="table-container">
                <table id="csvPreview"></table>
              </div>
            </div>
            <div class="submit-button-container">
              <button class="submit-button" type="submit" name="upload">Muat Naik Data</button>
            </div>
          </form>
        </div>
      </section>

    </section>
  </main>
  <!-- Main content ends here! -->

  <!--Footer starts here!-->
  <footer>
    <div class=" footer">
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
  <script src="data/HeaderLinksDataAdmin.js"></script>
  <script src="js/generateHeaderLinksAdmin.js"></script>
  <script src="data/mobileHeaderLinksDataAdmin.js"></script>
  <script src="js/generateMobileHeaderLinksAdmin.js"></script>
  <script src="js/admin-mobile-navigation-bar.js"></script>

  <script>
    //! Function to preview imported csv file
    function previewCSV(input) {
      const file = input.files[0];

      if (!file) {
        alert("No file selected!");
        return;
      }

      if (!file.name.endsWith('.csv')) {
        alert("Please select a valid CSV file!");
        input.value = ""; // Clear the input
        return;
      }

      const reader = new FileReader();
      reader.onload = function(e) {
        const lines = e.target.result.split('\n');
        const table = document.getElementById('csvPreview');
        table.innerHTML = ""; // Clear previous preview

        lines.forEach((line, index) => {
          // Skip empty or whitespace-only rows
          if (line.trim() === "") return;

          const row = document.createElement('tr');
          const cells = line.split(',');

          cells.forEach(cell => {
            const cellElement = document.createElement(index === 0 ? 'th' : 'td');
            cellElement.textContent = cell.trim();
            row.appendChild(cellElement);
          });

          table.appendChild(row);
        });
      };
      reader.readAsText(file);
    }
  </script>
</body>

</html>