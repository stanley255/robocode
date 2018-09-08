<?php
  include '../includes/links.html';
  include '../includes/navbar.php';
  if (empty($_SESSION['privilege']) or $_SESSION['privilege']==1){
    header('location:../../index.html');
  }
  
?>
<!--<embed src="../lessons/edkniha1.pdf" style="width: 100%;height: 100%;border: none;" /> -->


<?php
  include '../includes/end.html';
?>
