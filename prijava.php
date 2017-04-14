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
      $poruka = "";
      $lozinka ="";
      $email = "";
      $upit = "";
      $rezultat= "";
      if(isset($_POST['submit'])){
        $lozinka = $_POST['password'];
        $email = $_POST['email'];

        $upit = "select * from users where email = '{$email}' and lozinka = '{$lozinka}'";
        $rezultat = $baza->select($upit);
        if(mysqli_num_rows($rezultat) == 1){
          $prijavljeni = mysqli_fetch_array($rezultat);
          $_SESSION['email'] = $prijavljeni['email'];
          $_SESSION['id_user'] = $prijavljeni['id_user'];
          if(isset($_POST['checkbox'])){
            setcookie('email', $prijavljeni['email'], time() + (86400 * 3));
            setcookie('lozinka', $prijavljeni['lozinka'], time() + (86400 * 3));
          }
          header("Location: index.php");
        }else{
          $poruka .= "Neispravan email i lozinka. Ponovite. \r\n";
          ?>

          <?php
        }

      }
    ?>
    <div class="sadrzaj_prijava">
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
      <div class="center_div">
            <form method="POST" id="forma" name="forma" enctype="multipart/form-data"  >
              <p>Unesite podatke za prijavu</p>
              <input type="email" placeholder="Email" class="inputi" id="email" size="20" name="email" value="<?php
                if(isset($_COOKIE['email'])) echo $_COOKIE['email'];
              ?>">

              <input type="password" class="inputi" name="password" id="password" placeholder="Lozinka" value="<?php
                  if(isset($_COOKIE['lozinka'])) echo $_COOKIE['lozinka'];
              ?>">
              <label>Zapamti me?</label>
              <input type="checkbox" id="checkbox" name="checkbox" >

              <input type="submit" name="submit" id="submit" value="Prijava" class="inputi">
              <a class="zab_loz" href="zaboravljena_lozinka.php">Zaboravljena lozinka?</a>

            </form>


    </div>
    </div>


  </body>
</html>
<?php
ob_end_flush();
?>
