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
    // Ziskanie info o pocte questov
    $query = "SELECT * FROM "; /* TODO */
    $query_run = ;
    // Vytvorenie záznamov / načítanie záznamov z QUESTS_SOLVERS
    $query = "SELECT MAX(fk_quest_id) as q_id FROM ROBOCODE.QUESTS_SOLVERS WHERE fk_lesson_id = ".$_GET["id"]." AND fk_user_id = ".$_SESSION["user_id"]." AND STATUS IN ('N','P')";
    $query_run = mysqli_query($con,$query);
    if (mysqli_num_rows($query_run)){
      // Záznamy už existujú -> vytvor hidden element s najaktualnejsim IDckom doposial nevyrieseneho questu
    } else{
      // User otvoril lekciu prvý krát -> vytvor mu záznamy pre všetky questy v lekcií

    }

    $id = $_GET["id"];
    if ($stmt = mysqli_prepare($con,"SELECT name, description FROM ROBOCODE.LESSONS WHERE id = ? AND valid = 'Y'")){
      if (mysqli_stmt_bind_param($stmt,"i",$id)){
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
