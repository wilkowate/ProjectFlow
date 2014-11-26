<?php

require_once ('../functions_PF.php');

/*
 * Script: DataTables server-side script for PHP and MySQL Copyright: 2010 - Allan Jardine License: GPL v2 or BSD (3-point)
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
*/

/* Array of database columns which should be read and sent back to DataTables. Use a space where
 * you want to insert a non-database field (for example a counter or static image)
*/
$aColumns = array (	'"1"','project_id','project_A8id','project_name','archive_flag','portfolio_flag');

$nColumns = array (	'lp','project_id','project_A8id','project_name','archive_flag','portfolio_flag');


/* Indexed column (used for fast and accurate table cardinality) */
$sIndexColumn = "project_id";

/* DB table to use */
$sTable = "Projects";

$sJoin = "";

/* Database connection information */
$gaSql ['user'] = DBUSER;
$gaSql ['password'] = DBPASS;
$gaSql ['db'] = DBNAME;
$gaSql ['server'] = DBHOST;

/*
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * If you just want to use the basic configuration for DataTables with PHP server-side, there is no need to edit below this line
 */

/*
 * MySQL connection
*/
$gaSql ['link'] = mysql_pconnect ( $gaSql ['server'], $gaSql ['user'], $gaSql ['password'] ) or die ( 'Could not open connection to server' );


mysql_select_db ( $gaSql ['db'], $gaSql ['link'] ) or die ( 'Could not select database ' . $gaSql ['db'] );

mysql_query("SET CHARACTER SET utf8", $gaSql['link']) or die ( mysql_error () );

/*
 * Paging
 */
$sLimit = "";
if (isset ( $_GET ['iDisplayStart'] ) && $_GET ['iDisplayLength'] != '-1') {
	$sLimit = "LIMIT " . mysql_real_escape_string ( $_GET ['iDisplayStart'] ) . ", " . mysql_real_escape_string ( $_GET ['iDisplayLength'] );
}

/*
 * Ordering
 */
if (isset ( $_GET ['iSortCol_0'] )) {
	$sOrder = "ORDER BY  ";
	for($i = 0; $i < intval ( $_GET ['iSortingCols'] ); $i ++) {
		if ($_GET ['bSortable_' . intval ( $_GET ['iSortCol_' . $i] )] == "true") {
			$sOrder .= $aColumns [intval ( $_GET ['iSortCol_' . $i] )] . "
				 	" . mysql_real_escape_string ( $_GET ['sSortDir_' . $i] ) . ", ";
		}
	}
	
	$sOrder = substr_replace ( $sOrder, "", - 2 );
	if ($sOrder == "ORDER BY") {
		$sOrder = "";
	}
}

/*
 * Filtering NOTE this does not match the built-in DataTables filtering which does it word by word on any field. It's possible to do here, but concerned about efficiency on very large tables, and MySQL's regex functionality is very limited
 */
$sWhere = "";
if ($_GET ['sSearch'] != "") {
	$sWhere = "WHERE (";
	for($i = 0; $i < count ( $aColumns ); $i ++) {
		$sWhere .= $aColumns [$i] . " LIKE '%" . mysql_real_escape_string ( $_GET ['sSearch'] ) . "%' OR ";
	}
	$sWhere = substr_replace ( $sWhere, "", - 3 );
	$sWhere .= ')';
}

/* Individual column filtering */
for($i = 0; $i < count ( $aColumns ); $i ++) {
	if ($_GET ['bSearchable_' . $i] == "true" && $_GET ['sSearch_' . $i] != '') {
		if ($sWhere == "") {
			$sWhere = "WHERE ";
		} else {
			$sWhere .= " AND ";
		}
		$sWhere .= $aColumns [$i] . " LIKE '%" . mysql_real_escape_string ( $_GET ['sSearch_' . $i] ) . "%' ";
	}
}

/*
 * SQL queries Get data to display
 */
$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS " . str_replace ( " , ", " ", implode ( ", ", $aColumns ) ) . "
		FROM   $sTable
		$sJoin
		$sWhere
		$sOrder
		$sLimit
		";

//echo 'sql'.$sQuery;

$rResult = mysql_query ( $sQuery, $gaSql ['link'] ) or die ( mysql_error () );

/* Data set length after filtering */
$sQuery = "
SELECT FOUND_ROWS()
	";
$rResultFilterTotal = mysql_query ( $sQuery, $gaSql ['link'] ) or die ( mysql_error () );
$aResultFilterTotal = mysql_fetch_array ( $rResultFilterTotal );
$iFilteredTotal = $aResultFilterTotal [0];

/* Total data set length */
$sQuery = "
		SELECT COUNT(" . $sIndexColumn . ")
		FROM   $sTable
		";
$rResultTotal = mysql_query ( $sQuery, $gaSql ['link'] ) or die ( mysql_error () );
$aResultTotal = mysql_fetch_array ( $rResultTotal );
$iTotal = $aResultTotal [0];

/*
 * Output
 */
$output = array (
		"sEcho" => intval ( $_GET ['sEcho'] ),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array () 
);


$lp = $_GET ['iDisplayStart'];
while ( $aRow = mysql_fetch_array ( $rResult ) ) {
	$row = array ();
	
	for($i = 0; $i < count ( $nColumns ); $i ++) {
		if($nColumns [$i] == 'lp'){
			$row [] = $lp;
		}
		else if ($nColumns [$i] != ' ' && $nColumns [$i] !='project_id') {
			/* General output */
			$row [] = $aRow [$aColumns [$i]];
		}
	}
	
	$row [] = '<a class="popup_edycja" name="popup_edycja" project_A8id ="' . $aRow ['project_A8id'] . '" >dodaj zadanie</a>';
	
	$output ['aaData'] [] = $row;
	$lp++;
}

echo json_encode ( $output );
?>