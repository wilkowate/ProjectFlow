<?php
session_start();
error_reporting(E_ALL);
// I don't know if you need to wrap the 1 inside of double quotes.
ini_set("display_startup_errors",1);
ini_set("display_errors",1);


require_once ('constants_PF.php');


/**
 *
 * @return PDO
 */
function getDBConnection() {
	$dbhost = DBHOST;
	$dbuser = DBUSER;
	$dbpass = DBPASS;
	$dbname = DBNAME;
	$dbh = new PDO ( "mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass );
	$dbh->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh->exec ( "SET CHARACTER SET utf8" );
	return $dbh;
}

function getTranslatorIdFromName($translatorName){
		$db = getDBConnection();
		$sql = "SELECT * FROM `Translator` ";
		$sql .=" WHERE `translator_name` = '".$translatorName."'";
		$stmt = $db->prepare($sql);
		
		//echo $sql;
	
		$stmt->execute ();
		$rows = $stmt->fetchAll ( PDO::FETCH_ASSOC );
		//for($i = 0; $i < count ( $rows ); $i ++) {
		return $rows [0] ['translator_id'];
	}
	
	function getPersons(){
		$db = getDBConnection();
		$sql = "SELECT * FROM `Persons`";
		//$sql .=" INNER JOIN  `Projects_portfolio` on `Projects`.`project_id`=`Projects_portfolio`.`project_id`";
		//$sql .=" WHERE `portfolio_flag` = 1";
		$stmt = $db->prepare($sql);
	
		//echo $sql;
	
		$stmt->execute ();
		$rows = $stmt->fetchAll ( PDO::FETCH_ASSOC );
		//for($i = 0; $i < count ( $rows ); $i ++) {
		return $rows;// [0] ['translator_id'];
	}
	
/////////////////////////////////////// GENERAL functions /////////////////////////////////////////////////

function escapeDBText($sourceText){
	$destText = str_replace(')',')',$sourceText);
	$destText = str_replace(array("\r\n", "\n", "\r"), ' ', $destText);
	return $destText;
}

function endsWith($haystack, $needle){
	$length = strlen($needle);
	if ($length == 0) {
		return true;
	}
	return (substr($haystack, -$length) === $needle);
}
