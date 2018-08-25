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
    <form action="adm_attendance_add.php" method="post" onsubmit="return checkForm()">
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
        <input type="text" class="form-control" name="date" id="datumId" required>
      </div>
      <br>
      <div class="form-inline">
        <label>Začiatok:&nbsp&nbsp&nbsp&nbsp&nbsp</label>
        <input type="text" class="form-control" name="startTime" id="startTimeId" required>
      </div>
      <br>
      <div class="form-inline">
        <label>Koniec:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
        <input type="text" class="form-control" name="endTime" id="endTimeId">
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

<!-- JS -->
<script>
  // Pre-fill datumu
  function fillDate(){
    var today        = new Date();
    var dd           = today.getDate();
    var mm           = today.getMonth()+1;
    var yyyy         = today.getFullYear();
    var hoursStart   = today.getHours();
    var minutesStart = today.getMinutes();
    var hoursEnd     = hoursStart;
    var minutesEnd   = minutesStart;
    // Dopocitanie konca
    hoursEnd = hoursEnd + 2 < 24 ? hoursEnd + 2 : 24 - hoursEnd - 2 > 0 ? 24 - hoursEnd - 2 : -1 * (24 - hoursEnd - 2);
    // Pridanie pripadnych nul
    dd = dd < 10 ? '0' + dd : dd;
    mm = mm < 10 ? '0' + mm : mm;
    hoursStart   = hoursStart   < 10 ? '0' + hoursStart   : hoursStart;
    minutesStart = minutesStart < 10 ? '0' + minutesStart : minutesStart;
    hoursEnd     = hoursEnd     < 10 ? '0' + hoursEnd     : hoursEnd;
    minutesEnd   = minutesEnd   < 10 ? '0' + minutesEnd   : minutesEnd;
    today = dd + '.' + mm + '.' + yyyy;
    document.getElementById("datumId").value     = today;
    document.getElementById("startTimeId").value = hoursStart  + ':' + minutesStart;
    document.getElementById("endTimeId").value =   hoursEnd    + ':' + minutesEnd;
  } fillDate();

  // Kontrola formu
  function checkDate(input){
    if (input.indexOf(".")==1){
        input = '0' + input;
    }
    if (input.lastIndexOf(".")==4){
        input = input.slice(0,3)+'0'+input.slice(3)
    }
    var pattern = /(\d{2})\.(\d{2})\.(\d{4})/;
    var dt = input.replace(pattern,'$3-$2-$1');
    var d = new Date(dt);
    if (isNaN(d)){
        return false;
    }
    else{
        return true;
    }
  }
  function checkForm(){
    if (checkDate(window["datumId"].value)==false){
      alert("Zlý dátum!");
      return false;
    }

  }

</script>


<?php
  include 'includes/end.html';
  if (isset($_POST["submit_btn"])){
    // Ziskanie info z formu
    $category             = $_POST["event_category_id"];
    $date                 = str_replace('.','-',$_POST["date"]);
    $start_time           = $_POST["startTime"].':00';
    $end_time             = $_POST["endTime"].':00';
    $description          = $_POST["description"];
    $lector_id            = $_POST["lector"];
    if (!empty($_POST["students"])){
      $a_present_students = $_POST["students"];
    } else{
      $a_present_students = [];
    }

    // Priprava DATETIME-ov
    $start_time  = $date.' '.$start_time;
    $end_time    = $date.' '.$end_time;
    $start_time  = date("Y-m-d H:i:s",strtotime($start_time));
    $end_time    = date("Y-m-d H:i:s",strtotime($end_time));
    // Vytvorenie eventu
    if ($stmt = mysqli_prepare($con,"INSERT INTO ROBOCODE.EVENTS(fk_event_category_id,start_stamp,end_stamp,description,fk_lector_id) VALUES(?,?,?,?,?)")){
      if (mysqli_stmt_bind_param($stmt,"isssi",$category,$start_time,$end_time,$description,$lector_id)){
        if (mysqli_stmt_execute($stmt)){
          // Po uspesnom vytvoreni eventu -> pridanie studentov, ktori sa zucastnili
          if (!empty($a_present_students)){
            // Ziskanie IDcka novo vytvoreneho eventu
            $query = "SELECT MAX(id) AS id FROM ROBOCODE.EVENTS";
            $query_run = mysqli_query($con,$query);
            if (mysqli_num_rows($query_run)){
              if ($row = mysqli_fetch_assoc($query_run)){
                 $event_id = $row["id"];
                 // Counter na pocet uspesne pridanych studentov ku danej udalosti
                 $counter = 0;
                 // Pridanie študentov
                 foreach ($a_present_students as $present_student){
                   $query = 'INSERT INTO ROBOCODE.ATTENDANCE VALUES('.$event_id.','.$present_student.')';
                   $query_run = mysqli_query($con,$query);
                   if (mysqli_affected_rows($con)){
                     $counter++;
                   }
                 }
                 echo '<script>alert("Udalosť bola vytvorená a úspešne bolo zapísaných '.$counter.' z '.count($a_present_students).' študentov!");</script>';
              } else{
                echo '<script>alert("K udalosti sa nepodarilo pridať študentov!");</script>';
              }
            } else{
              echo '<script>alert("K udalosti sa nepodarilo pridať študentov!");</script>';
            }
          } else{
            echo '<script>alert("Udalosť bol úspešne vytvorená, ale žiaden žiak nebol pridaný!");</script>';
          }
        } else{
          echo '<script>alert("Nepodarilo sa vytvoriť udalosť a priradiť účastníkov");</script>';
        }
      } else{
        echo '<script>alert("Nepodarilo sa vytvoriť udalosť a priradiť účastníkov");</script>';
      }
    } else{
      echo '<script>alert("Nepodarilo sa vytvoriť udalosť a priradiť účastníkov");</script>';
    }
  }






























?>
