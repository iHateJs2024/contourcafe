<?php
include_once(__DIR__ . '/connect.php');
$conn = getConnection();

// Signup validation
if (isset($_POST['signup-button'])) {
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
  $email = $_POST['email'];
  $nohp = $_POST['nohp'];

  $checkusername = "SELECT * FROM pelanggan WHERE namapelanggan='$username'";
  $result = $conn->query($checkusername);
  if ($result->num_rows > 0) {
    echo '<script>alert("Nama Pengguna Sudah Wujud!");
                  window.location.href = "signup.php";
            </script>';
  } else {
    $activation_token = bin2hex(random_bytes(16));
    $activation_token_hash = hash("sha256", $activation_token);

    $newId = getNextPelangganId($conn);

    $insertQuery = "INSERT INTO pelanggan (idpelanggan, namapelanggan, password_pelanggan, nohp, email_pelanggan, account_activation_hash)
                          VALUES ('$newId', '$username', '$password', '$nohp', '$email', '$activation_token_hash')";
    if ($conn->query($insertQuery) == TRUE) {
      $mail = require __DIR__ . "/mailer.php";

      $mail->setFrom("kongjiale12@gmail.com", "Contour Cafe'");
      $mail->addAddress($email);
      $mail->Subject = "Aktivasi Akaun Anda untuk Contour Cafe'";
      $mail->Body =
        <<<END
            <div style="background-color: rgb(243, 243, 243); padding: 20px; border-radius: 10px;">
              <div style="background-color: white; padding: 20px; border-radius: 10px; margin: 0 auto; max-width: 580px;">
                <p style="font-size: 16px; font-weight: 600; margin-bottom: 10px;">
                  Yang Dihormati $email,
                </p>
                <p style="font-size: 14px; font-weight: 400; margin-bottom: 20px; text-align: center;">
                  Kami telah menerima permintaan untuk aktivasi akaun anda bagi akaun Contour Cafe' anda. Sila ikuti pautan di bawah untuk aktivasi akaun anda:
                </p>
                <a style="font-family: Arial; max-width: 243.79px; font-weight: 700; font-size: 20px; cursor: pointer; background-color: rgb(237, 80, 94); color: rgb(254, 254, 254); padding: 10px 20px; border-radius: 5px; text-decoration: none; margin-bottom: 30px; text-align: center; display: block; margin-left: auto; margin-right: auto;" href="http://contourcafe.great-site.net/activate-account.php?token=$activation_token">
                  Aktivasi Akaun
                </a>
                <p style="text-align: center; font-size: 14px; font-weight: 400; margin-bottom: 26px;">
                  Jika anda tidak meminta untuk aktivasi akaun anda, jangan risau! Akaun anda masih selamat dan anda boleh memadamkan emel ini.
                </p>
                <p style="font-size: 14px; font-weight: 400; margin-bottom: 10px;">
                  Salam hormat,
                </p>
                <p style="font-size: 14px; font-weight: 400; margin-bottom: 10px;">
                  Contour Cafe'
                </p>
              </div>
            </div>
  
      END;

      try {
        $mail->send();

        header("Location: signup-success.html");
        exit;
      } catch (Exception $e) {
        echo "Email could not be sent.";
        echo "Mailer Error: " . $mail->ErrorInfo;
        exit;
      }
    } else {
      echo "Error:" . $conn->error;
    }
  }
}

// Login validation
if (isset($_POST['login-button'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $email = $_POST['email'];
  $nohp = $_POST['nohp'];

  // Prepare a statement to check credentials in jurujual table
  $checkAdmin = $conn->prepare(
    "SELECT idjurujual, namajurujual, password_jurujual, nohp, email_jurujual 
    FROM jurujual 
    WHERE namajurujual = ? 
    AND password_jurujual = ?
    AND nohp = ?
    AND email_jurujual = ?
    "
  );
  $checkAdmin->bind_param("siss", $username, $password, $nohp, $email);
  $checkAdmin->execute();
  $resultAdmin = $checkAdmin->get_result();

  if ($resultAdmin->num_rows > 0) {
    // Fetch the row data
    $admin = $resultAdmin->fetch_assoc();

    // Start a session and store the admin details
    session_start();
    $_SESSION['idjurujual'] = $admin['idjurujual'];
    $_SESSION['namajurujual'] = $admin['namajurujual'];
    $_SESSION['password_jurujual'] = $admin['password_jurujual'];
    $_SESSION['nohp_jurujual'] = $admin['nohp'];
    $_SESSION['email_jurujual'] = $admin['email_jurujual'];

    // Redirect to admin page
    echo '<script>
              alert("Login Admin Berjaya!");
              window.location.href = "admin/admin-index.php"; // Redirect to admin page
            </script>';
    exit;
  }


  $sql = "SELECT * FROM pelanggan
          WHERE namapelanggan='$username' 
          AND password_pelanggan='$password'
          AND nohp='$nohp' 
          AND email_pelanggan='$email'";

  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    session_start();
    $row = $result->fetch_assoc();
    $_SESSION['idpelanggan'] = $row['idpelanggan'];
    $_SESSION['namapelanggan'] = $row['namapelanggan'];
    $_SESSION['password_pelanggan'] = $row['password_pelanggan'];
    $_SESSION['nohp_pelanggan'] = $row['nohp'];
    $_SESSION['email_pelanggan'] = $row['email_pelanggan'];

    //? Account Activated
    if ($row['account_activation_hash'] === null) {
      echo '<script>alert("Berjaya Log Masuk!");
                    window.location.href = "user-index.php";
              </script>';
    } else {
      echo '<script>alert("Akaun Anda Belum Aktif! Sila cek e-mel anda untuk aktivasi akaun.");
                    window.location.href = "signup.php";
              </script>';
    }
    exit();
  } else {
    echo '<script>alert("Tidak Ditemui, Nama Pengguna, E-mel, Nombor Telefon atau Kata Laluan Salah!");
                  window.location.href = "signup.php";
            </script>';
  }
}

//?Close connection.
mysqli_close($conn);
?>

<script src="js/signup.js"></script>