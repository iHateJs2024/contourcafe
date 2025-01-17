<!--WELCOME TO HOMEPAGE CONTOUR CAFE'-->
<!-- Author: Kong Jia Le' -->
<!-- Start date:12-12-2024 -->
<!-- Version: 1.0 -->

<?php
session_start();
//! PHP TO GET gambar_makanan of Istimewa makanan from makanan table
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "tempahan";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

//! PHP TO CHECK IF USER exists in db and logged in
if (!isset($_SESSION['idpelanggan'])) {
  //? The user does not exists in db and not logged in!
  echo '<script>alert("Sila Log masuk/Daftar!");
                  window.location.href = "signup.php";
          </script>';
  exit();
} else {
  $idpelanggan = $_SESSION['idpelanggan'];

  $query = "SELECT idpelanggan FROM pelanggan WHERE idpelanggan = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $idpelanggan);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    //? The user exists in db and logged in!
  } else {
    //? The user does not exists in db!
    echo '<script>alert("Sila Log masuk/Daftar!");
                  window.location.href = "signup.php";
          </script>';
  }

  // Close the statement
  $stmt->close();
}

// Fetch items with status_makanan = 'istimewa'
$sql_istimewa = "SELECT gambar_makanan FROM makanan WHERE status_makanan = 'Istimewa'";
$result_istimewa = $conn->query($sql_istimewa);

$items = [];
if ($result_istimewa->num_rows > 0) {
  while ($row = $result_istimewa->fetch_assoc()) {
    $items[] = $row;
  }
}

$positionCount = 1;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contour Cafe'</title>

  <!--Barlow fonts-->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700&display=swap"
    rel="stylesheet" />

  <!--Link the CSS file-->
  <link rel="stylesheet" href="css/menu.css" />
  <link rel="stylesheet" href="css/general.css" />
  <link rel="stylesheet" href="css/scrollbar.css" />
  <link rel="stylesheet" href="css/home.css" />
  <link rel="stylesheet" href="css/mobile-navigation.css" />
  <link rel="icon" type="image/jpg" href="Logo image/Contour Cafe’.jpg" />

  <!--Link the icons file-->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
</head>

<body>
  <!--Navigation bar starts here-->
  <header>
    <div class="main-header">
      <!--Middle section starts here-->
      <div class="middle-section">
        <!--Logo section-->
        <a class="logo-link" href="user-index.php">
          <img
            class="contour-cafe-logo"
            src="Logo image/Contour Cafe’.jpg"
            alt="" />
        </a>
        <!--Links section-->
        <nav class="links-section">
          <ul id="ul-header" class="ul-header"></ul>
        </nav>
      </div>
      <!--Middle section ends here-->
    </div>
  </header>
  <!--Navigation bar ends here-->

  <!-- Background image starts here! -->
  <section class="background-image-section">
    <div class="welcome-content">
      <p class="welcome-text">
        Selamat Datang Ke <strong>Contour Cafe'!</strong>
      </p>
      <p class="description-text">
        <strong>Contour Cafe'</strong> telah ditubuhkan sejak tahun
        <strong>1999</strong>. Kami menyediakan hidangan lazat bagi pelanggan
        setia kami!
      </p>
      <div class="welcome-links">
        <a class="order-now-link" href="user-menu.php">
          <div class="order-now-div">
            BUAT PESANAN SEKARANG
            <img src="icons/right-round-32.png" alt="" />
          </div>
        </a>
        <a class="learn-more-link" href="#AboutUs">Ketahui lebih lanjut
          <img src="icons/right-round-32 (2).png" alt="" /></a>
      </div>
    </div>

    <div class="mobile-welcome-content">
      <p class="welcome-text">
        Selamat Datang Ke <strong>Contour Cafe'!</strong>
      </p>
      <p class="description-text">
        <strong>Contour Cafe'</strong> telah ditubuhkan sejak tahun
        <strong>1999</strong>. Kami menyediakan hidangan lazat bagi pelanggan
        setia kami!
      </p>
      <div class="welcome-links">
        <a class="order-now-link" href="user-menu.php">
          <div class="order-now-div">
            BUAT PESANAN SEKARANG
            <img src="icons/right-round-32.png" alt="" />
          </div>
        </a>
        <a class="learn-more-link" href="#AboutUs">Ketahui lebih lanjut
          <img src="icons/right-round-32 (2).png" alt="" /></a>
      </div>
    </div>
  </section>
  <!-- Background image ends here! -->

  <!-- Check Out Text starts here!-->
  <section class="check-out-text-section">
    <div>
      <p>LIHAT</p>
      <strong>PRODUK TERLARIS KAMI</strong>
    </div>
  </section>
  <!-- Check Out Text ends here!-->

  <!--Main Content starts here!-->
  <div class="slider" style="--width: 200px; --height: 300px; --quantity: 8">
    <div class="list">
      <?php foreach ($items as $item): ?>
        <div class="item" style="--position: <?= $positionCount; ?>">
          <img src="<?= $item['gambar_makanan'] ?>" alt="" />
        </div>
        <?php $positionCount++; ?>
      <?php endforeach; ?>
    </div>
  </div>
  <!--Main Content ends here!-->

  <!-- About Us Text starts here!-->
  <section id="AboutUs" class="about-us-text-section">
    <div>
      <p>Sejarah</p>
      <strong>Tentang Kami</strong>
    </div>
    <div class="text-div">
      <p
        class="text"
        style="
            font-size: 16px;
            max-width: 500px;
            text-align: center;
            text-shadow: none;
          ">
        Contour Cafe' ditubuhkan pada tahun 1999 dengan visi untuk mencipta
        ruang yang mesra dan nyaman bagi pencinta makanan. Ditubuhkan oleh
        sekumpulan peminat kulinari yang bersemangat, konsep kafe ini berpusat
        di sekitar penyediaan hidangan lazat yang disediakan dengan bahan
        segar dalam suasana yang selesa.<br /><br />Selama bertahun-tahun,
        Contour Cafe' telah menjadi tempat berkumpul yang popular bagi
        rakan-rakan, keluarga, dan komuniti tempatan, menawarkan bukan sahaja
        makanan yang enak, tetapi juga tempat untuk bersantai dan mencipta
        kenangan. Dengan komitmen terhadap bahan berkualiti dan perkhidmatan
        pelanggan yang luar biasa, Contour Cafe' terus berkembang, mencipta
        pengalaman baru bagi setiap tetamu.
      </p>
    </div>
  </section>
  <!-- About Us Text ends here!-->

  <!-- Contact Us Text starts here! -->
  <section class="about-us-text-section">
    <div>
      <p style="font-size: 25px">Jangan Ragu</p>
      <strong>Hubungi Kami</strong>
    </div>
    <div class="text-div">
      <p
        class="text"
        style="
            font-size: 60px;
            max-width: 500px;
            text-align: center;
            text-shadow: none;
            text-decoration: underline;
          ">
        011-1673 5503
      </p>
    </div>
  </section>
  <!-- Contact Us Text ends here! -->

  <!-- Mobile Navigation bar starts here-->
  <header class="mobile-header-container">
    <div class="mobile-header">
      <!--Middle section starts here-->
      <div class="mobile-middle-section">
        <!--Links section-->
        <nav class="mobile-links-section">
          <ul id="mobile-ul-header" class="mobile-ul-header"></ul>
        </nav>
      </div>
      <!--Middle section ends here-->
    </div>
  </header>
  <!-- Mobile Navigation bar ends here-->

  <!--Footer starts here!-->
  <footer>
    <div class="footer">
      <div style="display: flex; justify-content: center">
        <div class="logo-and-socialmedia-icons-div">
          <div>
            <a href="user-index.php">
              <img
                class="logo-image-footer"
                src="Logo image/Contour Cafe’.jpg"
                alt="" />
            </a>
          </div>
          <div class="socialmedia-icons-div">
            <div class="socialmedia-icons-subdiv">
              <a
                class="footer-icon-link"
                href="https://www.instagram.com/contour.pj/?hl=en"
                target="_blank">
                <img class="insta-icon" src="icons/insta.svg" alt="" />
              </a>
              <a
                class="footer-icon-link"
                href="https://www.facebook.com/p/ContourPJ-61551751387405/"
                target="_blank">
                <img class="fb-icon" src="icons/fb.svg" alt="" />
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
            <a href="user-privacy-policy.html" class="a-footer-link">Dasar Privasi</a>
            <a href="user-terms-and-conditions.html" class="a-footer-link">Syarat Penggunaan</a>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!--Footer ends here!-->

  <!--Link the JS file-->
  <script src="data/HeaderLinksDataUserMenu.js"></script>
  <script src="js/generateHeaderLinksUserMenu.js"></script>
  <script src="data/mobileHeaderLinksDataUserMenu.js"></script>
  <script src="js/generateMobileHeaderLinksUserMenu.js"></script>
  <script src="js/home.js"></script>
</body>

</html>