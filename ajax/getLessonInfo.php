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
        if (mysqli_stmt_bind_result($stmt,$ret_obj['name'],$ret_obj['description'],$ret_obj['valid'])){
          if (mysqli_stmt_fetch($stmt)){
            mysqli_stmt_close($stmt);
            echo json_encode($ret_obj);
          }
        }
      }
    }
  }
?>
