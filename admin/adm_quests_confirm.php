<?php
  require '../dbconfig/config.php';
  include '../includes/links.html';
  include '../includes/navbar.php';
  // Pristupy
  if (empty($_SESSION['privilege']) or $_SESSION['privilege']!=4){
    header('location:../index.html');
  }
?>
<script>
// Vymazanie riadku v tabulke
function deleteRow(r) {
    var i = r.parentNode.parentNode.rowIndex;
    document.getElementById("questConfTable").deleteRow(i);
}
// Funkcia na pridanie skusenosti pouzivatelovi a zmeny stavu z P->Y
  // TODO: Zmena parametrov funkcie + priprava APIs
function confirmQuest(row){
  // Zmeň status
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      // Zapíš EXP (ďalší AJAX)

    }
  };
  xhttp.open("GET", "../ajax/changeSolverQuestStatus.php?id="+id, true);
  xhttp.send();
}

</script>
<?php

  // Získanie aktívnych lekcií
  $query = "SELECT DISTINCT L.id, L.name FROM ROBOCODE.QUESTS_SOLVERS QS JOIN ROBOCODE.LESSONS L ON QS.fk_lesson_id = L.id";
  $query_run = mysqli_query($con,$query);
  $a_lesson_id   = [];
  $a_lesson_name = [];
  while ($row = mysqli_fetch_assoc($query_run)){
    $a_lessons[] = $row["id"];
    $a_lesson_name[] = $row["name"];
  }
?>
<div class="container pagination">
  <div class="col-md-1"></div>
  <div class="col-md-10 text-center">
    <hr>
<?php
  for ($i = 0; $i < count($a_lessons); $i++){
    echo '<h5>'.$a_lesson_name[$i].'</h5>';
    echo '<hr>';
    echo '<table class="table table-bordered table-sm" id="questConfTable">';
    echo '  <tr>';
    echo '    <th>Žiak</th>';
    echo '    <th>Lekcia</th>';
    echo '    <th>Úloha</th>';
    echo '    <th>Akcia</th>';
    echo '  </tr>';
    // Loopuj záznamami v stave P = Pending
    $query = "SELECT QS.id, U.username, QS.fk_lesson_id, QS.fk_quest_id FROM QUESTS_SOLVERS QS JOIN USERS U ON QS.fk_user_id = U.id WHERE status = 'P' ORDER BY QS.fk_lesson_id, QS.fk_quest_id";
    $query_run = mysqli_query($con,$query);
    while ($row = mysqli_fetch_assoc($query_run)){
      echo '<tr>';
      // Fetch udajov tabulky
      echo '  <td>'.$row["username"].'</td>';
      echo '  <td>'.$row["fk_lesson_id"].'</td>';
      echo '  <td>'.$row["fk_quest_id"].'</td>';
      // Button pre potvrdenie vypracovanej úlohy
      echo '  <td><button class="btn btn-outline-info my-2 my-sm-0" type="submit" name="submit_btn" value="'.$row["id"].'" onclick="confirmQuest(this);deleteRow(this)">Potvrď</button></td>';
      echo '</tr>';
    }
  }

?>
