<?php

if (isset($_POST["email"])) {
  $email = $_POST["email"];


  $token = bin2hex(random_bytes(16));

  $token_hash = hash("sha256", $token);

  date_default_timezone_set("Asia/Kuala_Lumpur");
  $expiry = date("Y-m-d H:i:s", time() + 60 * 30);

  $mysqli = require __DIR__ . "/database.php";

  $sql = "UPDATE pelanggan
        SET reset_token_hash = ?,
            reset_token_expires_at = ?
        WHERE email_pelanggan = ?";

  $stmt = $mysqli->prepare($sql);

  $stmt->bind_param("sss", $token_hash, $expiry, $email);

  $stmt->execute();

  if ($mysqli->affected_rows) {
    $mail = require __DIR__ . "/mailer.php";

    $mail->setFrom("kongjiale12@gmail.com", "Contour Cafe'");
    $mail->addAddress($email);
    $mail->Subject = "Tetapkan Semula Kata Laluan Anda untuk Contour Cafe'";
    $mail->Body =
      <<<END
          <div style="background-color: rgb(243, 243, 243); padding: 20px; border-radius: 10px;">
            <div style="background-color: white; padding: 20px; border-radius: 10px; margin: 0 auto; max-width: 580px;">
              <p style="font-size: 16px; font-weight: 600; margin-bottom: 10px;">
                Yang Dihormati $email,
              </p>
              <p style="font-size: 14px; font-weight: 400; margin-bottom: 20px; text-align: center;">
                Kami telah menerima permintaan untuk menetapkan semula kata laluan anda bagi akaun Contour Cafe' anda. Sila ikuti pautan di bawah untuk menetapkannya semula:
              </p>
              <a style="font-family: Arial; max-width: 243.79px; font-weight: 700; font-size: 20px; cursor: pointer; background-color: rgb(237, 80, 94); color: rgb(254, 254, 254); padding: 10px 20px; border-radius: 5px; text-decoration: none; margin-bottom: 30px; text-align: center; display: block; margin-left: auto; margin-right: auto;" href="http://localhost/restaurant/reset-password.php?token=$token_hash">
                Tetapkan Semula Kata Laluan
              </a>
              <p style="text-align: center; font-size: 14px; font-weight: 400; margin-bottom: 26px;">
                Jika anda tidak meminta untuk menukar kata laluan anda, jangan risau! Kata laluan anda masih selamat dan anda boleh memadamkan emel ini.
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

      header("Location: forgot-password.php");
      exit;
    } catch (Exception $e) {
      echo "Email could not be sent.";
      echo "Mailer Error: " . $mail->ErrorInfo;
    }
  }
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
  <link rel="icon" type="image/jpg" href="Logo image/Contour Cafe’.jpg" />
</head>

<body>
  <!--Navigation bar starts here-->
  <header>
    <div class="main-header">
      <!--Middle section starts here-->
      <div class="middle-section">
        <!--Logo section-->
        <a class="logo-link" href="index.php">
          <img
            class="contour-cafe-logo"
            src="Logo image/Contour Cafe’.jpg"
            alt="" />
        </a>
        <!--Links section-->
        <nav class="links-section">
          <ul id="ul-header" class="ul-header">
            <li>
              <a href="signup.php">
                <img src="icons/popup-back-icon (1).svg" alt="" />
                Kembali
              </a>
            </li>
          </ul>
        </nav>
      </div>
      <!--Middle section ends here-->
    </div>
  </header>
  <!--Navigation bar ends here-->

  <!-- Main Content starts here! -->
  <main class="main">
    <form action="forgot-password.php" method="post">
      <div class="main-content">
        <div>
          <h1 class="title">Lupa kata laluan</h1>
        </div>
        <div class="text-box">
          <div class="entryarea">

            <!-- Email -->
            <input
              type="email"
              name="email"
              id="email"
              class="username"
              required />
            <label for="email" id="label-email" class="label-username">Email</label>
          </div>
          <p id="example-text-email" class="example-text-email">e.g. kongjiale12gmail.com</p>
        </div>

        <div class="send-username-div">
          <button id="forgot-password-button" type="submit" name="change-password-button" class="change-password-button" onclick="checkInputValueForgotPasswordForm()">
            TUKAR KATA LALUAN
          </button>
        </div>
        <div class="send-username-div loading-button-div">
          <button id="loading-button" class="change-password-button" disabled>
            SEDANG HANTAR E-MEL...
          </button>
        </div>
      </div>
    </form>

    <div class="main-content-success">
      <h1>Lupa Kata Laluan</h1>

      <div class="note">
        <p>
          Sila semak peti masuk anda dan klik pautan untuk menetapkan semula kata laluan anda.</p>
        <p>
          Jangan lupa untuk semak folder spam anda!
        </p>
      </div>

      <div class="check-mail-img">
        <svg _ngcontent-ng-c2144836179="" xmlns="http://www.w3.org/2000/svg" width="238" height="183" viewBox="0 0 238 183" fill="none">
          <path _ngcontent-ng-c2144836179="" d="M54.8016 126.646L73.2284 97.6484L31.9611 71.3887L10.6495 104.926L44.7828 126.646H54.8016Z" fill="#7FC411" class="svgColor"></path>
          <path _ngcontent-ng-c2144836179="" d="M80.9141 135.693H35.2702V126.498H80.9141V135.693ZM35.863 135.099H80.3213V127.091H35.863V135.099Z" fill="#3F3D56"></path>
          <path _ngcontent-ng-c2144836179="" d="M75.2827 134.062C76.9196 134.062 78.2466 132.734 78.2466 131.096C78.2466 129.458 76.9196 128.13 75.2827 128.13C73.6458 128.13 72.3188 129.458 72.3188 131.096C72.3188 132.734 73.6458 134.062 75.2827 134.062Z" fill="#7FC411" class="svgColor"></path>
          <path _ngcontent-ng-c2144836179="" d="M66.9838 134.062C68.6207 134.062 69.9477 132.734 69.9477 131.096C69.9477 129.458 68.6207 128.13 66.9838 128.13C65.3469 128.13 64.0199 129.458 64.0199 131.096C64.0199 132.734 65.3469 134.062 66.9838 134.062Z" fill="#7FC411" class="svgColor"></path>
          <path _ngcontent-ng-c2144836179="" d="M58.6849 134.062C60.3218 134.062 61.6488 132.734 61.6488 131.096C61.6488 129.458 60.3218 128.13 58.6849 128.13C57.048 128.13 55.721 129.458 55.721 131.096C55.721 132.734 57.048 134.062 58.6849 134.062Z" fill="#7FC411" class="svgColor"></path>
          <path _ngcontent-ng-c2144836179="" d="M50.3861 134.062C52.023 134.062 53.3499 132.734 53.3499 131.096C53.3499 129.458 52.023 128.13 50.3861 128.13C48.7491 128.13 47.4222 129.458 47.4222 131.096C47.4222 132.734 48.7491 134.062 50.3861 134.062Z" fill="#7FC411" class="svgColor"></path>
          <path _ngcontent-ng-c2144836179="" d="M42.0872 134.062C43.7241 134.062 45.0511 132.734 45.0511 131.096C45.0511 129.458 43.7241 128.13 42.0872 128.13C40.4503 128.13 39.1233 129.458 39.1233 131.096C39.1233 132.734 40.4503 134.062 42.0872 134.062Z" fill="#7FC411" class="svgColor"></path>
          <path _ngcontent-ng-c2144836179="" d="M135.153 33.5154V10.9741H146.416V0H91.2877V55.167H138.413V49.7732C138.413 48.7677 138.611 47.7721 138.996 46.8432C139.38 45.9142 139.944 45.0702 140.654 44.3592C141.365 43.6483 142.208 43.0843 143.137 42.6995C144.065 42.3147 145.06 42.1167 146.065 42.1167H146.416V33.5154H135.153Z" fill="#7FC411" class="svgColor"></path>
          <path _ngcontent-ng-c2144836179="" d="M196.209 142.366H159.457V150.078H196.209V142.366Z" fill="#7FC411" class="svgColor"></path>
          <path _ngcontent-ng-c2144836179="" d="M204.212 104.402H150.269V111.521H204.212V104.402Z" fill="white"></path>
          <path _ngcontent-ng-c2144836179="" d="M230.59 182.406H0V182.999H230.59V182.406Z" fill="#3F3D56"></path>
          <path _ngcontent-ng-c2144836179="" d="M213.455 183H146.065C143.957 182.998 141.937 182.159 140.448 180.668C138.958 179.177 138.119 177.156 138.117 175.047V49.7734C138.119 47.6648 138.958 45.6433 140.447 44.1524C141.937 42.6614 143.957 41.8227 146.065 41.8203H213.455C215.562 41.8227 217.582 42.6614 219.072 44.1524C220.562 45.6433 221.4 47.6648 221.402 49.7734V175.047C221.4 177.156 220.562 179.177 219.072 180.668C217.582 182.159 215.562 182.998 213.455 183ZM146.065 42.4135C144.115 42.4157 142.245 43.1918 140.866 44.5716C139.488 45.9514 138.712 47.8221 138.71 49.7734V175.047C138.712 176.998 139.488 178.869 140.866 180.249C142.245 181.629 144.115 182.405 146.065 182.407H213.455C215.405 182.405 217.274 181.629 218.653 180.249C220.032 178.869 220.807 176.998 220.809 175.047V49.7734C220.807 47.8221 220.032 45.9514 218.653 44.5716C217.274 43.1918 215.405 42.4157 213.455 42.4135H146.065Z" fill="#3F3D56"></path>
          <path _ngcontent-ng-c2144836179="" d="M171.757 47.7525C171.405 47.7525 171.062 47.6481 170.769 47.4526C170.477 47.2571 170.249 46.9791 170.114 46.654C169.98 46.3288 169.944 45.971 170.013 45.6258C170.082 45.2806 170.251 44.9635 170.5 44.7146C170.748 44.4657 171.065 44.2962 171.41 44.2276C171.755 44.1589 172.113 44.1941 172.438 44.3288C172.763 44.4635 173.04 44.6916 173.236 44.9843C173.431 45.2769 173.535 45.621 173.535 45.9729C173.535 46.4448 173.347 46.8971 173.014 47.2307C172.681 47.5643 172.229 47.752 171.757 47.7525ZM171.757 44.7866C171.523 44.7866 171.293 44.8561 171.098 44.9865C170.904 45.1169 170.752 45.3021 170.662 45.5189C170.572 45.7357 170.549 45.9743 170.594 46.2044C170.64 46.4345 170.753 46.6459 170.919 46.8118C171.085 46.9778 171.296 47.0907 171.526 47.1365C171.756 47.1823 171.994 47.1588 172.211 47.069C172.427 46.9792 172.613 46.8272 172.743 46.6321C172.873 46.437 172.943 46.2076 172.943 45.9729C172.942 45.6584 172.817 45.3568 172.595 45.1344C172.373 44.912 172.071 44.7869 171.757 44.7866Z" fill="#3F3D56"></path>
          <path _ngcontent-ng-c2144836179="" d="M189.244 45.6758H176.203V46.269H189.244V45.6758Z" fill="#3F3D56"></path>
          <path _ngcontent-ng-c2144836179="" d="M216.067 50.4219V74.1496C209.779 74.1493 203.748 71.6494 199.301 67.1996C194.855 62.7499 192.356 56.7148 192.356 50.4219H216.067Z" fill="#7FC411" class="svgColor"></path>
          <path _ngcontent-ng-c2144836179="" d="M216.364 174.992H143.156V50.125H216.364V174.992ZM143.748 174.399H215.771V50.7182H143.748V174.399Z" fill="#3F3D56"></path>
          <path _ngcontent-ng-c2144836179="" opacity="0.1" d="M198.877 72.6656C203.296 72.6656 206.879 69.0803 206.879 64.6575C206.879 60.2348 203.296 56.6494 198.877 56.6494C194.457 56.6494 190.874 60.2348 190.874 64.6575C190.874 69.0803 194.457 72.6656 198.877 72.6656Z" fill="black"></path>
          <path _ngcontent-ng-c2144836179="" d="M197.691 73.5562C202.111 73.5562 205.694 69.9709 205.694 65.5481C205.694 61.1254 202.111 57.54 197.691 57.54C193.271 57.54 189.689 61.1254 189.689 65.5481C189.689 69.9709 193.271 73.5562 197.691 73.5562Z" fill="#7FC411" class="svgColor"></path>
          <path _ngcontent-ng-c2144836179="" d="M157.679 55.167H146.416V59.3193H157.679V55.167Z" fill="#3F3D56"></path>
          <path _ngcontent-ng-c2144836179="" d="M163.903 94.6143H152.64V98.7666H163.903V94.6143Z" fill="#3F3D56"></path>
          <path _ngcontent-ng-c2144836179="" d="M207.176 110.037H152.64V102.325H207.176V110.037ZM153.233 109.444H206.583V102.918H153.233V109.444Z" fill="#3F3D56"></path>
          <path _ngcontent-ng-c2144836179="" d="M204.212 126.943H150.269V134.062H204.212V126.943Z" fill="white"></path>
          <path _ngcontent-ng-c2144836179="" d="M163.903 117.155H152.64V121.308H163.903V117.155Z" fill="#3F3D56"></path>
          <path _ngcontent-ng-c2144836179="" d="M207.176 132.579H152.64V124.867H207.176V132.579ZM153.233 131.986H206.583V125.46H153.233V131.986Z" fill="#3F3D56"></path>
          <path _ngcontent-ng-c2144836179="" d="M198.58 148.298H161.235V139.993H198.58V148.298ZM161.828 147.705H197.988V140.586H161.828V147.705Z" fill="#3F3D56"></path>
          <path _ngcontent-ng-c2144836179="" d="M205.694 107.071C205.694 107.682 205.505 108.278 205.154 108.777C204.803 109.277 204.307 109.656 203.733 109.862C203.159 110.069 202.535 110.093 201.946 109.932C201.358 109.771 200.833 109.431 200.445 108.961L200.442 108.958C200.158 108.614 199.956 108.21 199.85 107.778C199.744 107.345 199.737 106.893 199.829 106.457C199.921 106.021 200.11 105.612 200.383 105.259C200.655 104.906 201.003 104.619 201.401 104.419C201.799 104.22 202.238 104.113 202.683 104.106C203.128 104.099 203.569 104.193 203.974 104.38C204.378 104.567 204.735 104.843 205.018 105.188C205.3 105.532 205.502 105.935 205.608 106.368C205.608 106.369 205.608 106.369 205.608 106.37C205.608 106.37 205.608 106.37 205.609 106.371C205.609 106.371 205.609 106.371 205.61 106.371C205.61 106.371 205.61 106.371 205.611 106.371C205.665 106.601 205.693 106.836 205.694 107.071Z" fill="#7FC411" class="svgColor"></path>
          <path _ngcontent-ng-c2144836179="" opacity="0.1" d="M205.694 107.072C205.694 107.682 205.505 108.278 205.154 108.777C204.803 109.277 204.307 109.656 203.733 109.862C203.159 110.069 202.535 110.093 201.946 109.932C201.358 109.771 200.833 109.432 200.445 108.961L200.442 108.958C200.387 108.729 200.359 108.494 200.359 108.258C200.359 107.647 200.547 107.052 200.898 106.552C201.249 106.053 201.745 105.674 202.32 105.467C202.894 105.26 203.518 105.236 204.106 105.397C204.695 105.559 205.219 105.898 205.608 106.369C205.608 106.369 205.608 106.369 205.608 106.37C205.608 106.37 205.608 106.37 205.609 106.371C205.609 106.371 205.609 106.371 205.609 106.371C205.61 106.372 205.61 106.372 205.611 106.372C205.665 106.601 205.693 106.836 205.694 107.072Z" fill="black"></path>
          <path _ngcontent-ng-c2144836179="" d="M203.915 111.817C205.552 111.817 206.879 110.489 206.879 108.851C206.879 107.213 205.552 105.885 203.915 105.885C202.278 105.885 200.951 107.213 200.951 108.851C200.951 110.489 202.278 111.817 203.915 111.817Z" fill="#7FC411" class="svgColor"></path>
          <path _ngcontent-ng-c2144836179="" d="M205.694 129.612C205.694 130.223 205.505 130.819 205.154 131.318C204.803 131.818 204.307 132.197 203.733 132.403C203.159 132.61 202.535 132.634 201.946 132.473C201.358 132.312 200.833 131.972 200.445 131.502L200.442 131.499C200.158 131.155 199.956 130.751 199.85 130.319C199.744 129.886 199.737 129.434 199.829 128.998C199.921 128.562 200.11 128.153 200.383 127.8C200.655 127.447 201.003 127.16 201.401 126.961C201.799 126.761 202.238 126.654 202.683 126.647C203.128 126.64 203.569 126.734 203.974 126.921C204.378 127.108 204.735 127.384 205.018 127.729C205.3 128.073 205.502 128.476 205.608 128.91C205.608 128.91 205.608 128.91 205.608 128.911C205.608 128.911 205.608 128.911 205.609 128.912C205.609 128.912 205.609 128.912 205.61 128.912C205.61 128.912 205.61 128.912 205.611 128.912C205.665 129.142 205.693 129.377 205.694 129.612Z" fill="#7FC411" class="svgColor"></path>
          <path _ngcontent-ng-c2144836179="" opacity="0.1" d="M205.694 129.613C205.694 130.223 205.505 130.819 205.154 131.318C204.803 131.818 204.307 132.197 203.733 132.404C203.159 132.61 202.535 132.634 201.946 132.473C201.358 132.312 200.833 131.973 200.445 131.502L200.442 131.499C200.387 131.27 200.359 131.035 200.359 130.799C200.359 130.188 200.547 129.593 200.898 129.093C201.249 128.594 201.745 128.215 202.32 128.008C202.894 127.801 203.518 127.777 204.106 127.938C204.695 128.1 205.219 128.439 205.608 128.91C205.608 128.91 205.608 128.91 205.608 128.911C205.608 128.911 205.608 128.911 205.609 128.912C205.609 128.912 205.609 128.912 205.609 128.912C205.61 128.913 205.61 128.913 205.611 128.913C205.665 129.142 205.693 129.377 205.694 129.613Z" fill="black"></path>
          <path _ngcontent-ng-c2144836179="" d="M203.915 134.358C205.552 134.358 206.879 133.03 206.879 131.392C206.879 129.754 205.552 128.426 203.915 128.426C202.278 128.426 200.951 129.754 200.951 131.392C200.951 133.03 202.278 134.358 203.915 134.358Z" fill="#7FC411" class="svgColor"></path>
          <path _ngcontent-ng-c2144836179="" d="M154.122 14.2363H138.71V29.6593H154.122V14.2363Z" fill="#7FC411" class="svgColor"></path>
          <path _ngcontent-ng-c2144836179="" d="M198.877 25.507C198.173 25.507 197.486 25.2982 196.901 24.9072C196.316 24.5161 195.86 23.9602 195.591 23.3099C195.322 22.6595 195.251 21.9439 195.388 21.2535C195.526 20.5631 195.864 19.9289 196.362 19.4311C196.859 18.9334 197.493 18.5944 198.183 18.4571C198.873 18.3197 199.588 18.3902 200.238 18.6596C200.888 18.929 201.443 19.3852 201.834 19.9705C202.225 20.5558 202.433 21.2439 202.433 21.9478C202.432 22.8915 202.057 23.7961 201.39 24.4634C200.724 25.1306 199.82 25.5059 198.877 25.507ZM198.877 18.9819C198.29 18.9819 197.717 19.1558 197.23 19.4817C196.743 19.8076 196.363 20.2708 196.138 20.8128C195.914 21.3548 195.855 21.9511 195.97 22.5265C196.084 23.1018 196.366 23.6303 196.781 24.0451C197.195 24.4599 197.724 24.7424 198.298 24.8568C198.873 24.9712 199.469 24.9125 200.011 24.688C200.552 24.4635 201.015 24.0834 201.341 23.5956C201.667 23.1079 201.841 22.5344 201.841 21.9478C201.84 21.1615 201.527 20.4076 200.971 19.8516C200.416 19.2955 199.662 18.9828 198.877 18.9819Z" fill="#3F3D56"></path>
          <path _ngcontent-ng-c2144836179="" d="M120.63 75.0387H112.924V67.3271H120.63V75.0387ZM113.517 74.4455H120.037V67.9203H113.517V74.4455Z" fill="#3F3D56"></path>
          <path _ngcontent-ng-c2144836179="" d="M238 132.579H230.294V124.867H238V132.579ZM230.887 131.986H237.407V125.46H230.887V131.986Z" fill="#3F3D56"></path>
          <path _ngcontent-ng-c2144836179="" d="M85.9535 80.8721L87.2759 81.5849L91.7757 84.0118L99.0402 87.9286L103.274 80.0683C103.673 79.3298 103.922 78.5198 104.007 77.6847C104.093 76.8497 104.013 76.0059 103.772 75.2018C103.532 74.3977 103.135 73.6489 102.604 72.9985C102.074 72.348 101.421 71.8085 100.682 71.411C99.1034 70.5578 97.2946 70.2282 95.5166 70.4696C93.7385 70.711 92.0829 71.511 90.7883 72.7543L90.7861 72.7565C90.1492 73.3687 89.6116 74.0767 89.193 74.8548L85.9535 80.8721Z" fill="#2F2E41"></path>
          <path _ngcontent-ng-c2144836179="" d="M105.781 161.033L104.894 170.802L101.565 171.246L99.3465 160.145L105.781 161.033Z" fill="#FFB8B8"></path>
          <path _ngcontent-ng-c2144836179="" d="M100.678 171.247C100.678 171.247 104.894 169.027 105.781 170.803C105.781 170.803 105.115 176.132 107.778 176.798C110.441 177.464 111.55 182.127 107.778 182.571C104.006 183.015 101.565 181.683 100.234 181.683C98.9027 181.683 100.234 177.02 100.234 177.02L100.678 171.247Z" fill="#2F2E41"></path>
          <path _ngcontent-ng-c2144836179="" d="M78.933 161.033L79.8207 170.802L83.1488 171.246L85.3676 160.145L78.933 161.033Z" fill="#FFB8B8"></path>
          <path _ngcontent-ng-c2144836179="" d="M84.0364 171.247C84.0364 171.247 79.8205 169.027 78.933 170.803C78.933 170.803 79.5987 176.132 76.9361 176.798C74.2734 177.464 73.164 182.127 76.9361 182.571C80.7081 183.015 83.1488 181.683 84.4801 181.683C85.8114 181.683 84.4801 177.02 84.4801 177.02L84.0364 171.247Z" fill="#2F2E41"></path>
          <path _ngcontent-ng-c2144836179="" d="M97.1276 82.6526C100.314 82.6526 102.897 80.0679 102.897 76.8795C102.897 73.6911 100.314 71.1064 97.1276 71.1064C93.9414 71.1064 91.3586 73.6911 91.3586 76.8795C91.3586 80.0679 93.9414 82.6526 97.1276 82.6526Z" fill="#FFB8B8"></path>
          <path _ngcontent-ng-c2144836179="" d="M78.7111 88.204L83.3707 95.5313L88.9178 95.0872L98.0151 95.7534C98.0151 95.7534 98.8738 94.0348 99.575 92.3628C99.9589 91.5278 100.254 90.6549 100.456 89.7583C100.678 88.204 97.5714 86.6497 97.5714 86.6497C97.5714 86.6497 97.4493 86.6275 97.2452 86.5786C96.9745 86.5142 96.5573 86.4077 96.1002 86.2522C95.0685 85.9014 93.8281 85.3063 93.5774 84.4293C93.1336 82.875 96.4619 80.6546 96.4619 80.6546L92.468 76.8799C92.468 76.8799 88.9201 81.8114 84.6111 82.9349C84.3612 83.001 84.1079 83.0529 83.8522 83.0903C83.8389 83.0926 83.8278 83.0948 83.8145 83.097C79.1549 83.7631 78.7111 88.204 78.7111 88.204Z" fill="#FFB8B8"></path>
          <path _ngcontent-ng-c2144836179="" d="M81.1519 86.2051L78.7112 88.2034C78.7112 88.2034 76.7142 97.7512 77.3798 100.416C78.0455 103.08 77.1579 105.523 77.1579 105.967C77.1579 106.411 75.161 123.064 75.161 123.064C75.161 123.064 71.167 131.945 75.3829 131.057C79.5987 130.169 78.0455 121.288 78.0455 121.288L83.1489 105.745V92.6443L81.1519 86.2051Z" fill="#FFB8B8"></path>
          <path _ngcontent-ng-c2144836179="" d="M98.6808 87.9814L100.456 89.3137L101.343 107.965L108 123.952C108 123.952 112.881 131.723 109.775 131.946C106.669 132.168 104.672 123.952 104.672 123.952L98.6808 108.631L97.7932 93.7545L98.6808 87.9814Z" fill="#FFB8B8"></path>
          <path _ngcontent-ng-c2144836179="" d="M87.5865 89.3135C87.5865 89.3135 92.0242 92.422 99.1245 91.3118C99.1245 91.3118 101.121 95.3086 100.012 97.529C98.9026 99.7494 101.343 105.078 101.787 105.744C102.231 106.411 107.334 120.177 109.109 135.054C110.884 149.931 115.766 160.144 111.55 161.477C107.334 162.809 98.6807 164.363 97.7932 163.031C96.9057 161.699 95.5744 128.615 95.5744 128.615L93.1336 141.271C93.1336 141.271 96.4619 162.809 94.0212 163.253C91.5804 163.697 76.2704 162.365 76.7141 159.7C77.1579 157.036 81.5956 143.047 81.5956 143.047C81.5956 143.047 81.3737 115.07 85.8114 109.741C85.8114 109.741 87.5865 106.855 85.1458 103.08C82.705 99.3053 82.9269 93.3102 82.9269 93.3102C82.9269 93.3102 84.702 93.3102 87.5865 89.3135Z" fill="#2F2E41"></path>
          <path _ngcontent-ng-c2144836179="" d="M83.8522 83.09L87.5867 91.3122L88.4741 90.8682L84.6109 82.9346L83.8522 83.09Z" fill="#2F2E41"></path>
          <path _ngcontent-ng-c2144836179="" d="M96.1003 86.252L98.9027 92.4225L99.575 92.3625L99.3464 91.3123L97.2452 86.5784C96.9745 86.514 96.5573 86.4074 96.1003 86.252Z" fill="#2F2E41"></path>
          <path _ngcontent-ng-c2144836179="" d="M87.2759 81.5843C87.4892 82.2712 87.9004 82.8798 88.4579 83.3339C89.0153 83.788 89.6943 84.0674 90.4097 84.1371C91.1252 84.2068 91.8452 84.0637 92.4798 83.7257C93.1143 83.3878 93.635 82.8699 93.9768 82.2371C91.17 78.5646 93.5774 76.2576 98.1371 74.5123C98.4806 73.8744 98.6266 73.1487 98.5567 72.4275C98.4868 71.7063 98.204 71.0222 97.7444 70.4623C96.4876 70.3014 95.2107 70.4237 94.0072 70.8202C92.8037 71.2166 91.7039 71.8773 90.7883 72.7538L90.7861 72.756L87.5466 78.7689C87.3159 79.1963 87.1731 79.6656 87.1266 80.1492C87.0801 80.6327 87.1308 81.1207 87.2759 81.5843Z" fill="#2F2E41"></path>
          <path _ngcontent-ng-c2144836179="" d="M93.6884 78.8774C94.1173 78.8774 94.465 78.2809 94.465 77.5451C94.465 76.8093 94.1173 76.2129 93.6884 76.2129C93.2595 76.2129 92.9118 76.8093 92.9118 77.5451C92.9118 78.2809 93.2595 78.8774 93.6884 78.8774Z" fill="#FFB8B8"></path>
          <path _ngcontent-ng-c2144836179="" d="M96.0026 75.3716L102.673 78.3958L103.895 75.6958C103.684 74.7089 103.243 73.786 102.608 73.0024L98.0174 70.9219L96.0026 75.3716Z" fill="#2F2E41"></path>
          <path _ngcontent-ng-c2144836179="" d="M112.035 134.951C113.672 134.951 114.999 133.624 114.999 131.985C114.999 130.347 113.672 129.02 112.035 129.02C110.398 129.02 109.071 130.347 109.071 131.985C109.071 133.624 110.398 134.951 112.035 134.951Z" fill="#7FC411" class="svgColor"></path>
          <path _ngcontent-ng-c2144836179="" d="M16.8813 152.583C17.951 152.583 18.8183 151.715 18.8183 150.644C18.8183 149.574 17.951 148.706 16.8813 148.706C15.8115 148.706 14.9442 149.574 14.9442 150.644C14.9442 151.715 15.8115 152.583 16.8813 152.583Z" fill="#FF6584"></path>
          <path _ngcontent-ng-c2144836179="" d="M26.6676 160.747H26.0748V182.817H26.6676V160.747Z" fill="#3F3D56"></path>
          <path _ngcontent-ng-c2144836179="" d="M26.3713 163.868C28.0937 163.868 29.4901 162.471 29.4901 160.747C29.4901 159.023 28.0937 157.626 26.3713 157.626C24.6488 157.626 23.2524 159.023 23.2524 160.747C23.2524 162.471 24.6488 163.868 26.3713 163.868Z" fill="#3F3D56"></path>
          <path _ngcontent-ng-c2144836179="" d="M26.3712 174.349C26.3712 174.349 25.9257 164.76 16.7919 165.874L26.3712 174.349Z" fill="#3F3D56"></path>
        </svg>
      </div>

      <div>
        <button class="button-forgot-password" onclick="resendEmail()">HANTAR SEMULA E-MEL</button>
      </div>
    </div>
  </main>
  <!-- Main Content ends here! -->

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
            <a href="index.php">
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
          <div>
            <p>Sertai Kami</p>
            <a
              href="signup.php"
              class="a-footer-link">Log Masuk/Daftar</a>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!--Footer ends here!-->

  <script src="data/mobileHeaderLinksDataMenu.js"></script>
  <script src="js/generateMobileHeaderLinksMenu.js"></script>
  <script src="js/forgot-password.js"></script>
</body>

</html>