<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Hesap Doğrulama</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>


<?php
require('db.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Composer autoload dosyasını yükle
require 'vendor/autoload.php';

if (isset($_POST['submit_verification'])) {
    $email = $_POST['email'];
    $verification_code = $_POST['verification_code'];

    // Veritabanından doğrulama kodunu ve e-posta adresini kontrol et
    $check_query = "SELECT * FROM `users` WHERE `email`='$email' AND `verification_code`='$verification_code'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) == 1) {
        // Doğrulama kodu doğruysa kullanıcıyı doğrula
        $update_query = "UPDATE `users` SET `email_verified`=1, `verification_code`=NULL WHERE `email`='$email'";
        mysqli_query($con, $update_query);

        echo "<div class='form'>
              <h3>Hesabınız başarıyla doğrulandı.</h3><br/>
              <p class='link'>Giriş yapmak için <a href='login.php'>buraya tıklayın</a>.</p>
              </div>";
    } else {
        echo "<div class='form'>
              <h3>Geçersiz doğrulama kodu.</h3><br/>
              <p class='link'>Yeniden <a href='verify.php'>doğrulama kodu isteyin</a>.</p>
              </div>";
    }
} else {
    // Doğrulama kodu girilmesi için form
    $email = $_GET['email'] ?? '';
    if (empty($email)) {
        echo "<div class='form'>
              <h3>E-posta adresi bulunamadı.</h3><br/>
              <p class='link'>Yeniden <a href='registration.php'>kayıt</a> olmayı deneyin.</p>
              </div>";
        exit();
    }

    echo "<form class='form' action='' method='post'>
              <h4 class='login-title' style='color: white;'>E-posta hesabınızı gelen kodu aşağıdaki alana girerek doğrulayın! 'Aksi takdirde site kullanıma sunulmayacaktır'</h4>
              <input type='text' class='login-input' name='verification_code' placeholder='Doğrulama Kodu' required />
              <input type='hidden' name='email' value='$email' />
              <input type='submit' name='submit_verification' value='Doğrula' class='login-button'>
          </form>";
}
?>
</body>
</html>
