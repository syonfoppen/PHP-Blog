<?php
if(isset($_POST["post"]))
{
  $bestand = fopen("blogs.txt", "ab");
  if(!$bestand)
  {
    echo "Kon geen bestand openen!";
  }
  session_start();
  $email = htmlspecialchars($_SESSION["USER"]);
  $text = htmlspecialchars($_POST["blogpost"]);
  if (strlen($text) <= 500){
    $datum = date("d/m/Y - H:i");
    $text = str_replace(array("\n"), '<br>', $text);
    $blogpost = $email . "*" . $text . "*". $datum;

    $token = $blogpost;

    $cipher_method = 'aes-256-ctr';
    $enc_key = openssl_digest(php_uname(), 'SHA256', TRUE);
    $enc_iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher_method));
    $crypted_token = openssl_encrypt($token, $cipher_method, $enc_key, 0, $enc_iv) . "::" . bin2hex($enc_iv);
    unset($token, $cipher_method, $enc_key, $enc_iv);

    $result = $crypted_token . "\n";
    fwrite($bestand, $result,strlen($result));

    echo "
    <script>
    location.href='blog.php';
    </script>
    ";
  }
  else {
    echo"<script>alert('De tekst is te lang!');
    location.href='blog.php';
    </script>";
  }
}

 ?>
