<?php
  include 'includes/links.html';
  include 'includes/navbar.html';
?>

<div class="container pagination">
  <div class="col-md-4"></div>
  <div class="col-md-4 text-center">
      <form action="register.php" method="post">
      <hr><h3>Pridaj sa k nám!</h3><hr>
      <div class="form-group">
        <label for="usr">Prihlasovacie meno:</label>
        <input type="text" class="form-control" id="" name="" required>
      </div>
      <div class="form-group">
        <label for="pwd">Meno:</label>
        <input type="password" class="form-control" id="" name="" required>
      </div>
      <div class="form-group">
        <label for="pwd">Priezvisko:</label>
        <input type="text" class="form-control" id="" name="" required>
      </div>
      <div class="form-group">
        <label for="pwd">E-mail:</label>
        <input type="text" class="form-control" id="" name="">
      </div>
      <div class="form-group">
        <label for="pwd">Heslo:</label>
        <input type="password" class="form-control" id="" name="" required>
      </div>
      <div class="form-group">
        <label for="pwd">Potvrď heslo:</label>
        <input type="password" class="form-control" id="" name="" required>
      </div>
      <input class="btn btn-outline-info my-2 my-sm-0" type="submit" value="Registruj" name="submit_btn">
      </form>
  </div>
  <div class="col-md-4"></div>
</div>



<?php
  include 'includes/end.html';
?>
