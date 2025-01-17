<?php
// Start the session
session_start();

// Include the database connection (replace with actual DB connection details)
$host = "localhost";
$username = "root";
$password = "";
$dbname = "tempahan"; // Replace with your database name

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

require 'vendor/autoload.php';

$stripe_secret_key = "sk_test_51QaSfSInQZmbpKG5NqiLHw71WDrGhzUXtKtvGYzM7w1U7xbrXyHbE7t1mCIx2W7coPKCoyomOSEoIUwHDXuzXz4q00BOyyLkCt";

\Stripe\Stripe::setApiKey($stripe_secret_key);

header('Content-Type: application/json');

try {
    // Decode the received data from Fetch
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    $basketItems = $data['items']; // Array of basket items
    $customerDetails = new stdClass();
    $customerDetails->deliveryAddress = $data['customerDetails']['deliveryAddress'];
    $customerDetails->additionalNotes = $data['customerDetails']['additionalNotes'];
    $customerDetails->subtotal = $data['customerDetails']['subtotal'];
    $customerDetails->tax = $data['customerDetails']['tax'];
    $customerDetails->totalItems = $data['customerDetails']['totalItems'];
    $_SESSION['customer_details'] = $customerDetails;

    $line_items = [];
    $_SESSION['order'] = [];

    // Build line items for individual products
    foreach ($basketItems as $item) {
        // Convert To Cents
        $item['price'] = $item['price'] * 100;
        //Convert To Cents
        $item['takeAwayPackaging'] = $item['takeAwayPackaging'] * 100;
        $itemTotal = $item['price'] * $item['quantity'];

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
            'takeAwayPackaging' => $item['takeAwayPackaging'],
            'notes' => $item['notes'], // Notes: undefined
        ];
    }

    $session = \Stripe\Checkout\Session::create([
        'line_items' => $line_items,
        'mode' => 'payment',
        'success_url' => 'http://localhost:3000/success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => 'http://localhost:3000/user-menu-checkout.php',
    ]);

    ob_clean(); // Clean any prior output
    header('Content-Type: application/json');
    echo json_encode(['url' => $session->url]);
    flush(); // Clear everything to the output buffer
} catch (Error $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
