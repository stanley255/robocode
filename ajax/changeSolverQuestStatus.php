<?php
  require '../dbconfig/config.php';
  session_start();
  // kontrola pristupov
  if (empty($_SESSION['privilege']) or $_SESSION['privilege']!=4){
    echo '-1';
  }

  header('Content-Type: application/json');
  $id = $_REQUEST["id"];
  $response["action"] = -1;
  if ($stmt = mysqli_prepare($con,"UPDATE ROBOCODE.QUESTS_SOLVERS SET status = 'Y' WHERE id = ?")){
    if (mysqli_stmt_bind_param($stmt,"i",$id)){
      if (mysqli_stmt_execute($stmt)){
        mysqli_stmt_close($stmt);
        $response["action"] = 1;
        echo json_encode($response);
      } else{
        echo json_encode($response);
      }
    } else{
      echo json_encode($response);
    }
  } else{
    echo json_encode($response);
  }
?>
