<?php
  require 'dbconfig/config.php';
  include 'includes/links.html';
  include 'includes/navbar.php';
  // Ak je pouzivatel prihlaseny -> redirect
  if ($_SESSION['privilege']==1){
    header('location:index.php');
  } else if ($_SESSION['privilege']>1){
    header('location:dashboard.php');
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
    $username           = $_POST['username'];
    $password           = $_POST['password'];
    $retrievedUsername  = '';
    $retrievedPassword  = '';
    $retrievedPrivilege = 0;
    $retrievedId        = 0;
    // Získanie zahashovaného hesla + overenie správneho username
    if ($stmt = mysqli_prepare($con,"SELECT id ,username, password, privilege FROM ROBOCODE.USERS WHERE username = ?")){
      mysqli_stmt_bind_param($stmt,"s",$username);
      if (mysqli_stmt_execute($stmt)){
        mysqli_stmt_bind_result($stmt,$retrievedId,$retrievedUsername,$retrievedPassword,$retrievedPrivilege);
        if(mysqli_stmt_fetch($stmt)){
          // Našielo sa heslo k danému username-u
          if (password_verify($password,$retrievedPassword)){
            // Heslá sa zhodujú
              // Nastavenie session variables
            $_SESSION['privilege'] = $retrievedPrivilege;
            $_SESSION['username']  = $retrievedUsername;
            $_SESSION['user_id']   = $retrievedId;
            mysqli_stmt_close($stmt);
            // Redirect na dashboard | index (na zakl. privilege)
            echo '<script>alert(0)</script>';
            if ($_SESSION['privilege']>1){
              header('location:dashboard.php');
            } else{
              header('location:index.php');
            }
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
