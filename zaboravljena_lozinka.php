<?php
session_start();
ob_start();
include_once 'baza.php';
$baza = new Baza();
if(isset($_SESSION['email'])){
  header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="author" content="Mateo Tišljar">
    <title>Zaboravljena lozinka</title>
  </head>
  <body>
    <aside>
      <nav>
        <ul>
          <li>
            <a>Početna stranica</a>
          </li>
          <li>
            <a>Prijava</a>
          </li>
          <li>
            <a>Registracija</a>
          </li>
        </ul>
      </nav>
    </aside>
    <div class="sadrzaj_zab_lozinka">
      <div>
        <fieldset>
          <legend>Zaboravljena lozinka</legend>
            <form method="POST" id="forma" name="forma" enctype="multipart/form-data"  >
              <label class="labele" for="email">Email adresa: </label>
              <input type="email" class="inputi" id="email" size="20" name="email" ><br>
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
      </fieldset>
    </div>
    </div>


  </body>
</html>
<?php
ob_end_flush();
?>
