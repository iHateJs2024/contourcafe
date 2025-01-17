<!-- Database connection -->
<!-- Database connection -->
<!-- Database connection -->

<?php
$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "tempahan";
$conn = "";

$conn = mysqli_connect(
  $db_server,
  $db_user,
  $db_pass,
  $db_name
);

if ($conn->connect_error) {
  echo "Failed to connect DB" . $conn->connect_error;
}
?>