<?php
  require '../dbconfig/config.php';
  session_start();
  // kontrola pristupov
  if (empty($_SESSION['privilege']) or $_SESSION['privilege']!=4){
    echo '-1';
  }
  // Ziskanie questov, ktore prisluchaju k danej lekcii
  //header('Content-Type: application/json');
  $id = $_REQUEST["id"];
  if ($stmt = mysqli_prepare($con,"SELECT * FROM ROBOCODE.QUESTS WHERE id = ?")){
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
  }
?>
