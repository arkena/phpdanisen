<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1"
 http-equiv="Content-Type">
  <title>Danisen status</title>
  <link rel="stylesheet" href="style.css">
  <link rel="icon" type="image/png" href="favicon.ico">
</head>

<?php 
include("vars.php"); 
include("functions.php");
include("connect.php");

$scorewidth=10; // width for the score column (in percentile)

?>

<body>
<div style="text-align: center;">
<h1><span style="font-weight: bold;">Danisen status</span></h1>
<?php
if (isset($_POST['playername'])) {
// coming back from the admin page, create a player if POST set
  echo "Player " . $_POST['playername'] . " created !";
  create_player($_POST['playername'],$dbconn);
}
?>

<table style="text-align: center; width: 100%;" border="1" cellpadding="2"
 cellspacing="2">
  <tbody>
    <tr>
      <td style="vertical-align: top;" width="<?php echo $scorewidth; ?>%">
      </td>
      <?php for ($column=$DANMIN;$column>0;$column--) // creating the columns with the league numbers
        { ?>
        <td style="vertical-align: top; text-align: center;" width="<?php $width=(100-$scorewidth)/$DANMIN ; echo $width; ?>%">Dan <?php echo $column; ?><br>
        </td>
       <?php } ?>
    </tr>
<?php 

// looping on the scores
for ($playerscore = $SCOREMAX; $playerscore >$SCOREMIN-1; $playerscore--)
 { ?>
    <tr>
      <td style="vertical-align: top; text-align: center;"><?php echo $playerscore ?><br>
      </td>
<?php
      // looping on the dan
      for ($playerleague=$DANMIN;$playerleague>0;$playerleague--) 
      { ?>
        <td style="vertical-align: top; text-align: center;">
        <?php 
        $query = "SELECT id,name FROM player WHERE score=" . $playerscore . " AND in_league='" . $playerleague . "';";
        $names = pg_query($dbconn,$query);
        while ($playerlist = pg_fetch_row($names)) { ?>
          <a href="player.php?id=<?php echo $playerlist[0]; ?>"><?php echo $playerlist[1]; ?></a>
        <?php
        } ?>
      <br>
      </td>
<?php
      } ?>
    </tr>
<?php } ?>
  </tbody>
</table>
<br />
<h4><a href="matches.php">See all matches</h4>
<small><a href="contact.php">Contact the webmaster</a> - <a href="./admin/">Admin page</a></small>
</div>
</body>
</html>
