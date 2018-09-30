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
  echo '<form action="adm_posts_manage.php" method="post">';
  // TABULKA
  echo '<div class="col-md-1"></div>';
  echo '<div class="table-responsive col-md-10 container pagination">';
  echo '<table class="table table-bordered">';
    // HEADING
  echo '  <tr>';
  echo '    <th>ID</th>';
  echo '    <th>Dátum</th>';
  echo '    <th>Autor</th>';
  echo '    <th>Pripnutý</th>';
  echo '    <th>Text</th>';
  echo '    <th>Úprava</th>';
  echo '  </tr>';
  // Vypis clankov, ktore sa daju upravit (UPDATE: Iba tých, ktoré pridal daný používateľ)
  $query = "SELECT * FROM ROBOCODE.V_POSTS WHERE username = '".$_SESSION['username']."'";
  $query_run = mysqli_query($con,$query);
  if (mysqli_num_rows($query_run)){
    while ($row = mysqli_fetch_assoc($query_run)){
      echo '<tr>';
      echo '  <td>'.$row["id"].'</td>';
      echo '  <td>'.$row["date"].'</td>';
      echo '  <td>'.$row["username"].'</td>';
      echo '  <td>';
      echo '    <select class="form-control" name="pinned'.$row["id"].'">';
      if ($row["pinned"]=='Y'){
        echo '    <option value="Y" selected>Áno</option>';
        echo '    <option value="N">Nie</option>';
      } else{
        echo '    <option value="Y">Áno</option>';
        echo '    <option value="N" selected>Nie</option>';
      }
      echo '    </select>';
      echo '  </td>';
      echo '  <td><textarea class="form-control" name="text'.$row["id"].'" id="textId">'.$row["text"].'</textarea></td>';
      echo '  <td><button class="btn btn-outline-info my-2 my-sm-0" type="submit" name="submit_btn" value="'.$row["id"].'">Uprav</button></td>';
      echo '</tr>';
    }
  } else{
    echo '<script>alert("Nenašli sa žiadne články");</script>';
  }
  // KONIEC FORMU
  echo '</form>';
  // KONIEC TABULKY
  echo '</table>';
  echo '</div>';
  echo '<div class="col-md-1"></div>';

  include '../includes/end.html';


  // POST PART
  if (isset($_POST["submit_btn"])){
    // Ziskanie premmenych z form-u
    echo "<meta http-equiv='refresh' content='0'>";
    $id     = $_POST["submit_btn"];
    $text   = $_POST["text$id"];
    $pinned = $_POST["pinned$id"];
    // Update starých údajov
    if ($stmt = mysqli_prepare($con,"UPDATE ROBOCODE.POSTS SET text = ? , pinned = ? WHERE id = ?")){
      if (mysqli_stmt_bind_param($stmt,"ssi",$text,$pinned,$id)){
        if (mysqli_stmt_execute($stmt)){
          mysqli_stmt_close($stmt);
          echo '<script>alert("Vami zvolený záznam bol úspešne upravený!");</script>';
        } else{
          echo '<script>alert("Nepodarilo sa upraviť vami zvolený záznam!");</script>';
        }
      } else{
        echo '<script>alert("Nepodarilo sa upraviť vami zvolený záznam!");</script>';
      }
    } else{
      echo '<script>alert("Nepodarilo sa upraviť vami zvolený záznam!");</script>';
    }
  }


?>
