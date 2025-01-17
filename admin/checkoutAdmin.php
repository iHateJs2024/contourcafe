<?php
session_start();

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

require '../vendor/autoload.php';

$stripe_secret_key = "sk_test_51QaSfSInQZmbpKG5NqiLHw71WDrGhzUXtKtvGYzM7w1U7xbrXyHbE7t1mCIx2W7coPKCoyomOSEoIUwHDXuzXz4q00BOyyLkCt";

\Stripe\Stripe::setApiKey($stripe_secret_key);

header('Content-Type: application/json');

try {
  // Decode the received data from Fetch
  $json = file_get_contents('php://input');
  $data = json_decode($json, true);

  $basketItems = $data['items']; // Array of basket items

  $subtotal = 0;
  $line_items = [];
  $_SESSION['order'] = [];

  // Build line items for individual products
  foreach ($basketItems as $item) {
    // Convert To Cents
    $item['price'] = $item['price'] * 100;
    //Convert To Cents
    $item['takeAwayPackaging'] = $item['takeAwayPackaging'] * 100;
    $itemTotal = $item['price'] * $item['quantity'];
    $subtotal += $itemTotal;

    // Add the product line item
    $line_items[] = [
      'price_data' => [
        'currency' => 'myr',
        'product_data' => [
          'name' => $item['name'],
        ],
        'unit_amount' => $item['price'], // In cents
      ],
      'quantity' => $item['quantity'],
      'tax_rates' => ['txr_1QawJVInQZmbpKG5yq18madU'],
    ];

    // Add takeaway packaging cost
    if (!empty($item['takeAwayPackaging']) && $item['takeAwayPackaging'] > 0) {
      $packagingCost = $item['takeAwayPackaging'] * $item['quantity'];
      $subtotal += $packagingCost;

      $line_items[] = [
        'price_data' => [
          'currency' => 'myr',
          'product_data' => [
            'name' => "Takeaway Packaging",
          ],
          'unit_amount' => $item['takeAwayPackaging'], // In cents
        ],
        'quantity' => $item['quantity'],
      ];
    }

    // Save order details in session
    $_SESSION['order'][] = [
      'name' => $item['name'],
      'quantity' => $item['quantity'],
      'price' => $item['price'],
    ];
  }

  $session = \Stripe\Checkout\Session::create([
    'line_items' => $line_items,
    'mode' => 'payment',
    'success_url' => 'http://localhost:3000/admin/successAdmin.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => 'http://localhost:3000/admin/admin-menu-checkout.php',
  ]);

  echo json_encode(['url' => $session->url]);
} catch (Error $e) {
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
}
