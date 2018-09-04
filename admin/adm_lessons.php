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

  function addQuestToSelect(id,name){
    var questSelect = document.getElementById("questsId");
    var option = document.createElement("option");
    option.value   = id;
    option.text    = name;
    option.onclick = function(){fillQuest(this.value)};
    questSelect.add(option);
  }

  function fillQuest(id){
    if (id == 0){
      document.getElementById("questNameId").value = "";
      document.getElementById("questTextId").value = "";
      document.getElementById("questExpId").value  = "";
    } else{
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          // Naplnenie inputov na zaklade vratenych udajov
          var response = JSON.parse(this.responseText);
          document.getElementById("questNameId").value = response["name"];
          document.getElementById("questTextId").value = response["description"];
          document.getElementById("questExpId").value  = response["exp"];
        }
      };
      xhttp.open("GET", "../ajax/getQuestInfo.php?id="+id, true);
      xhttp.send();
    }
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
              for (var i = 0; i < quests.length; i++){
                addQuestToSelect(quests[i]["id"],quests[i]["name"]);
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

  function addQuest(){
    // Ziskanie udajov z form-u
    var q_id = document.getElementById("questsId").value;
    var l_id = document.getElementById("lessonId").value;
    var name = document.getElementById("questNameId").value;
    var desc = document.getElementById("questTextId").value;
    var exp  = document.getElementById("questExpId").value;
    // Odoslanie poziadavky na BE - insert/update
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var response = JSON.parse(this.responseText);
        if (response["action"]==1 || response["action"]==2){
          alert();
        } else{
          //alert("Nastala chyba pri zapísaní / aktualizovaní úlohy!");
          alert(response["action"]);
        }
      }
    };
    xhttp.open("POST", "../ajax/newQuest.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("q_id="+q_id+"l_id="+l_id+"&name="+name+"&desc="+desc+"&exp="+exp);
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
        <option value="0" onclick="fillQuest(0)">Vytvoriť novú</option>
      </select>
    </div>
    <br>
    <div class="form-inline">
      <label>Názov:&nbsp&nbsp</label>
      <input type="text" class="form-control" id="questNameId">
    </div>
    <br>
    <div class="form-inline">
      <label>EXP:&nbsp&nbsp&nbsp&nbsp&nbsp</label>
      <input type="text" class="form-control" id="questExpId" style="width:70px">
    </div>
    <br>
    <div class="form-group">
      <label>Zadanie:</label>
      <textarea class="form-control" rows="5" name="text" id="questTextId"></textarea>
    </div>
    <div class="form-group">
      <input class="btn btn-outline-info my-2 my-sm-0" onclick="addQuest()" type="button" value="Ďalej" id="submit_btn_id" name="submit_btn">
    </div>
  </form>

</div>
<?php
  include '../includes/end.html';
?>
