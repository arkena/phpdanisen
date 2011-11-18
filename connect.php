<?php
  // database connection routine
  $dbconn = pg_connect("dbname=db user=user password=password")
	or die ("Unable to connect");
?>
