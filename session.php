<?php
  session_start();
  if (empty($_SESSION['privilege'])){
    echo 'PRIVILEGE: ';
  } else{
    echo 'PRIVILEGE: '.$_SESSION['privilege'];
  }
  if (empty($_SESSION['username'])){
    echo 'USERNAME: ';
  } else{
    echo 'USERNAME: '.$_SESSION['username'];
  }
?>
