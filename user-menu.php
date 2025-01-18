<!--WELCOME TO USER MENU (MENU AFTER USER SIGNED UP AND LOGGED IN) CONTOUR CAFE'-->
<!-- Author: Kong Jia Le' -->
<!-- Start date:12-12-2024 -->
<!-- Version: 1.0 -->

<?php
session_start();
//! PHP TO GET ALL THE CATEGORY FROM kategori table
include_once(__DIR__ . '/connect.php');
$conn = getConnection();

//! PHP TO CHECK IF USER exists in db and logged in
if (!isset($_SESSION['idpelanggan'])) {
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
    //? The user does not exists in db and not logged in!
    echo '<script>alert("Sila Log masuk/Daftar!");
                  window.location.href = "signup.php";
          </script>';
  }

  // Close the statement
  $stmt->close();
}

// Fetch all categories from kategori table
$sql_kategori = "SELECT idkategori, namakategori FROM kategori";
$result_kategori = $conn->query($sql_kategori);

$kategori_list = [];
if ($result_kategori->num_rows > 0) {
  while ($row = $result_kategori->fetch_assoc()) {
    $kategori_list[] = $row;
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
  <link rel="stylesheet" href="css/menu.css" />
  <link rel="stylesheet" href="css/general.css" />
  <link rel="stylesheet" href="css/basket-items.css" />
  <link rel="stylesheet" href="css/modal-food-preview.css" />
  <link rel="stylesheet" href="css/scrollbar.css" />
  <link rel="stylesheet" href="css/mobile-navigation.css" />
  <link rel="icon" type="image/jpg" href="Logo image/Contour Cafe’.jpg" />

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

  <!--Background image starts here-->
  <div class="div-background-image">
    <!--Main banner starts here-->
    <div class="main-banner">
      <!--Sub banner starts here-->
      <div class="sub-banner">
        <!--Venue Details starts here-->
        <div class="venue-detail-container">
          <!--Cafe name and location icon-->
          <div class="cafe-name-and-location-icon">
            <div>
              <p class="cafe-name">Contour Cafe'</p>
            </div>
            <div class="div-banner-map-link">
              <a
                class="banner-map-link"
                target="_blank"
                href="https://www.google.com/maps/place/Contour/@3.0860515,101.6462004,984m/data=!3m2!1e3!4b1!4m6!3m5!1s0x31cc4befa556e89b:0x30b9864d474dcde1!8m2!3d3.0860515!4d101.6487753!16s%2Fg%2F11vd6_624y?entry=ttu&g_ep=EgoyMDI0MTIxMS4wIKXMDSoASAFQAw%3D%3D">
                <img src="icons/banner-map.svg" alt="" />
              </a>
            </div>
          </div>

          <!--Phone number, location and open hour-->
          <div class="phone-number-location-and-open-hour">
            <!--Phone number-->
            <div class="div-banner-call-icon">
              <div>
                <img
                  class="banner-call-icon"
                  src="icons/telephone grey.png"
                  alt="" />
              </div>
              <div class="div-phone-number">
                <span class="phone-number">011-1673 5503</span>
              </div>
            </div>

            <!--Location-->
            <div class="div-banner-location-icon">
              <div>
                <img src="icons/banner-location.svg" alt="" />
              </div>
              <div class="div-address">
                <span class="location">
                  55, Jalan 1/12, Pj Old Town, 46000 Petaling Jaya, Selangor
                </span>
              </div>
            </div>

            <!--Work hour-->
            <div class="div-work-hour">
              <div class="div-banner-time">
                <img src="icons/banner-time.svg" alt="" />
              </div>
              <div class="div-open-work-hour-button">
                <button
                  class="open-work-hour-button"
                  id="open-work-hour-button">
                  <div>Open</div>
                  <button class="drop-down-button" id="drop-down-button">
                    <img
                      class="drop-down-icon"
                      id="drop-down-icon"
                      src="icons/drop-down-icon.png"
                      alt="" />
                  </button>
                </button>
              </div>
            </div>
          </div>
        </div>
        <!--Venue Details ends here-->
      </div>
      <!--Sub banner ends here-->
    </div>
    <!--Main banner ends here-->

    <!-- Mobile version -->
    <div class="mobile-div-banner-map-link">
      <a
        class="banner-map-link"
        target="_blank"
        href="https://www.google.com/maps/place/Contour/@3.0860515,101.6462004,984m/data=!3m2!1e3!4b1!4m6!3m5!1s0x31cc4befa556e89b:0x30b9864d474dcde1!8m2!3d3.0860515!4d101.6487753!16s%2Fg%2F11vd6_624y?entry=ttu&g_ep=EgoyMDI0MTIxMS4wIKXMDSoASAFQAw%3D%3D">
        <img src="icons/banner-map.svg" alt="" />
      </a>
    </div>

    <div class="mobile-main-banner">
      <!--Cafe name and location icon-->
      <div class="cafe-name-and-location-icon">
        <div>
          <p class="cafe-name">Contour Cafe'</p>
        </div>
      </div>

      <!--Phone number, location and open hour-->
      <div class="phone-number-location-and-open-hour">
        <!--Phone number-->
        <div class="div-banner-call-icon">
          <div>
            <img
              class="banner-call-icon"
              src="icons/telephone grey.png"
              alt="" />
          </div>
          <div class="div-phone-number">
            <span class="phone-number">011-1673 5503</span>
          </div>
        </div>

        <!--Location-->
        <div class="div-banner-location-icon">
          <div>
            <img src="icons/banner-location.svg" alt="" />
          </div>
          <div class="div-address">
            <span class="location">
              55, Jalan 1/12, Pj Old Town, 46000 Petaling Jaya, Selangor
            </span>
          </div>
        </div>

        <!--Work hour-->
        <div class="div-work-hour">
          <div class="div-banner-time">
            <img src="icons/banner-time.svg" alt="" />
          </div>
          <div class="div-open-work-hour-button">
            <button
              class="open-work-hour-button"
              id="mobile-open-work-hour-button">
              <div>Open</div>
              <button class="drop-down-button" id="mobile-drop-down-button">
                <img
                  class="drop-down-icon"
                  id="mobile-drop-down-icon"
                  src="icons/drop-down-icon.png"
                  alt="" />
              </button>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--Background image ends here-->

  <!--Pop up open hours menu starts here!-->
  <dialog class="open-hours-menu-dialog" id="open-hours-menu-dialog">
    <!--Inner modal above-->
    <div class="inner-modal-above">
      <div>
        <p class="opening-hours-text">Opening Hours</p>
      </div>
      <div>
        <button class="x-button" id="close-modal-button">
          <img
            class="close-button-image"
            src="icons/close-button-icon.png"
            alt="" />
        </button>
      </div>
    </div>

    <!--Inner modal bottom-->
    <div class="inner-modal-bottom">
      <!--1st Day-->
      <div class="div-day-inner-modal">
        <div>
          <p class="first-day-inner-modal" id="1st-day">Monday:</p>
        </div>
        <div>
          <p class="first-time-inner-modal" id="1st-time">8am&ndash;4pm</p>
        </div>
      </div>

      <!--2nd Day-->
      <div class="div-day-inner-modal">
        <div>
          <p class="days-inner-modal" id="2nd-day">Tuesday:</p>
        </div>
        <div>
          <p class="time-inner-modal" id="2nd-time">8am&ndash;4pm</p>
        </div>
      </div>

      <!--3rd Day-->
      <div class="div-day-inner-modal">
        <div>
          <p class="days-inner-modal" id="3rd-day">Wednesday:</p>
        </div>
        <div>
          <p class="time-inner-modal" id="3rd-time">8am&ndash;6pm</p>
        </div>
      </div>

      <!--4th Day-->
      <div class="div-day-inner-modal">
        <div>
          <p class="days-inner-modal" id="4th-day">Thursday:</p>
        </div>
        <div>
          <p class="time-inner-modal" id="4th-time">8am&ndash;4pm</p>
        </div>
      </div>

      <!--5th Day-->
      <div class="div-day-inner-modal">
        <div>
          <p class="days-inner-modal" id="5th-day">Friday:</p>
        </div>
        <div>
          <p class="time-inner-modal" id="5th-time">8am&ndash;4pm</p>
        </div>
      </div>

      <!--6th Day-->
      <div class="div-day-inner-modal">
        <div>
          <p class="days-inner-modal" id="6th-day">Saturday:</p>
        </div>
        <div>
          <p class="time-inner-modal" id="6th-time">8am&ndash;6pm</p>
        </div>
      </div>

      <!--7th Day-->
      <div class="div-Sunday-inner-modal">
        <div>
          <p class="days-inner-modal" id="7th-day">Sunday:</p>
        </div>
        <div>
          <p class="time-inner-modal" id="7th-time">8am&ndash;6pm</p>
        </div>
      </div>
    </div>
  </dialog>
  <!--Pop up open hours menu ends here!-->

  <!--Food Section starts here!-->
  <main class="food-section">
    <!--Navigation bar Food Category starts here!-->
    <div
      class="navigation-bar-food-category"
      id="navigation-bar-food-category">
      <nav class="nav-food-category-links">
        <ul id="ul-food-category" class="ul-food-category">
          <?php foreach ($kategori_list as $kategori): ?>
            <li class="li-food-category">
              <a class="food-category-link <?= str_replace(" ", "", $kategori['idkategori']) ?>" href="#<?= $kategori['idkategori']; ?>">
                <?= $kategori['namakategori'] ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </nav>
    </div>
    <!--Navigation bar Food Category ends here!-->

    <!--Food Order Menu Section starts here!-->
    <div class="food-order-menu-section-div">
      <!--Food Selection starts here!-->
      <div class="food-selection-div">
        <div>
          <p class="welcome-text">Selamat Datang Ke Contour Cafe'!</p>
        </div>

        <hr class="welcome-text-hr" />

        <!--Food Sections and Food Previews-->
        <div class="main" id="main">
          <?php foreach ($kategori_list as $kategori): ?>
            <section id="<?= $kategori['idkategori'] ?>">
              <!-- Display category title -->
              <p class="food-category-text"><?= $kategori['namakategori'] ?></p>

              <!-- Display items for this category -->
              <div class="food-previews-div">
                <?php
                // Database connection for each category's items
                include_once(__DIR__ . '/connect.php');
                $conn = getConnection();

                // Fetch all items for the current category
                $idkategori = $kategori['idkategori'];
                $sql_items = "
                    SELECT m.gambar_makanan, m.namamakanan, m.maklumat_makanan, m.harga, m.harga_pembungkusan, m.status_makanan, m.idmakanan
                    FROM kategori_makanan km
                    INNER JOIN makanan m ON km.idmakanan = m.idmakanan
                    WHERE km.idkategori = '$idkategori'
                ";
                $result_items = $conn->query($sql_items);

                if ($result_items->num_rows > 0) {
                  while ($item = $result_items->fetch_assoc()): ?>
                    <div
                      id="product-id-<?= $item['idmakanan'] ?>"
                      class="food-preview <?= $item['status_makanan'] ?>"
                      onclick="
                        showFoodPreviewModal('dialog-<?= $item['idmakanan'] ?>');
                        showAddToBasketPrice('price-<?= $item['idmakanan'] ?>', <?= str_replace('RM', '', $item['harga']) ?>, <?= str_replace('RM', '', $item['harga_pembungkusan']) ?>);
                      ">
                      <div class="food-preview-image-div">
                        <img class="food-preview-image" src="<?= $item['gambar_makanan'] ?>" alt="Gambar Makanan">
                      </div>
                      <p class="menu-item-name"><?= $item['namamakanan'] ?></p>
                      <p class="menu-item-description"><?= $item['maklumat_makanan'] ?></p>
                      <p class="menu-item-price"><?= $item['harga'] ?></p>
                      <img class="sold-out-image" style="display: none;" src="icons/sold-out-icon.png" alt="">
                    </div>

                    <dialog id="dialog-<?= $item['idmakanan'] ?>" class="food-preview-modal">
                      <!--First Vertical Layout-->
                      <div class="modal-title-and-close-button">
                        <div class="modal-title">
                          Tambah Item Ke Bakul
                        </div>
                        <button
                          class="close-button-food-preview-modal"
                          onclick="
                            closeFoodPreviewModal('dialog-<?= $item['idmakanan'] ?>');
                            resetQuantity(<?= $item['idmakanan'] ?>);
                            resetTextArea('text-area-<?= $item['idmakanan'] ?>');
                        ">
                          x
                        </button>
                      </div>

                      <div class="modal-scrollable-content">
                        <!--Second Vertical Layout-->
                        <div class="modal-food-preview-image">
                          <img class="food-preview-image-modal" src="<?= $item['gambar_makanan'] ?>" alt="" />
                        </div>

                        <!--Third Vertical Layout-->
                        <div>
                          <div class="modal-food-preview-name-and-price">
                            <div class="modal-food-preview-name"><?= $item['namamakanan'] ?></div>
                            <div class="modal-food-preview-price">
                              <?= $item['harga'] ?>
                            </div>
                          </div>
                          <p class="modal-menu-item-description">
                            <?= $item['maklumat_makanan'] ?>
                          </p>
                        </div>

                        <!--Fourth Vertical Layout-->
                        <div class="modal-food-preview-packaging-type">
                          <p class="modal-food-preview-takeaway">Pembungkusan Bawa pulang</p>
                          <p class="modal-food-preview-packaging-description">Diperlukan &middot; Pilih 1</p>
                        </div>

                        <!--Fifth Vertical Layout-->
                        <div class="modal-food-preview-packaging-choice-and-price">
                          <div class="modal-food-preview-packaging-choice">Pembungkusan Bawa pulang</div>
                          <div class="modal-food-preview-packaging-price">
                            <p>+<?= $item['harga_pembungkusan'] ?></p>
                            <div class="modal-food-preview-packaging-price-tick">
                              <img src="icons/tick.svg"></img>
                            </div>
                          </div>
                        </div>

                        <!--Sixth Vertical Layout-->
                        <div class="modal-food-preview-additional-notes">
                          <p>Nota Tambahan</p>
                        </div>

                        <!--Seventh Vertical Layout-->
                        <div class="modal-food-preview-additional-notes-textarea-div">
                          <textarea id="text-area-<?= $item['idmakanan'] ?>" class="modal-food-preview-additional-notes-textarea" placeholder="e.g. tidak mahu jeruk"></textarea>
                        </div>

                        <!--Eighth Vertical Layout-->
                        <div class="modal-food-preview-blank"></div>
                      </div>

                      <!--Ninth Vertical Layout-->
                      <div class="modal-food-preview-add-to-basket-button">
                        <div class="quantity-input-group">
                          <button class="minus-button"
                            onclick="
                              decrement(<?= $item['idmakanan'] ?>); 
                              decreaseAddToBasketPrice(<?= str_replace('RM', '', $item['harga']) ?>, 'price-<?= $item['idmakanan'] ?>', <?= str_replace('RM', '', $item['harga_pembungkusan']) ?>)
                          ">
                            <img src="icons/minus-circular-button.png"></img>
                          </button>
                          <div id=<?= $item['idmakanan'] ?> class="quantity">1</div>
                          <button class="plus-button"
                            onclick="
                              increment(<?= $item['idmakanan'] ?>);
                              increaseAddToBasketPrice(<?= str_replace('RM', '', $item['harga']) ?>, '<?= $item['idmakanan'] ?>', 'price-<?= $item['idmakanan'] ?>', <?= str_replace('RM', '', $item['harga_pembungkusan']) ?>)
                            ">
                            <img src="icons/plus-button.png"></img>
                          </button>

                        </div>
                        <button class="add-to-basket-button" onclick="
                          let quantity = Number(document.getElementById('<?= $item['idmakanan'] ?>').innerHTML);
                          let notes = getAdditionalNotes('text-area-<?= $item['idmakanan'] ?>');
                          addObject('<?= $item['namamakanan'] ?>', <?= str_replace('RM', '', $item['harga']) ?>, <?= str_replace('RM', '', $item['harga_pembungkusan']) ?>, quantity, notes, '<?= $item['gambar_makanan'] ?>');
                          removeItem();
                          generateBasketItems();
                          closeFoodPreviewModal('dialog-<?= $item['idmakanan'] ?>');
                          resetTextArea('text-area-<?= $item['idmakanan'] ?>');
                          resetQuantity(<?= $item['idmakanan'] ?>);
                        ">
                          <div>
                            TAMBAH KE BAKUL
                          </div>
                          <div id="price-<?= $item['idmakanan'] ?>"></div>
                        </button>
                      </div>
                    </dialog>
                <?php endwhile;
                } else {
                  echo "<p>Tiada item dalam kategori ini.</p>";
                }

                $conn->close();
                ?>
              </div>
            </section>
          <?php endforeach; ?>
        </div>
      </div>
      <!--Food Selection ends here!-->

      <!--Food Basket starts here!-->
      <div class="food-basket-div">
        <div class="basket-wrapper">
          <div class="basket">
            <div class="basket-main">
              <p class="basket-name">Bakul</p>
              <p
                style="
                    font-size: 12px;
                    color: rgb(140, 140, 140);
                    margin-bottom: 10px;
                  ">
                Dari
                <strong
                  style="
                      color: rgb(194, 138, 132);
                      font-size: 12px;
                      font-weight: 700;
                    ">Contour Cafe'</strong>
              </p>
              <a class="basket-link" href="user-ordertype.php">
                <div class="basket-type-text">Jenis</div>
                <div class="basket-delivery-text">
                  <span id="order-type" class="order-type"></span>
                  <img class="edit-icon" src="icons/edit icon.png" alt="" />
                </div>
              </a>

              <p class="basket-edit-text">
                Edit item dengan mengklik padanya
              </p>

              <div id="basket-items" class="basket-items"></div>

              <div
                id="checkout-summary-details"
                class="checkout-summary-details">
                <div class="details-top">
                  <div class="basket-subtotal-and-price">
                    <strong>Jumlah kecil</strong>
                    <strong id="subtotal-basket" class="total">RM0.00</strong>
                  </div>

                  <div id="basket-tax-and-price" class="basket-tax-and-price">
                    <strong>SST(6%)</strong>
                    <strong id="tax-basket" class="total">RM0.00</strong>
                  </div>

                  <!-- <div
                      id="rounding-and-price-basket"
                      class="rounding-and-price-basket"
                    >
                      <strong>Membulatkan</strong>
                      <strong id="rounding-price" class="total">RM0.00</strong>
                    </div> -->
                </div>

                <div class="basket-total-and-price">
                  <strong>Jumlah</strong>
                  <strong id="total-price-basket" class="total">RM0.00</strong>
                </div>
              </div>
            </div>
            <div id="footer-basket-main" class="footer-basket-main"></div>
          </div>
        </div>
      </div>
      <!--Food Basket ends here!-->
    </div>
    <!--Food Order Menu Section ends here!-->
  </main>
  <!--Food Section ends here!-->

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
  <script>
    //! ALWAYS SELECT THE FIRST LINK AND ADD active CLASSNAME
    document.querySelector(".food-category-link").classList.add("active");

    //! Clear the url when reload
    const navigationEntries = performance.getEntriesByType("navigation");
    if (navigationEntries.length > 0 && navigationEntries[0].type === "reload") {
      // If the page was reloaded
      window.location.href = "user-menu.php";
    }
  </script>
  <script src="data/HeaderLinksDataUserMenu.js"></script>
  <script src="js/generateHeaderLinksUserMenu.js"></script>
  <script src="data/mobileHeaderLinksDataUserMenu.js"></script>
  <script src="js/generateMobileHeaderLinksUserMenu.js"></script>
  <script src="js/generateFoodBasketUserMenu.js"></script>
  <script src="js/menu.js"></script>
  <script src="js/orderType.js"></script>
</body>

</html>