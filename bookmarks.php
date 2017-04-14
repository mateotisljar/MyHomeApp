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
    <?php
    $tocno = false;
    if(isset($_SESSION['email'])){
      $korisnik = $_SESSION['email'];
      $upit ="SELECT id_user FROM users WHERE email = '{$korisnik}'";
      $rezultat = $baza->select($upit);
      $podaciKorisnika = $rezultat->fetch_array();
      $id=$podaciKorisnika['id_user'];
    }
    if(isset($_GET['id_bookmark']) && isset($_SESSION['email'])){
      $id_bookmark = $_GET['id_bookmark'];
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
                  $upit = "UPDATE bookmarks SET slika='{$linkslika}' WHERE id_user = '{$id}'";
                  $baza->update($upit);
              }
          }
      }
      $upit = "SELECT * from bookmarks where id_bookmark = '{$id_bookmark}' and id_user = '{$id}'";
      $rezultat = $baza->select($upit);
      if(mysqli_num_rows($rezultat) == 1){
        $podaci = $rezultat->fetch_array();
      }else{
        header("Location: bookmarks.php?uspjesno=0");
      }
    }
    if(isset($_GET['uspjesno']) && $_GET['uspjesno']== 0){
      $poruka = "Nepoznati bookmark, odaberite drugu vrijednost";
    }
    if(isset($_POST['submit'])){
      $naziv = $_POST['naziv'];
      $url = $_POST['url'];
      $opis = $_POST['opis'];
      $poredak = $_POST['poredak'];
      $broj = "";
      if(is_numeric($poredak) && $poredak>0){
        if(isset($_GET['id_bookmark']) && isset($_SESSION['email'])){
          $id_bookmark = $_GET['id_bookmark'];
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
                      $upit = "UPDATE bookmarks SET slika='{$linkslika}' WHERE id_user = '{$id}'";
                      $baza->update($upit);
                  }
              }
          }
          $upit = "UPDATE bookmarks set naziv = '{$naziv}', url = '{$url}', opis = '{$opis}', redni_broj = '{$poredak}' where id_bookmark = '{$id_bookmark}'";
          $baza->update($upit);
          }
        else if(!isset($_GET['id_bookmark']) && isset($_SESSION['email'])){
          $upit = "SELECT * FROM bookmarks where redni_broj >= '{$poredak}'";

          $rezultat = $baza->select($upit);
          if(mysqli_num_rows($rezultat) == 0){
            $upit= "SELECT COUNT(*) FROM bookmarks";
            $rezultat = $baza->select($upit);
            $lista = $rezultat->fetch_array();
            $broj = $lista[0] +1;

          }else{
              $tocno = true;
          }
          if(!empty($broj)) $poredak = $broj;
            if(filter_var($url, FILTER_VALIDATE_URL)){
              if(empty($naziv) || empty($opis)){
                $poruka = "Naziv i opis moraju biti popunjeni.";
              }else{
                if($tocno == true){
                  $upit = "UPDATE bookmarks SET redni_broj = redni_broj + 1 WHERE redni_broj >= '{$poredak}' and id_user = '{$id}'";
                  $baza->update($upit);
                }
                $upit = "INSERT INTO bookmarks (id_bookmark, naziv, opis, url, redni_broj, id_user) VALUES (default, '{$naziv}', '{$opis}', '{$url}', '{$poredak}', '{$id}')";
                if($baza->update($upit)){
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
                              $upit = "UPDATE bookmarks SET slika='{$linkslika}' WHERE id_user = '{$id}'";
                              $baza->update($upit);
                          }
                      }
                  }
                  header("Location: all_bookmarks.php?uspjesno=1");
                }
              }
            }else{
              $poruka = "URL nije točnog formata.";
            }
        }else{
          header("Location: prijava.php");

        }
      }else{
        $poruka = "Redni broj mora biti cjelobrojna vrijednost veća od 0!";
      }

    }
    ?>
    <div class="sadrzaj_bookmarks">
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
      <div class="center_div bookmarks_div">
        <div>

          <form method="POST" id="forma" name="forma" enctype="multipart/form-data"  >
            <p>Podaci bookmarka</p>
            <?php
            if(isset($_GET['id_bookmark'])){
              ?>
              <img class="bookmark_img" alt="slika" src="<?php if(!empty($podaci['slika'])) echo $podaci['slika']; else echo "img/person.png";?>" class="slika_progila" ><br><br>

            <?php
            }
            ?>
            <input type="text" placeholder="Naziv" id="naziv" class="inputi" name="naziv" value="<?php if(!empty($podaci)) echo $podaci['naziv'];?>">

            <input type="text" placeholder="URL" id="url" class="inputi" name="url" value="<?php if(!empty($podaci)) echo $podaci['url'];?>">

            <input id="opis" placeholder="Opis" class="inputi" name="opis" value="<?php if(!empty($podaci)) echo $podaci['opis'];?>">

            <input type="text" placeholder="Redni broj" id="poredak" class="inputi" name="poredak" value="<?php if(!empty($podaci)) echo $podaci['redni_broj'];?>"><br><br>

            <label for="slika" class="labele">Slika</label><br>

            <input name="slika[]"  type="file" multiple="multiple"/><br><br>
            <input type="submit" name="submit" id="submit" value="Spremi" ><br><br>
          </form>
        </div>


      </div>
    </div>


  </body>
</html>
<?php
ob_end_flush();
?>
