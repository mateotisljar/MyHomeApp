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
    <title>Prijava</title>
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
    <div class="sadrzaj_prijava">
      <div>
        <fieldset>
          <legend>Prijava</legend>
            <form method="POST" id="forma" name="forma" enctype="multipart/form-data"  >
              <label class="labele" for="email">Email adresa: </label>
              <input type="email" class="inputi" id="email" size="20" name="email" value="<?php
                if(isset($_COOKIE['email'])) echo $_COOKIE['email'];
              ?>"><br>
              <span class="nevidljivo"></span>

              <label class="labele" for="password">Lozinka: </label>
              <input type="password" class="inputi" name="password" id="password" placeholder="Lozinka" value="<?php
                  if(isset($_COOKIE['lozinka'])) echo $_COOKIE['lozinka'];
              ?>"><br>
              <span class="nevidljivo"></span>

              <label for="checkbox" class="labele">Zapamti me?</label>
              <input type="checkbox" id="checkbox" name="checkbox" class="inputi">

              <input type="submit" name="submit" id="submit" value="Prijavi se" class="inputi">
              <a href="zaboravljena_lozinka.php">Zaboravljena lozinka?</a>

            </form>

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
            if(isset($_POST['checkbox'])){
              setcookie('email', $prijavljeni['email'], time() + (86400 * 3));
              setcookie('lozinka', $prijavljeni['lozinka'], time() + (86400 * 3));
            }
            header("Location: index.php");
          }else{
            $poruka .= "Neispravan email i lozinka. Ponovite. \r\n";
            ?>
            <div class="greske">
              <p><span><?php echo $poruka; ?></span></p>
            </div>
            <?php
          }

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
