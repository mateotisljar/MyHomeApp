<?php
session_start();
ob_start();
include_once 'baza.php';
$baza = new Baza();
$obavijest = "";
$korisnik = "";
if(!isset($_SESSION['email'])){
  header("Location: prijava.php");
}else{
  $korisnik = $_SESSION['email'];
  $upit = "SELECT * FROM users WHERE email = '{$korisnik}'";
  $rezultat = $baza->select($upit);
  $podaci = $rezultat->fetch_array();
  if(empty($podaci['ime']) || empty($podaci['prezime']) || empty($podaci['lozinka']) || empty($podaci['ponovljena_lozinka']) || empty($podaci['telefon']) || empty($podaci['email']) || empty($podaci['datum_rodjenja']) || empty($podaci['slika'])){
    $poruka = "Nisu popunjeni svi Vaši podaci, nadopunite ih na profilu.";
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="author" content="Mateo Tišljar">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/script.js"></script>
    <title>Bookmarks</title>
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
    <div class="sidebar">

      <?php
      $idUser = $_SESSION['id_user'];
      $upit = "select * from user_information_conf where id_user = '{$idUser}'";
      $rezultat = $baza->select($upit);
      while($podaciInf = $rezultat->fetch_array()){
        $idConf = $podaciInf['id_conf'];
       ?>
       <p><?php echo $podaciInf['opis'] . ":"; ?></p>
       <p class="<?php echo $podaciInf['opis']; ?>"></p>
        <script>
        function <?php echo $podaciInf['opis']; ?>() {
            jQuery.ajax({
                url:'update.php',
                type:'GET',
                data:{
                  id_conf:<?php echo $idConf; ?>
                },
                success:function(results) {
                    jQuery(".<?php echo $podaciInf['opis']; ?>").html(results);
                }
            });
        }

        t = setInterval(<?php echo $podaciInf['opis']; ?>,<?php echo $podaciInf['poll_interval'] * 1000; ?>);

      </script>
      <?php } ?>
    </div>


      <div class="index_sadrzaj">
        <div class="new_bookmark_div">
          <a class="new_bookmark" href="all_bookmarks.php">Bookmarks</a>
          <a class="new_bookmark" href="all_informations.php">Pretplate</a>
        </div>
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
      <div class="index_bookmarks">
        <?php
        $korisnik = $_SESSION['email'];
        $upit = "SELECT * from users where email = '{$korisnik}'";
        $rezultat = $baza->select($upit);
        $podaci = mysqli_fetch_array($rezultat);
        $id_korisnika = $podaci['id_user'];
        $upit = "SELECT * FROM bookmarks WHERE id_user = '{$id_korisnika}'";
        $rezultat = $baza->select($upit);
        while($podaciBookmarka = mysqli_fetch_array($rezultat)){
          ?>
          <div id="bookmark_link">
            <a href="<?php echo $podaciBookmarka['url']; ?>" target="_blank">
              <img src="<?php if(empty($podaciBookmarka['slika'])) echo 'img/default_bookmark.png'; else echo $podaciBookmarka['slika']; ?>" alt="Bookmark">
              <p class="bookmark_naziv"><?php echo $podaciBookmarka['naziv']; ?></p>
              <p class="bookmark_opis"><?php echo $podaciBookmarka['opis']; ?></p>
            </a>
          </div>
          <?php
        }

         ?>
         <div class="add_bookmark">
           <a href="bookmarks.php">
             <img src="img/add_bookmark.png" alt="Add bookmark">
           </a>
         </div>
      </div>
    </div>
  </body>
</html>
<?php
ob_end_flush();
?>
