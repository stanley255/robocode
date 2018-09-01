<?php
require '../dbconfig/config.php';
include '../includes/links.html';
include '../includes/navbar.php';
  // Pristupy
  if (empty($_SESSION['privilege']) or $_SESSION['privilege']!=4){
    header('location:../index.html');
  }
?>
<div class="container pagination">
  <div class="col-md-1"></div>
  <div class="col-md-10 text-center">
    <!-- Vytvorenie tabulky -->
    <hr>
    <h3>Dochádzka</h3>
    <hr>
    <table class="table table-bordered table-sm">
      <tr>
        <th>Dátum/Žiak</th>
<?php
  // Žiaci
  $a_students_id = [];
  $i = 0;
  $query = "SELECT id,name FROM ROBOCODE.USERS WHERE privilege IN (2) ORDER BY ID";
  $query_run = mysqli_query($con,$query);
    // Vypis heading-u
  if (mysqli_num_rows($query_run)){
    while($row = mysqli_fetch_assoc($query_run)){
      $a_students_id[$i++] = $row['id'];
      echo '<th> '.$row['name'].' </th>';
    }
  }
  echo '</tr>';
  // Pole datumov eventov a ich ID-čka
  $a_events_id   = [];
  $a_events_date = [];
  $query     = "SELECT id, start_stamp FROM ROBOCODE.EVENTS ORDER BY start_stamp DESC";
  $query_run = mysqli_query($con,$query);
  if (mysqli_num_rows($query_run)){
    $i = 0;
    while ($row = mysqli_fetch_assoc($query_run)){
      $a_events_id[$i]     = $row['id'];
      $a_events_date[$i++] = substr($row['start_stamp'],0,10);
    }
  }
  // Počítadlo, koľkých hodín sa študent zúčastnil
  $a_students_present = [];
  foreach ($a_students_id as $student_id){
    $a_students_present[$student_id] = 0;
  }
  $i = 0;
  // Vypis obsahu tabulky
  for ($i; $i<count($a_events_id); $i++){
    // Vypis datum
    echo '<tr>';
    echo '  <td><a href="/robocode/admin/adm_attendance_edit.php?id='.$a_events_id[$i].'">'.date("d.m.Y",strtotime($a_events_date[$i])).'</a></td>';
    // Ziskaj študentov, ktorí sa zúčastnili daného eventu -> sprav pole
    $j = 0;
    $a_attended_students = [];
    $query = "SELECT fk_user_id FROM ROBOCODE.ATTENDANCE WHERE fk_event_id = ".$a_events_id[$i];
    $query_run = mysqli_query($con,$query);
    if (mysqli_num_rows($query_run)){
      while ($row = mysqli_fetch_assoc($query_run)){
        $a_attended_students[$j++] = $row["fk_user_id"];
      }
    }
    // Loop-uj cez studentov a zisti, kto bol pritomny
    foreach ($a_students_id as $student_id){
      if (in_array($student_id,$a_attended_students)){
        $a_students_present[$student_id]++;
        echo '<td class="table-success"></td>';
      } else{
        echo '<td class="table-danger"></td>';
      }
    }
    echo '</tr>';
  }
  echo '<tr>';
  echo '  <td>Dokopy</td>';
  foreach($a_students_present as $student_total){
    echo '<td>'.$student_total.'</td>';
  }
  echo '</tr>';
?>
    </table>
  </div>
  <div class="col-md-1"></div>
</div>

<?php
  include '../includes/end.html';
?>
