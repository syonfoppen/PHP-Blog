<?php
  session_start();
  $mijnsession = session_id();
  if (isset($_SESSION['ID']) && $_SESSION['ID'] === $mijnsession) {

  }
  else {
    echo "
    <script>
    alert('Je moet eerst inloggen!');
    location.href='index.html';
    </script>

    ";
  }
 ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>blogs</title>
        <link rel="stylesheet" type="text/css" href="mystyle.css">
    </head>
    <body>
      <script>document.addEventListener('contextmenu', event => event.preventDefault());</script>
        <header class="header">BLOG</header>

        <div class="blogs">
          <table border="0" cellspacing="20" class="table1">
            <?php
            $bestand=fopen("gebruikers.txt", "r");
            $bestand2=fopen("blogs.txt", "r");
            $fotos = array();

            if (!$bestand)
            {
              echo "Kon geen bestand openen!";
            }
            $user = array();
            $blogpost = array();

            while(!feof($bestand))
            {
              $acount = fgets($bestand);
              $acount = str_replace("\n", '', $acount);
              $crypted_token = $acount;
              if (isset($acount[0])){
                list($crypted_token, $enc_iv) = explode("::", $crypted_token);
                $cipher_method = 'aes-256-ctr';
                $enc_key = openssl_digest(php_uname(), 'SHA256', TRUE);
                $token = openssl_decrypt($crypted_token, $cipher_method, $enc_key, 0, hex2bin($enc_iv));
                unset($crypted_token, $cipher_method, $enc_key, $enc_iv);
                $acount = $token;
                $acount = explode("*", $acount);
            }

              array_push($user, array(isset($acount[0]) ? $acount[0] : null, isset($acount[1]) ? $acount[1] : null, isset($acount[3]) ? $acount[3] : null));
            }
            while(!feof($bestand2)){
              $post = fgets($bestand2);
              $post = str_replace("\n", '', $post);

              $crypted_token = $post;
              if (isset($post[0])){
                list($crypted_token, $enc_iv) = explode("::", $crypted_token);
                $cipher_method = 'aes-256-ctr';
                $enc_key = openssl_digest(php_uname(), 'SHA256', TRUE);
                $token = openssl_decrypt($crypted_token, $cipher_method, $enc_key, 0, hex2bin($enc_iv));
                unset($crypted_token, $cipher_method, $enc_key, $enc_iv);
                $post = $token;
                $post = explode("*", $post);
              }
              array_push($blogpost, array(isset($post[0]) ? $post[0] : null, isset($post[1]) ? $post[1] : null,isset($post[2]) ? $post[2] : null));
            }
            $blog_length  = count($blogpost);
            $acount_length = count($user);
            for ($i = 0; $i < $blog_length - 1; $i++) {
              for ($b = 0; $b < $acount_length - 1; $b++) {
                if ($blogpost[$i][0] == $user[$b][1]) {

                  $foto = $user[$b][2];
                  $naam = $user[$b][0];
                  $datum = $blogpost[$i][2];
                  $text = $blogpost[$i][1];
                  $text = wordwrap($text, 50, "<br />\n");
                  echo"
                  <tr>
                    <td>
                      <div class='Foto'>";
                      if (isset($blogpost[$i][1]))
                      {
                        echo"<h3>$naam - $datum </h3><br><img src='uploads/$foto'>";
                        echo"
                      </div>
                    </td>
                    <td>
                      <div class='text'>
                      <p>
                        $text
                        </p>
                      </div>
                    </td>
                  </tr>";
                  }
                }
              }
            }

            ?>
            </table>
        </div>
        <form action="post_blog.php" name="post_blogs" method="POST">
            <textarea class="textinput" name="blogpost"></textarea>
            <br>
            <input type="submit" class="submit" name="post">
            <a href="uitloggen.php"><input type="button" name="Back" value=" uitloggen " class="uitloggen"/></a>
        </form>
    </body>
</html>
