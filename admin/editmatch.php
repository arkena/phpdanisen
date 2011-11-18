<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1"
 http-equiv="Content-Type">
  <title>Matches</title>
  <link rel="stylesheet" href="../style.css">
</head>

<?php 
include("../connect.php");
include("../functions.php"); 

$match=pg_fetch_row(get_match($_GET["id"],$dbconn));

// this page will mostly contain the same code than match.php
// there probably is a much cleaner way to not replicate the code
// honestly, I don't care for now, I might look at it later

?>

<body>
<div style="text-align: center;">
<h1>Edit match</h1>
<hr />
<h3>Match date <?php echo($match[5]); ?></h3>
<form method="post" action="../matches.php?id=<?php echo $_GET["id"]; ?>">
<div align="center"><table style="text-align: center; width:60%;" border="1" cellpadding="2" cellspacing="2">
 <tbody>
  <tr>
   <td style="vertical-align: top; text-align: center;" width=50%>
     <?php if ($match[4]<0) { // if opponent score < 0, trollface ?>
     <img src="../troll-face.png" alt="Trollface" title="Problem ?" />
     <?php } else { // else normal victory face ?>
     <img src="../fuck-yea.png" alt="Fuck yeah victory" title="Victory is mine" />
     <?php } ?>
   </td>
   <td style="vertical-align: top; text-align: center;" width=50%>
     <?php if ($match[2]-$match[4]>8) { // if big score difference fuuu ?>
     <img src="../rage-guy.png" alt="FUUUUUUUU" title="FUUUUUUUUUUUUUUU" />
     <?php } else { // else normal okayface ?>
     <img src="../okay-face.png" alt="Okay" title="Okay, I lost." />
     <?php } ?>
   </td>
  </tr>
  <tr>
   <td style="vertical-align: top; text-align: center;"><a href="player.php?id=<?php echo $match[1]; ?>"><?php echo(get_player_name($match[1],$dbconn)); ?></a> <input type="text" maxlength="3" size="3" name="winner" value="<?php echo($match[2]); ?>" /></td>
   <td style="vertical-align: top; text-align: center;"><a href="player.php?id=<?php echo $match[3]; ?>"><?php echo(get_player_name($match[3],$dbconn)); ?></a> <input type="text" maxlength="3" size="3" name="loser" value="<?php echo($match[4]); ?>" /></td>
  </tr>
 </tbody>
</table>
</div>
<input type="submit" value="Modify scores" />
</form>
<hr />
<form method="post" action="../matches.php?id=<?php echo $_GET["id"]; ?>">
<input type="hidden" name="delete">
<input type="submit" value="Delete match" />
</form>
<p>
Back to <a href="../index.php">Home page</a>
</p>
</div>
</body>
</html>
