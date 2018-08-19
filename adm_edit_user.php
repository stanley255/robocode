<?php
  require 'dbconfig/config.php';
  include 'includes/links.html';
  include 'includes/navbar.php';
  // Pristupy
  if (empty($_SESSION['privilege']) or $_SESSION['privilege']!=4){
    header('location:index.php');
  }
  echo '<br>';
  // FORM
  echo '<form action="adm_edit_user.php" method="post">';
  // TABULKA
  echo '<div class="table-responsive col-md-12 container pagination">';
  echo '<table class="table table-bordered">';
    // HEADING
  echo '  <tr>';
  echo '    <th>ID</th>';
  echo '    <th>Username</th>';
  echo '    <th>Meno</th>';
  echo '    <th>Priezvisko</th>';
  echo '    <th>E-mail</th>';
  echo '    <th>Team</th>';
  echo '    <th>EXP</th>';
  echo '    <th>Právo</th>';
  echo '    <th>Úprava</th>';
  echo '  </tr>';
  // Získanie teamov
  $i = 0;
  $a_team_id = array();
  $a_team_name = array();
  $query = "SELECT * FROM ROBOCODE.teams";
  $query_run = mysqli_query($con,$query);
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
      // ID (nemozno upraviť)
      echo '<td>'.$row['id'].'</td>';
      // Username
      echo '<td><input type="text" class="form-control" id="" name="username" value="'.$row['username'].'" required></td>';
      // Meno
      echo '<td><input type="text" class="form-control" id="" name="" value="'.$row['name'].'" required></td>';
      // Priezvisko
      echo '<td><input type="text" class="form-control" id="" name="" value="'.$row['surname'].'" required></td>';
      // E-mail
      echo '<td><input type="text" class="form-control" id="" name="" value="'.$row['email'].'"></td>';
      // Team-y
      echo '<td>'..'</td>';
      // EXP
      echo '<td><input type="text" class="form-control" id="" name="" value="'.$row['exp'].'" required></td>';
      // Práva
      echo '<td>&nbsp</td>';
      // Úprava
    }
  } else{
    echo '<script>alert("Žiadny používatelia neboli nájdení!");</script>';
  }
  // KONIEC FORMU
  echo '</form>';
  // KONIEC TABULKY
  echo '</table>';
  echo '</div>';
  include 'includes/end.html';
?>
