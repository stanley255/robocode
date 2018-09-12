<?php
  require '../dbconfig/config.php';
  include '../includes/links.html';
  include '../includes/navbar.php';
  if (empty($_SESSION['privilege']) or $_SESSION['privilege']==1){
    header('location:../../index.html');
  }
?>
<div class="container">
<?php
  if (isset($_GET["id"])){
    $l_id = $_GET["id"];
    $a_quests = [];
    // Ziskanie info o pocte questov
    $query = "SELECT * FROM ROBOCODE.QUESTS_MAP WHERE fk_lesson_id = ".$l_id;
    $query_run = mysqli_query($con,$query);
    if (mysqli_num_rows($query_run)){
      while ($row = mysqli_fetch_assoc($query_run)){
        $a_quests[] = $row["fk_quest_id"];
      }
    }
    // Vytvorenie záznamov / načítanie záznamov z QUESTS_SOLVERS
    $query = "SELECT MIN(fk_quest_id) as q_id FROM ROBOCODE.QUESTS_SOLVERS WHERE fk_lesson_id = ".$l_id." AND fk_user_id = ".$_SESSION["user_id"]." AND STATUS IN ('N','P')";
    $query_run = mysqli_query($con,$query);
    if (mysqli_num_rows($query_run)){
      if ($row = mysqli_fetch_assoc($query_run)){
        if (is_numeric($row["q_id"])){
          // Záznamy už existujú -> vytvor hidden element s najaktualnejsim IDckom doposial nevyrieseneho questu
          echo '<input type="hidden" id="recQuestId" value='.$row["q_id"].'>';
          $query = "SELECT STATUS FROM ROBOCODE.QUESTS_SOLVERS WHERE fk_lesson_id = ".$l_id." AND fk_user_id = ".$_SESSION["user_id"]." AND fk_quest_id = ".$row["q_id"];
          echo $query;
          $query_run = mysqli_query($con,$query);
          if (mysqli_num_rows($query_run)){
            if ($row = mysqli_fetch_assoc($query_run)){
              echo '<input type="hidden" id="recQuestStatus" value="'.$row["status"].'">';
            }
          }
        } else{
          // User otvoril lekciu prvý krát -> vytvor mu záznamy pre všetky questy v lekcií
          foreach ($a_quests as $quest){
            $query = "INSERT INTO ROBOCODE.QUESTS_SOLVERS(fk_lesson_id,fk_quest_id,fk_user_id,status) VALUES (".$l_id.",".$quest.",".$_SESSION["user_id"].",'N')";
            $query_run = mysqli_query($con,$query);
          }
          // Vytvorenie hidden elementu s najaktualnejsim questom
          echo '<input type="hidden" id="recQuestId" value='.reset($a_quests).'>'; /*TODO*/
        }
      }
    }

    if ($stmt = mysqli_prepare($con,"SELECT name, description FROM ROBOCODE.LESSONS WHERE id = ? AND valid = 'Y'")){
      if (mysqli_stmt_bind_param($stmt,"i",$l_id)){
        if (mysqli_stmt_execute($stmt)){
          if (mysqli_stmt_bind_result($stmt,$l_name,$l_desc)){
            if (mysqli_stmt_fetch($stmt)){
              echo '<br><hr><h1 style="text-align:center;">'.$l_name.'</h1><hr><br>';
              echo '<h4>'.$l_desc.'</h4>';
              // Získanie prvého questu
              /*TODO*/
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
