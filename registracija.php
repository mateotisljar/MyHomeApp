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
    <title>Registracija</title>
  </head>
  <body>
    <header>
      <?php
        if(isset($_SESSION())){
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
    <div class="sadrzaj_registracija">
      <div>
        <fieldset>
          <legend>Registracija</legend>
            <form method="POST" id="forma" name="forma" enctype="multipart/form-data"  >
              <label class="labele" for="email">Email adresa: </label>
              <input type="email" class="inputi" id="email" size="20" name="email"><br><span class="nevidljivo"></span><br><br>

              <label class="labele" for="password">Lozinka: </label>
              <input type="password" class="inputi" name="password" id="password" placeholder="Lozinka"><br><span class="nevidljivo"></span><br><br>

              <input type="submit" name="submit" id="submit" value="Registriraj me" ><br><br>

            </form>

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
            $poruka.= "Ovaj email je već zauzet. \r\n";
          }
          $uzorak = '/[A-Z]+[a-z]+[0-9]+/';
          if (!preg_match($uzorak, $lozinka)) {
              $poruka.="Lozinka mora sadržavati slova i brojeve.\r\n";
          }
          if (strlen($lozinka) < 7) {
              $poruka.="Lozinka mora biti dulja od 7 znakova.\r\n";
          }
          $uzorak_email = '^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+^';
          if (!preg_match($uzorak_email, $email)) {
              $poruka.="Email mora biti formata: nesto@nesto.nesto. \r\n";
          }
          if(empty($poruka)){
            $upit= "INSERT into users (id_user,lozinka, email) VALUES (default, '{$lozinka}', '{$email}')";
            if($baza->update($upit)){
              ?>
              <p>Preusmjeravanje za <span id="counter">3</span> sekunde.</p>
              <script type="text/javascript">
              function countdown() {
                  var i = document.getElementById('counter');
                  if (parseInt(i.innerHTML) < 0) {
                      location.href = 'prijava.php';
                  }
                  i.innerHTML = parseInt(i.innerHTML)-1;
              }
              setInterval(function(){ countdown(); },1000);
              </script>
              <?php
            }else{
              $poruka .= "Neuspješna registracija. Provjerite unesene podatke.\r\n";
            }
          }else{
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
