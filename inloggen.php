<?php
$email = htmlspecialchars($_POST['e-mail']);
$wachtwoord = htmlspecialchars($_POST['wachtwoord']);
$bestand=fopen("gebruikers.txt", "r");
if (!$bestand)
{
  echo "Kon geen bestand openen!";
}
while(!feof($bestand))
{
  $account = fgets($bestand);
  $account = str_replace("\n", '', $account);
  $crypted_token = $account;
    list($crypted_token, $enc_iv) = explode("::", $crypted_token);;
    $cipher_method = 'aes-256-ctr';
    $enc_key = openssl_digest(php_uname(), 'SHA256', TRUE);
    $token = openssl_decrypt($crypted_token, $cipher_method, $enc_key, 0, hex2bin($enc_iv));
    unset($crypted_token, $cipher_method, $enc_key, $enc_iv);

    $account = $token;

  $account = explode("*", $account);

  if ($account[1] == $email && $account[2] == $wachtwoord) {
    session_start();
    $_SESSION["USER"] = $email;
    $_SESSION["STATUS"] = 1;
    $_SESSION["ID"] = $_COOKIE["PHPSESSID"];
    echo "
    <script>
    alert('U bent ingelogd als $email.');
    location.href='blog.php';
    </script>
    ";
  }
}
echo "
<script>
alert('Wachtwoord of gebruikersnaam ongeldig.');
location.href='index.html';
</script>
";


 ?>
