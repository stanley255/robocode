<?php
  require 'dbconfig/config.php';
  include 'includes/links.html';
  include 'includes/navbar.php';
  // Ak je pouzivatel prihlaseny -> redirect na dashboard | index (na zakl. privilege)
  if (!(empty($_SESSION['privilege']))){
    if ($_SESSION['privilege']==1){
      header('location:index.php');
    } else{
      header('location:dashboard.php');
    }
  }
?>
<div class="container pagination">
  <div class="col-md-3"></div>
  <div class="col-md-6 text-center">
      <form action="login.php" method="post">
      <hr><h3>Prihlás sa!</h3><hr>
      <div class="form-group">
        <label for="usr">Prihlasovacie meno:</label>
        <input type="text" class="form-control" id="usernameId" name="username" required>
      </div>
      <div class="form-group">
        <label for="pwd">Heslo:</label>
        <input type="password" class="form-control" id="passwordId" name="password" required>
      </div>
      <input class="btn btn-outline-info my-2 my-sm-0" type="submit" value="Prihlásiť" name="submit_btn">
      <hr>
      </form>
  </div>
  <div class="col-md-3"></div>
</div>
<?php
  include 'includes/end.html';
  // Prihlásenie
  if (isset($_POST['submit_btn'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $retrievedUsername = '';
    $retrievedPassword = '';
    $retrievedPrivilege = 0;
    // Získanie zahashovaného hesla + overenie správneho username
    if ($stmt = mysqli_prepare($con,"SELECT username, password, privilege FROM ROBOCODE.USERS WHERE username = ?")){
      mysqli_stmt_bind_param($stmt,"s",$username);
      if (mysqli_stmt_execute($stmt)){
        mysqli_stmt_bind_result($stmt,$retrievedUsername,$retrievedPassword,$retrievedPrivilege);
        if(mysqli_stmt_fetch($stmt)){
          // Našielo sa heslo k danému username-u
          if (password_verify($password,$retrievedPassword)){
            // Heslá sa zhodujú
              // Nastavenie session variables
            $_SESSION['privilege'] = $retrievedPrivilege;
            $_SESSION['username']  = $retrievedUsername;
              // Redirect spätne na login -> index || dashboard
            mysqli_stmt_close($stmt);
            header('location:login.php');
          } else{
            // Heslá sa nezhodujú
            echo '<script>alert("Nesprávne zadané heslo!");</script>';
          }
        } else{
          // Nenašlo sa heslo k danému username-u
          echo '<script>alert("Nesprávne používateľské meno!");</script>';
        }
      } else{
        echo '<script>alert("Nepodarilo sa prihlásiť!");</script>';
      }
      mysqli_stmt_close($stmt);
    }
  }
?>
