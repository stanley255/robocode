<?php
  require '../dbconfig/config.php';
  include '../includes/links.html';
  include '../includes/navbar.php';
?>

<script>
  function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
  }

  function validateForm(){
    var email =       document.getElementById('emailId').value;
    var password =    document.getElementById('passwordId').value;
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
          <input id="teamGreen" type="radio" name="team" value="1" required/>
          <label class="team-cc teamGreen" for="teamGreen"></label>
          <input id="teamRed" type="radio" name="team" value="2" />
          <label class="team-cc teamRed" for="teamRed"></label>
          <input id="teamYellow" type="radio" name="team" value="3" />
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
    $username     = $_POST['username'];
    $name         = $_POST['name'];
    $surname      = $_POST['surname'];
    $email        = $_POST['email'];
    $password     = $_POST['password'];
    $password_ver = $_POST['passwordVer'];
    $team         = $_POST['team'];
    $date         = DATE("Y-m-d");
    $exp          = 0;
    $privilege    = 1;
    // Kedže e-mail je nepovinný, treba overiť, či je definovaný
    if (empty($email)){
      $email='';
    }
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
                // Hash hesla
                $password = password_hash($password,PASSWORD_DEFAULT);
                // Vlozenie pouzivatelskych udajov do tabulky users
                if ($stmt = mysqli_prepare($con, "INSERT INTO ROBOCODE.USERS(username,name,surname,email,password,registration_date,team_id,exp,privilege) VALUES(?,?,?,?,?,?,?,?,?)")){
                  if (mysqli_stmt_bind_param($stmt,"sssssssii",$username,$name,$surname,$email,$password,$date,$team,$exp,$privilege)){
                    if (mysqli_stmt_execute($stmt)){
                      // Záznam sa podarilo uložiť
                      echo '<script>alert("Registrácia bola úspešná, môžete sa prihlásiť!");window.location.replace("../login.php");</script>';
                      mysqli_stmt_close($stmt);
                    } else{
                      // Nepodarilo sa uložiť...
                      echo '<script>alert("Registrácia bola neúspešná! '.mysqli_error($con).'")</script>';
                    }
                  } else{
                    echo '<script>alert("Registrácia bola neúspešná!")</script>';
                  }
                } else{
                  echo '<script>alert("Registrácia bola neúspešná!")</script>';
                }
            } else{
                // Ak nebolo heslo správne zadané
                echo '<script>alert("Zadané heslá sa nezhodujú!")</script>';
            }
        } else{
            // Ak nie je zadané užívateľské meno prístupné
            echo '<script>alert("Dané používateľské meno sa už používa!")</script>';
        }
        mysqli_close($con);
    }
  }

  include '../includes/end.html';
?>
