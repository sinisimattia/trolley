<?php // SNISNI
/*
 * "Planning Script" Server-Side
 * Mattia Sinisi - https://snisni.it/about/me
 *
 */

// Login
$dbserver = "localhost";
$dbname = "2do";
$dbuser = "root";
$dbpassword = "";

global $link;

$link = mysqli_connect($dbserver, $dbuser, $dbpassword);
mysqli_select_db($link, $dbname);

mysqli_set_charset($link, "utf-8");
// -----

// Operation Selection
switch ( $_POST["opr"] ){
	case ("add"):
		echo add();
	break;

	case ("change_status"):
		if ( req_check() ) echo change( $_POST["id"], $_POST["stato"] );
		else echo "invalid_request";
	break;

	default:
		echo "no_opr_selected";
	break;
}

mysqli_close($link);

// Functions
function req_check(){
	if ( isset($_POST["id"]) AND $_POST["id"] AND isset($_POST["stato"]) ) return true;
	else return false;
}

function change( $id, $stato ){
	if ( !req_check() ) return "no_id_given";

	if ( $stato != -1 )  $query = "UPDATE memos SET stato = ".$stato." WHERE id = ".$id;
	else $query = "DELETE FROM memos WHERE id=".$id;

	$result = mysqli_query( $GLOBALS["link"], $query );

	if ( $result ) return "change_success";
	else return "change_error";
}

function add(){
	$titolo = htmlspecialchars( $_POST["title"], ENT_QUOTES );
	$descrizione = htmlspecialchars( $_POST["content"], ENT_QUOTES );
	$scadenza = $_POST["date"];
	
	$query = 'INSERT INTO memos ( titolo, descrizione, scadenza ) VALUES ( "'.$titolo.'", "'.$descrizione.'", '.$scadenza.' )';

	$result = mysqli_query( $GLOBALS["link"], $query );

	if ( $result ) return "change_success";
	else return "change_error";
}
?>