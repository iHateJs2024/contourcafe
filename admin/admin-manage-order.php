<?php
session_start();
//! PHP FOR GENERATING ORDERS
// Database connection
$host = 'localhost';
$db = 'tempahan';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

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

// Fetch orders grouped by 'tarikh' (full date-time) and filtered by status
if (isset($_POST['order_status'])) {
  $order_status = $_POST['order_status'];

  $sql = "SELECT mp.*, p.namapelanggan, p.nohp, p.email_pelanggan, p.alamat_pelanggan FROM maklumat_pesanan mp 
  JOIN pelanggan p ON mp.idpelanggan = p.idpelanggan
  WHERE mp.status_pesanan = ? ORDER BY mp.tarikh";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $order_status);
  $stmt->execute();
  $result = $stmt->get_result();

  // Group orders by the full 'tarikh' (date-time)
  $orders_by_datetime = [];
  while ($row = $result->fetch_assoc()) {
    $datetime = $row['tarikh']; // Use the full date-time
    if (!isset($orders_by_datetime[$datetime])) {
      $orders_by_datetime[$datetime] = [];
    }
    $orders_by_datetime[$datetime][] = $row;
  }

  //! Output grouped orders by date-time
  foreach ($orders_by_datetime as $datetime => $orders) {
    // Sum the 'bilangan' column to get the total number of items in the order
    $jumlah_item = $orders[0]['jumlah_item'];
    $nota = $orders[0]['nota_pesanan'];

    // Get the order status based on food items status
    $status = get_order_status($orders);
    $customer_name = $orders[0]['namapelanggan']; // Use the first entry for the customer
    $nohp = $orders[0]['nohp'];
    $email = $orders[0]['email_pelanggan'];
    $alamat = $orders[0]['alamat_pelanggan'];
    $subtotal = $orders[0]['subtotal'];
    $tax = $orders[0]['tax'];
    $total = $orders[0]['total'];

    // Get customer id and date for the current order
    $idpelanggan = $orders[0]['idpelanggan'];

    // Format Time To AM/PM
    $formatted_time = date('h:i:s A', strtotime($datetime));
    $date = date('Y-m-d', strtotime($datetime));

    echo "
      <li>
        <div class='order-date-group'>
          <div>
            <div class='order-date'>Tarikh Pesanan: " . $date . " " . $formatted_time . "</div>
            <p class='order-quantity'>Nama Pelanggan: " . htmlspecialchars($customer_name) . "</p>
            <p class='order-quantity'>Jumlah Item: " . $jumlah_item . "</p>
            <p class='order-quantity'>
              Nota Pesanan: " . htmlspecialchars($nota) . "
            </p>
          </div>
          <div class='order-status-and-lihat-button-container'>
            <p class='order-status'>Status:
              <span 
                class='order-status-text 
                      js-order-status-text-" . $datetime . "
                      " . htmlspecialchars($status) . "'
                id='js-order-status-text-" . $datetime . "'
              >
              " . htmlspecialchars($status) . "</span>
            </p>
            <button
              class='lihat-order-button js-lihat-order-button-" . $datetime . "'
              data-order-id='" . $datetime . "'
              onclick='
                makeLihatButtonInteractive(`" . $datetime . "`);
                submitOrder(`" . $datetime . "`, `" . htmlspecialchars($customer_name) . "`);
                ChangeOrderStatusButton(`" . $datetime . "`);
              '>
              Lihat
            </button>
          </div>
        </div>

        <!-- Hidden Form -->
        <form id='orderForm' method='POST' style='display:none;'>
          <input type='hidden' name='tarikh' id='tarikh'>
        </form>

        <dialog class='order-dialog food-preview-modal' id='js-order-dialog-" . $datetime . "'>
          <!--First Vertical Layout-->
          <div class='modal-title-and-close-button'>
            <div class='change-order-status-buttons-container'>
              <button 
                class='ready-button' 
                data-button-id='" . $datetime . "'
                id='ready-button-" . $datetime . "'
                onclick='
                  changeReadyButtonColor(`" . $datetime . "`);
                  updateStatus(`Ready`, `" . htmlspecialchars($customer_name) . "`, `" . $datetime . "`)
              '>
                Ready
              </button>
              <button 
                class='pending-button' 
                data-button-id='" . $datetime . "'
                id='pending-button-" . $datetime . "'
                onclick='changePendingButtonColor(`" . $datetime . "`);
                updateStatus(`Pending`, `" . htmlspecialchars($customer_name) . "`, `" . $datetime . "`)
              '>
                Pending
              </button>
              <button 
                class='cancel-button' 
                data-button-id='" . $datetime . "'
                id='cancel-button-" . $datetime . "'
                onclick='changeCancelButtonColor(`" . $datetime . "`);
                updateStatus(`Cancelled`, `" . htmlspecialchars($customer_name) . "`, `" . $datetime . "`)
              '>
                Cancel
              </button>
            </div>
            <button 
              class='
                      close-button-food-preview-modal 
                      js-close-order-dialog-button' 
                      data-order-id='" . $datetime . "
                    '
              onclick='
                closeOrderDialog(`" . $datetime . "`);
                resetButtonsOrderStatusColor(`" . $datetime . "`);
              ';
            >
              x
            </button>
          </div>

          <div class='modal-scrollable-content'>
            <div>
              <p class='restaurant-name'>Contour Cafe'</p>
              <div class='order-details'>
                <div class='order-date'>
                  Tarikh Pesanan: " . $date . " " . $formatted_time . "
                </div>
                <p>Butiran Pelanggan:</p>
                <span>Nama: " . $customer_name . "</span>
                <span>Nombor Telefon: " . $nohp . "</span>
                <span>E-mel: " . $email . "</span>
                <span>Alamat: " . $alamat . "</span>
                <p>Item:</p>
              </div>
              <div id='order-items-container-" . $datetime . "' class='order-items-container'></div>
            </div>
            

              <div
              id='checkout-summary-details'
              class='checkout-summary-details'>
              <div class='details-top'>
                <div class='basket-subtotal-and-price'>
                  <strong>Jumlah kecil</strong>
                  <strong id='subtotal-basket-" . $datetime . "' class='total'>
                    " . $subtotal . "
                  </strong>
                </div>

                <div id='basket-tax-and-price' class='basket-tax-and-price'>
                  <strong>SST(6%)</strong>
                  <strong id='tax-basket-" . $datetime . "' class='total'>
                    " . $tax . "
                  </strong>
                </div>
              </div>

              <div class='basket-total-and-price'>
                <strong>Jumlah</strong>
                <strong
                  id='total-price-basket-" . $datetime . "'
                  class='total'>
                  " . $total . "
                </strong>
              </div>
            </div>
          </div>

          <div class='modal-closing-text'>
            <p>Terima Kasih! Sila datang lagi!</p>
          </div>
        </dialog>
      </li>
    ";
  }

  exit(); // Make sure to exit after outputting orders
}

// Fetch total unique order counts for each status (Pending, Ready, Cancelled) based on distinct 'tarikh'
$status_counts = [
  'Pending' => 0,
  'Ready' => 0,
  'Cancelled' => 0
];

// Count the distinct 'tarikh' values per status to get the total number of orders
$count_sql = "SELECT status_pesanan , COUNT(DISTINCT tarikh) AS total FROM maklumat_pesanan GROUP BY status_pesanan";
$count_result = $conn->query($count_sql);

if ($count_result->num_rows > 0) {
  while ($row = $count_result->fetch_assoc()) {
    if ($row['status_pesanan'] == 'Pending') {
      $status_counts['Pending'] = $row['total'];
    } elseif ($row['status_pesanan'] == 'Ready') {
      $status_counts['Ready'] = $row['total'];
    } elseif ($row['status_pesanan'] == 'Cancelled') {
      $status_counts['Cancelled'] = $row['total'];
    }
  }
}

// Function to determine the status of an order based on food items' statuses
function get_order_status($orders)
{
  $statuses = array_map(function ($order) {
    return $order['status_pesanan']; // Get status of each order item
  }, $orders);

  // If all statuses are the same, use that status
  if (count(array_unique($statuses)) == 1) {
    return $statuses[0]; // Return the same status (e.g., "Ready", "Cancelled")
  } else {
    return 'Partially Ready'; // If statuses are mixed
  }
}
?>

<?php
//! PHP FOR GENERATING FOOD ITEMS
// Database connection
$host = 'localhost';
$db = 'tempahan';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle the AJAX request for fetching order details
if (isset($_GET['tarikh']) && isset($_GET['namapelanggan'])) {
  $tarikh = $_GET['tarikh'];
  $namapelanggan = $_GET['namapelanggan'];

  // Query to fetch orders by exact DATETIME and customer name
  $sql = "
        SELECT t.idmakanan, t.bilangan, t.nota, m.namamakanan, m.harga, m.harga_pembungkusan
        FROM tempahan t
        INNER JOIN makanan m ON t.idmakanan = m.idmakanan
        INNER JOIN pelanggan p ON t.idpelanggan = p.idpelanggan
        WHERE t.tarikh = ? AND p.namapelanggan = ?
    ";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $tarikh, $namapelanggan);
  $stmt->execute();
  $result = $stmt->get_result();

  $orderDetails = [];

  while ($row = $result->fetch_assoc()) {
    $orderDetails[] = [
      'namamakanan' => $row['namamakanan'],
      'harga' => $row['harga'],
      'harga_pembungkusan' => $row['harga_pembungkusan'],
      'bilangan' => $row['bilangan'],
      'nota' => $row['nota'],
    ];
  }

  // Return data as JSON
  echo json_encode($orderDetails);
  exit();  // Make sure to stop execution here
}
?>

<?php
//! PHP FOR UPDATING ORDER STATUS
// Database configuration
$host = "localhost";
$dbname = "tempahan";
$username = "root";
$password = "";

// Establish database connection
try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Database connection failed: " . $e->getMessage());
}

// Handle AJAX requests
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
  $action = $_POST['action'];
  $namapelanggan = $_POST['namapelanggan'];
  $tarikh = $_POST['tarikh'];

  try {
    // Get idpelanggan
    $stmt = $conn->prepare("SELECT idpelanggan FROM pelanggan WHERE namapelanggan = :namapelanggan");
    $stmt->bindParam(':namapelanggan', $namapelanggan);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
      $idpelanggan = $result['idpelanggan'];

      // Update the status in the maklumat_pesanan table
      $stmt = $conn->prepare("UPDATE maklumat_pesanan SET status_pesanan = :status_pesanan WHERE idpelanggan = :idpelanggan AND tarikh = :tarikh");
      $stmt->bindParam(':status_pesanan', $action);
      $stmt->bindParam(':idpelanggan', $idpelanggan);
      $stmt->bindParam(':tarikh', $tarikh);
      $stmt->execute();

      echo json_encode(['success' => true, 'message' => 'Order status updated successfully.']);
    } else {
      echo json_encode(['success' => false, 'message' => 'Customer not found.']);
    }
  } catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
  }
  exit;
}
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
  <link rel="stylesheet" href="../css/menu.css" />
  <link rel="stylesheet" href="../css/general.css" />
  <link rel="stylesheet" href="../css/scrollbar.css" />
  <link rel="stylesheet" href="../css/home.css" />
  <link rel="stylesheet" href="../css/modal-food-preview.css" />
  <link rel="stylesheet" href="css/admin-index.css" />
  <link rel="stylesheet" href="css/admin-manage-order.css" />
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
    <div class="main-content">
      <div class="main-content-title">

        <button
          class="
            pesanan-siap-button 
            js-display-pesanan-siap-button 
            status-btn
          "
          data-status="Ready">
          Pesanan <span>Siap</span>
        </button>
        <button
          class="
            pesanan-belum-selesai-button 
            js-display-pesanan-belum-siap-button 
            status-btn
          "
          data-status="Pending">
          Pesanan <span>Belum Selesai</span>
        </button>
        <button
          class="
            pesanan-dibatalkan-button 
            js-display-pesanan-dibatalkan-button 
            status-btn
          "
          data-status="Cancelled">
          Pesanan <span>Dibatalkan</span>
        </button>

      </div>

      <div class="container">

        <!-- Pesanan Siap Section -->
        <div
          class="sub-container"
          id="ready-container">
          <p class="jumlah-pesanan-text">
            Jumlah Pesanan: <span id="ready-count"><?= $status_counts['Ready'] ?></span>
          </p>
          <ol id="ready-orders"></ol>
        </div>

        <!-- Pesanan Belum Selesai Section -->
        <div
          class="sub-container"
          id="pending-container">
          <p class="jumlah-pesanan-text">
            Jumlah Pesanan: <span id="pending-count"><?= $status_counts['Pending'] ?></span>
          </p>
          <ol id="pending-orders"></ol>
        </div>

        <!-- Pesanan Dibatalkan Section -->
        <div
          class="sub-container"
          id="cancelled-container">
          <p class="jumlah-pesanan-text">
            Jumlah Pesanan: <span id="cancelled-count"><?= $status_counts['Cancelled'] ?></span>
          </p>
          <ol id="cancelled-orders"></ol>
        </div>

      </div>
    </div>
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
    // Display orders when a button is clicked
    const statusButtons = document.querySelectorAll('.status-btn');

    statusButtons.forEach(button => {
      button.addEventListener('click', (event) => {
        // const status = event.target.dataset.status; //? Wrong code
        const status = button.dataset.status;

        // Hide all containers first
        document.querySelectorAll('.sub-container').forEach(container => {
          container.classList.remove('active');
        });

        // Show the clicked container
        const activeContainer = document.getElementById(status.toLowerCase() + '-container');
        activeContainer.classList.add('active');

        // Make AJAX request to fetch orders based on status
        fetchOrders(status);
      });
    });

    // Default: Show Pesanan Belum Selesai on page load
    window.onload = function() {
      const defaultStatus = 'Pending';
      document.getElementById('pending-container').classList.add('active'); // Ensure "Pesanan Belum Selesai" is visible by default
      fetchOrders(defaultStatus);
    };

    // Function to fetch orders via AJAX
    function fetchOrders(status) {
      const containerId = status.toLowerCase() + '-orders';

      // Make AJAX request to fetch data
      const xhr = new XMLHttpRequest();
      xhr.open('POST', '', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onload = function() {
        if (xhr.status === 200) {
          // Update the appropriate orders container with the received data
          document.getElementById(containerId).innerHTML = xhr.responseText;
        }
      };
      xhr.send('order_status=' + status);
    }

    // Display food items when 'Lihat' button is clicked
    async function submitOrder(tarikh, namapelanggan) {
      // Use fetch to make a GET request to the server for the selected 'tarikh' and 'namapelanggan'
      const response = await fetch(window.location.href + `?tarikh=${encodeURIComponent(tarikh)}&namapelanggan=${encodeURIComponent(namapelanggan)}`);
      const orderDetails = await response.json(); // Parse the JSON response

      let html = '';

      if (orderDetails.length > 0) {
        html += `<ol class="modal-item-ol">`;

        let subTotal = 0;
        let tax = 0;

        orderDetails.forEach(order => {
          const harga = parseFloat(order.harga.replace('RM', '').trim());
          const totalPrice = harga * order.bilangan;
          const takeAwayPrice = (parseFloat(order.harga_pembungkusan.replace('RM', '').trim()) * order.bilangan).toFixed(2);
          html += `
              <li class="modal-item-li">
                <div class="modal-item-container">
                  <div class="modal-item-container-left">
                    <div>
                      <p class="modal-item-food-name">${order.namamakanan}</p>
                      <p class="modal-item-price">(RM${harga.toFixed(2)})</p>
                      <p class="modal-item-quantity">x${order.bilangan}</p>
                    </div>
                    <div>
                      <p class="modal-item-notes">
                        Nota: ${order.nota}
                      </p>
                    </div>
                  </div>
                  <div class="modal-item-container-right">
                    <div>
                      <p class="modal-item-total-price">
                        Harga: <strong>RM${totalPrice.toFixed(2)}</strong>
                      </p>
                    </div>
                    <div>
                      <p>
                        Harga Pembungkusan: <strong>RM${takeAwayPrice}</strong>
                      </p>
                    </div>
                  </div>
                </div>
              </li>
          `;
        });

        html += `</ol>`;
      } else {
        html = '<p>No items found for the selected order.</p>';
      }

      document.getElementById(`order-items-container-${tarikh}`).innerHTML = html;
    }

    // Function to update order status
    function updateStatus(action, namapelanggan, tarikh) {
      const data = new FormData();
      data.append('action', action);
      data.append('namapelanggan', namapelanggan);
      data.append('tarikh', tarikh);

      fetch('', { // Send the request to the same PHP file
          method: 'POST',
          body: data
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {

            location.reload(); // Reload page to reflect updated status
          } else {
            alert('Error: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
        });
    }
  </script>
  <script src="data/HeaderLinksDataAdmin.js"></script>
  <script src="js/generateHeaderLinksAdmin.js"></script>
  <script src="data/mobileHeaderLinksDataAdmin.js"></script>
  <script src="js/generateMobileHeaderLinksAdmin.js"></script>
  <script src="js/admin-mobile-navigation-bar.js"></script>
  <script src="js/admin-manage-order.js"></script>

</body>

</html>