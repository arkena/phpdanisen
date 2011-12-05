<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<?php 
include("functions.php");
include("connect.php");
?>
<head>
  <meta content="text/html; charset=ISO-8859-1"
 http-equiv="Content-Type">
  <title>Player status</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
<div style="text-align: center;">
<?php 
if (isset($_GET["id"])) {

if (isset($_POST["name"]))
{
  edit_player_name($_GET["id"],$_POST["name"],$dbconn);
} 

// in case of inconsistency, we recalculate the score for the player
// reset_score($_GET["id"],$dbconn);

$wins=get_win_count($_GET["id"],$dbconn);
$losses=get_loss_count($_GET["id"],$dbconn);

?>

<h1><span style="font-weight: bold;"><?php echo get_player_name($_GET["id"],$dbconn);?></span></h1>
<br />

<h3>Ranking : dan <?php echo get_player_league($_GET["id"],$dbconn); ?></br>
Score : <?php echo get_player_score($_GET["id"],$dbconn); ?></br></h3>

<h4>Tournaments played :  <a href="matches.php?player=<?php echo $_GET["id"] ?>"><?php echo $wins+$losses ?></a></h4><br />
<h5>Victories : <a href="matches.php?winner=<?php echo $_GET["id"] ?>"><?php echo $wins ?></a><br />
Defeats : <a href="matches.php?loser=<?php echo $_GET["id"] ?>"><?php echo $losses ?></a><br />
Ratio : <?php 
  if ($wins+$losses != 0) {
    echo round(100*$wins/($wins+$losses), 2);
    } else { echo 0; } ?>%<br />
Average score : <?php echo get_player_average($_GET["id"],$dbconn);  ?>
</h5>
<br />
<small>( <a href="admin/editplayer.php?id=<?php echo $_GET["id"] ?>">Edit player name</a> )</small>
<?php
} ?>
<p>
Back to <a href="index.php">Home page</a>
</p>
</div>
</body>
</html>
