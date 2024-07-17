<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Kayıt Ol</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
<?php
require('db.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Composer autoload dosyasını yükle
require 'vendor/autoload.php';

if (isset($_POST['submit'])) {
    $username = stripslashes($_POST['username']);
    $username = mysqli_real_escape_string($con, $username);
    
    $email = stripslashes($_POST['email']);
    $email = mysqli_real_escape_string($con, $email);

    // E-posta adresinin doğru bir formatla girilip girilmediğini kontrol edin
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='form'>
              <h3>Geçerli bir e-posta adresi girin.</h3><br/>
              <p class='link'>Yeniden <a href='registration.php'>kayıt</a> olmayı deneyin.</p>
              </div>";
        exit();
    }

    $password = stripslashes($_POST['password']);
    $password = mysqli_real_escape_string($con, $password);

    // Şifre hashleme
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $create_datetime = date("Y-m-d H:i:s");
    
    // Gizlilik şartlarını kabul etme kontrolü
    if (!isset($_POST['privacy_policy'])) {
        echo "<div class='form'>
              <h3>Gizlilik şartlarını kabul etmelisiniz.</h3><br/>
              <p class='link'>Yeniden <a href='registration.php'>kayıt</a> olmayı deneyin.</p>
              </div>";
        exit();
    }

    // E-posta adresi ve kullanıcı adının daha önce kullanılıp kullanılmadığını kontrol edin
    $check_query = "SELECT * FROM `users` WHERE `email`='$email' OR `username`='$username'";
    $check_result = mysqli_query($con, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        echo "<div class='form'>
              <h3>Belirtilen e-posta veya kullanıcı adı zaten kullanılıyor.</h3><br/>
              <p class='link'>Yeniden <a href='registration.php'>kayıt</a> olmayı deneyin.</p>
              </div>";
        exit();
    }

    // Kullanıcıyı veritabanına ekle
    $query = "INSERT into `users` (username, password, email, create_datetime, email_verified)
              VALUES ('$username', '$hashed_password', '$email', '$create_datetime', 0)";
    $result = mysqli_query($con, $query);

    if ($result) {
        // E-posta doğrulama işlemi
        $verification_code = md5($email . time()); // E-posta doğrulama kodu oluştur
        $verification_query = "UPDATE `users` SET `verification_code`='$verification_code' WHERE `email`='$email'";
        mysqli_query($con, $verification_query); // Veritabanına doğrulama kodunu kaydet

        
        $mail = new PHPMailer(true);
        try {
            // SMTP ayarları
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';   // SMTP sunucu adresi
            $mail->SMTPAuth = true;
            $mail->Username = 'ahmeterdemserceoglo@gmail.com'; // Gmail kullanıcı adı
            $mail->Password = 'yask xqoh gkrc bqrz';   // Gmail şifre
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Alıcı bilgileri
            $mail->setFrom('ahmeterdemserceoglo@gmail.com', 'Gönderen Adı');
            $mail->addAddress($email, $username);  // Yeni kayıtlı kullanıcının e-posta adresi ve adı

            // E-posta içeriği
            $mail->isHTML(true);
            $mail->Subject = 'Hesap Doğrulama';
            $mail->Body    = 'Kaydınızı tamamlamak için aşağıdaki kodu kullanın: ' . $verification_code;
            $mail->addAddress($email, $username);  // Yeni kayıtlı kullanıcının e-posta adresi ve adı
    
            $mail->send();

            // Yönlendirme
            header("Location: verify.php?email=".$email);
            exit();
        } catch (Exception $e) {
            echo "<div class='form'>
                  <h3>Kaydınız başarıyla tamamlandı ancak e-posta gönderilemedi.</h3><br/>
                  <p class='link'>Giriş yapmak için <a href='login.php'>buraya tıklayın</a>.</p>
                  </div>";
        }
    } else {
        echo "<div class='form'>
              <h3>Gerekli alanları doldurun.</h3><br/>
              <p class='link'>Yeniden <a href='registration.php'>kayıt</a> olmayı deneyin.</p>
              </div>";
    }
} else {
?>
    <form class="form" action="" method="post">
        <h1 class="login-title">Kayıt Ol</h1>
        <input type="text" class="login-input" name="username" placeholder="Kullanıcı Adı" required />
        <input type="email" class="login-input" name="email" placeholder="E-posta Adresi" required />
        <input type="password" class="login-input" name="password" placeholder="Şifre" required />
        <label style="color: #ffffff; font-weight: bold;">
            <input type="checkbox" name="privacy_policy" required />
            Gizlilik şartlarını kabul 
            <a target="_blank" href="gizlilik-sartlari.html" style="color: #fff;">ediyorum.</a>
        </label>
        <input type="submit" name="submit" value="Kayıt Ol" class="login-button">
        <p class="link">Zaten hesabınız var mı? <a href="login.php">Giriş Yapın</a></p>
    </form>
<?php
}
?>
</body>
</html>
