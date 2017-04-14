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
      include_once 'baza.php';
      $baza = new Baza();
      $poruka = "";
      if(isset($_POST['submit'])){
        $lozinka = $_POST['password'];
        $email = $_POST['email'];

        $upit = "select * from users where email = '{$email}'";
        $rezultat = $baza->select($upit);
        if($rezultat->num_rows == 1){
          $poruka= "Ovaj email je već zauzet. <br>";
        }
        $uzorak = '([a-zA-Z].*[0-9]|[0-9].*[a-zA-Z])';
        if (!preg_match($uzorak, $lozinka)) {
            $poruka="Lozinka mora sadržavati slova i brojeve.<br>";
        }
        if (strlen($lozinka) < 7) {
            $poruka="Lozinka mora biti dulja od 7 znakova.<br>";
        }
        $uzorak_email = '^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+^';
        if (!preg_match($uzorak_email, $email)) {
            $poruka="Email mora biti formata: nesto@nesto.nesto. <br>";
        }
        if(empty($poruka)){
          $upit= "INSERT into users (id_user,lozinka, email) VALUES (default, '{$lozinka}', '{$email}')";
          if($baza->update($upit)){
            ?>
            <div class="uspjeh">
              <p>
                <?php
                echo "Uspješna registracija, preusmjeravanje za "; ?><span id="counter">3</span> sekunde.
              </p>
            </div>
            <script type="text/javascript">
            function countdown() {
                var i = document.getElementById('counter');
                if (parseInt(i.innerHTML) <= 1) {
                    location.href = 'prijava.php';
                }
                i.innerHTML = parseInt(i.innerHTML)-1;
            }
            setInterval(function(){ countdown(); },1000);
            </script>
            <?php
          }else{
            $poruka= "Neuspješna registracija. Provjerite unesene podatke.\r\n";
          }
        }
      }
    ?>
    <div class="sadrzaj_registracija">
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
      <div class="center_div registracija_div">
            <form method="POST" id="forma" name="forma" enctype="multipart/form-data"  >
              <p>Unesite podatke za registraciju</p>
              <input type="email" class="inputi" id="email" size="20" placeholder="Email"name="email">

              <input type="password" class="inputi" name="password" id="password" placeholder="Lozinka">

              <input type="submit" name="submit" id="submit" value="Registracija" >

            </form>
    </div>
    </div>


  </body>
</html>
<?php
ob_end_flush();
?>
