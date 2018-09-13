<?php
  require '../dbconfig/config.php';
  session_start();
  // kontrola pristupov
  if (empty($_SESSION['privilege']) or $_SESSION['privilege']!=4){
    echo '-1';
  }

  //header('Content-Type: application/json');
  $l_id = $_REQUEST["l_id"];
  // Ziskanie recent quest-u

  if ($stmt = mysqli_prepare($con,"SELECT fk_quest_id FROM ROBOCODE.QUESTS_SOLVERS WHERE fk_lesson_id = ? AND fk_user_id = ".$_SESSION["user_id"]." AND fk_quest_id = (SELECT MIN(fk_quest_id) FROM ROBOCODE.QUESTS_SOLVERS WHERE fk_lesson_id = ? AND fk_user_id = ".$_SESSION["user_id"]." AND status = 'N')")){
    if (mysqli_stmt_bind_param($stmt,"ii",$l_id,$l_id)){
      if (mysqli_stmt_execute($stmt)){
        if (mysqli_stmt_bind_result($stmt,$q_id)){
          if (mysqli_stmt_fetch($stmt)){
            mysqli_stmt_close($stmt);
            if (is_null($q_id)){
              // Lekcia je dokoncena
              $response["status"] = "Y";
              echo json_encode($response);
            } else{
              // Ziskane aktualneho quest id
              $response["status"] = "N";
              // Ziskanie quest info
              $query = "SELECT * FROM ROBOCODE.QUESTS WHERE id = ".$q_id;
              $query_run = mysqli_query($con,$query);
              if (mysqli_num_rows($query_run)){
                if ($row = mysqli_fetch_assoc($query_run)){
                  $response["id"]          = $row["id"];
                  $response["name"]        = $row["name"];
                  $response["description"] = $row["description"];
                  $response["exp"]         = $row["exp"];
                  echo json_encode($response);
                }
              }
            }
          } else{
            $response["status"] = "Y";
            echo json_encode($response);
          }
        }
      }
    }
  }

  /*if ($stmt = mysqli_prepare($con,"SELECT * FROM ROBOCODE.QUESTS WHERE id = ?")){
    if (mysqli_stmt_bind_param($stmt,"i",$id)){
      if (mysqli_stmt_execute($stmt)){
        if (mysqli_stmt_bind_result($stmt,$q_id,$q_name,$q_description,$q_exp)){
          if (mysqli_stmt_fetch($stmt)){
            $response["id"]          = $q_id;
            $response["name"]        = $q_name;
            $response["description"] = $q_description;
            $response["exp"]         = $q_exp;
          }
          mysqli_stmt_close($stmt);
          if (empty($response)){
            $response = -1;
          }
          echo json_encode($response);
        }
      }
    }
  }*/
?>
