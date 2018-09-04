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
  }

  function getLessonInfo(id){
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        // Naplnenie inputov na zaklade vratenych udajov
        var response = JSON.parse(this.responseText);
        document.getElementById('nameId').value = response['name'];
        document.getElementById('descId').value = response['description'];
        if (response['valid']=='Y'){
          document.getElementById('validId').selectedIndex = "0";
        } else{
          document.getElementById('validId').selectedIndex = "1";
        }
      }
    };
    xhttp.open("GET", "../ajax/getLessonInfo.php?id="+id, true);
    xhttp.send();
  }

  function addLesson(){
    // Ziskanie udajov z formu
    var id    = document.getElementById("lessonId").value;
    var name  = document.getElementById("nameId").value;
    var desc  = document.getElementById("descId").value;
    var valid = document.getElementById("validId").value;

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var response = JSON.parse(this.responseText);
        if (response["action"]==1 || response["action"]==2){
          // Disable inputov
          document.getElementById("lessonId").disabled      = true;
          document.getElementById("nameId").disabled        = true;
          document.getElementById("descId").disabled        = true;
          document.getElementById("validId").disabled       = true;
          document.getElementById("submit_btn_id").disabled = true;
          // Skryť lessonAddForm
          document.getElementById("lessonFormId").style.display="none";
          // Zobraziť form na pridávanie questov
          document.getElementById("questFormId").style.display="block";
          // Získaj údaje o questoch
          var xhttp2 = new XMLHttpRequest();
          xhttp2.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              // Získat response z getQuestInfo
              var quests = JSON.parse(this.responseText);
              // Nastav možnosti do select-u
              var questSelect = document.getElementById("questsId");
              for (var i = 0; i < quests.length; i++){
                var option = document.createElement("option");
                option.text  = quests[i]["name"];
                option.value = quests[i]["id"];
                questSelect.add(option);
              }
            }
          };
          xhttp2.open("POST", "../ajax/getQuests.php", true);
          xhttp2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xhttp2.send("id="+ document.getElementById("lessonId").value);
        } else{
          alert('Nepodarilo sa pridať/upraviť lekciu!');
        }
      }
    };
    xhttp.open("POST", "../ajax/newLesson.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("id="+id+"&name="+name+"&desc="+desc+"&valid="+valid);
  }

  function getQuestInfo(id){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        // Naplnenie inputov na zaklade vratenych udajov
        var response = JSON.parse(this.responseText);
        /* TODO */
      }
    };
    xhttp.open("GET", "../ajax/getQuestnInfo.php?id="+id, true);
    xhttp.send();
  }
</script>

<div class="container text-center pagnation">
    <hr>
      <h3>Lekcia</h3>
    <hr>
    <form id="lessonFormId">
    <!-- Select -->
    <div class="form-inline">
      <label>Lekcia:&nbsp</label>
      <select name="id" id="lessonId" class="form-control" style="width:200px;">
        <option value="0" onclick="clearLessonInfo()">Vytvor novú</option>
<?php
  $query = "SELECT id, name FROM ROBOCODE.lessons ORDER BY id";
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
    </div>
    <br>
    <div class="form-inline text-left">
      <!-- Description -->
      <label>Popis:&nbsp</label>
      <input type="textarea" class="form-control" id="descId" name="desc" style="width: 650px;">
    </div>
    <br>
    <div class="form-inline">
      <!-- Prístupnosť -->
      <br>
      <label>Prístupnosť:&nbsp&nbsp</label>
      <select name="valid" id ="validId" class="form-control">
        <option value="Y">Prístupná</option>
        <option value="N">Neprístupná</option>
      </select>&nbsp&nbsp
      <input class="btn btn-outline-info my-2 my-sm-0" onclick="addLesson()" type="button" value="Ďalej" id="submit_btn_id" name="submit_btn">
    </div>
  </form>


  <form id="questFormId" style="display:none">
    <div class="form-inline">
      <label>Úloha:&nbsp&nbsp</label>
      <select id="questsId" class="form-control">
        <option value="0">Vytvoriť novú</option>
      </select>
      <label>&nbsp&nbspNázov:&nbsp&nbsp</label>
    </div>
    <div class="form-group">
      <label>Zadanie:</label>
      <textarea class="form-control" rows="5" name="text" id="textId"></textarea>
    </div>
  </form>

</div>
<?php
  include '../includes/end.html';
?>
