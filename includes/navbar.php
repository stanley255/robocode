<?php
  session_start();
  if (empty($_SESSION["privilege"])){
    $_SESSION["privilege"] = 0;
  }
?>
<body>
  <header>
  <nav class="navbar navbar-expand-md fixed-top">
    <?php
    if ($_SESSION['privilege']>1){
      echo '<a class="navbar-brand" href="/robocode/dashboard.php">ROBO CODE</a>';
    } else{
      echo '<a class="navbar-brand" href="/robocode/index.html">ROBO CODE</a>';
    }
    ?>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbar2">
    <?php
      if (!(empty($_SESSION['privilege'])) and $_SESSION['privilege']>1){
      echo '<ul class="navbar-nav mr-auto">';
      echo '  <li class="nav-item dropdown">';
      echo '    <a class="nav-link dropdown-toggle" href="" id="dropdown02" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Lekcie</a>';
      echo '    <div class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdown02">';

        $query = "SELECT id, name FROM ROBOCODE.LESSONS WHERE valid = 'Y'";
        $query_run = mysqli_query($con,$query);
        if (mysqli_num_rows($query_run)){
          while ($row = mysqli_fetch_assoc($query_run)){
            echo '  <a class="dropdown-item" href="/robocode/lessons/lesson.php?id='.$row['id'].'" >'.$row['name'].'</a>';
          }
        }
        echo '    </div>';
        echo '  </li>';
        echo '  <li class="nav-item">';
        echo '    <a class="nav-link" href="/robocode/materials.php">Materiály</a>';
        echo '  </li>';
        echo '</ul>';
      } else{
        echo '<ul class="navbar-nav mr-auto">';
        echo '</ul>';
      }
      ?>
      <ul class="nav navbar-nav navbar-right">
      <?php
      if ($_SESSION["privilege"]==4){
        echo '<li class="nav-item">';
        echo '  <a class="nav-link" href="/robocode/admin/admin.php">ADMIN</a>';
        echo '</li>';
      }
      if($_SESSION["privilege"]==0){
        echo '<li class="nav-item">';
        echo '  <a class="nav-link" href="#">O NÁS</a>';
        echo '</li>';
      }
      ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="" id="dropdown02" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">PROFIL</a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown02">
          <?php
            if ($_SESSION['privilege']>=1){
              echo '<a class="dropdown-item" href="/robocode/profile.php">'.$_SESSION["username"].'</a>';
              echo '<a class="dropdown-item" href="/robocode/session/logout.php">ODHLÁSENIE</a>';
            } else{
              echo '<a class="dropdown-item" href="/robocode/session/login.php">PRIHLÁSENIE</a>';
              echo '<a class="dropdown-item" href="/robocode/session/register.php">REGISTRÁCIA</a>';
            }
          ?>
          </div>
        </li>
      </ul>
    </div>
  </nav>-
  <hr>
</header>
