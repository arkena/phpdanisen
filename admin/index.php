<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1"
 http-equiv="Content-Type">
  <title>index.php</title>
  <link rel="stylesheet" href="../style.css">
  <link rel="icon" type="image/png" href="../favicon.ico">
</head>

<body>

<div style="text-align: center;">
<h1><span style="font-weight: bold;">Admin</span></h1>

<h3>Add a new match</h3>
<form method="post" action="../matches.php">
<p>
<?php 
// display player names in the dropdown list
include("../connect.php");
$query = "SELECT name,id FROM player;";
$result = pg_query($dbconn,$query); ?>
<select name="player1">
<?php
while($row = pg_fetch_row($result)) {
echo "<option value=" . '"' . $row[1] . '"' . ">" . $row[0] . "</option>" ;
}
?>
</select>
Score: <input type="text" maxlength="2" size="2" name="score1">
<br />
<?php
$result = pg_query($dbconn,$query); ?>
<select name="player2">
<?php
while($row = pg_fetch_row($result)) {
echo "<option value=" . '"' . $row[1] . '"' . ">" . $row[0] . "</option>" ;
}
?>
</select>
Score: <input type="text" maxlength="2" size="2" name="score2">
</p>
<input type="submit" value="Create match" />
</form>

<hr />

<h3>Add a new player</h3>
<form method="post" action="../index.php">
<p>
Player name: <input type="text" maxlength="32" size="8" name="playername" />
<input type="submit" value="Create player" />
</form>

<br />
<hr />
Back to <a href="../index.php">Home page</a>
</p>
</div>
</body>
</html>
