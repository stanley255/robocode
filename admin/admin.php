<?php
  require '../dbconfig/config.php';
  include '../includes/links.html';
  include '../includes/navbar.php';
  // Pristupy
  if (empty($_SESSION['privilege']) or $_SESSION['privilege']!=4){
    header('location:../index.html');
  }
?>
<div class="container pagination">
  <div class="col-md-2"></div>
  <div class="col-md-8 text-center">
    <br>
    <hr>
      <h3>OZNAMY</h3>
        <a class="btn btn-outline-info my-2 my-sm-0 active" href="/robocode/admin/adm_posts_add.php">Pridaj oznam</a>
        <a class="btn btn-outline-info my-2 my-sm-0 active" href="/robocode/admin/adm_posts_manage.php">Spravuj oznamy</a>
    <hr>
    <hr>
      <h3>POUŽÍVATELIA</h3>
      <a class="btn btn-outline-info my-2 my-sm-0 active" href="/robocode/admin/adm_edit_user.php">Privilégiá</a>
    <hr>
    <hr>
      <h3>DOCHÁDZKA</h3>
      <a class="btn btn-outline-info my-2 my-sm-0 active" href="/robocode/admin/adm_attendance_show.php">Zobraz</a>
      <a class="btn btn-outline-info my-2 my-sm-0 active" href="/robocode/admin/adm_attendance_add.php">Pridaj</a>
    <hr>
    <hr>
    <h3>LEKCIE</h3>
    <a class="btn btn-outline-info my-2 my-sm-0" href="/robocode/admin/adm_lessons.php">Pridaj</a>
    <hr>
  </div>
  <div class="col-md-2"></div>
</div>
<?php
  include '../includes/end.html';
?>
