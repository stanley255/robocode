<?php
  require 'dbconfig/config.php';
  include 'includes/links.html';
  include 'includes/navbar.php';
  // Pristupy
  if (empty($_SESSION['privilege']) or $_SESSION['privilege']==1){
    header('location:index.php');
  }

  // ? Zobrazenie pinnutych oznamov ?
    // Ziskanie pinnutych zaznamov z databazy
  //if ()
    // Vypisanie zaznamov

  // Zobrazenie prispevkov DESC na zaklade datumu (prvych 10)
    // Ziskanie zaznamov z databazy

    // Vypisanie zaznamov

?>


<?php
  include 'includes/end.html';
?>
