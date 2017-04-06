<?php
session_start();
ob_start();
include_once 'baza.php';
$baza = new Baza();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="author" content="Mateo Tišljar">
    <title>Profil</title>
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
    <?php
      
    ?>
    <div class="sadrzaj_registracija">
      <div>
        <form method="POST" id="forma" name="forma" enctype="multipart/form-data"  >
          <label class="labele" for="ime">Ime: </label>
          <input type="text" class="inputi" id="ime" name="ime" placeholder="Ime" size="20" ><br><span class="nevidljivo"></span><br><br>

          <label class="labele" for="prezime">Prezime: </label>
          <input type="text" class="inputi" id="prezime" name="prezime" placeholder="Prezime" size="20" ><br><span class="nevidljivo"></span><br><br>

          <label class="labele" for="password">Lozinka: </label>
          <input type="password" class="inputi" name="password" id="password" placeholder="Lozinka"><br><span class="nevidljivo"></span><br><br>

          <label class="labele" for="password2">Ponovno upišite lozinku: </label>
          <input type="password" class="inputi" name="password2" id="password2" placeholder="Lozinka"><br><span class="nevidljivo"></span><br><br>

          <label class="labele" for="datum_rodjenja" >Datum rođenja: </label>
          <input type="date" id="datum_rodjenja" size="21" name="datum_rodjenja"><br><br>

          <label class="labele" for="mobilni_telefon">Mobilni telefon: </label>
          <input type="tel" class="inputi" id="mobilni_telefon" size="20" name="mobilni_telefon"><br><span class="nevidljivo"></span><br><br>

          <label class="labele" for="email">Email adresa: </label>
          <input type="email" class="inputi" id="email" size="20" name="email" disabled="disabled"><br><span class="nevidljivo"></span><br><br>

          <label for="slika" class="labele">Slika</label>
          <input name="slika[]" class="inputi" type="file" multiple="multiple"/><br><br>

          <input type="submit" name="submit" id="submit" value="Ažuriraj profil" ><br><br>

          <article id="greske">
          </article>
        </form>
      </div>
    </div>


  </body>
</html>
<?php
  include_once 'baza.php';
  $baza = new Baza();
  $poruka = "";
  if(isset($_POST['submit'])){
    $ime = $_POST['ime'];
    $prezime = $_POST['prezime'];
    $lozinka = $_POST['password'];
    $lozinkaPonovljena = $_POST['password2'];
    $datumRodjenja = $_POST['datum_rodjenja'];
    $telefon = $_POST['mobilni_telefon'];

    $upit = "select * from users where email = '{$email}'";
    $rezultat = $baza->select($upit);
    if($rezultat->num_rows == 1){
      $poruka.= "Ovaj email je već zauzet.";
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
                  $upit = "UPDATE users SET slika='{$linkslika}' WHERE korisnicko_ime = '{$korisnicko_ime}'";
                  $baza->update($upit);
              }
          }
      }
      $upit= "INSERT into users (id_user, ime, prezime, lozinka, ponovljena_lozinka, telefon, email, datum_rodjenja) "
      . "VALUES (default, '{$ime}', '{$prezime}', '{$lozinka}', '{$lozinkaPonovljena}', '{$telefon}', '{$email}', '{$datumRodjenja}')";
      if($baza->update($upit)){
        header("Location: prijava.php");
      }else{
        $poruka .= "Neuspješna registracija. Provjerite unesene podatke.";
      }
    }
  }
?>
<?php
ob_end_flush();
?>
