<?php
  require 'dbconfig/config.php';
  include 'includes/links.html';
  include 'includes/navbar.php';
  // Pristupy
  if (empty($_SESSION['privilege']) or $_SESSION['privilege']!=4){
    header('location:index.php');
  }
?>
<script>
function controlForm(){
  if (document.getElementById('textId').value).length == 0){
    alert('Zadajte svoj oznam, prosím!');
    return false;
  } else{
    return true;
  }
}
</script>

<div class="container pagination">
  <div class="col-md-3"></div>
  <div class="col-md-6 text-center">
      <form action="adm_posts_add.php" method="post">
      <hr><h3>Pridať oznam:</h3><hr>
      <div class="form-group">
        <label>Oznam:</label>
        <textarea class="form-control" rows="5" name="text" id="textId"></textarea>
      </div>
      <div class="form-inline">
        <label>Pripnutý príspevok:&nbsp</label>
        <select class="form-control" name="pinned">
          <option value="Y">Áno</option>
          <option value="N" selected>Nie</option>
        </select>
      </div>
      <br>
      <input class="btn btn-outline-info my-2 my-sm-0" type="submit" value="Pridať" name="submit_btn">
      <hr>
      </form>
  </div>
  <div class="col-md-3"></div>
</div>
<?php
  include 'includes/end.html';

  // POST PART
  if (isset($_POST['submit_btn'])){
    // Ziskanie premmenych z form-u
    $text = $_POST["text"];
    $pinned = $_POST["pinned"];
    $date = DATE("Y-m-d");
    // Udaj o user_id sa nachadza v session variables

    // Prepared statement na vlozenie oznamu do tabulky
    if ($stmt = mysqli_prepare($con, "INSERT INTO ROBOCODE.POSTS(date,fk_user_id,text,pinned) VALUES(?,?,?,?)")){
      if (mysqli_stmt_bind_param($stmt,"siss",$date,$_SESSION['user_id'],$text,$pinned)){
        if (mysqli_stmt_execute($stmt)){
          echo '<script>alert("Oznam sa podarilo pridať!")</script>';
          header('location:dashboard.php');
          mysqli_stmt_close($stmt);
        } else{
          echo '<script>alert("Oznam sa nepodarilo pridať!")</script>';
        }
      } else{
        echo '<script>alert("Oznam sa nepodarilo pridať!")</script>';
      }
    }
  }


?>
