<?php
  require '../dbconfig/config.php';
  include '../includes/links.html';
  include '../includes/navbar.php';
  if (empty($_SESSION['privilege']) or $_SESSION['privilege']==1){
    header('location:../../index.html');
  }
?>
<div class="container">

<!-- JAVASCRIPT -->
<script>
  function hideLesson(btn){
    if (document.getElementById("recQuestId").value==0){
      alert("Splnil si všetky úlohy v tejto lekcií!");
    } else{
      btn.style.display="none";
      btn.disabled = true;
      document.getElementById("control_quest_btn_id").style.display="inline";
      document.getElementById("next_quest_btn_id").style.display="inline";
      // Ziskanie info o prvom queste
      var id = document.getElementById("recQuestId").value;
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          // Naplnenie inputov na zaklade vratenych udajov
          var response = JSON.parse(this.responseText);
          document.getElementById("titleId").innerHTML       = response["name"];
          document.getElementById("descriptionId").innerHTML = response["description"];
        }
      };
      xhttp.open("GET", "../ajax/getQuestInfo.php?id="+id, true);
      xhttp.send();
    }
  }

  function validateQuest(btn){
    var q_id = document.getElementById("recQuestId").value;
    var l_id = document.getElementById("lessonId").value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        // Naplnenie inputov na zaklade vratenych udajov
        var response = JSON.parse(this.responseText);
        if (response["status"] != "Y"){
          // Disable buttonu na 5 sekund, aby nenastavali prilis caste requesty
          btn.disabled = true;
          setTimeout(function() {
            btn.disabled = false;
          }, 5000);
        } else{
          // Enable buttonu na dalsi quest
          document.getElementById("next_quest_btn_id").disabled = false;
          // Disable kontrolovacieho button-u
          document.getElementById("control_quest_btn_id").disabled = true;
        }
      }
    };
    xhttp.open("GET", "../ajax/isQuestValidated.php?q_id="+q_id+"&l_id="+l_id, true);
    xhttp.send();
  }

  function getNextQuest(){
    var l_id = document.getElementById("lessonId").value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        // Naplnenie inputov na zaklade vratenych udajov
        var response = JSON.parse(this.responseText);
        if (response["status"] == "Y"){
          // Lekcia bola dokončená
          alert("Splnil si všetky úlohy v tejto lekcií!");
        } else{
          // Nastavenie noveho nazvu a textu
          document.getElementById("titleId").innerHTML       = response["name"];
          document.getElementById("descriptionId").innerHTML = response["description"];
          // Nastavenie IDcka a statusu
          document.getElementById("recQuestId").value = response["id"];
          document.getElementById("recQuestStatus").value = response["status"];
          // Enable a disable prislusnych buttonov
          /*TODO*/
        }
      }
    };
    xhttp.open("GET", "../ajax/getActualQuest.php?l_id="+l_id, true);
    xhttp.send();
  }
</script>

<?php
  if (isset($_GET["id"])){
    // Premenné
    $l_id             = $_GET["id"];   // ID lekcie
    $a_quests         = [];            // Pole IDciek questov danej lekcie
    $rec_quest_id     = -1;            // ID aktualneho questu
    $rec_quest_status = 'X';           // Status aktualneho questu

    // Ziskanie info o pocte questov
    $query = "SELECT * FROM ROBOCODE.QUESTS_MAP WHERE fk_lesson_id = ".$l_id;
    $query_run = mysqli_query($con,$query);
    if (mysqli_num_rows($query_run)){
      while ($row = mysqli_fetch_assoc($query_run)){
        $a_quests[] = $row["fk_quest_id"];
      }
    }
    // Vytvorenie hidden elementu pre lesson id
    echo '<input type="hidden" id="lessonId" value="'.$l_id.'">';
    // Vytvorenie záznamov / načítanie záznamov z QUESTS_SOLVERS
    $query = "SELECT MIN(fk_quest_id) as q_id FROM ROBOCODE.QUESTS_SOLVERS WHERE fk_lesson_id = ".$l_id." AND fk_user_id = ".$_SESSION["user_id"]." AND STATUS IN ('N','P')";
    $query_run = mysqli_query($con,$query);
    if (mysqli_num_rows($query_run)){
      if ($row = mysqli_fetch_assoc($query_run)){
        if (is_numeric($row["q_id"])){
          // Záznamy už existujú -> vytvor hidden element s najaktualnejsim IDckom doposial nevyrieseneho questu
          $rec_quest_id = $row["q_id"];
          echo '<input type="hidden" id="recQuestId" value='.$rec_quest_id.'>';
          $query = "SELECT status FROM ROBOCODE.QUESTS_SOLVERS WHERE fk_lesson_id = ".$l_id." AND fk_user_id = ".$_SESSION["user_id"]." AND fk_quest_id = ".$rec_quest_id;
          $query_run = mysqli_query($con,$query);
          if (mysqli_num_rows($query_run)){
            if ($row = mysqli_fetch_assoc($query_run)){
              $rec_quest_status = $row["status"];
              echo '<input type="hidden" id="recQuestStatus" value="'.$rec_quest_status.'">';
            }
          }
        } else{
          // User nemá N/P status zaznamy pre danú lekciu, zisti, či nemá záznam / splnil lekciu
          $query = "SELECT * FROM ROBOCODE.QUESTS_SOLVERS WHERE fk_lesson_id = ".$l_id." AND fk_user_id = ".$_SESSION["user_id"];
          $query_run = mysqli_query($con,$query);
          if (mysqli_num_rows($query_run)){
            // Má záznam
            echo '<input type="hidden" id="recQuestId" value=0>';
            echo '<input type="hidden" id="recQuestStatus" value="Y">';
          } else{
            // Nemá záznam -> vytvor mu ich
            foreach ($a_quests as $quest){
              $query = "INSERT INTO ROBOCODE.QUESTS_SOLVERS(fk_lesson_id,fk_quest_id,fk_user_id,status) VALUES (".$l_id.",".$quest.",".$_SESSION["user_id"].",'N')";
              $query_run = mysqli_query($con,$query);
            }
            $rec_quest_id     = reset($a_quests);
            $rec_quest_status = 'N';
            // Vytvorenie hidden elementu s najaktualnejsim questom
            echo '<input type="hidden" id="recQuestId" value='.$rec_quest_id.'>';
            echo '<input type="hidden" id="recQuestStatus" value="N">';
          }
        }
      }
    }

    if ($stmt = mysqli_prepare($con,"SELECT name, description FROM ROBOCODE.LESSONS WHERE id = ? AND valid = 'Y'")){
      if (mysqli_stmt_bind_param($stmt,"i",$l_id)){
        if (mysqli_stmt_execute($stmt)){
          if (mysqli_stmt_bind_result($stmt,$l_name,$l_desc)){
            if (mysqli_stmt_fetch($stmt)){
              echo '<br><hr><h1 id="titleId" style="text-align:center;">'.$l_name.'</h1><hr><br>';
              echo '<h4 id="descriptionId">'.$l_desc.'</h4>';
              mysqli_stmt_close($stmt);
              // Button pre skrytie popisu lekcie, etc. a načítanie najaktuálnejšieho questu pre daného user-a
              echo '<div style="text-align:center">';
              echo '  <input class="btn btn-info my-2 my-sm-0"  onclick="hideLesson(this)" type="button" value="Úlohy" id="submit_btn_id">';
              echo '  <input class="btn btn-warning my-2 my-sm-0" onclick="validateQuest(this)" style="display:none" type="button" value="Kotrola" id="control_quest_btn_id">&nbsp&nbsp';
              echo '  <input class="btn btn-info my-2 my-sm-0"  onclick="getNextQuest()" style="display:none" type="button" value="Ďalej" id="next_quest_btn_id" disabled>';
              echo '</div>';
            }
          }
        }
      }
    }
  }
?>


</div>

<?php
  include '../includes/end.html';
?>

<!--<embed src="../lessons/edkniha1.pdf" style="width: 100%;height: 100%;border: none;" /> -->
