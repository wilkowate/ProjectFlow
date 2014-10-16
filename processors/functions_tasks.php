<?php
//$method = $_POST ['method'];
require_once ('functions_PF.php');


if (isset ( $_POST ["method"] )) {
	$method = $_POST ["method"];
	if ($method == "insert_task") {
		insert_task();
	} else if ($method == "update_status") {
		update_status();
	} else if ($method == "select_tasks") {
		select_tasks();
	}
}


function getProjectA8IdsWithTasksForPersonId($personId){
		$db = getDBConnection();
		$sql = "SELECT distinct project_A8id FROM `Tasks` Where person_id = ".$personId;
		$stmt = $db->prepare($sql);
	
		//echo $sql;
	
		$stmt->execute ();
		$rows = $stmt->fetchAll ( PDO::FETCH_ASSOC );
		//for($i = 0; $i < count ( $rows ); $i ++) {
		return $rows;// [0] ['translator_id'];
	}
	
	function getTasksForPerson($personId ){
		$db = getDBConnection();
		$sql = "SELECT * FROM `Tasks` Where person_id = ".$personId;
		$stmt = $db->prepare($sql);
	
		//echo $sql;
	
		$stmt->execute ();
		$rows = $stmt->fetchAll ( PDO::FETCH_ASSOC );
		//for($i = 0; $i < count ( $rows ); $i ++) {
		return $rows;// [0] ['translator_id'];
	}
	
	function getTasksForPersonProjectA8id($personId,$project_A8id ){
		$db = getDBConnection();
		$sql = "SELECT * FROM `Tasks` Where person_id = ".$personId." AND project_A8id='".$project_A8id."'";
		$stmt = $db->prepare($sql);
	
		//echo $sql;
	
		$stmt->execute ();
		$rows = $stmt->fetchAll ( PDO::FETCH_ASSOC );
		//for($i = 0; $i < count ( $rows ); $i ++) {
		return $rows;// [0] ['translator_id'];
	}
	
	function getTaskComments($taskId){
		$db = getDBConnection();
		$sql = "SELECT * FROM `TasksComments` WHERE `task_id` = ".$taskId;
		$stmt = $db->prepare($sql);
	
		//echo $sql;
	
		$stmt->execute ();
		$rows = $stmt->fetchAll ( PDO::FETCH_ASSOC );
		//for($i = 0; $i < count ( $rows ); $i ++) {
		return $rows;
	}
	
	
// 	function getPortfolioProjectA8Ids(){
// 		$db = getDBConnection();
// 		$sql = "SELECT distinct task, task_id, ProjectId, `project_A8id` FROM `Tasks`";
// 		//$sql .=" INNER JOIN  `Projects_portfolio` on `Projects`.`project_id`=`Projects_portfolio`.`project_id`";
// 		//$sql .=" WHERE `portfolio_flag` = 1";
// 		$stmt = $db->prepare($sql);
	
// 		//echo $sql;
	
// 		$stmt->execute ();
// 		$rows = $stmt->fetchAll ( PDO::FETCH_ASSOC );
// 		//for($i = 0; $i < count ( $rows ); $i ++) {
// 		return $rows;// [0] ['translator_id'];
// 	}
	

	function getTaskDetails($taskId){
		try {
		$db = getDBConnection();
		$sql = "SELECT * FROM `Tasks` WHERE id = ".$taskId;
		$stmt = $db->prepare($sql);
	
		//echo $sql;
	
		$stmt->execute ();
		$rows = $stmt->fetchAll ( PDO::FETCH_ASSOC );
		//for($i = 0; $i < count ( $rows ); $i ++) {
		return $rows;
		} catch ( PDOException $e ) {
			echo '{"error":{"text":' . $e->getMessage () . '}}';
			
		}
	}
	
	////////////////////////////////////////////////// METHODS FOR /////////////////
	
	function update_task($taskId){
		$arr = array();
		//$uzytkownik_id=$_SESSION['uzytkownik_id'];
		$task_id = $_POST['task_id'];
		$task = $_POST['task'];
		$sql = 'UPDATE `Tasks` SET task = :task WHERE `id`= '.$task_id;
		try {
			$db = getDBConnection();
			$stmt = $db->prepare ( $sql );
			$stmt->bindParam ( ":task", $task);
			$stmt->execute();
			$db = null;
			//dodajZdarzenie(59, 'Usunięty protokół o id: '.$protokol_id, $uzytkownik_id);
			//$arr['protokol_id'] = $protokol_id;
			echo json_encode ( $arr );
		} catch ( PDOException $e ) {
			echo '{"error":{"text":' . $e->getMessage () . '}}';
		}
	}
	
	function insert_task(){
		$arr = array();
		//$uzytkownik_id=$_SESSION['uzytkownik_id'];
		$project_A8id = $_POST['project_A8id'];
		$task = $_POST['task'];
		$person_id = $_POST['person_id'];
		
		$task_id =  isset($_POST['task_id']) ? $_POST['task_id'] : -1;
		
		if($task_id>0)
			$sql = 'UPDATE `Tasks` SET `task`=:task WHERE `id` = :id;';//,project_A8id=,person_id) values(,:project_A8id,:person_id)';
		else 
			$sql = 'INSERT INTO `Tasks` (`task`,`project_A8id`,`person_id`) values(:task,:project_A8id,:person_id)';
		
		echo $sql.'   '.$task_id;
		
		try {
			$db = getDBConnection();
			$stmt = $db->prepare ( $sql );
			$stmt->bindParam ( ":task", $task);
			if($task_id==-1){
				$stmt->bindParam ( ":project_A8id", $project_A8id);
				$stmt->bindParam ( ":person_id", $person_id);
			}else{
				$stmt->bindParam ( ":id", $task_id);
			}
			
			$stmt->execute();
			$db = null;
			//dodajZdarzenie(59, 'Usunięty protokół o id: '.$protokol_id, $uzytkownik_id);
			//$arr['protokol_id'] = $protokol_id;
			echo json_encode ( $arr );
		} catch ( PDOException $e ) {
			echo '{"error":{"text":' . $e->getMessage () . '}}';
		}
	}
	
	/**
	 * 
	 * @param unknown $task_id
	 * @param unknown $status_id
	 */
	function update_status(){
		$arr = array();
		//$uzytkownik_id=$_SESSION['uzytkownik_id'];
		$task_id = $_POST['task_id'];
		$status_id = $_POST['status_id'];
		echo $task_id. $status_id;
		//$task = $_POST['task'];
		$sql = 'UPDATE `Tasks` SET status_id = :status_id WHERE `id`= '.$task_id;
		echo $sql;
		try {
			$db = getDBConnection();
			$stmt = $db->prepare ( $sql );
			//$stmt->bindParam ( ":task_id", $task_id);
			$stmt->bindParam ( ":status_id", $status_id);
			$stmt->execute();
			$db = null;
			//dodajZdarzenie(59, 'Usunięty protokół o id: '.$protokol_id, $uzytkownik_id);
			$arr['protokol_id'] = $sql;
			echo json_encode ( $arr );
		} catch ( PDOException $e ) {
			echo '{"error":{"text":' . $e->getMessage () . '}}';
		}
	}
	
	function select_tasks(){
		$arr = array();
		//$uzytkownik_id=$_SESSION['uzytkownik_id'];
		$sql = 'SELECT * FROM `Tasks` WHERE `id` <> -1 ';
		
		if(isset($_POST['person_id']))
			$sql .= ' AND person_id = '.$_POST['person_id'];
		
		$sql .= ' ORDER BY `project_A8id` ';
		
		//echo $sql;
		try {
			$db = getDBConnection();
			$stmt = $db->prepare ( $sql );
			//$stmt->bindParam ( ":task_id", $task_id);
			//$stmt->bindParam ( ":status_id", $status_id);
			$stmt->execute();
			$rows = $stmt->fetchAll ( PDO::FETCH_ASSOC );
			for($i = 0; $i < count ( $rows ); $i ++) {
				$arr[$i]['task'] = $rows [$i] ['task'];
				$arr[$i]['id'] = $rows [$i] ['id'];
				$arr[$i]['project_A8id'] = $rows [$i] ['project_A8id'];
				$arr[$i]['assign_date'] = $rows [$i] ['assign_date'];
				
				
				//$arr[]['opis_z_faktury'] = $rows [0] ['opis_z_fakury'];
				//$arr[]['ilosc_produktow'] = count($rows);
			}
			
			//var_dump($arr);
			//echo json_encode ( $arr );
			$db = null;
			//dodajZdarzenie(59, 'Usunięty protokół o id: '.$protokol_id, $uzytkownik_id);
			//$arr['protokol_id'] = $sql;
			echo json_encode ( $arr );
		} catch ( PDOException $e ) {
			echo '{"error":{"text":' . $e->getMessage () . '}}';
		}
	}
	
	
	

