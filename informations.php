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

    if(isset($_POST['submit'])){
      $opis = $_POST['opis'];
      $idInformation = $_POST['informacija'];
      $poredak = $_POST['redni_broj'];
      $interval = $_POST['interval'];
      if(empty($interval)){
        $upit = "select * from informations where id_information = '{$idInformation}'";
        $rezultat = $baza->select($upit);
        $podaci = $rezultat->fetch_array();
        $interval = $podaci['poll_interval'];
      }
      $broj = "";
      $upit = "SELECT * FROM informations";
      $rezultat = $baza->select($upit);
      $podaci = $rezultat->fetch_array();
      $idUser = $_SESSION['id_user'];
      if(is_numeric($poredak) && $poredak>0){
        if(isset($_SESSION['email'])){
          $upit = "SELECT * FROM user_information_conf where redni_broj >= '{$poredak}'";
          $rezultat = $baza->select($upit);
          if(mysqli_num_rows($rezultat) == 0){
            $upit= "SELECT COUNT(*) FROM user_information_conf";
            $rezultat = $baza->select($upit);
            $lista = $rezultat->fetch_array();
            $broj = $lista[0] +1;

          }else{
              $tocno = true;
          }
          if(!empty($broj)) $poredak = $broj;
              if(empty($opis)){
                $upit = "select * from informations where id_information = '{$idInformation}'";
                $rezultat = $baza->select($upit);
                $podaciInf = $rezultat->fetch_array();
                $opis = $podaciInf['naziv'];
              }
                $upit = "INSERT INTO user_information_conf (id_conf, id_user, id_information, opis, redni_broj, poll_interval) VALUES (default, '{$idUser}', '{$idInformation}', '{$opis}', '{$poredak}', '{$interval}')";
                $baza->update($upit);
        }
      }else{
        $poruka = "Redni broj mora biti cjelobrojna vrijednost veća od 0!";
      }

    }
     ?>
    <div class="sadrzaj_informations">
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
            <p>Unesite podatke pretplate</p>
            <label>Informacija</label>
            <select name="informacija" class="inputi">
              <?php
              $upit = "SELECT * FROM informations";
              $rezultat = $baza->select($upit);
              while($podaci = $rezultat->fetch_array()){
              ?>
              <option value="<?php echo $podaci['id_information']; ?>"><?php echo $podaci['naziv']; ?></option>
            <?php } ?>
          </select><br><br>
          <label>Opis</label>
          <input type="text" name="opis" class="inputi"><br><br>

          <label>Interval</label>
          <input type="text" name="interval" class="inputi"><br><br>

          <label>Redni broj</label>
          <input type="text" class="inputi" name="redni_broj">
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
