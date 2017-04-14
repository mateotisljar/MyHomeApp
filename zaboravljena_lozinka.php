<?php
session_start();
ob_start();
include_once 'baza.php';
$baza = new Baza();
if(!isset($_SESSION['email'])) header("Location: prijava.php");
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="author" content="Mateo Tišljar">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="js/script.js"></script>
    <title>Pregled bookmarka</title>
  </head>
  <body>
    <header>
    </header>
    <aside>
      <nav>
        <ul>
          <li class="li_pocetni">
            <a class="home" href="index.php">MyHomeApp</a>
          </li>
          <?php
            if(isset($_SESSION['email'])){
              ?>
              <li class="li_odjava">
                <a class="links" href="odjava.php">Odjava</a>
              </li>
              <li class="li_profil">
                <a class="links" href="profil.php">Profil</a>
              </li>
              <?php
            }else{
          ?>
          <li class="li_prijava">
            <a class="links" href="prijava.php">Prijava</a>
          </li>
          <li class="li_registracija">
            <a class="links" href="registracija.php">Registracija</a>
          </li>
          <?php } ?>
        </ul>
      </nav>
    </aside>
    <div class="sadrzaj_zab_lozinka">
      <div class="center_div">
            <form method="POST" id="forma" name="forma" enctype="multipart/form-data"  >
              <p>Unesite email</p>
              <input type="email" placeholder="Email" class="inputi" id="email" size="20" name="email" >
              <input type="submit" name="submit" id="submit" value="Pošalji" class="inputi">
            </form>

      <?php
        $email = "";
        $upit = "";
        $rezultat= "";
        if(isset($_POST['submit'])){
          $email = $_POST['email'];
          $upit = "select * from users where email = '{$email}'";
          $rezultat = $baza->select($upit);
          $uzorak_email = '^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+^';
          if (!preg_match($uzorak_email, $email)) {
              $poruka.="Email mora biti formata: nesto@nesto.nesto. \r\n";
          }
          if(empty($poruka)){
            $aktivacijski_kod = md5($email . time());
            $upit = "UPDATE users set akt_kod = '{$aktivacijski_kod}' where email = '{$email}'";
            $baza->update($upit);
            $primaoc = $email;
            $subject = "Zaboravljena lozinka";
            $message = "Poštovani, <br><br> zaprimili smo zahtjev za promjenom lozinke. <br>Kako bi promijenili lozinku, kopirajte sljedeći "
            ." kod '{$aktivacijski_kod}' bez zagrada i kliknite <a href='https://mateotisljar.000webhostapp.com/MyHomeApp/nova_lozinka.php'>ovdje</a>. <br>"
            . "Ukoliko niste zatražili promjenu lozinke, ignorirajte ovaj email.";
            $headers = "From: " . "MyHomeApp" . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            mail($primaoc, $subject, $message, $headers);
          }
          ?>
          <div class="greske">
            <p><span><?php if(!empty($poruka)) echo $poruka; ?></span></p>
          </div>
          <?php
        }
      ?>
    </div>
    </div>


  </body>
</html>
<?php
ob_end_flush();
?>
