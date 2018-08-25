<?php
  require 'dbconfig/config.php';
  include 'includes/links.html';
  include 'includes/navbar.php';
  // Pristupy
  if (empty($_SESSION['privilege']) or $_SESSION['privilege']!=4){
    header('location:index.php');
  }
?>
 <!-- Form na pridanie udalosti -->
 <div class="container pagination">
  <div class="col-md-3"></div>
  <div class="col-md-6 text-center">
    <form action="adm_attendance_add.php" method="post">
      <hr><h3>Pridať udalosť:</h3><hr>
      <div class="form-inline">
        <label>Typ udalosti:&nbsp</label>
        <select class="form-control" name="event_category_id">
<?php
// Výber kategórie udalosti
  // Získanie typu udalostí
$query = "SELECT * FROM ROBOCODE.EVENTS_CATEGORY ORDER BY ID ASC";
$query_run = mysqli_query($con,$query);
if (mysqli_num_rows($query_run)){
  while ($row = mysqli_fetch_assoc($query_run)){
    // Možnosti do selectu s typami udalostí
    echo '  <option value='.$row['id'].'>'.$row['name'].'</option>';
  }
} else{
  echo '<script>alert("Nepodarilo sa získať typy udalostí!");</script>';
}
?>
        </select>
      </div>
      <br>
      <!-- Start_stamp/End_stamp -->
      <div class="form-inline">
        <label>Dátum:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
        <input type="text" class="form-control" name="date" required>
      </div>
      <br>
      <div class="form-inline">
        <label>Začiatok:&nbsp&nbsp&nbsp&nbsp&nbsp</label>
        <input type="text" class="form-control" name="startTime" required>
      </div>
      <br>
      <div class="form-inline">
        <label>Koniec:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
        <input type="text" class="form-control" name="endTime">
      </div>
      <br>
      <!-- Popis -->
      <div class="form-inline">
        <label>Popis:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
        <textarea class="form-control" rows="2" name="description" required></textarea>
      </div>
      <br>
      <div class="form-inline">
        <label>Lektor:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
        <select class="form-control" name="lector">
<?php
      // Výber lektorov(príp. správcov) z databázy
  $query = "SELECT id, name, surname FROM ROBOCODE.USERS WHERE PRIVILEGE IN (3,4)";
  $query_run = mysqli_query($con,$query);
  if (mysqli_num_rows($query_run)){
    while($row = mysqli_fetch_assoc($query_run)){
      // Vypísanie lektorov/správcov ako možnosti
      echo '<option value='.$row['id'].'>'.$row['name'].' '.$row['surname'].'</option>';
    }
  } else{
    echo '<script>alert("Nepodarilo sa získať údaje o lektoroch!");</script>';
  }
?>
        </select>
      </div>
      <hr>
<!-- Zapisanie ziakov -->
      <table>
        <tr>
          <th>Žiak</th>
          <th>Prítomnosť</th>
        </tr>
<?php
  // Získanie žiakov z databázy
  $query = "SELECT id, name, surname FROM ROBOCODE.USERS WHERE privilege IN(2)";
  $query_run = mysqli_query($con,$query);
  if (mysqli_num_rows($query_run)){
    while ($row = mysqli_fetch_assoc($query_run)){
      echo '<tr>';
      echo '  <td>'.$row['name'].' '.$row['surname'].'</td>';
      echo '  <td><input class="checkbox" type="checkbox" name="students[]" value="'.$row['id'].'"></td>';
      echo '</tr>';
    }
  } else{
    echo '<script>alert("Nepodarilo sa získať/neexistujú žiadny žiaci!");</script>';
  }
?>
      </table>
      <hr>
      <input class="btn btn-outline-info my-2 my-sm-0" type="submit" name="submit_btn" value="Pridaj">
    </form>
  </div>
</div>
<br>
<?php
  include 'includes/end.html';
  if (isset($_POST["submit_btn"])){
    // Ziskanie info z formu
    $category           = $_POST["event_category_id"];
    $date               = $_POST["date"];
    $start_time         = $_POST["startTime"];
    $end_time           = $_POST["endTime"];
    $description        = $_POST["description"];
    $lector_id          = $_POST["lector"];
    $a_present_students = $_POST["students"];

    foreach ($a_present_students as $present_student){
      echo $present_student;
    }
    echo $category;
    echo $description;
    echo $lector_id;
    echo $date;
    echo $start_time;
    echo $end_time;
  }
?>
