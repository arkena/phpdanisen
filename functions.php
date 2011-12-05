<?php

function create_player($name,$dbconn)
// function used to create a player
{
  include("vars.php");
  $query = "INSERT INTO player VALUES (nextval('player_id'),'"  . $name . "',0,'" . $DANMIN . "');";
  $result = pg_query($dbconn, $query);
  return $result; // returns a query resource
}

function delete_player($id,$dbconn)
// function used to delete a player
{
  $query = "DELETE FROM player WHERE id='" . $id . "';";
  $result = pg_query($dbconn, $query);
  return $result; // returns a query resource
}

function edit_player_name($id,$name,$dbconn)
// function used to edit a player name
{
  $query="UPDATE player SET name='" . $name . "' WHERE id='" . $id . "';";
  $result=pg_query($dbconn,$query);
  return $result; // returns a query resource
}

function get_player_name($id,$dbconn) 
// function used to get name from player id
{
  $query = "SELECT name FROM player WHERE id='" . $id . "';";
  $row = pg_fetch_row(pg_query($dbconn, $query));
  return $row[0]; // returns a string
}

function get_player_score($id,$dbconn)
// function used to get score from player id
{
  $query = "SELECT score FROM player WHERE id='" . $id . "';";
  $row = pg_fetch_row(pg_query($dbconn, $query));
  return $row[0]; // returns an int
}

function get_player_league($id,$dbconn)
// function used to get score from player id
{
  $query = "SELECT in_league FROM player WHERE id='" . $id . "';";
  $row = pg_fetch_row(pg_query($dbconn, $query));
  return $row[0]; // returns an int
}

function get_player_victories($id,$dbconn) 
// function used to get winner from matches played by player id
{  
  $query = "SELECT winner_id FROM match WHERE (winner_id='" . $id . "' OR loser_id='" . $id . "');";
  $result = pg_query($dbconn,$query);
  return $result; // returns a query resource
}

function get_player_average($id,$dbconn)
// function used to calculate average score by played id
{
  $query = "SELECT winner_id,winner_score,loser_score FROM match WHERE (winner_id='" . $id . "' OR loser_id='" . $id . "');";
  $result = pg_query($dbconn,$query);
  $total=0;
  $count=0;
  while ($tourneys = pg_fetch_row($result)) {
    $count++;
    if ($tourneys[0] == $id) {
      $total=$total+$tourneys[1];
    } else {
      $total=$total+$tourneys[2];
    }
  }
  $average=round($total/$count,2);
  return $average; // returns a float
}

function set_score($id,$score,$dbconn)
// setting score for a player
{
  $query = "UPDATE player SET score='" . $score . "' WHERE id='" . $id . "';";
  $result = pg_query($dbconn,$query);
  return $result; // returns a query resource
}

function set_score_and_league($id,$score,$league,$dbconn) 
// setting score and league for a player
{
  $query = "UPDATE player SET score='" . $score . "',in_league='" . $league . "' WHERE id='" . $id . "';";
  $result = pg_query($dbconn,$query);
  return $result; // returns a query resource
}

function add_point($id,$dbconn)
// add a point and modify league if needed 
{
  include("vars.php");
  $score=get_player_score($id,$dbconn);
  $score++;
  if ( $score > $SCOREMAX ) { // we might need to change league
    $league=get_player_league($id,$dbconn);
    $league--;
    if ( $league < $DANMAX ) { // already in top league
      set_score_and_league($id,$SCOREMAX,$DANMAX,$dbconn); // player stays at maximal score top league
    } else {
      set_score_and_league($id,0,$league,$dbconn); // go to upper league, score back to 0
    } 
  } else { // no league change
    set_score($id,$score,$dbconn);
  }
  return 0; // returns 0 upon exit
}

function remove_point($id,$dbconn)
// remove a point and modify league if needed
{
  include("vars.php");
  $score=get_player_score($id,$dbconn);
  $score--;
  if ( $score < $SCOREMIN ) { // we might need to change league
    $league=get_player_league($id,$dbconn);
    $league++;
    if ( $league > $DANMIN ) { // already in bottom league
      set_score_and_league($id,$SCOREMIN,$DANMIN,$dbconn); // player stays at minimal score bottom league
    } else { 
      set_score_and_league($id,0,$league,$dbconn); // go to lesser league, score back to 0
    }
  } else { // no league change
    set_score($id,$score,$dbconn);
  }
  return 0; // returns 0 upon exit
}

function init_score($id,$dbconn)
{
  include("vars.php");
  set_score_and_league($id,0,$DANMIN,$dbconn); // initialize score
  return 0; // returns 0 upon exit ;
}

function reset_score($id,$dbconn)
// recalculate score from beginning using matches
{
  init_score($id,$dbconn);

  $tourneys = get_player_victories($id,$dbconn);

  while ($row=pg_fetch_row($tourneys)) // looping played matches
  {
    if ($row[0]==$id ) // if player is the winner
    { 
      add_point($id,$dbconn);
    } else {
      remove_point($id,$dbconn);
    } 
  }
}

function create_match($winner,$winner_points,$loser,$loser_points,$dbconn)
// create a match
{
  if (get_player_league($winner,$dbconn)!=get_player_league($loser,$dbconn)) {
    echo "Players are not in the same league: match not created.";
    return NULL;
  } else {
    $query = "INSERT INTO match VALUES (nextval('match_id'),'" . $winner . "','" . $winner_points . "','" . $loser . "','" . $loser_points . "','" . date('c') . "');";
    $result=pg_query($dbconn, $query);
    echo "Match created.";
    return $result; // returns a query resource
  }
}

function get_match($id,$dbconn)
// get match characteristics
{
  $query = "SELECT * FROM match WHERE id='" . $id . "';";
  $result = pg_query($dbconn,$query);
  return $result; // returns a query resource
}

function delete_match($id,$dbconn)
// delete a match
{ 
  // getting ids of both players
  $query = "SELECT winner_id,loser_id FROM match WHERE id='" . $id . "';";
  $row = pg_fetch_row(pg_query($dbconn, $query));
  $query = "DELETE FROM match WHERE id='" . $id . "';";
  $result = pg_query($dbconn, $query);
  reset_score($row[0],$dbconn);
  reset_score($row[1],$dbconn);
  return 0; // returns 0 upon exit
}

function edit_match($id,$winner_score,$loser_score,$dbconn)
// function used to edit scores in a match
{
  if ($winner_score < $loser_score) {
    echo "Can't edit match if winner changes. Delete match, then re-create one.";
    return NULL;
  } else {
    $query = "UPDATE match SET winner_score='" . $winner_score . "',loser_score='" . $loser_score . "' WHERE id='" . $id . "';";
    $result = pg_query($dbconn,$query);
    return $result;
  }
}

function get_win_count($id,$dbconn)
{
  $query = "SELECT count(*) FROM match WHERE match.winner_id='" . $id . "';";
  $row = pg_fetch_row(pg_query($dbconn,$query));
  return $row[0]; // returns an int
}

function get_loss_count($id,$dbconn)
{
  $query = "SELECT count(*) FROM match WHERE match.loser_id='" . $id . "';";
  $row = pg_fetch_row(pg_query($dbconn,$query));
  return $row[0]; // returns an int
}

?>
