<?php
session_start();
// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'tempahan';
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
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

// Initialize variables
$searchQuery = '';
$searchResults = [];
$totalPelanggan = 0;
$menaikResults = [];
$menurunResults = [];

//! Handle search and menaik/menurun button actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['search'])) {
    // Handle search
    $searchQuery = trim($conn->real_escape_string($_POST['searchQuery']));
    if (!empty($searchQuery)) {
      $sqlSearch = "SELECT * FROM pelanggan WHERE namapelanggan LIKE '%$searchQuery%'";
      $resultSearch = $conn->query($sqlSearch);
      if ($resultSearch && $resultSearch->num_rows > 0) {
        $searchResults = $resultSearch->fetch_all(MYSQLI_ASSOC);
      }
    }
  }

  //! Menaik button
  if (isset($_POST['menaik'])) {
    // Handle menaik button
    $sqlHighestOrder = "SELECT p.idpelanggan, p.namapelanggan, p.nohp, p.email_pelanggan, p.alamat_pelanggan, p.password_pelanggan, COUNT(mp.idpelanggan) AS jumlahPesanan 
                            FROM pelanggan p
                            LEFT JOIN maklumat_pesanan mp ON p.idpelanggan = mp.idpelanggan
                            GROUP BY p.idpelanggan
                            ORDER BY jumlahPesanan DESC";
    $resultHighestOrder = $conn->query($sqlHighestOrder);

    if ($resultHighestOrder) {
      // Get the highest jumlahPesanan
      $highestOrderCount = 0;
      $menaikResults = [];
      while ($row = $resultHighestOrder->fetch_assoc()) {
        if ($row['jumlahPesanan'] > $highestOrderCount) {
          $highestOrderCount = $row['jumlahPesanan'];
          $menaikResults = [$row];
        } elseif ($row['jumlahPesanan'] == $highestOrderCount) {
          $menaikResults[] = $row;
        }
      }
    }
  }

  //! Menurun button
  if (isset($_POST['menurun'])) {
    // Handle menurun button
    $sqlLowestOrder = "SELECT p.idpelanggan, p.namapelanggan, p.nohp, p.email_pelanggan, p.alamat_pelanggan, p.password_pelanggan, COUNT(mp.idpelanggan) AS jumlahPesanan 
                        FROM pelanggan p
                        LEFT JOIN maklumat_pesanan mp ON p.idpelanggan = mp.idpelanggan
                        GROUP BY p.idpelanggan
                        ORDER BY jumlahPesanan ASC";
    $resultLowestOrder = $conn->query($sqlLowestOrder);

    if ($resultLowestOrder) {
      // Get the lowest jumlahPesanan
      $lowestOrderCount = PHP_INT_MAX;
      $menurunResults = [];
      while ($row = $resultLowestOrder->fetch_assoc()) {
        if ($row['jumlahPesanan'] < $lowestOrderCount) {
          $lowestOrderCount = $row['jumlahPesanan'];
          $menurunResults = [$row];
        } elseif ($row['jumlahPesanan'] == $lowestOrderCount) {
          $menurunResults[] = $row;
        }
      }
    }
  }
}

//! Fetch all pelanggan if no search is performed
if (empty($searchQuery) && empty($menaikResults) && empty($menurunResults)) {
  $sqlPelanggan = "SELECT * FROM pelanggan";
  $resultPelanggan = $conn->query($sqlPelanggan);
  $totalPelanggan = $resultPelanggan->num_rows;
}

//! Check if the 'delete' button is clicked and process the deletion
if (isset($_POST['delete'])) {
  // Get the idpelanggan to delete
  $idpelanggan = $_POST['idpelanggan'];

  //* Delete query
  $deleteQuery = "DELETE FROM pelanggan WHERE idpelanggan = '$idpelanggan'";

  if ($conn->query($deleteQuery)) {
    echo "Record deleted successfully.";
  } else {
    echo "Error deleting record: " . $conn->error;
  }

  // Redirect back to the same page after deletion (to refresh the table)
  header("Location: admin-manage-user.php");
  exit(); // Stop further execution
}

?>

<?php
//! PHP TO ADD NEW USER TO pelanggan TABLE
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tempahan";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the database connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['add-user-button'])) {
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
  $email = $_POST['email'];
  $nohp = $_POST['nohp'];
  $alamat = $_POST['alamat'];


  $checkemail = "SELECT * FROM pelanggan WHERE email_pelanggan='$email'";
  $resultEmail = $conn->query($checkemail);
  $checknohp = "SELECT * FROM pelanggan WHERE nohp='$nohp'";
  $resultNohp = $conn->query($checknohp);
  if ($resultEmail->num_rows > 0) {
    echo '<script>alert("E-mel Sudah Wujud!");
                    window.location.href = "admin-manage-user.php";
              </script>';
  } else if ($resultNohp->num_rows > 0) {
    echo '<script>alert("Nohp Sudah Wujud!");
                    window.location.href = "admin-manage-user.php";
              </script>';
  } else {
    $insertQuery = "INSERT INTO pelanggan (namapelanggan, password_pelanggan, nohp, email_pelanggan, alamat_pelanggan)
                          VALUES ('$username', '$password', '$nohp', '$email', '$alamat')";
    if ($conn->query($insertQuery) == TRUE) {
      echo '<script>alert("Berjaya tambah pengguna!");
                  window.location.href = "admin-manage-user.php";
            </script>';
    } else {
      echo "Error:" . $conn->error;
    }
  }
}

?>

<?php
//! PHP TO UPDATE USER DATA
if (isset($_POST['edit-user-button'])) {

  // Retrieve form inputs
  $idpelanggan = $_POST['idpelanggan'];
  $namapelanggan = $_POST['username'] ?? null;
  $email = $_POST['email'] ?? null;
  $nohp = $_POST['nohp'] ?? null;
  $password_pelanggan = $_POST['password'];
  $alamat = $_POST['alamat'] ?? null;

  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "tempahan";

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Message array to store results
  $messages = [];

  // Always update `namapelanggan` if provided
  if (!empty($namapelanggan)) {
    $sql_update_namapelanggan = "UPDATE pelanggan SET namapelanggan = ? WHERE idpelanggan = ?";
    $stmt_update_name = $conn->prepare($sql_update_namapelanggan);
    $stmt_update_name->bind_param("ss", $namapelanggan, $idpelanggan);
    if ($stmt_update_name->execute()) {
      $messages[] = "Nama berjaya dikemas kini!";
    } else {
      $messages[] = "Failed to update nama pelanggan!";
    }
  }

  // Always update `password_pelanggan` if provided
  if (!empty($password_pelanggan)) {
    $sql_update_password_pelanggan = "UPDATE pelanggan SET password_pelanggan = ? WHERE idpelanggan = ?";
    $stmt_update_password = $conn->prepare($sql_update_password_pelanggan);
    $stmt_update_password->bind_param("is", $password_pelanggan, $idpelanggan);
    if ($stmt_update_password->execute()) {
      $messages[] = "Kata laluan berjaya dikemas kini!";
    } else {
      $messages[] = "Failed to update password!";
    }
  }

  // Check and update `email_pelanggan`
  if (!empty($email)) {
    $sql_check_email = "SELECT COUNT(*) AS count FROM pelanggan WHERE email_pelanggan = ?";
    $stmt_check_email = $conn->prepare($sql_check_email);
    $stmt_check_email->bind_param("s", $email);
    $stmt_check_email->execute();
    $result_check_email = $stmt_check_email->get_result();
    $row_check_email = $result_check_email->fetch_assoc();

    if ($row_check_email['count'] > 0) {
      $messages[] = "E-mel '$email' sudah wujud!";
    } else {
      $sql_update_email = "UPDATE pelanggan SET email_pelanggan = ? WHERE idpelanggan = ?";
      $stmt_update_email = $conn->prepare($sql_update_email);
      $stmt_update_email->bind_param("ss", $email, $idpelanggan);
      if ($stmt_update_email->execute()) {
        $messages[] = "E-mel berjaya dikemas kini!";
      } else {
        $messages[] = "Failed to update email!";
      }
    }
  }

  // Check and update `nohp`
  if (!empty($nohp)) {
    $sql_check_nohp = "SELECT COUNT(*) AS count FROM pelanggan WHERE nohp = ?";
    $stmt_check_nohp = $conn->prepare($sql_check_nohp);
    $stmt_check_nohp->bind_param("s", $nohp);
    $stmt_check_nohp->execute();
    $result_check_nohp = $stmt_check_nohp->get_result();
    $row_check_nohp = $result_check_nohp->fetch_assoc();

    if ($row_check_nohp['count'] > 0) {
      $messages[] = "Nombor telefon '$nohp' sudah wujud!";
    } else {
      $sql_update_nohp = "UPDATE pelanggan SET nohp = ? WHERE idpelanggan = ?";
      $stmt_update_nohp = $conn->prepare($sql_update_nohp);
      $stmt_update_nohp->bind_param("ss", $nohp, $idpelanggan);
      if ($stmt_update_nohp->execute()) {
        $messages[] = "Nombor telefon berjaya dikemas kini!";
      } else {
        $messages[] = "Failed to update No HP!";
      }
    }
  }

  if (isset($alamat)) { // `isset` allows empty values to pass through
    if ($alamat === '') {
      // Update directly if empty
      $sql_update_empty_alamat = "UPDATE pelanggan SET alamat_pelanggan = '' WHERE idpelanggan = ?";
      $stmt_update_empty_alamat = $conn->prepare($sql_update_empty_alamat);
      $stmt_update_empty_alamat->bind_param("s", $idpelanggan);
      if ($stmt_update_empty_alamat->execute()) {
        $messages[] = "Alamat berjaya dikosongkan!";
      } else {
        $messages[] = "Failed to clear alamat!";
      }
    } else {
      // Otherwise, check if it already exists
      $sql_check_alamat = "SELECT COUNT(*) AS count FROM pelanggan WHERE alamat_pelanggan = ?";
      $stmt_check_alamat = $conn->prepare($sql_check_alamat);
      $stmt_check_alamat->bind_param("s", $alamat);
      $stmt_check_alamat->execute();
      $result_check_alamat = $stmt_check_alamat->get_result();
      $row_check_alamat = $result_check_alamat->fetch_assoc();

      if ($row_check_alamat['count'] > 0) {
        $messages[] = "Alamat '$alamat' sudah wujud!";
      } else {
        $sql_update_alamat = "UPDATE pelanggan SET alamat_pelanggan = ? WHERE idpelanggan = ?";
        $stmt_update_alamat = $conn->prepare($sql_update_alamat);
        $stmt_update_alamat->bind_param("ss", $alamat, $idpelanggan);
        if ($stmt_update_alamat->execute()) {
          $messages[] = "Alamat berjaya dikemas kini!";
        } else {
          $messages[] = "Failed to update alamat!";
        }
      }
    }
  }

  // Close connection
  $conn->close();

  if (!empty($messages)) {
    $messagesString = implode("\n", $messages); // Convert array to a newline-separated string
    echo "<script type='text/javascript'>
      alert(`$messagesString`);
      window.location.href = 'admin-manage-user.php';
    </script>";
  }
}

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
  <link rel="stylesheet" href="../css/mobile-navigation.css" />
  <link rel="stylesheet" href="css/admin-mobile-navigation-bar.css" />
  <link rel="stylesheet" href="css/admin-manage-user.css" />
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
        <p class="main-title">Urus Pengguna</p>
        <button id="js-tambah-pengguna-button" class="add-category-button">
          Tambah Pengguna
        </button>
      </div>

      <div class="main-container">
        <!-- Search bar/Add user button -->
        <div class="search-bar-and-add-user-button-container">
          <div>
            <form method="POST" action="" onsubmit="return validateSearch()">
              <input
                class="input-username"
                type="text"
                name="searchQuery"
                id="searchQuery"
                value="<?php echo htmlspecialchars($searchQuery); ?>"
                placeholder="Masukkan nama pelanggan">
              <button name="search" type="submit" class="search-button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" width="30px" height="30px">
                  <path d="M 13 3 C 7.4886661 3 3 7.4886661 3 13 C 3 18.511334 7.4886661 23 13 23 C 15.396652 23 17.59741 22.148942 19.322266 20.736328 L 25.292969 26.707031 A 1.0001 1.0001 0 1 0 26.707031 25.292969 L 20.736328 19.322266 C 22.148942 17.59741 23 15.396652 23 13 C 23 7.4886661 18.511334 3 13 3 z M 13 5 C 17.430666 5 21 8.5693339 21 13 C 21 17.430666 17.430666 21 13 21 C 8.5693339 21 5 17.430666 5 13 C 5 8.5693339 8.5693339 5 13 5 z" />
                </svg>
              </button>
            </form>
            <button class="reset-button">
              <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                <title>Trade_Icons</title>
                <rect x="7.3" y="3.93" width="2.15" height="9.9" transform="translate(-3.82 8.52) rotate(-45)" />
                <rect x="3.43" y="7.8" width="9.9" height="2.15" transform="translate(-3.82 8.52) rotate(-45)" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Dialog for add user button -->
        <dialog id="add-user-dialog" class="food-preview-modal">
          <!--First Vertical Layout-->
          <div class="modal-title-and-close-button">
            <div class="modal-title">
              Tambah Pengguna
            </div>
            <button
              class="close-button-food-preview-modal"
              id="close-dialog-button">
              x
            </button>
          </div>

          <!-- Main content -->
          <form id="add-user-form" class="form-dialog" action="" method="post" enctype="multipart/form-data">
            <div class="modal-scrollable-content">
              <div class="text-box">
                <div class="entryarea">
                  <input
                    type="text"
                    name="username"
                    id="username"
                    class="username"
                    required
                    maxlength="30"
                    pattern="[A-Za-z ]+"
                    autocomplete="off"
                    title="Only alphabets are allowed" />
                  <label for="username" id="label-username" class="label-username">Nama</label>
                </div>
                <p id="example-text-username" class="example-text-username">e.g. Kong Jia Le</p>
              </div>
              <div class="text-box">
                <div class="entryarea">
                  <input
                    type="email"
                    name="email"
                    id="email"
                    class="email"
                    pattern="[A-Za-z0-9@.]*"
                    required
                    maxlength="100000"
                    autocomplete="off" />
                  <label for="email" id="label-email" class="label-username">E-mel</label>
                </div>
                <p id="example-text-email" class="example-text-username">e.g. kongjiale12@gmail.com</p>
              </div>
              <div class="text-box">
                <div class="entryarea">
                  <input
                    type="tel"
                    name="nohp"
                    id="nohp"
                    class="username"
                    required
                    pattern="\d*"
                    minlength="10"
                    maxlength="11"
                    autocomplete="off" />
                  <label for="nohp" id="label-nohp" class="label-username">Nombor Telefon</label>
                </div>
                <p id="example-text-nohp" class="example-text-username">e.g. 0123456789</p>
              </div>
              <div class="text-box">
                <div class="entryarea">
                  <input
                    type="text"
                    name="password"
                    id="password"
                    required
                    title="Only numbers are allowed!"
                    pattern="\d*"
                    minlength="3"
                    maxlength="3"
                    autocomplete="off"
                    class="password" />
                  <label for="password" id="label-password" class="label-password">Katalaluan</label>
                </div>
                <p class="example-text-password" id="example-text-password">e.g. 123</p>
              </div>
              <div class="text-box">
                <div class="entryarea">
                  <textarea
                    name="alamat"
                    id="alamat"
                    placeholder="e.g. Bandar Kinrara"></textarea>
                </div>
              </div>
            </div>
          </form>

          <!-- Add User button layout -->
          <div
            class="
            modal-food-preview-add-to-basket-button add-button-container
        ">
            <button
              form="add-user-form"
              type="submit"
              name="add-user-button"
              class="add-button-dialog">
              Tambah Pengguna
            </button>
          </div>
        </dialog>

        <!-- Menaik/Menurun button -->
        <div class=" highest-lowest-orders-container">
          <p>Jumlah Pesanan:</p>
          <form method="POST" action="">
            <button id="menaik-button" type="submit" name="menaik">Menaik</button>
            <button id="menurun-button" type="submit" name="menurun">Menurun</button>
          </form>
        </div>

        <!-- Display Jumlah Pelanggan -->
        <?php if (empty($searchQuery) && empty($menaikResults) && empty($menurunResults)): ?>
          <h3>Jumlah: <?php echo $totalPelanggan; ?></h3>
        <?php endif; ?>

        <div class="user-list-container">

          <!-- Display Menaik Results (Highest Orders) -->
          <?php if (!empty($menaikResults)): ?>
            <h3>Jumlah: <?php echo count($menaikResults); ?></h3>
            <?php if (count($menaikResults) > 0): ?>
              <ol id="menaik-ol">
                <?php foreach ($menaikResults as $result): ?>
                  <li>
                    <div class="user-details-container">
                      <div class="user-details">
                        <p>ID: <?= $result['idpelanggan']; ?></p>
                        <p>Nama: <?= $result['namapelanggan']; ?></p>
                        <p>Jumlah Pesanan: <?= $result['jumlahPesanan']; ?></p>
                      </div>
                      <div class="user-action-buttons-container">
                        <div class="order-report-link-container">
                          <a href="admin-order-report.php?idpelanggan=<?= $result['idpelanggan']; ?>&namapelanggan=<?= $result['namapelanggan']; ?>" class="order-report-button">
                            Laporan Pesanan
                          </a>
                        </div>
                        <div class="edit-and-delete-buttons-container">
                          <button
                            id="js-edit-button-<?= $result['idpelanggan']; ?>"
                            class="edit-button"
                            data-button-id="<?= $result['idpelanggan']; ?>">
                            Edit
                          </button>
                          <form action="admin-manage-user.php" method="POST" style="display:inline;">
                            <input type="hidden" name="idpelanggan" value="<?= $result['idpelanggan']; ?>" />
                            <button type="submit" class="delete-button" name="delete" onclick="return confirm('Are you sure you want to delete this record?');">Buang</button>
                          </form>
                        </div>

                        <dialog id="edit-user-dialog-<?= $result['idpelanggan']; ?>" class="food-preview-modal">
                          <!--First Vertical Layout-->
                          <div class="modal-title-and-close-button">
                            <div class="modal-title">
                              Edit Pengguna
                            </div>
                            <button
                              class="close-button-food-preview-modal"
                              id="close-dialog-button"
                              onclick="
                            closeEditUserDialog('<?= $result['idpelanggan']; ?>')
                        ">
                              x
                            </button>
                          </div>

                          <!-- Main content -->
                          <form id="edit-user-form-<?= $result['idpelanggan']; ?>" class="form-dialog" action="" method="post" enctype="multipart/form-data">
                            <div class="modal-scrollable-content">
                              <div class="text-box">
                                <div class="entryarea">
                                  <input
                                    type="text"
                                    name="username"
                                    id="username-<?= $result['idpelanggan']; ?>"
                                    class="username"
                                    required
                                    maxlength="30"
                                    pattern="[A-Za-z ]+"
                                    autocomplete="off"
                                    value="<?= $result['namapelanggan']; ?>"
                                    title="Only alphabets are allowed" />
                                  <label for="username-<?= $result['idpelanggan']; ?>" id="label-username-<?= $result['idpelanggan']; ?>" class="label-username">Nama</label>
                                </div>
                                <p id="example-text-username-<?= $result['idpelanggan']; ?>" class="example-text-username">e.g. Kong Jia Le</p>
                              </div>
                              <div class="text-box">
                                <div class="entryarea">
                                  <input
                                    type="email"
                                    name="email"
                                    id="email-<?= $result['idpelanggan']; ?>"
                                    class="email"
                                    pattern="[A-Za-z0-9@.]*"
                                    required
                                    maxlength="100000"
                                    value="<?= $result['email_pelanggan']; ?>"
                                    autocomplete="off" />
                                  <label for="email-<?= $result['idpelanggan']; ?>" id="label-email-<?= $result['idpelanggan']; ?>" class="label-username">E-mel</label>
                                </div>
                                <p id="example-text-email-<?= $result['idpelanggan']; ?>" class="example-text-username">e.g. kongjiale12@gmail.com</p>
                              </div>
                              <div class="text-box">
                                <div class="entryarea">
                                  <input
                                    type="tel"
                                    name="nohp"
                                    id="nohp-<?= $result['idpelanggan']; ?>"
                                    class="username"
                                    required
                                    pattern="\d*"
                                    minlength="10"
                                    maxlength="11"
                                    value="<?= $result['nohp']; ?>"
                                    autocomplete="off" />
                                  <label for="nohp-<?= $result['idpelanggan']; ?>" id="label-nohp-<?= $result['idpelanggan']; ?>" class="label-username">Nombor Telefon</label>
                                </div>
                                <p id="example-text-nohp-<?= $result['idpelanggan']; ?>" class="example-text-username">e.g. 0123456789</p>
                              </div>
                              <div class="text-box">
                                <div class="entryarea">
                                  <input
                                    type="text"
                                    name="password"
                                    id="password-<?= $result['idpelanggan']; ?>"
                                    required
                                    title="Only numbers are allowed!"
                                    pattern="\d*"
                                    minlength="3"
                                    maxlength="3"
                                    autocomplete="off"
                                    value="<?= $result['password_pelanggan']; ?>"
                                    class="password" />
                                  <label for="password-<?= $result['idpelanggan']; ?>" id="label-password-<?= $result['idpelanggan']; ?>" class="label-username">Katalaluan</label>
                                </div>
                                <p class="example-text-password" id="example-text-password-<?= $result['idpelanggan']; ?>">e.g. 123</p>
                              </div>
                              <div class="text-box">
                                <div class="entryarea">
                                  <input
                                    type="text"
                                    name="alamat"
                                    id="alamat-<?= $result['idpelanggan']; ?>"
                                    autocomplete="off"
                                    value="<?= $result['alamat_pelanggan']; ?>"
                                    class="username" />
                                  <label for="alamat-<?= $result['idpelanggan']; ?>" id="label-alamat-<?= $result['idpelanggan']; ?>" class="label-alamat">Alamat</label>
                                </div>
                                <p class="example-text-password" id="example-text-alamat-<?= $result['idpelanggan']; ?>">e.g. Bandar Kinrara</p>
                              </div>
                            </div>

                            <!-- Hidden input field for idpelanggan -->
                            <input type="hidden" name="idpelanggan" value="<?= $result['idpelanggan']; ?>">
                          </form>

                          <!-- Add User button layout -->
                          <div
                            class="
                          modal-food-preview-add-to-basket-button add-button-container
                        ">
                            <button
                              form="edit-user-form-<?= $result['idpelanggan']; ?>"
                              type="submit"
                              name="edit-user-button"
                              class="add-button-dialog">
                              Edit Pengguna
                            </button>
                          </div>
                        </dialog>

                      </div>
                    </div>
                  </li>
                <?php endforeach; ?>
              </ol>
            <?php else: ?>
              <p>No results found</p>
            <?php endif; ?>
          <?php endif; ?>

          <!-- Display Menurun Results (Lowest Orders) -->
          <?php if (!empty($menurunResults)): ?>
            <h3>Jumlah: <?php echo count($menurunResults); ?></h3>
            <?php if (count($menurunResults) > 0): ?>
              <ol id="menurun-ol">
                <?php foreach ($menurunResults as $result): ?>
                  <li>
                    <div class="user-details-container">
                      <div class="user-details">
                        <p>ID: <?= $result['idpelanggan']; ?></p>
                        <p>Nama: <?= $result['namapelanggan']; ?></p>
                        <p>Jumlah Pesanan: <?= $result['jumlahPesanan']; ?></p>
                      </div>
                      <div class="user-action-buttons-container">
                        <div class="order-report-link-container">
                          <a href="admin-order-report.php?idpelanggan=<?= $result['idpelanggan']; ?>&namapelanggan=<?= $result['namapelanggan']; ?>" class="order-report-button">
                            Laporan Pesanan
                          </a>
                        </div>
                        <div class="edit-and-delete-buttons-container">
                          <button
                            id="js-edit-button-<?= $result['idpelanggan']; ?>"
                            class="edit-button"
                            data-button-id="<?= $result['idpelanggan']; ?>">
                            Edit
                          </button>
                          <form action="admin-manage-user.php" method="POST" style="display:inline;">
                            <input type="hidden" name="idpelanggan" value="<?= $result['idpelanggan']; ?>" />
                            <button type="submit" class="delete-button" name="delete" onclick="return confirm('Are you sure you want to delete this record?');">Buang</button>
                          </form>
                        </div>

                        <dialog id="edit-user-dialog-<?= $result['idpelanggan']; ?>" class="food-preview-modal">
                          <!--First Vertical Layout-->
                          <div class="modal-title-and-close-button">
                            <div class="modal-title">
                              Edit Pengguna
                            </div>
                            <button
                              class="close-button-food-preview-modal"
                              id="close-dialog-button"
                              onclick="
                            closeEditUserDialog('<?= $result['idpelanggan']; ?>')
                        ">
                              x
                            </button>
                          </div>

                          <!-- Main content -->
                          <form id="edit-user-form-<?= $result['idpelanggan']; ?>" class="form-dialog" action="" method="post" enctype="multipart/form-data">
                            <div class="modal-scrollable-content">
                              <div class="text-box">
                                <div class="entryarea">
                                  <input
                                    type="text"
                                    name="username"
                                    id="username-<?= $result['idpelanggan']; ?>"
                                    class="username"
                                    required
                                    maxlength="30"
                                    pattern="[A-Za-z ]+"
                                    autocomplete="off"
                                    value="<?= $result['namapelanggan']; ?>"
                                    title="Only alphabets are allowed" />
                                  <label for="username-<?= $result['idpelanggan']; ?>" id="label-username-<?= $result['idpelanggan']; ?>" class="label-username">Nama</label>
                                </div>
                                <p id="example-text-username-<?= $result['idpelanggan']; ?>" class="example-text-username">e.g. Kong Jia Le</p>
                              </div>
                              <div class="text-box">
                                <div class="entryarea">
                                  <input
                                    type="email"
                                    name="email"
                                    id="email-<?= $result['idpelanggan']; ?>"
                                    class="email"
                                    pattern="[A-Za-z0-9@.]*"
                                    required
                                    maxlength="100000"
                                    value="<?= $result['email_pelanggan']; ?>"
                                    autocomplete="off" />
                                  <label for="email-<?= $result['idpelanggan']; ?>" id="label-email-<?= $result['idpelanggan']; ?>" class="label-username">E-mel</label>
                                </div>
                                <p id="example-text-email-<?= $result['idpelanggan']; ?>" class="example-text-username">e.g. kongjiale12@gmail.com</p>
                              </div>
                              <div class="text-box">
                                <div class="entryarea">
                                  <input
                                    type="tel"
                                    name="nohp"
                                    id="nohp-<?= $result['idpelanggan']; ?>"
                                    class="username"
                                    required
                                    pattern="\d*"
                                    minlength="10"
                                    maxlength="11"
                                    value="<?= $result['nohp']; ?>"
                                    autocomplete="off" />
                                  <label for="nohp-<?= $result['idpelanggan']; ?>" id="label-nohp-<?= $result['idpelanggan']; ?>" class="label-username">Nombor Telefon</label>
                                </div>
                                <p id="example-text-nohp-<?= $result['idpelanggan']; ?>" class="example-text-username">e.g. 0123456789</p>
                              </div>
                              <div class="text-box">
                                <div class="entryarea">
                                  <input
                                    type="text"
                                    name="password"
                                    id="password-<?= $result['idpelanggan']; ?>"
                                    required
                                    title="Only numbers are allowed!"
                                    pattern="\d*"
                                    minlength="3"
                                    maxlength="3"
                                    autocomplete="off"
                                    value="<?= $result['password_pelanggan']; ?>"
                                    class="password" />
                                  <label for="password-<?= $result['idpelanggan']; ?>" id="label-password-<?= $result['idpelanggan']; ?>" class="label-username">Katalaluan</label>
                                </div>
                                <p class="example-text-password" id="example-text-password-<?= $result['idpelanggan']; ?>">e.g. 123</p>
                              </div>
                              <div class="text-box">
                                <div class="entryarea">
                                  <input
                                    type="text"
                                    name="alamat"
                                    id="alamat-<?= $result['idpelanggan']; ?>"
                                    autocomplete="off"
                                    value="<?= $result['alamat_pelanggan']; ?>"
                                    class="username" />
                                  <label for="alamat-<?= $result['idpelanggan']; ?>" id="label-alamat-<?= $result['idpelanggan']; ?>" class="label-alamat">Alamat</label>
                                </div>
                                <p class="example-text-password" id="example-text-alamat-<?= $result['idpelanggan']; ?>">e.g. Bandar Kinrara</p>
                              </div>
                            </div>

                            <!-- Hidden input field for idpelanggan -->
                            <input type="hidden" name="idpelanggan" value="<?= $result['idpelanggan']; ?>">
                          </form>

                          <!-- Add User button layout -->
                          <div
                            class="
                          modal-food-preview-add-to-basket-button add-button-container
                        ">
                            <button
                              form="edit-user-form-<?= $result['idpelanggan']; ?>"
                              type="submit"
                              name="edit-user-button"
                              class="add-button-dialog">
                              Edit Pengguna
                            </button>
                          </div>
                        </dialog>

                      </div>
                    </div>
                  </li>
                <?php endforeach; ?>
              </ol>
            <?php else: ?>
              <p>No results found</p>
            <?php endif; ?>
          <?php endif; ?>

          <!-- Display Search Results -->
          <?php if (!empty($searchQuery) && empty($menaikResults) && empty($menurunResults)): ?>
            <h3>Jumlah: <?php echo count($searchResults); ?></h3>
            <?php if (count($searchResults) > 0): ?>
              <!-- Display Search Results -->
              <ol id="search-ol">
                <?php foreach ($searchResults as $pelanggan): ?>
                  <li>
                    <div class="user-details-container">
                      <div class="user-details">
                        <p>ID: <?= $pelanggan['idpelanggan']; ?></p>
                        <p>Nama: <?= $pelanggan['namapelanggan']; ?></p>
                        <p>
                          <?php
                          // Count orders for this pelanggan
                          $idpelanggan = $pelanggan['idpelanggan'];
                          $sqlOrders = "SELECT COUNT(*) AS totalOrders FROM maklumat_pesanan WHERE idpelanggan = '$idpelanggan'";
                          $resultOrders = $conn->query($sqlOrders);
                          $rowOrders = $resultOrders->fetch_assoc();
                          echo "Jumlah Pesanan: {$rowOrders['totalOrders']}";
                          ?>
                        </p>
                      </div>
                      <div class="user-action-buttons-container">
                        <div class="order-report-link-container">
                          <a href="admin-order-report.php?idpelanggan=<?= $pelanggan['idpelanggan']; ?>&namapelanggan=<?= $pelanggan['namapelanggan']; ?>" class="order-report-button">
                            Laporan Pesanan
                          </a>
                        </div>
                        <div class="edit-and-delete-buttons-container">
                          <button
                            id="js-edit-button-<?= $pelanggan['idpelanggan']; ?>"
                            class="edit-button"
                            data-button-id="<?= $pelanggan['idpelanggan']; ?>">
                            Edit
                          </button>
                          <form action="admin-manage-user.php" method="POST" style="display:inline;">
                            <input type="hidden" name="idpelanggan" value="<?= $pelanggan['idpelanggan']; ?>" />
                            <button type="submit" class="delete-button" name="delete" onclick="return confirm('Are you sure you want to delete this record?');">Buang</button>
                          </form>
                        </div>

                        <dialog id="edit-user-dialog-<?= $pelanggan['idpelanggan']; ?>" class="food-preview-modal">
                          <!--First Vertical Layout-->
                          <div class="modal-title-and-close-button">
                            <div class="modal-title">
                              Edit Pengguna
                            </div>
                            <button
                              class="close-button-food-preview-modal"
                              id="close-dialog-button"
                              onclick="
                            closeEditUserDialog('<?= $pelanggan['idpelanggan']; ?>')
                        ">
                              x
                            </button>
                          </div>

                          <!-- Main content -->
                          <form id="edit-user-form-<?= $pelanggan['idpelanggan']; ?>" class="form-dialog" action="" method="post" enctype="multipart/form-data">
                            <div class="modal-scrollable-content">
                              <div class="text-box">
                                <div class="entryarea">
                                  <input
                                    type="text"
                                    name="username"
                                    id="username-<?= $pelanggan['idpelanggan']; ?>"
                                    class="username"
                                    required
                                    maxlength="30"
                                    pattern="[A-Za-z ]+"
                                    autocomplete="off"
                                    value="<?= $pelanggan['namapelanggan']; ?>"
                                    title="Only alphabets are allowed" />
                                  <label for="username-<?= $pelanggan['idpelanggan']; ?>" id="label-username-<?= $pelanggan['idpelanggan']; ?>" class="label-username">Nama</label>
                                </div>
                                <p id="example-text-username-<?= $pelanggan['idpelanggan']; ?>" class="example-text-username">e.g. Kong Jia Le</p>
                              </div>
                              <div class="text-box">
                                <div class="entryarea">
                                  <input
                                    type="email"
                                    name="email"
                                    id="email-<?= $pelanggan['idpelanggan']; ?>"
                                    class="email"
                                    pattern="[A-Za-z0-9@.]*"
                                    required
                                    maxlength="100000"
                                    value="<?= $pelanggan['email_pelanggan']; ?>"
                                    autocomplete="off" />
                                  <label for="email-<?= $pelanggan['idpelanggan']; ?>" id="label-email-<?= $pelanggan['idpelanggan']; ?>" class="label-username">E-mel</label>
                                </div>
                                <p id="example-text-email-<?= $pelanggan['idpelanggan']; ?>" class="example-text-username">e.g. kongjiale12@gmail.com</p>
                              </div>
                              <div class="text-box">
                                <div class="entryarea">
                                  <input
                                    type="tel"
                                    name="nohp"
                                    id="nohp-<?= $pelanggan['idpelanggan']; ?>"
                                    class="username"
                                    required
                                    pattern="\d*"
                                    minlength="10"
                                    maxlength="11"
                                    value="<?= $pelanggan['nohp']; ?>"
                                    autocomplete="off" />
                                  <label for="nohp-<?= $pelanggan['idpelanggan']; ?>" id="label-nohp-<?= $pelanggan['idpelanggan']; ?>" class="label-username">Nombor Telefon</label>
                                </div>
                                <p id="example-text-nohp-<?= $pelanggan['idpelanggan']; ?>" class="example-text-username">e.g. 0123456789</p>
                              </div>
                              <div class="text-box">
                                <div class="entryarea">
                                  <input
                                    type="text"
                                    name="password"
                                    id="password-<?= $pelanggan['idpelanggan']; ?>"
                                    required
                                    title="Only numbers are allowed!"
                                    pattern="\d*"
                                    minlength="3"
                                    maxlength="3"
                                    autocomplete="off"
                                    value="<?= $pelanggan['password_pelanggan']; ?>"
                                    class="password" />
                                  <label for="password-<?= $pelanggan['idpelanggan']; ?>" id="label-password-<?= $pelanggan['idpelanggan']; ?>" class="label-username">Katalaluan</label>
                                </div>
                                <p class="example-text-password" id="example-text-password-<?= $pelanggan['idpelanggan']; ?>">e.g. 123</p>
                              </div>
                              <div class="text-box">
                                <div class="entryarea">
                                  <input
                                    type="text"
                                    name="alamat"
                                    id="alamat-<?= $pelanggan['idpelanggan']; ?>"
                                    autocomplete="off"
                                    value="<?= $pelanggan['alamat_pelanggan']; ?>"
                                    class="username" />
                                  <label for="alamat-<?= $pelanggan['idpelanggan']; ?>" id="label-alamat-<?= $pelanggan['idpelanggan']; ?>" class="label-alamat">Alamat</label>
                                </div>
                                <p class="example-text-password" id="example-text-alamat-<?= $pelanggan['idpelanggan']; ?>">e.g. Bandar Kinrara</p>
                              </div>
                            </div>

                            <!-- Hidden input field for idpelanggan -->
                            <input type="hidden" name="idpelanggan" value="<?= $pelanggan['idpelanggan']; ?>">
                          </form>

                          <!-- Add User button layout -->
                          <div
                            class="
                          modal-food-preview-add-to-basket-button add-button-container
                        ">
                            <button
                              form="edit-user-form-<?= $pelanggan['idpelanggan']; ?>"
                              type="submit"
                              name="edit-user-button"
                              class="add-button-dialog">
                              Edit Pengguna
                            </button>
                          </div>
                        </dialog>

                      </div>
                    </div>
                  </li>
                <?php endforeach; ?>
              </ol>
            <?php else: ?>
              <p>No results found for "<?php echo htmlspecialchars($searchQuery); ?>"</p>
            <?php endif; ?>
          <?php endif; ?>

          <!-- Display All Pelanggan (if no search/menaik/menurun results) -->
          <?php if (empty($searchQuery) && empty($menaikResults) && empty($menurunResults)): ?>
            <ol id="all-users-ol">
              <?php while ($rowPelanggan = $resultPelanggan->fetch_assoc()): ?>
                <li>
                  <div class="user-details-container">
                    <div class="user-details">
                      <p>ID: <?= $rowPelanggan['idpelanggan']; ?></p>
                      <p>Nama: <?= $rowPelanggan['namapelanggan']; ?></p>
                      <p>
                        <?php
                        // Count orders for this pelanggan
                        $idpelanggan = $rowPelanggan['idpelanggan'];
                        $sqlOrders = "SELECT COUNT(*) AS totalOrders FROM maklumat_pesanan WHERE idpelanggan = '$idpelanggan'";
                        $resultOrders = $conn->query($sqlOrders);
                        $rowOrders = $resultOrders->fetch_assoc();
                        echo "Jumlah Pesanan: {$rowOrders['totalOrders']}";
                        ?>
                      </p>
                    </div>
                    <div class="user-action-buttons-container">
                      <div class="order-report-link-container">
                        <a href="admin-order-report.php?idpelanggan=<?= $rowPelanggan['idpelanggan']; ?>&namapelanggan=<?= $rowPelanggan['namapelanggan']; ?>" class="order-report-button">
                          Laporan Pesanan
                        </a>
                      </div>
                      <div class="edit-and-delete-buttons-container">
                        <button
                          id="js-edit-button-<?= $rowPelanggan['idpelanggan']; ?>"
                          class="edit-button"
                          data-button-id="<?= $rowPelanggan['idpelanggan']; ?>">
                          Edit
                        </button>
                        <form action=" admin-manage-user.php" method="POST" style="display:inline;">
                          <input type="hidden" name="idpelanggan" value="<?= $rowPelanggan['idpelanggan']; ?>" />
                          <button type="submit" class="delete-button" name="delete" onclick="return confirm('Are you sure you want to delete this record?');">Buang</button>
                        </form>
                      </div>

                      <dialog id="edit-user-dialog-<?= $rowPelanggan['idpelanggan']; ?>" class="food-preview-modal">
                        <!--First Vertical Layout-->
                        <div class="modal-title-and-close-button">
                          <div class="modal-title">
                            Edit Pengguna
                          </div>
                          <button
                            class="close-button-food-preview-modal"
                            id="close-dialog-button"
                            onclick="
                            closeEditUserDialog('<?= $rowPelanggan['idpelanggan']; ?>')
                        ">
                            x
                          </button>
                        </div>

                        <!-- Main content -->
                        <form id="edit-user-form-<?= $rowPelanggan['idpelanggan']; ?>" class="form-dialog" action="" method="post" enctype="multipart/form-data">
                          <div class="modal-scrollable-content">
                            <div class="text-box">
                              <div class="entryarea">
                                <input
                                  type="text"
                                  name="username"
                                  id="username-<?= $rowPelanggan['idpelanggan']; ?>"
                                  class="username"
                                  required
                                  maxlength="30"
                                  pattern="[A-Za-z ]+"
                                  autocomplete="off"
                                  value="<?= $rowPelanggan['namapelanggan']; ?>"
                                  title="Only alphabets are allowed" />
                                <label for="username-<?= $rowPelanggan['idpelanggan']; ?>" id="label-username-<?= $rowPelanggan['idpelanggan']; ?>" class="label-username">Nama</label>
                              </div>
                              <p id="example-text-username-<?= $rowPelanggan['idpelanggan']; ?>" class="example-text-username">e.g. Kong Jia Le</p>
                            </div>
                            <div class="text-box">
                              <div class="entryarea">
                                <input
                                  type="email"
                                  name="email"
                                  id="email-<?= $rowPelanggan['idpelanggan']; ?>"
                                  class="email"
                                  pattern="[A-Za-z0-9@.]*"
                                  required
                                  maxlength="100000"
                                  value="<?= $rowPelanggan['email_pelanggan']; ?>"
                                  autocomplete="off" />
                                <label for="email-<?= $rowPelanggan['idpelanggan']; ?>" id="label-email-<?= $rowPelanggan['idpelanggan']; ?>" class="label-username">E-mel</label>
                              </div>
                              <p id="example-text-email-<?= $rowPelanggan['idpelanggan']; ?>" class="example-text-username">e.g. kongjiale12@gmail.com</p>
                            </div>
                            <div class="text-box">
                              <div class="entryarea">
                                <input
                                  type="tel"
                                  name="nohp"
                                  id="nohp-<?= $rowPelanggan['idpelanggan']; ?>"
                                  class="username"
                                  required
                                  pattern="\d*"
                                  minlength="10"
                                  maxlength="11"
                                  value="<?= $rowPelanggan['nohp']; ?>"
                                  autocomplete="off" />
                                <label for="nohp-<?= $rowPelanggan['idpelanggan']; ?>" id="label-nohp-<?= $rowPelanggan['idpelanggan']; ?>" class="label-username">Nombor Telefon</label>
                              </div>
                              <p id="example-text-nohp-<?= $rowPelanggan['idpelanggan']; ?>" class="example-text-username">e.g. 0123456789</p>
                            </div>
                            <div class="text-box">
                              <div class="entryarea">
                                <input
                                  type="text"
                                  name="password"
                                  id="password-<?= $rowPelanggan['idpelanggan']; ?>"
                                  required
                                  title="Only numbers are allowed!"
                                  pattern="\d*"
                                  minlength="3"
                                  maxlength="3"
                                  autocomplete="off"
                                  value="<?= $rowPelanggan['password_pelanggan']; ?>"
                                  class="password" />
                                <label for="password-<?= $rowPelanggan['idpelanggan']; ?>" id="label-password-<?= $rowPelanggan['idpelanggan']; ?>" class="label-username">Katalaluan</label>
                              </div>
                              <p class="example-text-password" id="example-text-password-<?= $rowPelanggan['idpelanggan']; ?>">e.g. 123</p>
                            </div>
                            <div class="text-box">
                              <div class="entryarea">
                                <input
                                  type="text"
                                  name="alamat"
                                  id="alamat-<?= $rowPelanggan['idpelanggan']; ?>"
                                  autocomplete="off"
                                  value="<?= $rowPelanggan['alamat_pelanggan']; ?>"
                                  class="username" />
                                <label for="alamat-<?= $rowPelanggan['idpelanggan']; ?>" id="label-alamat-<?= $rowPelanggan['idpelanggan']; ?>" class="label-alamat">Alamat</label>
                              </div>
                              <p class="example-text-password" id="example-text-alamat-<?= $rowPelanggan['idpelanggan']; ?>">e.g. Bandar Kinrara</p>
                            </div>
                          </div>

                          <!-- Hidden input field for idpelanggan -->
                          <input type="hidden" name="idpelanggan" value="<?= $rowPelanggan['idpelanggan']; ?>">
                        </form>

                        <!-- Add User button layout -->
                        <div
                          class="
                          modal-food-preview-add-to-basket-button add-button-container
                        ">
                          <button
                            form="edit-user-form-<?= $rowPelanggan['idpelanggan']; ?>"
                            type="submit"
                            name="edit-user-button"
                            class="add-button-dialog">
                            Edit Pengguna
                          </button>
                        </div>
                      </dialog>

                    </div>
                  </div>
                </li>
              <?php endwhile; ?>
            </ol>
          <?php endif; ?>

        </div>
      </div>

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
</body>

<!--Link the JS file-->
<script>
  // Check if the page was reloaded and force redirect to the current page
  if (performance.navigation.type === 1) {
    window.location.href = window.location.pathname;
  }

  function validateSearch() {
    const searchField = document.getElementById('searchQuery').value.trim();
    if (searchField === '') {
      window.location.href = window.location.pathname;
      return false; // Prevent form submission
    }
    return true; // Allow form submission
  }

  document.querySelector(".reset-button").addEventListener("click", () => {
    let inputElement = document.querySelector(".input-username");
    inputElement.value = "";
  });
</script>
<script src="data/HeaderLinksDataAdmin.js"></script>
<script src="js/generateHeaderLinksAdmin.js"></script>
<script src="data/mobileHeaderLinksDataAdmin.js"></script>
<script src="js/generateMobileHeaderLinksAdmin.js"></script>
<script src="js/admin-mobile-navigation-bar.js"></script>
<script src="js/admin-manage-user.js"></script>

</html>