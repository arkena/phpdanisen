<?php
  // database connection routine
  $dbconn = pg_connect("dbname=quakedb user=quakeuser password=quakepassword")
	or die ("Unable to connect");
?>
