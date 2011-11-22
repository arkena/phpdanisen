<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1"
 http-equiv="Content-Type">
  <title>Matches</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
<div style="text-align: center;">
<h1>Tournaments</h1>
<p>
Back to <a href="index.php">Home page</a>
</p>
<hr />
<?php 

include("connect.php");
include("functions.php");

// **************** GET method specific code ****************
// I've been wondering if I could get this out of here to fit
// info functions.php but I'm not sure if it's possible. I'll
// leave it up to whoever will try to dig into this crap in
// the future.

$queryadd = ""; // this is what we add to the final query

if (isset($_POST['score1']) && isset($_POST['score2']))
// if we're creating a new match from the admin page
{

  if ($_POST['score1'] == $_POST['score2']) {
    echo "Draw game. No update.";
  } else {
  // if this is not a "draw game"
    if ($_POST['score1'] > $_POST['score2']) {
      create_match($_POST['player1'],$_POST['score1'],$_POST['player2'],$_POST['score2'],$dbconn);
      add_point($_POST['player1'],$dbconn);
      remove_point($_POST['player2'],$dbconn);
    } else {
      create_match($_POST['player2'],$_POST['score2'],$_POST['player1'],$_POST['score1'],$dbconn);
      add_point($_POST['player2'],$dbconn);
      remove_point($_POST['player1'],$dbconn);
    } // end of match creation
  } // end of not "draw game" condition
?><a href="./admin/">Back to admin page</a><?php
}

if (isset($_GET["player"])) 
// if we're looking at all the matches played
{
  $queryadd = $queryadd . "AND (winner_id='" . $_GET["player"] . "' OR loser_id='" . $_GET["player"] . "') ";
}
if (isset($_GET["winner"])) {
  $queryadd = $queryadd . "AND winner_id='" . $_GET["winner"] . "' ";
}
if (isset($_GET["loser"])) {
  $queryadd = $queryadd . "AND loser_id='" . $_GET["loser"] . "' ";
}

if (isset($_GET["id"])) { // when looking at a specific match 
  if (isset($_POST["delete"])) { // we might want to delete this match
      delete_match($_GET["id"],$dbconn);
  } else {
    if (isset($_POST["winner"]) && isset($_POST["loser"])) // or edit scores
    { 
      edit_match($_GET["id"],$_POST["winner"],$_POST["loser"],$dbconn);
    }
  $queryadd = $queryadd . "AND id='" . $_GET["id"] . "' ";
  }
}

$querymatch = "SELECT * FROM match WHERE (id>'0' " . $queryadd . ") ORDER BY id DESC;";

// ********************************************************
// **************** End of GET method code ****************
// ********************************************************

$matches=pg_query($dbconn,$querymatch);

while ($rowmatch=pg_fetch_row($matches)) { // displaying all matches
?>

<h3><?php echo($rowmatch[5]); ?></h3>
<div align="center"><table style="text-align: center; width:60%;" border="1" cellpadding="2" cellspacing="2">
 <tbody>
  <tr>
   <td style="vertical-align: top; text-align: center;" width=50%>
     <?php if ($rowmatch[4]<0) { // if opponent score < 0, trollface ?>
     <img src="troll-face.png" alt="Trollface" title="Problem ?" />
     <?php } else { // else normal victory face ?>
     <img src="fuck-yea.png" alt="Fuck yeah victory" title="Victory is mine" />
     <?php } ?>
   </td>
   <td style="vertical-align: top; text-align: center;" width=50%>
     <?php if ($rowmatch[2]-$rowmatch[4]>8) { // if big score difference fuuu ?>
     <img src="rage-guy.png" alt="FUUUUUUUU" title="FUUUUUUUUUUUUUUU" />
     <?php } else { // else normal okayface ?>
     <img src="okay-face.png" alt="Okay" title="Okay, I lost." />
     <?php } ?>
   </td>
  </tr>
  <tr>
   <td style="vertical-align: top; text-align: center;"><a href="player.php?id=<?php echo $rowmatch[1]; ?>"><?php echo(get_player_name($rowmatch[1],$dbconn)); ?></a> (<?php echo($rowmatch[2]); ?>)</td>
   <td style="vertical-align: top; text-align: center;"><a href="player.php?id=<?php echo $rowmatch[3]; ?>"><?php echo(get_player_name($rowmatch[3],$dbconn)); ?></a> (<?php echo($rowmatch[4]); ?>)</td>
  </tr>
 </tbody>
</table>
<small><a href="admin/editmatch.php?id=<?php echo $rowmatch[0]; ?>">Edit match</a></small>
</div>
<br />
<?php 
} ?>

<hr />
<p>
Back to <a href="index.php">Home page</a>
</p>
</div>
</body>
</html>
