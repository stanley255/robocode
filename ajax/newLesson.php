<?php
  require '../dbconfig/config.php';
  session_start();
  // kontrola pristupov
  if (empty($_SESSION['privilege']) or $_SESSION['privilege']!=4){
    echo '-1';
  }

  $id    = $_REQUEST['id'];
  $name  = $_REQUEST['name'];
  $desc  = $_REQUEST['desc'];
  $valid = $_REQUEST['valid'];
/*******************************\
* RESPONSE                      *
*  - action: 1 = INSERT         *
*            2 = UPDATE         *
*           -1 = FAIL           *
*                               *
*  - id: case insert -> new id  *
*        case update -> prev id *
*                               *
\*******************************/
header('Content-Type: application/json');
$response['action'] = -1;
  // Ak je ID==0 / submit_btn == 'Pridat' potom insert / update
  if ($id == 0){
    if ($stmt = mysqli_prepare($con,"INSERT INTO ROBOCODE.LESSONS(name,description,valid) VALUES(?,?,?)")){
      if (mysqli_stmt_bind_param($stmt,"sss",$name,$desc,$valid)){
        if (mysqli_stmt_execute($stmt)){
          mysqli_stmt_close($stmt);
          $response['action'] = 1;
          // Ziskanie noveho IDcka
          $query = "SELECT MAX(id) AS l_id FROM ROBOCODE.LESSONS";
          $query_run = mysqli_query($con,$query);
          if (mysqli_num_rows($query_run)){
            $row = mysqli_fetch_assoc($query_run);
            $response["id"] = $row["l_id"];
            echo json_encode($response);
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
    if ($stmt = mysqli_prepare($con,"UPDATE ROBOCODE.LESSONS SET name=?, description=?, valid=? WHERE id=?")){
      if (mysqli_stmt_bind_param($stmt,"sssi",$name,$desc,$valid,$id)){
        if (mysqli_stmt_execute($stmt)){
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
