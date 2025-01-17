<?php

$token = $_POST["token"];

$token_hash = $token;

$mysqli = require __DIR__ . "/database.php";

$sql = "SELECT * FROM pelanggan 
        WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
  die("Token not found.");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
  die("Token expired.");
}

if (strlen($_POST["password"]) < 3) {
  die("Password must be at least 3 characters.");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
  die("Passwords do not match.");
}

$sql = "UPDATE pelanggan 
        SET password_pelanggan = ?,
            reset_token_hash = NULL,
            reset_token_expires_at = NULL
        WHERE idpelanggan = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("ss", $_POST["password"], $user["idpelanggan"]);

$stmt->execute();

header("Location: password-reset-success.php");
