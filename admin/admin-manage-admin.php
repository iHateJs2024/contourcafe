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
$totalJurujual = 0;
$menaikResults = [];
$menurunResults = [];

//! Handle search and menaik/menurun button actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['search'])) {
    // Handle search
    $searchQuery = trim($conn->real_escape_string($_POST['searchQuery']));
    if (!empty($searchQuery)) {
      $sqlSearch = "SELECT * FROM jurujual WHERE namajurujual LIKE '%$searchQuery%'";
      $resultSearch = $conn->query($sqlSearch);
      if ($resultSearch && $resultSearch->num_rows > 0) {
        $searchResults = $resultSearch->fetch_all(MYSQLI_ASSOC);
      }
    }
  }
}

//! Fetch all pelanggan if no search is performed
if (empty($searchQuery)) {
  $sqlJurujual = "SELECT * FROM jurujual";
  $resultJurujual = $conn->query($sqlJurujual);
  $totalJurujual = $resultJurujual->num_rows;
}

//! Check if the 'delete' button is clicked and process the deletion
if (isset($_POST['delete'])) {
  // Get the idjurujual to delete
  $idjurujual = $_POST['idjurujual'];

  //* Delete query
  $deleteQuery = "DELETE FROM jurujual WHERE idjurujual = '$idjurujual'";

  if ($conn->query($deleteQuery)) {
    echo "Record deleted successfully.";
  } else {
    echo "Error deleting record: " . $conn->error;
  }

  // Redirect back to the same page after deletion (to refresh the table)
  header("Location: admin-manage-admin.php");
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

  $checkemail = "SELECT * FROM jurujual WHERE email_jurujual='$email'";
  $resultEmail = $conn->query($checkemail);
  $checknohp = "SELECT * FROM jurujual WHERE nohp='$nohp'";
  $resultNohp = $conn->query($checknohp);
  if ($resultEmail->num_rows > 0) {
    echo '<script>alert("E-mel Sudah Wujud!");
                    window.location.href = "admin-manage-admin.php";
              </script>';
  } else if ($resultNohp->num_rows > 0) {
    echo '<script>alert("Nohp Sudah Wujud!");
                    window.location.href = "admin-manage-admin.php";
              </script>';
  } else {
    $insertQuery = "INSERT INTO jurujual (namajurujual, password_jurujual, nohp, email_jurujual)
                          VALUES ('$username', '$password', '$nohp', '$email')";
    if ($conn->query($insertQuery) == TRUE) {
      echo '<script>alert("Berjaya tambah Jurujual!");
                  window.location.href = "admin-manage-admin.php";
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
  $idjurujual = $_POST['idjurujual'];
  $namajurujual = $_POST['username'] ?? null;
  $email = $_POST['email'] ?? null;
  $nohp = $_POST['nohp'] ?? null;
  $password_jurujual = $_POST['password'] ?? null;

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

  // Always update `namajurujual` if provided
  if (!empty($namajurujual)) {
    $sql_update_namajurujual = "UPDATE jurujual SET namajurujual = ? WHERE idjurujual = ?";
    $stmt_update_name = $conn->prepare($sql_update_namajurujual);
    $stmt_update_name->bind_param("ss", $namajurujual, $idjurujual);
    if ($stmt_update_name->execute()) {
      $_SESSION['namajurujual'] = $namajurujual;
      $messages[] = "Nama berjaya dikemas kini!";
    } else {
      $messages[] = "Failed to update nama pelanggan!";
    }
  }

  // Always update `password_jurujual` if provided
  if (!empty($password_jurujual)) {
    $sql_update_password_jurujual = "UPDATE jurujual SET password_jurujual = ? WHERE idjurujual = ?";
    $stmt_update_password = $conn->prepare($sql_update_password_jurujual);
    $stmt_update_password->bind_param("is", $password_jurujual, $idjurujual);
    if ($stmt_update_password->execute()) {
      $messages[] = "Kata laluan berjaya dikemas kini!";
    } else {
      $messages[] = "Failed to update password!";
    }
  }

  // Check and update `email_pelanggan`
  if (!empty($email)) {
    $sql_check_email = "SELECT COUNT(*) AS count FROM jurujual WHERE email_jurujual = ?";
    $stmt_check_email = $conn->prepare($sql_check_email);
    $stmt_check_email->bind_param("s", $email);
    $stmt_check_email->execute();
    $result_check_email = $stmt_check_email->get_result();
    $row_check_email = $result_check_email->fetch_assoc();

    if ($row_check_email['count'] > 0) {
      $messages[] = "E-mel '$email' sudah wujud!";
    } else {
      $sql_update_email = "UPDATE jurujual SET email_jurujual = ? WHERE idjurujual = ?";
      $stmt_update_email = $conn->prepare($sql_update_email);
      $stmt_update_email->bind_param("ss", $email, $idjurujual);
      if ($stmt_update_email->execute()) {
        $messages[] = "E-mel berjaya dikemas kini!";
      } else {
        $messages[] = "Failed to update email!";
      }
    }
  }

  // Check and update `nohp`
  if (!empty($nohp)) {
    $sql_check_nohp = "SELECT COUNT(*) AS count FROM jurujual WHERE nohp = ?";
    $stmt_check_nohp = $conn->prepare($sql_check_nohp);
    $stmt_check_nohp->bind_param("s", $nohp);
    $stmt_check_nohp->execute();
    $result_check_nohp = $stmt_check_nohp->get_result();
    $row_check_nohp = $result_check_nohp->fetch_assoc();

    if ($row_check_nohp['count'] > 0) {
      $messages[] = "Nombor telefon '$nohp' sudah wujud!";
    } else {
      $sql_update_nohp = "UPDATE jurujual SET nohp = ? WHERE idjurujual = ?";
      $stmt_update_nohp = $conn->prepare($sql_update_nohp);
      $stmt_update_nohp->bind_param("ss", $nohp, $idjurujual);
      if ($stmt_update_nohp->execute()) {
        $messages[] = "Nombor telefon berjaya dikemas kini!";
      } else {
        $messages[] = "Failed to update No HP!";
      }
    }
  }

  // Close connection
  $conn->close();

  if (!empty($messages)) {
    $messagesString = implode("\n", $messages); // Convert array to a newline-separated string
    echo "<script type='text/javascript'>
      alert(`$messagesString`);
      window.location.href = 'admin-manage-admin.php';
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
  <link rel="stylesheet" href="css/admin-manage-user.css" />
  <link rel="stylesheet" href="css/admin-manage-admin.css" />
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

      <!-- Title/Add user button  -->
      <div class="title-and-add-category-button">
        <p class="main-title">Urus Jurujual</p>
        <button id="js-tambah-pengguna-button" class="add-category-button">
          Tambah Jurujual
        </button>
      </div>

      <div class="main-container">
        <!-- Search bar-->
        <div class="search-bar-and-add-user-button-container">
          <div>
            <form method="POST" action="" onsubmit="return validateSearch()">
              <input
                class="input-username"
                type="text"
                name="searchQuery"
                id="searchQuery"
                value="<?php echo htmlspecialchars($searchQuery); ?>"
                placeholder="Masukkan nama jurujual">
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

        <!-- Dialog for add jurujual button -->
        <dialog id="add-user-dialog" class="food-preview-modal">
          <!--First Vertical Layout-->
          <div class="modal-title-and-close-button">
            <div class="modal-title">
              Tambah Jurujual
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
            </div>
          </form>

          <!-- Add Jurujual button layout -->
          <div
            class="
            modal-food-preview-add-to-basket-button add-button-container
        ">
            <button
              form="add-user-form"
              type="submit"
              name="add-user-button"
              class="add-button-dialog">
              Tambah Jurujual
            </button>
          </div>
        </dialog>

        <!-- Display Jumlah Jurujual -->
        <?php if (empty($searchQuery)): ?>
          <h3>Jumlah: <?php echo $totalJurujual; ?></h3>
        <?php endif; ?>

        <div class="user-list-container">

          <!-- Display Search Results -->
          <?php if (!empty($searchQuery)): ?>
            <h3>Jumlah: <?php echo count($searchResults); ?></h3>
            <?php if (count($searchResults) > 0): ?>
              <!-- Display Search Results -->
              <ol id="search-ol">
                <?php foreach ($searchResults as $jurujual): ?>
                  <li>
                    <div class="user-details-container">
                      <div class="user-details">
                        <p>ID: <?= $jurujual['idjurujual']; ?></p>
                        <p>Nama: <?= $jurujual['namajurujual']; ?></p>
                        <p>Nombor Telefon: <?= $jurujual['nohp']; ?></p>
                        <p>E-mel: <?= $jurujual['email_jurujual']; ?></p>
                      </div>
                      <div class="user-action-buttons-container">
                        <div class="edit-and-delete-buttons-container">
                          <button
                            id="js-edit-button-<?= $jurujual['idjurujual']; ?>"
                            class="edit-button"
                            data-button-id="<?= $jurujual['idjurujual']; ?>">
                            Edit
                          </button>
                          <form action="admin-manage-admin.php" method="POST" style="display:inline;">
                            <input type="hidden" name="idjurujual" value="<?= $jurujual['idjurujual']; ?>" />
                            <button type="submit" class="delete-button" name="delete" onclick="return confirm('Are you sure you want to delete this record?');">Buang</button>
                          </form>
                        </div>

                        <dialog id="edit-user-dialog-<?= $jurujual['idjurujual']; ?>" class="food-preview-modal">
                          <!--First Vertical Layout-->
                          <div class="modal-title-and-close-button">
                            <div class="modal-title">
                              Edit Jurujual
                            </div>
                            <button
                              class="close-button-food-preview-modal"
                              id="close-dialog-button"
                              onclick="
                            closeEditUserDialog('<?= $jurujual['idjurujual']; ?>')
                        ">
                              x
                            </button>
                          </div>

                          <!-- Main content -->
                          <form id="edit-user-form-<?= $jurujual['idjurujual']; ?>" class="form-dialog" action="" method="post" enctype="multipart/form-data">
                            <div class="modal-scrollable-content">
                              <div class="text-box">
                                <div class="entryarea">
                                  <input
                                    type="text"
                                    name="username"
                                    id="username-<?= $jurujual['idjurujual']; ?>"
                                    class="username"
                                    required
                                    maxlength="30"
                                    pattern="[A-Za-z ]+"
                                    autocomplete="off"
                                    value="<?= $jurujual['namajurujual']; ?>"
                                    title="Only alphabets are allowed" />
                                  <label for="username-<?= $jurujual['idjurujual']; ?>" id="label-username-<?= $jurujual['idjurujual']; ?>" class="label-username">Nama</label>
                                </div>
                                <p id="example-text-username-<?= $jurujual['idjurujual']; ?>" class="example-text-username">e.g. Kong Jia Le</p>
                              </div>
                              <div class="text-box">
                                <div class="entryarea">
                                  <input
                                    type="email"
                                    name="email"
                                    id="email-<?= $jurujual['idjurujual']; ?>"
                                    class="email"
                                    pattern="[A-Za-z0-9@.]*"
                                    required
                                    maxlength="100000"
                                    value="<?= $jurujual['email_jurujual']; ?>"
                                    autocomplete="off" />
                                  <label for="email-<?= $jurujual['idjurujual']; ?>" id="label-email-<?= $jurujual['idjurujual']; ?>" class="label-username">E-mel</label>
                                </div>
                                <p id="example-text-email-<?= $jurujual['idjurujual']; ?>" class="example-text-username">e.g. kongjiale12@gmail.com</p>
                              </div>
                              <div class="text-box">
                                <div class="entryarea">
                                  <input
                                    type="tel"
                                    name="nohp"
                                    id="nohp-<?= $jurujual['idjurujual']; ?>"
                                    class="username"
                                    required
                                    pattern="\d*"
                                    minlength="10"
                                    maxlength="11"
                                    value="<?= $jurujual['nohp']; ?>"
                                    autocomplete="off" />
                                  <label for="nohp-<?= $jurujual['idjurujual']; ?>" id="label-nohp-<?= $jurujual['idjurujual']; ?>" class="label-username">Nombor Telefon</label>
                                </div>
                                <p id="example-text-nohp-<?= $jurujual['idjurujual']; ?>" class="example-text-username">e.g. 0123456789</p>
                              </div>
                              <div class="text-box">
                                <div class="entryarea">
                                  <input
                                    type="text"
                                    name="password"
                                    id="password-<?= $jurujual['idjurujual']; ?>"
                                    required
                                    title="Only numbers are allowed!"
                                    pattern="\d*"
                                    minlength="3"
                                    maxlength="3"
                                    autocomplete="off"
                                    value="<?= $jurujual['password_jurujual']; ?>"
                                    class="password" />
                                  <label for="password-<?= $jurujual['idjurujual']; ?>" id="label-password-<?= $jurujual['idjurujual']; ?>" class="label-username">Katalaluan</label>
                                </div>
                                <p class="example-text-password" id="example-text-password-<?= $jurujual['idjurujual']; ?>">e.g. 123</p>
                              </div>
                            </div>

                            <!-- Hidden input field for idpelanggan -->
                            <input type="hidden" name="idjurujual" value="<?= $jurujual['idjurujual']; ?>">
                          </form>

                          <!-- Add User button layout -->
                          <div
                            class="
                          modal-food-preview-add-to-basket-button add-button-container
                        ">
                            <button
                              form="edit-user-form-<?= $jurujual['idjurujual']; ?>"
                              type="submit"
                              name="edit-user-button"
                              class="add-button-dialog">
                              Edit Jurujual
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
          <?php if (empty($searchQuery)): ?>
            <ol id="all-users-ol">
              <?php while ($rowJurujual = $resultJurujual->fetch_assoc()): ?>
                <li>
                  <div class="user-details-container">
                    <div class="user-details">
                      <p>ID: <?= $rowJurujual['idjurujual']; ?></p>
                      <p>Nama: <?= $rowJurujual['namajurujual']; ?></p>
                      <p>Nohp: <?= $rowJurujual['nohp']; ?></p>
                      <p>E-mel: <?= $rowJurujual['email_jurujual']; ?></p>
                    </div>
                    <div class="user-action-buttons-container">
                      <div class="edit-and-delete-buttons-container">
                        <button
                          id="js-edit-button-<?= $rowJurujual['idjurujual']; ?>"
                          class="edit-button"
                          data-button-id="<?= $rowJurujual['idjurujual']; ?>">
                          Edit
                        </button>
                        <form action=" admin-manage-admin.php" method="POST" style="display:inline;">
                          <input type="hidden" name="idjurujual" value="<?= $rowJurujual['idjurujual']; ?>" />
                          <button type="submit" class="delete-button" name="delete" onclick="return confirm('Are you sure you want to delete this record?');">Buang</button>
                        </form>
                      </div>

                      <dialog id="edit-user-dialog-<?= $rowJurujual['idjurujual']; ?>" class="food-preview-modal">
                        <!--First Vertical Layout-->
                        <div class="modal-title-and-close-button">
                          <div class="modal-title">
                            Edit Jurujual
                          </div>
                          <button
                            class="close-button-food-preview-modal"
                            id="close-dialog-button"
                            onclick="
                            closeEditUserDialog('<?= $rowJurujual['idjurujual']; ?>')
                        ">
                            x
                          </button>
                        </div>

                        <!-- Main content -->
                        <form id="edit-user-form-<?= $rowJurujual['idjurujual']; ?>" class="form-dialog" action="" method="post" enctype="multipart/form-data">
                          <div class="modal-scrollable-content">
                            <div class="text-box">
                              <div class="entryarea">
                                <input
                                  type="text"
                                  name="username"
                                  id="username-<?= $rowJurujual['idjurujual']; ?>"
                                  class="username"
                                  required
                                  maxlength="30"
                                  pattern="[A-Za-z ]+"
                                  autocomplete="off"
                                  value="<?= $rowJurujual['namajurujual']; ?>"
                                  title="Only alphabets are allowed" />
                                <label for="username-<?= $rowJurujual['idjurujual']; ?>" id="label-username-<?= $rowJurujual['idjurujual']; ?>" class="label-username">Nama</label>
                              </div>
                              <p id="example-text-username-<?= $rowJurujual['idjurujual']; ?>" class="example-text-username">e.g. Kong Jia Le</p>
                            </div>
                            <div class="text-box">
                              <div class="entryarea">
                                <input
                                  type="email"
                                  name="email"
                                  id="email-<?= $rowJurujual['idjurujual']; ?>"
                                  class="email"
                                  pattern="[A-Za-z0-9@.]*"
                                  required
                                  maxlength="100000"
                                  value="<?= $rowJurujual['email_jurujual']; ?>"
                                  autocomplete="off" />
                                <label for="email-<?= $rowJurujual['idjurujual']; ?>" id="label-email-<?= $rowJurujual['idjurujual']; ?>" class="label-username">E-mel</label>
                              </div>
                              <p id="example-text-email-<?= $rowJurujual['idjurujual']; ?>" class="example-text-username">e.g. kongjiale12@gmail.com</p>
                            </div>
                            <div class="text-box">
                              <div class="entryarea">
                                <input
                                  type="tel"
                                  name="nohp"
                                  id="nohp-<?= $rowJurujual['idjurujual']; ?>"
                                  class="username"
                                  required
                                  pattern="\d*"
                                  minlength="10"
                                  maxlength="11"
                                  value="<?= $rowJurujual['nohp']; ?>"
                                  autocomplete="off" />
                                <label for="nohp-<?= $rowJurujual['idjurujual']; ?>" id="label-nohp-<?= $rowJurujual['idjurujual']; ?>" class="label-username">Nombor Telefon</label>
                              </div>
                              <p id="example-text-nohp-<?= $rowJurujual['idjurujual']; ?>" class="example-text-username">e.g. 0123456789</p>
                            </div>
                            <div class="text-box">
                              <div class="entryarea">
                                <input
                                  type="text"
                                  name="password"
                                  id="password-<?= $rowJurujual['idjurujual']; ?>"
                                  required
                                  title="Only numbers are allowed!"
                                  pattern="\d*"
                                  minlength="3"
                                  maxlength="3"
                                  autocomplete="off"
                                  value="<?= $rowJurujual['password_jurujual']; ?>"
                                  class="password" />
                                <label for="password-<?= $rowJurujual['idjurujual']; ?>" id="label-password-<?= $rowJurujual['idjurujual']; ?>" class="label-username">Katalaluan</label>
                              </div>
                              <p class="example-text-password" id="example-text-password-<?= $rowJurujual['idjurujual']; ?>">e.g. 123</p>
                            </div>
                          </div>

                          <!-- Hidden input field for idpelanggan -->
                          <input type="hidden" name="idjurujual" value="<?= $rowJurujual['idjurujual']; ?>">
                        </form>

                        <!-- Add User button layout -->
                        <div
                          class="
                          modal-food-preview-add-to-basket-button add-button-container
                        ">
                          <button
                            form="edit-user-form-<?= $rowJurujual['idjurujual']; ?>"
                            type="submit"
                            name="edit-user-button"
                            class="add-button-dialog">
                            Edit Jurujual
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
<script src="js/admin-manage-admin.js"></script>

</html>