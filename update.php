<?php
session_start();
ob_start();
include_once 'baza.php';
$baza = new Baza();
if(!isset($_SESSION['email'])) header("Location: prijava.php");
$idUser = $_SESSION['id_user'];
$idConf = $_GET['id_conf'];
$upit = "select * from user_information_conf where id_user = '{$idUser}' and id_conf = '{$idConf}'";
$rezultat = $baza->select($upit);

$vremenskaPrognozaArray = ["Suncano", "Kisa", "Pljuskovi", "Maglovito", "Snijeg"];
$vremenskaPrognoza = array_rand($vremenskaPrognozaArray, 2);

$euro = mt_rand(7.4, 7.6) / 1000;
$usd = mt_rand(7.0, 7.3) / 1000;
$gbp = mt_rand(8.4, 8.8) / 1000;

$currentTime = time() + 3600;
if ($currentTime > strtotime('08:00:00')) {
    $temp = rand(8,12);
}else if ($currentTime > strtotime('12:00:00')) {
    $temp = rand(12,18);
}else if ($currentTime > strtotime('18:00:00')) {
    $temp = rand(18,20);
}else if ($currentTime > strtotime('00:00:00')) {
    $temp = rand(10,15);
}else {
  $temp = rand(8,12);
}

$datum = $today = date("m-d-Y");
$vrijeme = $today = date("H:i:s");
while ($podaci = $rezultat->fetch_array()) {
  if($podaci['id_information'] == 1){
    $vrijednost = $vremenskaPrognozaArray[$vremenskaPrognoza[0]];
  }else if($podaci['id_information'] == 2){
    /*if($podaci['parametar1'] == "Varaždin"){
      $vrijednost = $temp;
    }else if($podaci['parametar1'] == "Osijek"){
      $vrijednost = $temp -2;
    }else if($podaci['parametar1'] == "Zagreb"){
      $vrijednost = $temp -1;
    }else if($podaci['parametar1'] == "Split"){
      $vrijednost = $temp +3;
    }else{
      $vrijednost = $temp;
    }*/
  }else if($podaci['id_information'] == 3){

  }else if($podaci['id_information'] == 4){

  }else if($podaci['id_information'] == 5){

  }else if($podaci['id_information'] == 6){
    $vrijednost = $vrijeme;
  }else{

  }
  $upit = "update user_information_conf set vrijednost = '{$vrijednost}' where id_conf = '{$idConf}' ";
  $baza->update($upit);
  echo $vrijednost;
}

?>
<?php
ob_end_flush();
?>
