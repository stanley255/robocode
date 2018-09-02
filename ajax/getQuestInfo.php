<?php
  require '../dbconfig/config.php';
  session_start();
  // kontrola pristupov
  if (empty($_SESSION['privilege']) or $_SESSION['privilege']!=4){
    echo '-1';
  }
  // Ziskanie questov, ktore prisluchaju k danej lekcii
  //header('Content-Type: application/json');
  $id = $_REQUEST["q_id"];
  if ($stmt = mysqli_prepare($con,"SELECT fk_quest_id FROM ROBOCODE.QUESTS_MAP WHERE fk_lesson_id = ? ORDER BY quest_order ASC")){
    if (mysqli_stmt_bind_param($stmt,"i",$id)){
      if (mysqli_stmt_execute($stmt)){
        if (mysqli_stmt_bind_result($stmt,$fk_quest_id)){
          $response = [];
          while (mysqli_stmt_fetch($stmt)){
            $query = "SELECT * FROM ROBOCODE.QUESTS WHERE id = $fk_quest_id";
            $query_run = mysqli_query($con,$query);
            if (mysqli_num_rows($query_run)){
              $row = mysqli_fetch_assoc($query_run);
              $quest = [];
              $quest["id"]          = $row["id"];
              $quest["name"]        = $row["name"];
              $quest["description"] = $row["description"];
              $quest["exp"]         = $row["id"];
              $response[] = $quest;
            }
          }
          foreach ($response as $one){
            echo $one;
          }
        }
      }
    }
  }
?>