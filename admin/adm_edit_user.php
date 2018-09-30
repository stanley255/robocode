<?php
require '../dbconfig/config.php';
include '../includes/links.html';
include '../includes/navbar.php';
  // Pristupy
  if (empty($_SESSION['privilege']) or $_SESSION['privilege']!=4){
    header('location:../index.html');
  }
  echo '<br>';
  // FORM
  echo '<form action="adm_edit_user.php" method="post">';
  // TABULKA
  echo '<div class="table-responsive col-md-11 container pagination">';
  echo '<table class="table table-bordered">';
    // HEADING
  echo '  <tr>';
  echo '    <th>ID</th>';
  echo '    <th>Username</th>';
  /*echo '    <th width="150px">Meno</th>';
  echo '    <th width="170px">Priezvisko</th>';
  echo '    <th>E-mail</th>';*/
  echo '    <th width="180px">Team</th>';
  echo '    <th width="90px">EXP</th>';
  echo '    <th width="150px">Právo</th>';
  echo '    <th>Úprava</th>';
  echo '  </tr>';
  // Získanie teamov
  $i           = 0;
  $a_team_id   = array();
  $a_team_name = array();
  $query       = "SELECT * FROM ROBOCODE.teams";
  $query_run   = mysqli_query($con,$query);
  if (mysqli_num_rows($query_run)){
    while($row = mysqli_fetch_assoc($query_run)){
      $a_team_id[$i++] = $row['id'];
      $a_team_name[$row['id']] = $row['name'];
    }
  } else{
    echo '<script>alert("Neboli nájdené žiadne teamy!");</script>';
  }
  // Získanie privilégií
  $a_privilege_id = array();
  $a_privilege_name = array();
  $query = "SELECT * FROM ROBOCODE.PRIVILEGES WHERE valid = 'Y' ORDER BY ID ASC";
  $query_run = mysqli_query($con,$query);
  if (mysqli_num_rows($query_run)){
    while($row = mysqli_fetch_assoc($query_run)){
      $a_privilege_id[$i++] = $row['id'];
      $a_privilege_name[$row['id']] = $row['name'];
    }
  } else{
    echo '<script>alert("Neboli nájdené žiadne privilégiá!");</script>';
  }
  // Výpis jednotlivých používateľov
  $query = "SELECT * FROM ROBOCODE.USERS ORDER BY ID ASC";
  $query_run = mysqli_query($con,$query);
  if (mysqli_num_rows($query_run)){
    while($row = mysqli_fetch_assoc($query_run)){
      echo '<tr>';
      // ID (nemozno upraviť)
      echo '<td>'.$row['id'].'</td>';
      // Username
      echo '<td><input type="text" class="form-control" id="" name="username'.$row['id'].'" value="'.$row['username'].'" required></td>';
      // Meno
      /*echo '<td><input type="text" class="form-control" id="" name="name'.$row['id'].'" value="'.$row['name'].'" required></td>';
      // Priezvisko
      echo '<td><input type="text" class="form-control" id="" name="surname'.$row['id'].'" value="'.$row['surname'].'" required></td>';
      // E-mail
      echo '<td><input type="text" class="form-control" id="" name="email'.$row['id'].'" value="'.$row['email'].'"></td>';*/
      // Team-y
      echo '<td>';
      echo '  <select class="form-control" name="team'.$row["id"].'">';
      foreach ($a_team_id as $index){
        if ($row['team_id'] == $index){
          echo '<option value='.$index.' selected>'.$a_team_name[$index].'</option>';
        } else {
          echo '<option value='.$index.'>'.$a_team_name[$index].'</option>';
        }
      }
      echo '  </select>';
      echo '</td>';
      // EXP
      echo '<td><input type="text" class="form-control" id="" name="exp'.$row['id'].'" value="'.$row['exp'].'" required></td>';
      // Práva
      echo '<td>';
      echo '  <select class="form-control" name="privilege'.$row["id"].'">';
      foreach ($a_privilege_id as $index){
        if ($row['privilege'] == $index){
          echo '<option value='.$index.' selected>'.$a_privilege_name[$index].'</option>';
        } else {
          echo '<option value='.$index.'>'.$a_privilege_name[$index].'</option>';
        }
      }
      echo '  </select>';
      echo '</td>';
      // Úprava
      echo '<td><button class="btn btn-outline-info my-2 my-sm-0" type="submit" name="submit_btn" value="'.$row["id"].'">Uprav</button></td>';
      echo '</tr>';
    }
  } else{
    echo '<script>alert("Žiadny používatelia neboli nájdení!");</script>';
  }
  // KONIEC FORMU
  echo '</form>';
  // KONIEC TABULKY
  echo '</table>';
  echo '</div>';
  include '../includes/end.html';

  if (isset($_POST["submit_btn"])){
    // Kvôli refreshu po update-e
    echo "<meta http-equiv='refresh' content='0'>";
    // Ziskanie dat z formu
    $id         = $_POST["submit_btn"];
    $username   = $_POST["username$id"];
    /*$name       = $_POST["name$id"];
    $surname    = $_POST["surname$id"];
    $email      = $_POST["email$id"];*/
    $team       = $_POST["team$id"];
    $exp        = $_POST["exp$id"];
    $privilege  = $_POST["privilege$id"];
    // Kedže e-mail je nepovinný, treba overiť, či je definovaný
    /*if (empty($email)){
      $email='';
    }*/
    // Update udajov v databaze
    if ($stmt = mysqli_prepare($con,"UPDATE ROBOCODE.USERS SET username = ?, /*name = ?,surname = ?,email = ?,*/team_id = ?,exp = ?,privilege = ? WHERE id = ?")){
      if (mysqli_stmt_bind_param($stmt,"siiii"/*"ssssiiii"*/,$username,/*$name,$surname,$email,*/$team,$exp,$privilege,$id)){
        if (mysqli_stmt_execute($stmt)){
          // Údaje sa podarilo zmeniť
          mysqli_stmt_close($stmt);
          echo '<script>alert("Údaje sa podarilo zmeniť!"</script>';
        } else{
          echo '<script>alert("Užívateľove informácie sa nepodarilo upraviť!");</script>';
        }
      } else{
        echo '<script>alert("Užívateľove informácie sa nepodarilo upraviť!");</script>';
      }
    } else{
      echo '<script>alert("Užívateľove informácie sa nepodarilo upraviť!");</script>';
    }
  }
?>
