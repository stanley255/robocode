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
  // SELECT QS.fk_user_id FROM quests_solvers QS JOIN quests Q ON QS.fk_quest_id = 4id Q.id WHERE id = QS.
  if ($stmt = mysqli_prepare($con,"SELECT Q.exp, QS.fk_user_id  FROM QUESTS_SOLVERS QS JOIN QUESTS Q ON QS.fk_quest_id = Q.id WHERE QS.id = ?")){
    if (mysqli_stmt_bind_param($stmt,"i",$id)){
      if (mysqli_stmt_execute($stmt)){
        if (mysqli_stmt_bind_result($stmt,$exp,$u_id)){
          if (mysqli_stmt_fetch($stmt)){
            mysqli_stmt_close($stmt);
            if (!is_null($u_id)){
              // Pridaj EXP
              $query = "UPDATE ROBOCODE.USERS SET EXP = EXP + ".$exp." WHERE id = ".$u_id;
              $query_run = mysqli_query($con,$query);
              if (mysqli_affected_rows($con)){
                $response["action"] = 1;
                echo json_encode($response);
              }
            }
          }
        }
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
