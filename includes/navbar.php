<?php
  session_start();
  if (empty($_SESSION["privilege"])){
    $_SESSION["privilege"] = 0;
  }
?>
<body>
  <nav class="navbar navbar-expand-md fixed-top">
    <?php
    if ($_SESSION['privilege']>1){
      echo '<a class="navbar-brand" href="/robocode/dashboard.php">Robo Code</a>';
    } else{
      echo '<a class="navbar-brand" href="/robocode/index.php">Robo Code</a>';
    }
    ?>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
      <ul class="navbar-nav mr-auto">
      <?php
        if (!(empty($_SESSION['privilege'])) and $_SESSION['privilege']>1){
          echo '<li class="nav-item">';
          echo '  <a class="nav-link" href="/robocode/lessons.php">Lekcie</a>';
          echo '</li>';
          echo '<li class="nav-item">';
          echo '  <a class="nav-link" href="/robocode/materials.php">Materiály</a>';
          echo '</li>';
        }
      ?>
      </ul>
      <ul class="nav navbar-nav navbar-right">
      <?php
      if ($_SESSION["privilege"]==4){
        echo '<li class="nav-item">';
        echo '  <a class="nav-link" href="/robocode/admin/admin.php">Admin</a>';
        echo '</li>';
      }
      if($_SESSION["privilege"]==0){
        echo '<li class="nav-item">';
        echo '  <a class="nav-link" href="#">O nás</a>';
        echo '</li>';
      }
      ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="https://example.com" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Profil</a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown01">
          <?php
            if ($_SESSION['privilege']>=1){
              echo '<a class="dropdown-item" href="/robocode/profile.php">'.$_SESSION["username"].'</a>';
              echo '<a class="dropdown-item" href="/robocode/session/logout.php">Odhlásenie</a>';
            } else{
              echo '<a class="dropdown-item" href="/robocode/session/login.php">Prihlásenie</a>';
              echo '<a class="dropdown-item" href="/robocode/session/register.php">Registrácia</a>';
            }
          ?>
          </div>
        </li>
      </ul>
    </div>
  </nav>-
  <hr>
