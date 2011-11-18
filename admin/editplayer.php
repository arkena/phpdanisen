<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1"
 http-equiv="Content-Type">
  <title>Edit player</title>
  <link rel="stylesheet" href="../style.css">
</head>

<?php
include("../connect.php");
include("../functions.php");

// we don't do much in here other than changing the player name

$name = get_player_name($_GET["id"],$dbconn);

?>

<body>
<div style="text-align: center;">
<h1><span style="font-weight: bold;"><?php echo $name; ?></span></h1>
<br />
<form method="post" action="../player.php?id=<?php echo $_GET["id"]; ?>">
<p>
Player name: <input type="text" maxlength="32" size="8" name="name" value="<?php echo $name; ?>" />
<input type="submit" value="OK" />
</form>
<hr />
<p>
Back to <a href="../index.php">Home page</a>
</p>
</div>
</body>
</html>
