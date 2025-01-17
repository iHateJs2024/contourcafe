<?php
// Start the session
session_start();

// Remove 'idpelanggan' from the session
unset($_SESSION['idpelanggan']);

// Optionally destroy the entire session if no other session data is needed
session_destroy();

// Redirect to another page (e.g., homepage or login page)
header("Location: index.php"); // Replace 'homepage.php' with your desired page
exit(); // Ensure no further code is executed
