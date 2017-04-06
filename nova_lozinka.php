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
    <title>Nova lozinka</title>
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
    <div class="sadrzaj_nova_lozinka">
      <div>
        <fieldset>
          <legend>Nova lozinka</legend>
          <form method="POST" id="forma" name="forma" enctype="multipart/form-data"  >
            <label class="labele" for="email">Email adresa: </label>
            <input type="email" class="inputi" id="email" size="20" name="email"><br>
            <span class="nevidljivo"></span>

            <label class="labele" for="password">Lozinka: </label>
            <input type="password" class="inputi" name="password" id="password" placeholder="Lozinka"><br>
            <span class="nevidljivo"></span>

            <label class="labele" for="password">Ponovi lozinka: </label>
            <input type="password" class="inputi" name="password2" id="password2" placeholder="Lozinka"><br>
            <span class="nevidljivo"></span>

            <label class="labele" for="kod">Kod: </label>
            <input type="text" class="inputi" name="kod" id="kod" placeholder="Kod"><br>
            <span class="nevidljivo"></span>

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
      </fieldset>
    </div>
    </div>


  </body>
</html>
<?php
ob_end_flush();
?>
