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
  // Zistenie user id a počet EXP za lekciu TODO
  // SELECT QS.fk_user_id FROM quests_solvers QS JOIN quests Q ON QS.fk_quest_id = Q.id WHERE id = 
  if ($stmt = mysqli_prepare($con,"SELECT ")){
    if (mysqli_stmt_bind_param($stmt,"i",$id)){
      if (mysqli_stmt_execute($stmt)){
        /*mysqli_stmt_close($stmt);
        $response["action"] = 1;
        echo json_encode($response);*/
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
