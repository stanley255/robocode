<?php
  require 'dbconfig/config.php';
  include 'includes/links.html';
  include 'includes/navbar.html';
?>

<script>
  function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
  }

  function validateForm(){
    var email = document.getElementById('emailId').value;
    var password = document.getElementById('passwordId').value;
    var passwordVer = document.getElementById('passwordVerId').value;
    if (password.length < 5){
        alert("Heslo musí byť dlhé aspoň 5 znakov!");
        return false;
    }
    if (password !== passwordVer){
        alert("Zadané heslá sa nezhodujú!");
        return false;
    }
    if (validateEmail(email)==false && email.length!=0){
        alert("E-mail bol chybne vyplnený!");
        return false;
    }
    return true;
  }
</script>

<div class="container pagination">
  <div class="col-md-3"></div>
  <div class="col-md-6 text-center">
      <form action="register.php" method="post" onsubmit="return validateForm()">
      <hr><h3>Pridaj sa k nám!</h3><hr>
      <div class="form-group">
        <label for="usr">Prihlasovacie meno:</label>
        <input type="text" class="form-control" id="usernameId" name="username" required>
      </div>
      <div class="form-group">
        <label for="pwd">Meno:</label>
        <input type="text" class="form-control" id="nameId" name="name" required>
      </div>
      <div class="form-group">
        <label for="pwd">Priezvisko:</label>
        <input type="text" class="form-control" id="surnameId" name="surname" required>
      </div>
      <div class="form-group">
        <label for="pwd">E-mail:</label>
        <input type="text" class="form-control" id="emailId" name="email">
      </div>
      <div class="form-group">
        <label for="pwd">Heslo:</label>
        <input type="password" class="form-control" id="passwordId" name="password" required>
      </div>
      <div class="form-group">
        <label for="pwd">Potvrď heslo:</label>
        <input type="password" class="form-control" id="passwordVerId" name="passwordVer" required>
      </div>
      <div class="form-group">
        <label>Vyber si tím:</label>
        <div class="cc-selector">
          <input id="teamGreen" type="radio" name="team" value="teamGreen" required/>
          <label class="team-cc teamGreen" for="teamGreen"></label>
          <input id="teamRed" type="radio" name="team" value="teamRed" />
          <label class="team-cc teamRed" for="teamRed"></label>
          <input id="teamYellow" type="radio" name="team" value="teamYellow" />
          <label class="team-cc teamYellow" for="teamYellow"></label>
        </div>
      </div>
      <input class="btn btn-outline-info my-2 my-sm-0" type="submit" value="Registruj" name="submit_btn">
      <hr>
      </form>
  </div>
  <div class="col-md-3"></div>
</div>

<?php
  if (isset($_POST['submit_btn'])){
    // Inicializacia premennych
    $username = mysqli_real_escape_string($con,$_POST['username']);
    $name = mysqli_real_escape_string($con,$_POST['name']);
    $surname = mysqli_real_escape_string($con,$_POST['surname']);
    $email = mysqli_real_escape_string($con,$_POST['email']);
    $password = mysqli_real_escape_string($con,$_POST['password']);
    $password_ver = mysqli_real_escape_string($con,$_POST['passwordVer']);
    $team = mysqli_real_escape_string($con,$_POST['team']);
    // Overenie dostupnosti pouzivatelskeho mena
    if ($stmt = mysqli_prepare($con,"SELECT username FROM ROBOCODE.USERS WHERE username = ?")){
        mysqli_stmt_bind_param($stmt,"s",$username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt,$potentialName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        // Ak je zadané užívateľské meno prístupné
        if (empty($potentialName)){
            // Kontrola, či bolo heslo zadané správne
            if ($password==$password_ver){
                // Vlož údaje do databázy

            } else{
                // Ak nebolo heslo správne zadané
                echo '<script>alert("Zadané heslá sa nezhodujú!")</script>';
            }
        } else{
            // Ak nie je zadané užívateľské meno prístupné
            echo '<script>alert("Dané používateľské meno sa už používa!")</script>';
        }
    }
    // Hash hesla

    // Vlozenie pouzivatelskych udajov do tabulky users

    mysqli_close($con);
    // Presmerovanie na login a odtial na dashboard
  }

  include 'includes/end.html';
?>
