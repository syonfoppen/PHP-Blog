<?php
//vind de Session
session_start();
//eind sessie melden
echo "Tot ziens " . $_SESSION['USER'];
echo "<script>
await sleep(5000);
window.location.href = 'https://syon-tech.nl/blog/';
</script>
";
//verwijder de sessie
session_destroy();
 ?>
