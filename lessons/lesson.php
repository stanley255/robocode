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
