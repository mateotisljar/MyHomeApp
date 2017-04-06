<?php
session_start();
ob_start();
include_once 'baza.php';
$baza = new Baza();

if(!isset($_SESSION['email'])){
  header("Location: prijava.php");
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="author" content="Mateo Tišljar">
    <title>Bookmarks</title>
  </head>
  <body>
    <header>
      <?php
        if(isset($_SESSION['email'])){
          ?>
          <a href="odjava.php">Odjava</a>
          <?php
        }
      ?>
    </header>
  </body>
</html>
<?php
ob_end_flush();
?>
