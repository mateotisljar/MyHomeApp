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
    <div class="sadrzaj_pregled_bookmarka">
      <div class="new_bookmark_div">
        <a class="new_bookmark" href="informations.php">Dodaj</a>
      </div>
      <?php
      if(isset($_GET['uspjesno']) && $_GET['uspjesno'] ==1){
        ?>
        <div class="uspjeh">
          <p>
            <?php
            echo "Uspješno ste dodali novu pretplatu.";
             ?>
          </p>
        </div>
        <?php
      }else if(isset($_GET['uspjesno']) && $_GET['uspjesno'] ==2){
        $poruka = "Netočan id pretplate koju želite obrisati.";
      }else if(isset($_GET['uspjesno']) && $_GET['uspjesno'] ==3){
        ?>
        <div class="uspjeh">
          <p>
            <?php
            echo "Uspješno ste prekinuli pretplatu.";
             ?>
          </p>
        </div>
        <?php
      }
       ?>
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
      <div>
        <?php
        $user_id = $_SESSION['id_user'];
        $upit = "SELECT * FROM user_information_conf where id_user = '{$user_id}' order by redni_broj asc";
        $rezultat = $baza->select($upit);
        if($rezultat->num_rows == 0){
          echo "<div class='bookmark_error'>"
          . "<p>Niste se pretplatili ni na jednu informaciju, pretplatite se klikom na gumb.</p>"
          . "</div>";
        }else{
          ?>
          <div class="all_bookmarks_div">
          <div class="bookmark_header">
            <p class="b_header_redni_broj">Redni broj</p>
            <p class="b_header_opis">Opis</p>
          </div><br>
          <?php
          while($podaciBookmarka = $rezultat->fetch_array()){
            $redniBroj = $podaciBookmarka['redni_broj'];
            $opis = $podaciBookmarka['opis'];
            $id = $podaciBookmarka['id_conf'];
            echo "<div class='bookmark_row'>"
            . "<p class='b_redni_broj'>"
            . $redniBroj
            . "</p>"
            . "<p class='b_opis'>"
            . $opis
            . "</p>"
            . "<a class='x' href='delete.php?id_conf={$id}'>&#10006;</a>"
            . "</div>";
          }
        }

        ?>

        </div>
      </div>
    </div>


  </body>
</html>
<?php
ob_end_flush();
?>
