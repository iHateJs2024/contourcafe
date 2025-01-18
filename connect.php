<?php
function getConnection()
{
  // $db_server = "sql200.infinityfree.com";
  // $db_user = "if0_38125411";
  // $db_pass = "RVCWyRTNH9E";
  // $db_name = "if0_38125411_tempahan";

  $db_server = "localhost";
  $db_user = "root";
  $db_pass = "";
  $db_name = "tempahan";

  $conn = mysqli_connect(
    $db_server,
    $db_user,
    $db_pass,
    $db_name
  );

  if ($conn->connect_error) {
    die("Failed to connect DB: " . $conn->connect_error);
  }

  return $conn;
}

function getNextJurujualId($conn)
{
  // Get the highest current ID
  $sql = "SELECT idjurujual FROM jurujual ORDER BY idjurujual DESC LIMIT 1";
  $result = $conn->query($sql);

  if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $lastId = $row['idjurujual'];

    // Extract the numeric part
    $num_part = intval(substr($lastId, 1));

    // Generate next ID
    $nextId = "J" . str_pad($num_part + 1, 2, "0", STR_PAD_LEFT);
  } else {
    // If no existing records, start with J01
    $nextId = "J01";
  }

  return $nextId;
}

function getNextKategoriId($conn)
{
  // Get the highest current ID
  $sql = "SELECT idkategori FROM kategori ORDER BY idkategori DESC LIMIT 1";
  $result = $conn->query($sql);

  if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $lastId = $row['idkategori'];

    // Extract the numeric part (01, 02, etc)
    $num_part = intval(substr($lastId, 1));

    // Generate next ID with exactly 2 digits
    $nextId = "K" . str_pad($num_part + 1, 2, "0", STR_PAD_LEFT);
  } else {
    // Start with K01 if no records exist
    $nextId = "K01";
  }

  return $nextId;
}

function getNextPelangganId($conn)
{
  // Get the highest current ID
  $sql = "SELECT idpelanggan FROM pelanggan ORDER BY idpelanggan DESC LIMIT 1";
  $result = $conn->query($sql);

  if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $lastId = $row['idpelanggan'];

    // Extract the numeric part (001, 002, etc)
    $num_part = intval(substr($lastId, 1));

    // Generate next ID with exactly 3 digits
    $nextId = "P" . str_pad($num_part + 1, 3, "0", STR_PAD_LEFT);
  } else {
    // Start with P001 if no records exist
    $nextId = "P001";
  }

  return $nextId;
}
