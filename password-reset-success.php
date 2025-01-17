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

  <!-- Roboto fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
    rel="stylesheet" />

  <!--Link the CSS file-->
  <link rel="stylesheet" href="css/menu.css" />
  <link rel="stylesheet" href="css/general.css" />
  <link rel="stylesheet" href="css/scrollbar.css" />
  <link rel="stylesheet" href="css/signup.css" />
  <link rel="stylesheet" href="css/forgot-password.css" />
  <link rel="stylesheet" href="css/mobile-navigation.css" />
  <link rel="stylesheet" href="css/password-reset-success.css" />
  <link rel="icon" type="image/jpg" href="Logo image/Contour Cafe’.jpg" />
</head>

<body id="body">
  <!--Navigation bar starts here! -->
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
  <!--Navigation bar ends here! -->

  <!-- Main content starts here! -->
  <main class="main">
    <div class="main-content">
      <h1>Kata Laluan Berjaya Diperbaharui</h1>
      <a href="signup.php">Log Masuk</a>
    </div>
  </main>
  <!-- Main content ends here! -->

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
            <a href="privacy-policy.html" class="a-footer-link">Dasar Privasi</a>
            <a href="terms-and-conditions.html" class="a-footer-link">Syarat Penggunaan</a>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!--Footer ends here!-->

  <!-- Link the JS file -->
  <script src="data/HeaderLinksDataMenu.js"></script>
  <script src="js/generateHeaderLinksMenu.js"></script>
  <script src="data/mobileHeaderLinksDataMenu.js"></script>
  <script src="js/generateMobileHeaderLinksMenu.js"></script>
  <script>
    updateBasketItemCount();

    function updateBasketItemCount() {
      let basketItemsLength = JSON.parse(localStorage.getItem("basketItems")) || [];
      basketItemsLength = basketItemsLength.length;
      let basketItemCounter = document.querySelector(".basket-item-counter");
      basketItemCounter.innerHTML = basketItemsLength;

      if (basketItemsLength !== 0) {
        basketItemCounter.classList.add("active");
      } else if (basketItemsLength === 0) {
        basketItemCounter.classList.remove("active");
      }
    }
  </script>
</body>

</html>