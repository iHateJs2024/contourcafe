<?php
// Start the session
session_start();

include_once(__DIR__ . '/../connect.php');
$conn = getConnection();

//! PHP TO CHECK IF ADMIN exists in db and logged in
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

// Initialize the variable for the name
$namajurujual = "";
$idjurujual = $_SESSION['idjurujual'];

//!PHP TO GET nohp, email_jurujual and FROM jurujual table
$sql = "SELECT * FROM jurujual WHERE idjurujual = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $idjurujual);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $nohp_current = $row['nohp'];
  $email_current = $row['email_jurujual'];
} else {
  echo "No records found for the given idjurujual.";
}

?>

<?php
//! PHP TO UPDATE DATA
// Ensure `idpelanggan` is set in the session
if (!isset($_SESSION['idjurujual'])) {
  die("idjurujual is not set in the session.");
}

$idjurujual = $_SESSION['idjurujual']; // Retrieve `idpelanggan` from session

// Retrieve form inputs
$namajurujual = $_POST['username'] ?? null;
$password_jurujual = $_POST['password'] ?? null;
$email = $_POST['email'] ?? null;
$nohp = $_POST['nohp'] ?? null;

include_once(__DIR__ . '/../connect.php');
$conn = getConnection();

// Message array to store results
$messages = [];

// Always update `namapelanggan` if provided
if (!empty($namajurujual)) {
  $sql_update_namajurujual = "UPDATE jurujual SET namajurujual = ? WHERE idjurujual = ?";
  $stmt_update_name = $conn->prepare($sql_update_namajurujual);
  $stmt_update_name->bind_param("ss", $namajurujual, $idjurujual);
  if ($stmt_update_name->execute()) {
    $_SESSION['namajurujual'] = $namajurujual;
    $messages[] = "Nama berjaya dikemas kini!";
  } else {
    $messages[] = "Gagal untuk mengemas kini nama jurujual!";
  }
}

// Always update `password_jurujual` if provided
if (!empty($password_jurujual)) {
  $sql_update_password_jurujual = "UPDATE jurujual SET password_jurujual = ? WHERE idjurujual = ?";
  $stmt_update_password = $conn->prepare($sql_update_password_jurujual);
  $stmt_update_password->bind_param("ss", $password_jurujual, $idjurujual);
  if ($stmt_update_password->execute()) {
    $messages[] = "Kata laluan berjaya dikemas kini!";
  } else {
    $messages[] = "Gagal untuk mengemas kini kata laluan!";
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
      $messages[] = "Gagal mengemas kini e-mel!";
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
      $messages[] = "Gagal untuk mengemas kini nombor telefon!";
    }
  }
}

// Close connection
$conn->close();

if (!empty($messages)) {
  $messagesString = implode("\n", $messages); // Convert array to a newline-separated string
  echo "<script type='text/javascript'>
    alert(`$messagesString`);
    window.location.href = 'admin-profile.php';
  </script>";
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
  <link rel="stylesheet" href="../css/signup.css" />
  <link rel="stylesheet" href="../css/user-profile.css" />
  <link rel="stylesheet" href="css/admin-profile.css" />
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
    <!-- Mobile version settings starts here! -->
    <div class="mobile-nav-head">
      <p>
        Profil
      </p>
      <button class="setting-btn">
        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M15.3613 11.3418C14.6068 11.2673 13.8477 11.4278 13.188 11.8014C12.5282 12.175 12 12.7434 11.6758 13.4287C11.3515 14.1141 11.247 14.8829 11.3766 15.6299C11.5062 16.3769 11.8635 17.0657 12.3996 17.6018C12.9357 18.1379 13.6244 18.4952 14.3715 18.6248C15.1185 18.7544 15.8873 18.6499 16.5727 18.3256C17.258 18.0013 17.8264 17.4732 18.1999 16.8134C18.5735 16.1536 18.734 15.3945 18.6595 14.64C18.5746 13.7942 18.1998 13.0038 17.5987 12.4027C16.9976 11.8016 16.2072 11.4268 15.3613 11.3418V11.3418ZM24.2159 15.0014C24.2135 15.4012 24.1842 15.8003 24.128 16.196L26.7257 18.2336C26.8388 18.3273 26.9151 18.4582 26.9408 18.6028C26.9666 18.7475 26.9403 18.8966 26.8665 19.0236L24.4089 23.2756C24.3343 23.4015 24.2176 23.4969 24.0795 23.5452C23.9414 23.5934 23.7907 23.5914 23.6539 23.5394L21.074 22.5005C20.9317 22.4439 20.7776 22.4234 20.6255 22.4409C20.4734 22.4585 20.328 22.5135 20.2023 22.6011C19.8086 22.8722 19.3948 23.1131 18.9646 23.3216C18.8294 23.3873 18.7124 23.4854 18.624 23.607C18.5356 23.7287 18.4786 23.8702 18.4578 24.0192L18.0711 26.7709C18.0457 26.9162 17.9707 27.0482 17.8587 27.1443C17.7468 27.2404 17.605 27.2947 17.4575 27.2978H12.5424C12.3973 27.2953 12.2574 27.2435 12.1458 27.1508C12.0341 27.0582 11.9574 26.9303 11.9281 26.7882L11.542 24.0404C11.5203 23.8898 11.4618 23.747 11.3715 23.6245C11.2813 23.502 11.1622 23.4037 11.0249 23.3383C10.5952 23.1309 10.1828 22.8893 9.79177 22.616C9.66656 22.5289 9.52165 22.4743 9.37008 22.4571C9.21852 22.44 9.06507 22.4608 8.92356 22.5177L6.34419 23.556C6.2075 23.6081 6.05682 23.6102 5.91871 23.5621C5.78059 23.514 5.66389 23.4186 5.58917 23.2929L3.13162 19.0409C3.05773 18.9138 3.03133 18.7647 3.05711 18.62C3.08289 18.4753 3.15918 18.3445 3.2724 18.2508L5.46793 16.527C5.58821 16.4315 5.68275 16.3075 5.74295 16.1662C5.80316 16.0249 5.82713 15.8709 5.81269 15.718C5.792 15.4784 5.77936 15.2393 5.77936 14.9997C5.77936 14.7601 5.79143 14.5245 5.81269 14.2901C5.82555 14.1381 5.80041 13.9854 5.73952 13.8455C5.67863 13.7057 5.58392 13.5832 5.46391 13.4891L3.26952 11.7653C3.15814 11.6711 3.08346 11.5408 3.05856 11.397C3.03366 11.2533 3.06014 11.1054 3.13334 10.9793L5.59089 6.72726C5.66553 6.60141 5.78218 6.50595 5.92031 6.45771C6.05843 6.40946 6.20916 6.41151 6.34591 6.46352L8.92585 7.50239C9.06813 7.55903 9.22223 7.5795 9.37436 7.56195C9.52648 7.5444 9.67188 7.48939 9.79752 7.40183C10.1913 7.13069 10.605 6.88982 11.0352 6.68129C11.1704 6.61555 11.2874 6.51753 11.3758 6.39587C11.4642 6.27422 11.5213 6.13267 11.542 5.98373L11.9287 3.23198C11.9541 3.08666 12.0292 2.95466 12.1411 2.85856C12.253 2.76245 12.3949 2.7082 12.5424 2.70508H17.4575C17.6025 2.70759 17.7424 2.75944 17.8541 2.85208C17.9657 2.94472 18.0425 3.07264 18.0717 3.21475L18.4578 5.96247C18.4795 6.11305 18.5381 6.25594 18.6283 6.37844C18.7185 6.50094 18.8376 6.59925 18.975 6.66462C19.4047 6.87203 19.817 7.11356 20.2081 7.38689C20.3333 7.47401 20.4782 7.5286 20.6298 7.54576C20.7813 7.56291 20.9348 7.54208 21.0763 7.48515L23.6556 6.44685C23.7923 6.3948 23.943 6.39266 24.0811 6.4408C24.2192 6.48894 24.3359 6.58428 24.4107 6.71002L26.8682 10.962C26.9421 11.0891 26.9685 11.2382 26.9427 11.3829C26.9169 11.5276 26.8407 11.6584 26.7274 11.7521L24.5319 13.4759C24.4111 13.5711 24.316 13.6949 24.2553 13.8362C24.1946 13.9775 24.1702 14.1318 24.1843 14.2849C24.2032 14.5228 24.2159 14.7618 24.2159 15.0014Z" stroke="white" stroke-linecap="round" stroke-linejoin="round" />
        </svg>

      </button>
    </div>
    <!-- Mobile version settings ends here! -->
  </header>
  <!--Navigation bar ends here-->

  <!-- Mobile version Setting Dialog starts here! -->
  <dialog id="js-dialog-setting" class="delete-account-dialog">
    <!--First Vertical Layout-->
    <div class="modal-title-and-close-button">
      <div class="modal-title">
        Tetapan
      </div>
      <button id="js-dialog-setting-close-button" class="close-button-delete-account-dialog">
        x
      </button>
    </div>

    <div class="modal-scrollable-content setting">
      <div class="btn-group">
        <div class="btn-sub-group">
          <a class="btn-grid-frame" href="https://www.facebook.com/p/ContourPJ-61551751387405/" target="_blank">
            <img src="../icons/eye.svg" alt="">Like us on Facebook
          </a>
          <img src="icons/right-arrow.svg" alt="">
        </div>
        <div class="btn-sub-group">
          <a class="btn-grid-frame" href="https://www.instagram.com/contour.pj/?hl=en" target="_blank">
            <img class="instragram__icon" src="../icons/instagram.svg" alt="">Follow us on Instagram
          </a>
          <img src="../icons/right-arrow.svg" alt="">
        </div>
      </div>
      <div class="btn-group">
        <div class="btn-sub-group">
          <a class="btn-grid-frame" href="admin-privacy-policy.html">
            <img src="../icons/key-outline.svg" alt="">Dasar Privasi
          </a>
          <img src="../icons/right-arrow.svg" alt="">
        </div>
        <div class="btn-sub-group">
          <a class="btn-grid-frame" href="admin-terms-and-conditions.html">
            <img src="../icons/bytesize_lock.svg" alt="">Syarat Penggunaan
          </a>
          <img src="../icons/right-arrow.svg" alt="">
        </div>
      </div>
    </div>
  </dialog>
  <!-- Mobile version Setting Dialog ends here! -->

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
    <div class="main-content-wrapper">
      <div class="nav-tab">
        <button class="nav-button">Maklumat Akaun Jurujual</button>
        <div class="log-out-button-container">
          <form action="admin-logout.php" method="post">
            <button class="log-out-button" type="submit">LOG KELUAR</button>
          </form>
        </div>
      </div>
      <p class="account-details-text-mobile">Maklumat Akaun Jurujual</p>
      <div class="main-content-tab">
        <form method="post">
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
                title="Only alphabets are allowed"
                value="<?php echo htmlspecialchars($_SESSION['namajurujual']); ?>" />
              <label for="username" id="label-username" class="label-username">Nama</label>
            </div>
            <p id="example-text-username" class="example-text-username">
              e.g. Kong Jia Le
            </p>
          </div>
          <div class="text-box">
            <div class="entryarea">
              <input
                type="email"
                name="email"
                id="email"
                class="username"
                pattern="[A-Za-z0-9@.]*"
                required
                maxlength="100000"
                autocomplete="off"
                value="<?= $email_current ?>" />
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
                autocomplete="off"
                value="<?= $nohp_current; ?>" />
              <label for="nohp" id="label-nohp" class="label-username">Nombor Telefon</label>
            </div>
            <p id="example-text-nohp" class="example-text-username">e.g. 0123456789</p>
          </div>
          <div class="toggle-button-div">
            <input type="checkbox" id="check" class="toggle" />
            <label for="check">Tukar Kata laluan</label>
          </div>
          <div class="change-password-container"></div>
          <div class="submit-button-container">
            <input id="submit-button" type="submit" value="Simpan Data" />
          </div>
        </form>
        <p>Penggunaan Data</p>
        <div class="delete-account-button-container">
          <button id="delete-account-button">
            Padam Akaun Secara Kekal
            <img src="icons/right-arrow.svg" alt="" />
          </button>
        </div>
        <dialog id="delete-account-dialog" class="delete-account-dialog">
          <!--First Vertical Layout-->
          <div class="modal-title-and-close-button">
            <div class="modal-title">
              Padam Akaun Jurujual
            </div>
            <button class="close-button-delete-account-dialog">
              x
            </button>
          </div>

          <div class="modal-scrollable-content">
            <p>Adakah anda pasti ingin MEMADAMKAN akaun anda dengan kami secara KEKAL?</p>
            <p>Ia mungkin mengambil masa kira-kira 35 hari untuk semua data dipadamkan daripada pangkalan data dan storan sandaran kami.</p>
            <p>Anda TIDAK akan dapat memulihkan akaun anda selepas anda mengklik 'Sahkan Pemadaman'.</p>
            <div class="svg-div">
              <svg width="275" height="191" viewBox="0 0 275 191" fill="none" xmlns="http://www.w3.org/2000/svg" style="height: auto; max-width: 100%;">
                <g clip-path="url(#clip0)">
                  <path d="M196.624 130.603C196.624 147.888 186.378 153.923 173.738 153.923C161.099 153.923 150.852 147.888 150.852 130.603C150.852 113.318 173.738 91.3281 173.738 91.3281C173.738 91.3281 196.624 113.318 196.624 130.603Z"></path>
                  <path d="M173.139 136.813L182.893 118.916L173.175 134.544L173.281 128.039L180.004 115.091L173.309 126.318L173.498 114.619L180.697 104.311L173.528 112.78L173.646 91.3281L172.934 118.51L165.644 107.319L172.846 120.803L172.164 133.869L172.144 133.522L163.706 121.698L172.118 134.747L172.033 136.381L172.018 136.406L172.025 136.54L170.294 153.975H172.606L172.884 152.566L181.275 139.55L172.904 151.279L173.139 136.813Z" fill="#3F3D56"></path>
                  <path d="M275 75.0244C275 108.043 255.426 119.572 231.282 119.572C207.137 119.572 187.563 108.043 187.563 75.0244C187.563 42.0054 231.282 0 231.282 0C231.282 0 275 42.0054 275 75.0244Z"></path>
                  <path d="M229.689 114.522L230.137 86.8876L248.771 52.7005L230.207 82.5526L230.408 70.1272L243.251 45.3933L230.462 66.8391V66.8393L230.824 44.4916L244.575 24.8003L230.88 40.9775L231.107 0L229.685 54.2469L229.802 52.009L215.821 30.5466L229.578 56.3047L228.275 81.2631L228.236 80.6007L212.118 58.0148L228.188 82.941L228.025 86.0623L227.995 86.1093L228.009 86.3654L224.704 149.687H229.12L229.649 116.98L245.679 92.1151L229.689 114.522Z" fill="#3F3D56"></path>
                  <path d="M273.205 144.158C273.205 154.013 209.457 191 134.934 191C60.4122 191 0 171.858 0 162.003C0 152.147 58.9294 163.861 133.452 163.861C207.974 163.861 273.205 134.303 273.205 144.158Z" fill="#3F3D56"></path>
                  <path opacity="0.1" d="M273.205 144.158C273.205 154.013 209.457 191 134.934 191C60.4122 191 0 171.858 0 162.003C0 152.147 58.9294 163.861 133.452 163.861C207.974 163.861 273.205 134.303 273.205 144.158Z" fill="black"></path>
                  <path d="M273.205 144.158C273.205 154.013 209.457 179.847 134.934 179.847C60.4122 179.847 0 171.858 0 162.002C0 152.147 58.9294 152.708 133.452 152.708C207.974 152.708 273.205 134.303 273.205 144.158Z" fill="#3F3D56"></path>
                  <path opacity="0.1" d="M136.444 168.319C168.09 168.319 193.745 166.329 193.745 163.874C193.745 161.42 168.09 159.43 136.444 159.43C104.798 159.43 79.144 161.42 79.144 163.874C79.144 166.329 104.798 168.319 136.444 168.319Z" fill="black"></path>
                  <path d="M91.0511 74.8086C91.0511 74.8086 107.826 75.1201 104.72 78.0798C101.613 81.0394 89.9639 78.0798 89.9639 78.0798L91.0511 74.8086Z" fill="#A0616A"></path>
                  <path d="M56.0996 38.8602C56.0996 38.8602 62.8725 37.6472 65.2275 43.1305C67.5825 48.6138 72.4189 64.2518 72.4189 64.2518C72.4189 64.2518 85.3619 72.8436 87.7161 72.5613C90.0703 72.2791 94.0948 72.855 93.3566 75.4307C92.6183 78.0064 92.9461 80.927 91.2465 81.285C89.547 81.6429 86.844 78.226 84.7754 79.8718C82.7068 81.5176 64.271 75.5063 63.0874 74.2608C61.9039 73.0152 52.0679 46.0446 52.0679 46.0446C52.0679 46.0446 52.4613 38.7723 56.0996 38.8602Z"></path>
                  <path d="M70.6257 154.641L71.4023 161.183L64.8787 162.896L61.1509 161.183V155.419L70.6257 154.641Z" fill="#A0616A"></path>
                  <path d="M53.074 158.846L53.8506 165.388L47.327 167.102L43.5991 165.388V159.625L53.074 158.846Z" fill="#A0616A"></path>
                  <path d="M71.247 83.1412L74.1982 93.8893L78.5473 115.697C78.5473 115.697 79.7899 120.993 77.3047 127.38C74.8195 133.766 70.3151 154.64 71.247 155.886C72.179 157.132 60.6849 158.066 60.8402 156.82C60.9956 155.574 62.5488 138.439 62.5488 138.439C62.5488 138.439 64.7234 123.33 65.8106 121.461C66.8979 119.591 60.8402 114.918 60.8402 114.918C60.8402 114.918 59.287 132.832 56.9571 134.389C54.6272 135.947 54.9379 160.091 53.8506 160.403C52.7633 160.715 44.8417 161.961 43.9098 160.403C42.9779 158.845 43.9098 132.832 43.9098 132.832C43.9098 132.832 49.1909 113.049 48.1036 110.245C47.0163 107.441 47.6376 95.447 47.6376 95.447C47.6376 95.447 43.5991 86.2566 45.7737 81.4277L71.247 83.1412Z" fill="#2F2E41"></path>
                  <path d="M70.7809 23.3261C70.7809 23.3261 64.2572 31.4261 64.5679 35.0088C64.8785 38.5915 54.3164 28.4665 54.3164 28.4665C54.3164 28.4665 61.9273 18.1856 61.9273 16.0049C61.9273 13.8241 70.7809 23.3261 70.7809 23.3261Z" fill="#A0616A"></path>
                  <path d="M70.7031 26.3643C76.3648 26.3643 80.9546 21.7615 80.9546 16.0835C80.9546 10.4056 76.3648 5.80273 70.7031 5.80273C65.0414 5.80273 60.4517 10.4056 60.4517 16.0835C60.4517 21.7615 65.0414 26.3643 70.7031 26.3643Z" fill="#A0616A"></path>
                  <path d="M44.8683 77.3138C44.4039 79.049 43.8276 81.0164 43.91 82.6738C43.9628 83.7471 46.8037 84.4511 50.7318 84.9122C54.3742 85.3406 58.9517 85.5587 63.1128 85.6708C67.4821 85.7892 71.3885 85.7892 73.2664 85.7892C78.8581 85.7892 74.3537 82.2065 72.1791 80.493C70.0046 78.7796 70.4706 54.4795 70.6259 51.3641C70.7812 48.2487 69.228 39.214 69.228 37.5006C69.228 35.7871 65.5732 31.3149 65.5732 31.3149C65.5732 31.3149 64.7235 33.7621 61.3064 30.4909C57.8892 27.2198 53.3848 26.2852 53.3848 26.2852C49.9676 27.5313 44.2206 41.239 43.91 43.5756C43.7841 44.5242 43.8882 49.3827 44.0994 54.9297C44.4054 63.0375 44.9398 72.622 45.3079 73.6392C45.5983 74.4414 45.2768 75.7872 44.8683 77.3138Z"></path>
                  <path d="M61.4615 86.4121L62.5487 90.3064L58.3549 94.668L56.957 88.5928L61.4615 86.4121Z" fill="#A0616A"></path>
                  <path d="M62.8595 159.468C62.8595 159.468 66.1214 163.206 68.6066 160.714C71.0918 158.222 70.9364 156.664 71.4024 156.664C71.8684 156.664 78.858 162.895 78.858 162.895C78.858 162.895 90.8181 165.699 84.2944 167.724C77.7707 169.749 59.287 168.347 59.287 167.724C59.287 167.101 58.8211 158.845 60.5296 158.845L62.8595 159.468Z" fill="#2F2E41"></path>
                  <path d="M45.3078 163.675C45.3078 163.675 48.5696 167.413 51.0548 164.921C53.54 162.429 53.3847 160.871 53.8507 160.871C54.3166 160.871 61.3063 167.102 61.3063 167.102C61.3063 167.102 73.2663 169.906 66.7426 171.931C60.219 173.956 41.7353 172.554 41.7353 171.931C41.7353 171.308 41.2693 163.052 42.9779 163.052L45.3078 163.675Z" fill="#2F2E41"></path>
                  <path d="M59.1939 24.2889C58.8627 24.5035 58.4316 24.7209 58.1 24.507C56.8672 21.4236 55.9457 18.224 55.3494 14.9562C55.14 13.8083 54.9947 12.5323 55.6305 11.5551C55.8813 11.1697 56.2423 10.8545 56.426 10.4328C56.7042 9.79409 56.5211 9.05481 56.6135 8.36405C56.8071 6.9169 58.1712 5.90755 59.5429 5.41803C60.9146 4.92851 62.4044 4.77666 63.7119 4.13432C64.9534 3.5244 65.9477 2.51287 67.0989 1.74527C68.2501 0.977666 69.7248 0.45283 71.0068 0.971521C72.141 1.43038 72.8475 2.58974 73.9195 3.18005C74.6469 3.58064 75.4911 3.69211 76.3032 3.86406C78.5601 4.34427 80.679 5.33152 82.5005 6.75155C82.9437 7.0686 83.3258 7.46367 83.6284 7.91756C85.004 10.189 82.541 13.277 83.6276 15.7008L81.2784 13.8418C80.651 13.2914 79.9435 12.8405 79.1802 12.5045C78.4021 12.2089 77.4805 12.1732 76.7736 12.6135C75.7816 13.2315 75.5126 14.5225 75.166 15.6408C74.8195 16.7592 74.0532 17.9994 72.8858 17.9708C71.2981 17.9318 70.7429 15.7098 69.3181 15.0062C68.3893 14.5476 67.2201 14.8497 66.4283 15.5185C65.6365 16.1873 65.1614 17.1578 64.8129 18.1354C64.5951 18.7463 64.3908 19.435 64.004 19.9645C63.5775 20.5486 63.0318 20.5956 62.5762 21.0318C61.3902 22.1671 60.661 23.3384 59.1939 24.2889Z" fill="#2F2E41"></path>
                  <path opacity="0.1" d="M44.868 77.3138C46.7987 80.1223 48.9748 83.0196 50.7315 84.9122C54.3739 85.3406 58.9513 85.5587 63.1125 85.6708C62.3691 84.66 61.4395 83.8013 60.3741 83.1411C58.3549 81.8949 53.5398 67.0968 53.5398 67.0968C53.5398 67.0968 59.4422 51.8314 60.9954 46.0679C62.5487 40.3045 56.491 37.0333 56.491 37.0333C53.6951 34.6967 48.88 40.1487 48.88 40.1487C48.88 40.1487 46.4228 47.582 44.0991 54.9297C44.4051 63.0375 44.9394 72.622 45.3076 73.6392C45.598 74.4414 45.2765 75.7872 44.868 77.3138Z" fill="black"></path>
                  <path d="M56.0251 35.7872C56.0251 35.7872 62.0827 39.0584 60.5295 44.8218C58.9762 50.5853 53.0739 65.8507 53.0739 65.8507C53.0739 65.8507 57.889 80.6489 59.9082 81.895C61.9274 83.1412 64.7233 86.1008 62.5487 87.6585C60.3742 89.2162 58.8209 91.7085 57.2677 90.9297C55.7144 90.1508 55.7144 85.7893 53.0739 85.7893C50.4333 85.7893 39.7159 69.5892 39.5606 67.8758C39.4053 66.1623 48.4141 38.9026 48.4141 38.9026C48.4141 38.9026 53.2292 33.4506 56.0251 35.7872Z"></path>
                  <path d="M181.987 78.3644L180.24 76.612C179.221 75.5899 177.838 75.0156 176.397 75.0156C174.956 75.0156 173.573 75.5899 172.554 76.612L138.66 110.603L104.767 76.612C103.748 75.5899 102.365 75.0156 100.924 75.0156C99.4825 75.0156 98.1001 75.5899 97.0809 76.612L95.3335 78.3644C94.8288 78.8705 94.4285 79.4713 94.1554 80.1326C93.8823 80.7939 93.7417 81.5026 93.7417 82.2184C93.7417 82.9341 93.8823 83.6429 94.1554 84.3041C94.4285 84.9654 94.8288 85.5662 95.3335 86.0723L129.227 120.063L95.3335 154.053C94.8288 154.56 94.4285 155.16 94.1554 155.822C93.8823 156.483 93.7417 157.192 93.7417 157.907C93.7417 158.623 93.8823 159.332 94.1554 159.993C94.4285 160.654 94.8288 161.255 95.3335 161.761L97.0809 163.514C98.1001 164.536 99.4825 165.11 100.924 165.11C102.365 165.11 103.748 164.536 104.767 163.514L138.66 129.523L172.554 163.514C173.573 164.536 174.956 165.11 176.397 165.11C177.838 165.11 179.221 164.536 180.24 163.514L181.987 161.761C183.006 160.739 183.579 159.353 183.579 157.907C183.579 156.462 183.006 155.076 181.987 154.053L148.094 120.063L181.987 86.0723C183.006 85.0502 183.579 83.6639 183.579 82.2184C183.579 80.7728 183.006 79.3865 181.987 78.3644Z" fill="#EB4917"></path>
                  <path opacity="0.1" d="M94.124 155.9L127.011 122.919L126.695 122.602L95.3336 154.052C94.8083 154.578 94.3966 155.208 94.124 155.9Z" fill="black"></path>
                  <path opacity="0.1" d="M94.8649 79.4693C95.8842 78.4472 97.2665 77.873 98.7079 77.873C100.149 77.873 101.532 78.4472 102.551 79.4693L136.444 113.46L170.338 79.4693C171.357 78.4472 172.74 77.873 174.181 77.873C175.622 77.873 177.005 78.4472 178.024 79.4693L179.771 81.2217C180.521 81.9737 181.035 82.9291 181.25 83.9703C181.465 85.0115 181.371 86.0931 180.981 87.0817L181.987 86.0723C182.492 85.5662 182.892 84.9654 183.165 84.3041C183.439 83.6428 183.579 82.9341 183.579 82.2184C183.579 81.5026 183.439 80.7939 183.165 80.1326C182.892 79.4713 182.492 78.8705 181.987 78.3644L180.24 76.612C179.221 75.5899 177.838 75.0156 176.397 75.0156C174.956 75.0156 173.573 75.5899 172.554 76.612L138.66 110.603L104.767 76.612C103.748 75.5899 102.365 75.0156 100.924 75.0156C99.4826 75.0156 98.1002 75.5899 97.081 76.612L95.3336 78.3644C94.8083 78.8907 94.3966 79.5197 94.124 80.2124L94.8649 79.4693Z" fill="black"></path>
                  <path opacity="0.1" d="M148.411 120.381L145.878 122.921L179.771 156.911C180.521 157.663 181.035 158.619 181.25 159.66C181.465 160.701 181.371 161.783 180.981 162.771L181.987 161.762C182.492 161.256 182.892 160.655 183.166 159.994C183.439 159.332 183.579 158.624 183.579 157.908C183.579 157.192 183.439 156.483 183.166 155.822C182.892 155.161 182.492 154.56 181.987 154.054L148.411 120.381Z" fill="black"></path>
                  <path d="M39.4662 143.043C39.4662 157.399 30.9561 162.412 20.4584 162.412C9.96075 162.412 1.45068 157.399 1.45068 143.043C1.45068 128.687 20.4584 110.424 20.4584 110.424C20.4584 110.424 39.4662 128.687 39.4662 143.043Z"></path>
                  <path d="M19.9601 148.201L28.0618 133.337L19.9906 146.316L20.0782 140.914L25.6617 130.16L20.1014 139.484L20.2587 129.768L26.2377 121.206L20.2834 128.24L20.3817 110.424L19.7902 132.999L13.7357 123.705L19.7169 134.904L19.1506 145.755L19.1338 145.467L12.126 135.647L19.1124 146.485L19.0417 147.842L19.0289 147.863L19.0348 147.974L17.5977 162.454H19.5178L19.7482 161.285L26.7176 150.474L19.7655 160.215L19.9601 148.201Z"></path>
                </g>
                <defs>
                  <clipPath id="clip0">
                    <rect width="275" height="191" fill="white"></rect>
                  </clipPath>
                </defs>
              </svg>
            </div>
          </div>

          <div class="confirm-deletion-container">
            <button class="cancel-button">Batal</button>
            <form action="admin-delete_account.php" method="post">
              <button class="confirm-deletion-button" type="submit">Sahkan Pemadaman</button>
            </form>
          </div>
        </dialog>
      </div>

      <div class="log-out-button-container mobile">
        <form action="logout.php" method="post">
          <button class="log-out-button mobile" type="submit">
            LOG KELUAR <img src="icons/right-arrow.svg" alt="">
          </button>
        </form>
      </div>
    </div>
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
<script src="data/HeaderLinksDataAdmin.js"></script>
<script src="js/generateHeaderLinksAdmin.js"></script>
<script src="data/mobileHeaderLinksDataAdmin.js"></script>
<script src="js/generateMobileHeaderLinksAdmin.js"></script>
<script src="js/admin-mobile-navigation-bar.js"></script>
<script src="js/admin-profile.js"></script>

</html>