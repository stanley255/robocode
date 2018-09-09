<?php
  require '../dbconfig/config.php';
  session_start();
  // kontrola pristupov
  if (empty($_SESSION['privilege']) or $_SESSION['privilege']!=4){
    echo '-1';
  }

  $id = $_REQUEST['id'];

  header('Content-Type: application/json');
  $response['action'] = -1;
  // Získanie všetkých questov, ktoré prislúchajú k lekcii
    if ($stmt = mysqli_prepare($con,"SELECT fk_quest_id FROM ROBOCODE.QUESTS_MAP WHERE fk_lesson_id = ?")){
      if (mysqli_stmt_bind_param($stmt,"i",$id)){
        if (mysqli_stmt_execute($stmt)){
          if (mysqli_stmt_bind_result($stmt,$q_id)){
            $a_quests = [];
            while (mysqli_stmt_fetch($stmt)){
              $a_quests[] = $q_id;
            }
            mysqli_stmt_close($stmt);
            // Zmazanie všetkých questov z QUESTS_MAP (ak nejaké existujú)
            if (!empty($a_quests)){
              if ($stmt = mysqli_prepare($con,"DELETE FROM ROBOCODE.QUESTS_MAP WHERE fk_lesson_id = ?")){
                if (mysqli_stmt_bind_param($stmt,"i",$id)){
                  if (mysqli_stmt_execute($stmt)){
                    mysqli_stmt_close($stmt);
                    // Zmazanie údajov z QUESTS_SOLVERS pre danú lekciu (fk_lesson_id)
                    $query = "DELETE FROM ROBOCODE.QUESTS_SOLVERS WHERE fk_lesson_id = ".$id;
                    $query_run = mysqli_query($con,$query);
                    if (mysqli_affected_rows($con)>=0){
                      // Zmazanie samotných questov
                      $query = 'DELETE FROM ROBOCODE.QUESTS WHERE id IN ('.implode(",",$a_quests).')';
                      $query_run = mysqli_query($con,$query);
                      if (mysqli_affected_rows($con)){
                        $response['action'] = 1;
                      }
                    } else{
                      $response['action'] = -1;
                    }
                  }
                }
              }
            } else{
              $response['action'] = 1;
            }
            // V tomto bode lekcia nemá questy -> VYMAŽ LEKCIU
            if ($stmt = mysqli_prepare($con,"DELETE FROM ROBOCODE.LESSONS WHERE id = ?")){
              if (mysqli_stmt_bind_param($stmt,"i",$id)){
                if (mysqli_stmt_execute($stmt)){
                  echo json_encode($response);
                }
              }
            }
          }
        }
      }
    } else{
      echo json_encode($response);
    }
?>
