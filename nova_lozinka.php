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
    <div class="sadrzaj_nova_lozinka">
      <div class="center_div">
          <form method="POST" id="forma" name="forma" enctype="multipart/form-data"  >
            <p>Unesite podatke za novu lozinku</p>
            <input type="email" placeholder="Email" class="inputi" id="email" size="20" name="email">

            <input type="password" class="inputi" name="password" id="password" placeholder="Lozinka">
            <input type="password" class="inputi" name="password2" id="password2" placeholder="Ponovljena lozinka">
            <input type="text" class="inputi" name="kod" id="kod" placeholder="Kod">

            <input type="submit" name="submit" id="submit" value="Prijavi se" class="inputi">

          </form>

      <?php
        $email = "";
        $lozinka = "";
        $ponovljena_lozinka = "";
        $kod = "";
        $upit = "";
        $rezultat= "";
        $poruka = "";
        if(isset($_POST['submit'])){
          $email = $_POST['email'];
          $lozinka = $_POST['password'];
          $ponovljena_lozinka = $_POST['password2'];
          $kod = $_POST['kod'];
          $upit = "select * from users where email = '{$email}' and akt_kod = '{$kod}'";
          $rezultat = $baza->select($upit);
          if(mysqli_num_rows($rezultat) == 1){
            $podaci = mysqli_fetch_array($rezultat);
            $staraLozinka = $podaci['lozinka'];
            if(strpos($podaci['stare_lozinke'], $lozinka) || $podaci['lozinka'] == $lozinka){
              $poruka.= "Ova je lozinka već bila korištena, postavite nekorištenu lozinku.";
            }
          }else{
            $poruka .= "Netočan kod ili zahtjev za promjenom lozinke nije podnijet.";
          }
          $uzorak_email = '^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+^';
          if (!preg_match($uzorak_email, $email)) {
              $poruka.="Email mora biti formata: nesto@nesto.nesto. \r\n";
          }
          if(empty($poruka)){
            $upit = "UPDATE users set lozinka = '{$lozinka}' , ponovljena_lozinka = '{$ponovljena_lozinka}', stare_lozinke = CONCAT(stare_lozinke, ' {$staraLozinka}') where email = '{$email}'";
            if(!$baza->update($upit)){
              $poruka .= "Promjena lozinke nije uspjela";
            }
          }
          ?>
          <div class="greske">
            <p><span><?php echo $poruka; ?></span></p>
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
