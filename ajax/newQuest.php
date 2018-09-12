<?php
  require '../dbconfig/config.php';
  session_start();
  // kontrola pristupov
  if (empty($_SESSION['privilege']) or $_SESSION['privilege']!=4){
    echo '-1';
  }

  $q_id    = $_REQUEST['q_id'];
  $l_id    = $_REQUEST['l_id'];
  $name  = $_REQUEST['name'];
  $desc  = $_REQUEST['desc'];
  $exp = $_REQUEST['exp'];
/***********************\
* RESPONSE              *
*  - action: 1 = INSERT *
*            2 = UPDATE *
*           -1 = FAIL   *
\***********************/
header('Content-Type: application/json');
$response['action'] = -1;
  // Ak je ID==0 / submit_btn == 'Pridat' potom insert / update
  if ($q_id == 0){
    if ($stmt = mysqli_prepare($con,"INSERT INTO ROBOCODE.QUESTS(name,description,exp) VALUES(?,?,?)")){
      if (mysqli_stmt_bind_param($stmt,"ssi",$name,$desc,$exp)){
        if (mysqli_stmt_execute($stmt)){
          // Ziskanie q_id nového questu
          $response['id'] = mysqli_insert_id($con);
          $q_id = $response['id'];
          mysqli_stmt_close($stmt);
          $response['action'] = 1;
          // Keď bol úspešne pridaný -> zmapuj ho
          if ($stmt = mysqli_prepare($con,"INSERT INTO ROBOCODE.QUESTS_MAP(fk_lesson_id,fk_quest_id) VALUES(?,?)")){
            if (mysqli_stmt_bind_param($stmt,"ii",$l_id,$q_id)){
              if (mysqli_stmt_execute($stmt)){
                mysqli_stmt_close($stmt);
                echo json_encode($response);
              } else{
                $response['action'] = -1;
                echo json_encode($response);
              }
            } else{
              $response['action'] = -1;
              echo json_encode($response);
            }
          } else{
            $response['action'] = -1;
            echo json_encode($response);
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
  } else{
    if ($stmt = mysqli_prepare($con,"UPDATE ROBOCODE.QUESTS SET name=?, description=?, exp=? WHERE id=?")){
      if (mysqli_stmt_bind_param($stmt,"ssii",$name,$desc,$exp,$q_id)){
        if (mysqli_stmt_execute($stmt)){
          mysqli_stmt_close($stmt);
          $response['action'] = 2;
          echo json_encode($response);
        }
      } else{
        echo json_encode($response);
      }
    } else{
      echo json_encode($response);
    }
  }
?>
