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
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <title>Profil</title>
  </head>
  <body>
    <header>
    </header>
    <?php
      $poruka = "";
      $id = $_SESSION['id_user'];
      $upit = "SELECT * FROM users WHERE id_user = '{$id}'";
      $rezultat = $baza->select($upit);
      $podaci = mysqli_fetch_array($rezultat);
      if(isset($_POST['submit'])){
        $ime = $_POST['ime'];
        $prezime = $_POST['prezime'];
        $lozinka = $_POST['password'];
        $lozinkaPonovljena = $_POST['password2'];
        $datumRodjenja = $_POST['datum_rodjenja'];
        $telefon = $_POST['mobilni_telefon'];
        $uzorak = '/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/';
        if (!preg_match($uzorak, $lozinka)) {
            $poruka="Lozinka mora sadržavati slova i brojeve.\r\n";
        }
        if (strlen($lozinka) < 7) {
            $poruka="Lozinka mora biti dulja od 7 znakova.\r\n";
        }
        if($lozinka != $lozinkaPonovljena){
          $poruka= "Lozinke nisu jednake.";
        }
        if(empty($poruka)){
          $ekstenzije = array("gif", "jpeg", "jpg", "png");
          for ($i = 0; isset($_FILES['slika']['name'][$i]); $i++) {
              $target_dir = "img/";
              $target_file = $target_dir . basename($_FILES["slika"]["name"][$i]);
              $temp = explode(".", $_FILES["slika"]["name"][$i]);
              $extension = end($temp);

              if ((($_FILES["slika"]["type"][$i] == "image/gif") || ($_FILES["slika"]["type"][$i] == "image/jpeg") || ($_FILES["slika"]["type"][$i] == "image/jpg") || ($_FILES["slika"]["type"][$i] == "image/pjpeg") || ($_FILES["slika"]["type"][$i] == "image/x-png") || ($_FILES["slika"]["type"][$i] == "image/png")) && ($_FILES["slika"]["size"][$i] < 20000000) && in_array($extension, $ekstenzije)
              ) {
                  if ($_FILES["slika"]["error"][$i] > 0) {
                      echo "Return Code: " . $_FILES["slika"]["error"][$i] . "<br>";
                  } else {
                      move_uploaded_file($_FILES["slika"]["tmp_name"][$i], $target_file);
                      $linkslika = "img/" . $_FILES["slika"]["name"][$i];
                      $korisnik = $_SESSION['email'];
                      $upit = "UPDATE users SET slika='{$linkslika}' WHERE email = '{$korisnik}'";
                      $baza->update($upit);
                  }
              }
          }
          $upit= "UPDATE users set ime = '{$ime}', prezime = '{$prezime}', lozinka = '{$lozinka}', ponovljena_lozinka = '{$lozinkaPonovljena}', telefon = '{$telefon}', datum_rodjenja = '{$datumRodjenja}' WHERE id_user = '{$id}'";
          if($baza->update($upit)){
            header("Location: profil.php");
          }else{
            $poruka= "Neuspješno ažuriranje. Provjerite unesene podatke.";
          }
        }
      }
    ?>
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
    <div class="sadrzaj_profil">
      <?php if(!empty($poruka)){
        ?>
        <div class="greske">
          <p>
        <?php echo $poruka;
        ?>
         </p>
        </div>
        <?php
        }
      ?>
      <div class="center_div profil_div">
        <form method="POST" id="forma" name="forma" enctype="multipart/form-data"  >

          <img alt="slika" src="<?php if(!empty($podaci['slika'])) echo $podaci['slika']; else echo "img/person.png";?>" class="slika_profila" ><br><br>

          <label class="labele" for="ime">Ime: </label>
          <input type="text" value="<?php if(!empty($podaci['ime'])) echo $podaci['ime'];?>" class="inputi" id="ime" name="ime" placeholder="Ime" size="20" ><br><span class="nevidljivo"></span><br><br>

          <label class="labele" for="prezime">Prezime: </label>
          <input type="text" value="<?php if(!empty($podaci['prezime'])) echo $podaci['prezime'];?>" class="inputi" id="prezime" name="prezime" placeholder="Prezime" size="20" ><br><span class="nevidljivo"></span><br><br>

          <label class="labele" for="password">Lozinka: </label>
          <input type="password" value="<?php if(!empty($podaci['lozinka'])) echo $podaci['lozinka'];?>" class="inputi" name="password" id="password" placeholder="Lozinka"><br><span class="nevidljivo"></span><br><br>

          <label class="labele" for="password2">Ponovno upišite lozinku: </label>
          <input type="password" value="<?php if(!empty($podaci['ponovljena_lozinka'])) echo $podaci['ponovljena_lozinka'];?>" class="inputi" name="password2" id="password2" placeholder="Lozinka"><br><span class="nevidljivo"></span><br><br>

          <label class="labele" for="datum_rodjenja" >Datum rođenja: </label>
          <input type="date" class="inputi" value="<?php if(!empty($podaci['datum_rodjenja'])) echo $podaci['datum_rodjenja'];?>" id="datum_rodjenja" size="21" name="datum_rodjenja"><br><span class="nevidljivo"></span><br><br>

          <label class="labele" for="mobilni_telefon">Mobilni telefon: </label>
          <input type="tel" value="<?php if(!empty($podaci['telefon'])) echo $podaci['telefon'];?>" class="inputi" id="mobilni_telefon" size="20" name="mobilni_telefon"><br><span class="nevidljivo"></span><br><br>

          <label class="labele" for="email">Email adresa: </label>
          <input type="email" value="<?php if(!empty($podaci['email'])) echo $podaci['email'];?>" class="inputi" id="email" size="20" name="email" disabled="disabled"><br><span class="nevidljivo"></span><br><br>

          <label for="slika" class="labele">Slika:</label>

          <input name="slika[]" class="slika_input" type="file" multiple="multiple"/><br><br>
          <input type="submit" name="submit" id="submit" value="Ažuriraj" ><br><br>


        </form>
      </div>
    </div>


  </body>
</html>

<?php
ob_end_flush();
?>
