<?php
if(isset($_POST["submit"]))
{
  $fotoNaam = basename($_FILES["foto"]["name"]);
  global $uploadsMap;

  function upload()
  {
    global $uploadsMap;
    $uploadsMap = "uploads/";
    $uploadsMap = $uploadsMap . basename($_FILES["foto"]["name"]);
    $fotoType = pathinfo($uploadsMap,PATHINFO_EXTENSION);
    //controleer of deze foto al bestaat
    if (file_exists($uploadsMap))
    {
      echo "Deze foto bestaat al.";
      return false;
    }
    //valideer formaat
    if($fotoType != "jpg" && $fotoType != "png" && $fotoType != "jpeg" && $fotoType != "gif")
    {
      echo "Foto moet jpg, jpeg, png of gif zijn";
      return false;
    }
    return true;
  }
  //Verplaats foto van temp-map naar $uploadsMap
  if (upload())
  {
    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $uploadsMap))
    {
      echo "foto is geupload.";
      //gebruiker opslaan
      $bestand = fopen("gebruikers.txt", "ab");
      if(!$bestand)
      {
        echo "Kon geen bestand openen!";
      }
      $naam = htmlspecialchars($_POST['naam']);
      $email = htmlspecialchars($_POST['e-mail']);
      $wachtwoord = htmlspecialchars($_POST['password']);
      $profielFoto = $fotoNaam;
      $profiel = $naam . "*" . $email . "*" . $wachtwoord . "*" . $profielFoto;

      $token = $profiel;
      $cipher_method = 'aes-256-ctr';
      $enc_key = openssl_digest(php_uname(), 'SHA256', TRUE);
      $enc_iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher_method));
      $crypted_token = openssl_encrypt($token, $cipher_method, $enc_key, 0, $enc_iv) . "::" . bin2hex($enc_iv);
      unset($token, $cipher_method, $enc_key, $enc_iv);

      $result = $crypted_token . "\n";
      fwrite($bestand, $result,strlen($result));

      if (fclose($bestand))
      {
        echo "Account is aangemaakt.";
      }
      else
      {
        echo "kon bestand niet afsluiten.";
      }

    }
    else {
      echo "probleem bij het uploaden. Foto is niet geupload.";
    }
  }

}
 ?>
 <a href="index.html"><input type="button" name="Back" value=" Back " /></a>
