<?php
  require '../dbconfig/config.php';
  include '../includes/links.html';
  include '../includes/navbar.php';
  // Pristupy
  if (empty($_SESSION['privilege']) or $_SESSION['privilege']!=4){
    header('location:../../index.html');
  }
?>

<script>
  function clearLessonInfo(){
    document.getElementById('nameId').value = "";
    document.getElementById('descId').value = "";
    document.getElementById('validId').selectedIndex = "0";
    document.getElementById('submit_btn_id').value = 'Pridať';
  }

  function getLessonInfo(id){
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        // Naplnenie inputov na zaklade vratenych udajov
        var obj = JSON.parse(this.responseText);
        document.getElementById('nameId').value = obj['name'];
        document.getElementById('descId').value = obj['description'];
        if (obj['valid']=='Y'){
          document.getElementById('validId').selectedIndex = "0";
        } else{
          document.getElementById('validId').selectedIndex = "1";
        }
        document.getElementById('submit_btn_id').value = 'Upraviť';
      }
    };
    xhttp.open("GET", "../ajax/getLessonInfo.php?id="+id, true);
    xhttp.send();
  }

  function addLesson(){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("demo").innerHTML = this.responseText;
      }
    };
  xhttp.open("POST", "../ajax/newLesson.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  // Ziskanie udajov z formu
   /*                            TODO                                      */
  //Odoslanie requestu
  xhttp.send("");
  }
</script>

<div class="container text-center pagnation">
    <hr>
      <h3>Lekcia</h3>
    <hr>
    <form>
    <!-- Select -->
    <div class="form-inline">
      <select name="id" class="form-control" style="width:200px;">
        <option value="0" onclick="clearLessonInfo()">Vytvor novú</option>
<?php
  $query = "SELECT id, name FROM ROBOCODE.lessons WHERE valid='Y' ORDER BY id";
  $query_run = mysqli_query($con,$query);
  if (mysqli_num_rows($query_run)){
    while ($row = mysqli_fetch_assoc($query_run)){
      echo '<option value="'.$row['id'].'" onclick="getLessonInfo(this.value)">'.$row['name'].'</option>';
    }
  }
?>
      </select>
      <!-- Name -->
      <label>&nbsp&nbsp&nbspNázov:&nbsp</label>
      <input type="text" class="form-control" id="nameId" name="name" style="width:200px" required>
      <!-- Description -->
      <label>&nbsp&nbsp&nbspPopis:&nbsp</label>
      <input type="text" class="form-control" id="descId" name="desc" style="width:609px">
    </div>
    <br>
    <div class="form-inline">
      <!-- Prístupnosť -->
      <br>
      <label>Prístupnosť:&nbsp&nbsp</label>
      <select name="valid" id ="validId" class="form-control">
        <option value="Y">Prístupná</option>
        <option value="N">Nerístupná</option>
      </select>&nbsp&nbsp
      <input class="btn btn-outline-info my-2 my-sm-0" type="submit" value="Pridať" id="submit_btn_id" name="submit_btn">
    </div>
  </form>
</div>
<?php
  include '../includes/end.html';
?>
