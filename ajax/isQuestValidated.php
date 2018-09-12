<?php
  require '../dbconfig/config.php';
  session_start();
  // kontrola pristupov
  if (empty($_SESSION['privilege']) or $_SESSION['privilege']!=4){
    echo '-1';
  }

  header('Content-Type: application/json');
  $q_id = $_REQUEST["q_id"];
  $l_id = $_REQUEST["l_id"];
  // Ziskanie recent quest-u
  if ($stmt = mysqli_prepare($con,"SELECT status FROM ROBOCODE.QUESTS_SOLVERS WHERE fk_lesson_id = ? AND fk_quest_id = ? AND fk_user_id = ?")){
    if (mysqli_stmt_bind_param($stmt,"iii",$l_id,$q_id,$_SESSION["user_id"])){
      if (mysqli_stmt_execute($stmt)){
        if (mysqli_stmt_bind_result($stmt,$q_status)){
          if (mysqli_stmt_fetch($stmt)){
            if ($q_status == "P" OR $q_status == "Y"){
              //$response["status"] = $q_status;
              $response["status"] = $q_status;
              mysqli_stmt_close($stmt);
            } else{
              mysqli_stmt_close($stmt);
              if ($stmt = mysqli_prepare($con,"UPDATE ROBOCODE.QUESTS_SOLVERS SET status = 'P' WHERE fk_lesson_id = ? AND fk_quest_id = ? AND fk_user_id = ?")){
                if (mysqli_stmt_bind_param($stmt,"iii",$l_id,$q_id,$_SESSION["user_id"])){
                  if (mysqli_stmt_execute($stmt)){
                    $response["status"] = "P";
                    mysqli_stmt_close($stmt);
                  }
                }
              }
            }
          }
          if (empty($response)){
            $response["status"] = "X";
          }
          echo json_encode($response);
        }
      }
    }
  }
?>
