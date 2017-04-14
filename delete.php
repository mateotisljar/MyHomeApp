<?php
session_start();
ob_start();
include_once 'baza.php';
$baza = new Baza();
if(!isset($_SESSION['email'])){
  header("Location: prijava.php");
}
if(isset($_GET['id_bookmark']) && isset($_SESSION['id_user'])){
  $idBookmark = $_GET['id_bookmark'];
  $idUser = $_SESSION['id_user'];
  $upit = "SELECT * FROM bookmarks WHERE id_bookmark = '{$idBookmark}' and id_user = '{$idUser}'";
  $rezultat = $baza->select($upit);
  if(mysqli_num_rows($rezultat) == 1){
    $upit = "DELETE FROM bookmarks WHERE id_bookmark = '{$idBookmark}' and id_user = '{$idUser}'";
    $baza->update($upit);
    header("Location: all_bookmarks.php?uspjesno=3");
  }else{
    header("Location: all_bookmarks.php?uspjesno=2");
  }

}else if(isset($_GET['id_conf']) && isset($_SESSION['id_user'])){
  $idBookmark = $_GET['id_conf'];
  $idUser = $_SESSION['id_user'];
  $upit = "SELECT * FROM user_information_conf WHERE id_conf = '{$idBookmark}' and id_user = '{$idUser}'";
  $rezultat = $baza->select($upit);
  if(mysqli_num_rows($rezultat) == 1){
    $upit = "DELETE FROM user_information_conf WHERE id_conf = '{$idBookmark}' and id_user = '{$idUser}'";
    $baza->update($upit);
    header("Location: all_informations.php?uspjesno=3");
  }else{
    header("Location: all_informations.php?uspjesno=2");
  }
}


ob_end_flush();
 ?>
