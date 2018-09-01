<?php
  require '../dbconfig/config.php';
  session_start();
  // kontrola pristupov
  if (empty($_SESSION['privilege']) or $_SESSION['privilege']!=4){
    echo '-1';
  }


  $id   = $_POST['id'];
  $name = $_POST['name'];
  $desc = $_POST['desc'];
  $valid = $_POST['valid'];
  // Ak je ID==0 / submit_btn == 'Pridat' potom insert / update
  if ($id == 0){
    if ($stmt = mysqli_prepare($con,"INSERT INTO ROBOCODE.LESSONS(name,description,valid) VALUES(?,?,?)")){
      if (mysqli_stmt_bind_param($stmt,"sss",$name,$desc,$valid)){
        if (mysqli_stmt_execute($stmt)){

        }
      }
    }
  } else{

  }
?>
