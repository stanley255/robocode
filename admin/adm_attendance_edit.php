<?php
require '../dbconfig/config.php';
include '../includes/links.html';
include '../includes/navbar.php';
  // Pristupy
  if (empty($_SESSION['privilege']) or $_SESSION['privilege']!=4 or !isset($_GET['id'])){
    header('location:../index.html');
  }

  // Získanie údajov z pôvodného výkazu
  $query = "SELECT * FROM ROBOCODE.EVENTS WHERE id = ".$_GET['id'];
  $query_run = mysqli_query($con,$query);
  if (mysqli_num_rows($query_run)){
    if ($row = mysqli_fetch_assoc($query_run)){
      $category    = $row['fk_event_category_id'];
      $start_stamp = $row['start_stamp'];
      $end_stamp   = $row['end_stamp'];
      $description = $row['description'];
      $lector_id   = $row['fk_lector_id'];
    } else{
      echo '<script>alert("Daný záznam sa nepodarilo nájsť!");</script>';
      header('location:../index.html');
    }
  } else{
    echo '<script>alert("Daný záznam sa nepodarilo nájsť!");</script>';
    header('location:../index.html');
  }
?>
 <!-- Form na pridanie udalosti -->
 <div class="container pagination">
  <div class="col-md-3"></div>
  <div class="col-md-6 text-center">
    <form action="adm_attendance_edit.php?id=<?php echo $_GET['id'];?>" method="post" onsubmit="return checkForm()">
      <hr><h3>Upraviť udalosť:</h3><hr>
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
    if ($category == $row['id']){
      echo '<option value='.$row['id'].' selected>'.$row['name'].'</option>';
    } else{
      echo '<option value='.$row['id'].'>'.$row['name'].'</option>';
    }
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
        <textarea class="form-control" rows="2" name="description" id="descriptionId" required></textarea>
      </div>
      <br>
      <div class="form-inline">
        <label>Lektor:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>
        <select class="form-control" name="lector">
<?php
      // Výber lektorov(príp. správcov) z databázy
  $query = "SELECT id, username FROM ROBOCODE.USERS WHERE PRIVILEGE IN (3,4)";
  $query_run = mysqli_query($con,$query);
  if (mysqli_num_rows($query_run)){
    while($row = mysqli_fetch_assoc($query_run)){
      // Vypísanie lektorov/správcov ako možnosti
      if ($lector_id == $row['id']){
        echo '<option value='.$row['id'].' selected>'.$row['username'].'</option>';
      } else{
        echo '<option value='.$row['id'].'>'.$row['username'].'</option>';
      }
    }
  } else{
    echo '<script>alert("Nepodarilo sa získať údaje o lektoroch!");</script>';
  }
?>
        </select>
      </div>
      <hr>
<!-- Zapisanie ziakov -->
      <table class="table table-bordered table-sm">
        <tr>
          <th>Žiak</th>
        </tr>
<?php
  // Získanie žiakov z databázy
  $query = "SELECT id, username FROM ROBOCODE.USERS WHERE privilege IN(2)";
  $query_run = mysqli_query($con,$query);
  if (mysqli_num_rows($query_run)){
    while ($row = mysqli_fetch_assoc($query_run)){
      echo '<tr>';
      echo '  <td class="table-danger" onclick="changeColor(this,'.$row['id'].')" id = "mycolumn'.$row['id'].'"><label style="font-size:18px">'.$row['username'].'</label></td>';
      echo '  <input type="checkbox" class="checkbox" hidden style="position:absolute;" name="students[]" value="'.$row['id'].'" id="mycheckbox'.$row['id'].'">';
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
  // Farba buniek pre pritomnych studentov
  function changeColor(row,index){
    var checkbox = document.getElementById("mycheckbox"+index);
    if (checkbox.checked){
      checkbox.checked = false;
    } else{
      checkbox.checked = true;
    }
    if (checkbox.checked){
      row.classList.remove('table-danger');
      row.classList.add('table-success');
    } else{
      row.classList.remove('table-success');
      row.classList.add('table-danger');
    }
  }

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
  // Javascript na naplnenie poli datumu, hodin a popisu
  echo '<script>';
  echo '  function fillForm(){';
    // Naplnenie pôl udalosti
  echo '    document.getElementById("datumId").value     = "'.date("d.m.Y",strtotime(substr($start_stamp,0,10))).'";';
  echo '    document.getElementById("startTimeId").value = "'.substr($start_stamp,11,5).'";';
  echo '    document.getElementById("endTimeId").value   = "'.substr($end_stamp,11,5).'";';
  echo '    document.getElementById("descriptionId").value = "'.$description.'";';
    // Zaznačenie pôvodnej účasti
      // Získanie údajov o účasti
  $query     = "SELECT fk_user_id FROM ROBOCODE.ATTENDANCE WHERE fk_event_id = ".$_GET['id'];
  $query_run = mysqli_query($con,$query);
  if (mysqli_num_rows($query_run)){
    $a_students_id = [];
    while ($row = mysqli_fetch_assoc($query_run)){
      $a_students_id[] = $row['fk_user_id'];
    }
    foreach ($a_students_id as $student_id){
      // Nastavenie farby a checknutie prislusnych checkboxov
        // Farba
      echo 'document.getElementById("mycolumn'.$student_id.'").classList.remove("table-danger");';
      echo 'document.getElementById("mycolumn'.$student_id.'").classList.add("table-success");';
        // Check
      echo 'document.getElementById("mycheckbox'.$student_id.'").checked = true;';
    }
  }
  echo '}fillForm();';
  echo '</script>';



  include '../includes/end.html';
  if (isset($_POST["submit_btn"])){
    // Ziskanie info z formu
    $id                   = $_GET['id'];
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
    // Uprava eventu
    if ($stmt = mysqli_prepare($con,"UPDATE ROBOCODE.EVENTS SET fk_event_category_id = ?, start_stamp = ?, end_stamp = ?, description = ?, fk_lector_id = ? WHERE id = ?")){
      if (mysqli_stmt_bind_param($stmt,"isssii",$category,$start_time,$end_time,$description,$lector_id,$id)){
        if (mysqli_stmt_execute($stmt)){
          // Po uspesnom vytvoreni eventu -> pridanie studentov, ktori sa zucastnili
            // Zmazanie všetkých pôvodne zapísaných študentov
          $query = "DELETE FROM ROBOCODE.ATTENDANCE WHERE fk_event_id = ".$id;
          $query_run = mysqli_query($con,$query);
          if (mysqli_affected_rows($con)>=0){
            // Ak treba -> zapíš študentov k danému eventu
            $updated = 0;
            if (!empty($a_present_students)){
              foreach ($a_present_students as $student_id){
                $query = "INSERT INTO ROBOCODE.ATTENDANCE(fk_event_id,fk_user_id) VALUES('.$id.','.$student_id.')";
                $query_run = mysqli_query($con,$query);
                if (mysqli_affected_rows($con)>=0){
                  $updated++;
                }
              }
            }
            // Uspesna transakcia
            echo '<script></script>';
            echo '<script>alert("Udalosť bola úspešne upravená a bolo k nej úspešne priradených '.$updated.' z '.count($a_present_students).' študentov!");window.location.replace("adm_attendance_show.php");</script>';
          } else{
            echo '<script>alert("Udalosť bol úspešne upravená ale študentov sa nepodarilo odstrániť!");</script>';
          }
        } else{
          echo '<script>alert("Nepodarilo sa upraviť udalosť a priradiť účastníkov 3");</script>';
        }
      } else{
        echo '<script>alert("Nepodarilo sa upraviť udalosť a priradiť účastníkov 2");</script>';
      }
    } else{
      echo '<script>alert("Nepodarilo sa upraviť udalosť a priradiť účastníkov 1");</script>';
    }
  }
?>
