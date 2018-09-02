<?php
  require '../dbconfig/config.php';
  session_start();
  // kontrola pristupov
  if (empty($_SESSION['privilege']) or $_SESSION['privilege']!=4){
    echo '-1';
  }
  //
  $id = $_REQUEST["id"];
  if ($stmt = mysqli_prepare($con,"SELECT name,description,valid FROM ROBOCODE.LESSONS WHERE id = ?")){
    if (mysqli_stmt_bind_param($stmt,"i",$id)){
      if (mysqli_stmt_execute($stmt)){
        if (mysqli_stmt_bind_result($stmt,$response_obj['name'],$response_obj['description'],$response_obj['valid'])){
          if (mysqli_stmt_fetch($stmt)){
            mysqli_stmt_close($stmt);
            header('Content-Type: application/json');
            echo json_encode($response_obj);
          }
        }
      }
    }
  }
?>
