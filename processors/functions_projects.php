<?php

require_once ('processors/functions_PF.php');


function getPortfolioForProjectId($project_A8id){
		$db = getDBConnection();
		$sql = "SELECT * FROM `Projects` ";
		$sql .=" INNER JOIN  `Projects_portfolio` on `Projects`.`project_A8id`=`Projects_portfolio`.`project_A8id`";
		$sql .=" WHERE `Projects`.`project_A8id` = '".$project_A8id."'";
		$stmt = $db->prepare($sql);
		
		//echo $sql;
	
		$stmt->execute ();
		$rows = $stmt->fetchAll ( PDO::FETCH_ASSOC );
		
		$arr = array();
		
		$arr101 = array();
		$arr102 = array();
		$arr103 = array();
		$arr104 = array();
		$arr105 = array();
		$arr106 = array();
		$arr107 = array();
		
		for ($j = 0; $j < count ($rows); $j++) {
			//$arr['portfolio_column_id'][] = $rows[$j]['portfolio_column_id'];
			//echo $rows[$j]['portfolio_column_id'] .'   '. $rows[$j]['portfolio_value'];
			if($rows[$j]['portfolio_column_id']==101)
				$arr101[] = $rows[$j]['portfolio_value'];
			if($rows[$j]['portfolio_column_id']==102)
				$arr102[] = $rows[$j]['portfolio_value'];
			if($rows[$j]['portfolio_column_id']==103)
				$arr103[] = $rows[$j]['portfolio_value'];
			if($rows[$j]['portfolio_column_id']==104)
				$arr104[] = $rows[$j]['portfolio_value'];
			if($rows[$j]['portfolio_column_id']==105)
				$arr105[] = $rows[$j]['portfolio_value'];
			if($rows[$j]['portfolio_column_id']==106)
				$arr106[] = $rows[$j]['portfolio_value'];
			if($rows[$j]['portfolio_column_id']==107)
				$arr107[] = $rows[$j]['portfolio_value'];
		}
		
		$arr['project_name'] = $rows[0]['project_name'];
		$arr['project_foldername'] = $rows[0]['project_foldername'];
		$arr['xls_filled'] = $rows[0]['xls_filled'];
		$arr['archive_flag'] = $rows[0]['archive_flag'];
		
		//var_dump($rows);
		
		$arr[101] = $arr101;
		$arr[102] = $arr102;
		$arr[103] = $arr103;
		$arr[104] = $arr104;
		$arr[105] = $arr105;
		$arr[106] = $arr106;
		$arr[107] = $arr107;
		
		//var_dump($arr);
		
		//for($i = 0; $i < count ( $rows ); $i ++) {
		return $arr;// [0] ['translator_id'];
	}
	
	
	function getPortfolioProjectA8Ids($priority){
		$db = getDBConnection();
		$sql = "SELECT distinct `Projects_portfolio`.`project_A8id` FROM `Projects_portfolio`  ";
		$sql .=" INNER JOIN  `Projects` on `Projects`.`project_A8id`=`Projects_portfolio`.`project_A8id`";
		$sql .=" WHERE presentation_priority >= ".$priority;
		$stmt = $db->prepare($sql);
	
		//echo $sql;
	
		$stmt->execute ();
		$rows = $stmt->fetchAll ( PDO::FETCH_ASSOC );
		//for($i = 0; $i < count ( $rows ); $i ++) {
		return $rows;// [0] ['translator_id'];
	}

