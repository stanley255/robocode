<?php
  require 'dbconfig/config.php';
  include 'includes/links.html';
  include 'includes/navbar.php';
  // Pristupy
  if (empty($_SESSION['privilege']) or $_SESSION['privilege']==1){
    header('location:index.php');
  }
?>

<div class="container pagination">
  <div class="col-md-2"></div>
  <div class="col-md-8 text-left">
<?php
echo '<br><h3 class="text-center">OZNAMY</h3>';
  // Zobrazenie pinnutych oznamov
    // Ziskanie pinnutych zaznamov z databazy
  $query = "SELECT date, username, text FROM ROBOCODE.V_POSTS WHERE pinned = 'Y'";
  $query_run = mysqli_query($con,$query);
  if (mysqli_num_rows($query_run)){
    // Vypisanie pinnutych zaznamov
    while ($row = mysqli_fetch_assoc($query_run)){
    // Ziskanie pozadovaneho formatu datumu postu
    $shownDate = DATE('d.m.Y',strtotime($row["date"]));
    // Vypisanie karty postu
    echo '<br>';
    echo '<div class="card border-primary">';
    echo '  <div class="card-body">';
    echo '    <p>'.$row["text"].'</p>';
    echo '    <div class="text-muted text-right">';
    echo        $row["username"].', '.$shownDate;
    echo '    </div>';
    echo '  </div>';
    echo '</div>';
    }
    echo '<br><hr>'; // Oddelovac medzi pinned a unpinned clankami
  } /*else{
    // Nepodarilo sa ziskat pinnute zaznamy
    echo '<script>alert("Nepodarilo sa získať pinnuté záznamy / žiadne neexistujú!");</script>';
  }*/
  // Zobrazenie prispevkov DESC na zaklade datumu (prvych 10)
    $query = "SELECT date, username, text FROM ROBOCODE.V_POSTS WHERE pinned = 'N' LIMIT 10";
    $query_run = mysqli_query($con,$query);
    if (mysqli_num_rows($query_run)){
      // Vypisanie pinnutych zaznamov
      while ($row = mysqli_fetch_assoc($query_run)){
      // Ziskanie pozadovaneho formatu datumu postu
      $shownDate = DATE('d.m.Y',strtotime($row["date"]));
      // Vypisanie karty postu
      echo '<br>';
      echo '<div class="card border-info">';
      echo '  <div class="card-body">';
      echo '    <p>'.$row["text"].'</p>';
      echo '    <div class="text-muted text-right">';
      echo        $row["username"].', '.$shownDate;
      echo '    </div>';
      echo '  </div>';
      echo '</div>';
      }
    } /*else{
      echo '<script>alert("Nepodarilo sa získať nepinnuté záznamy / žiadne neexistujú!");</script>';
    }*/
    echo '<br>';
?>
  </div>
  <div class="col-md-2"></div>
</div>

<?php
  include 'includes/end.html';
?>
