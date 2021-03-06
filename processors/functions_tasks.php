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
	} else if ($method == "insert_comment") {
		insert_comment();
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
		
		$sql1 = "SELECT * FROM `Comments_Tasks` WHERE task_id = ".$taskId;
		$stmt1 = $db->prepare($sql1);
		$stmt1->execute ();
		$rows_comments = $stmt1->fetchAll ( PDO::FETCH_ASSOC );
		
		$rows[0]['comments'] = $rows_comments;
		
		//var_dump($rows);
		
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
			$sql = 'INSERT INTO `Tasks` (`task`,`project_A8id`,`person_id`,`status_id`) values(:task,:project_A8id,:person_id,3)';
		
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
		$sql = 'SELECT * FROM `Tasks` ';
		
		$sql .=' INNER JOIN `Projects` ON `Tasks`.`project_A8id` = `Projects`.`project_A8id`';
		$sql .= ' WHERE `id` <> -1 ';
		
		if(isset($_POST['person_id']))
			$sql .= ' AND person_id = '.$_POST['person_id'];
		
		//status
		$sql .= ' AND (status_id = 3';
		
		//if(isset($_POST['status_urgent'])&&$_POST['status_urgent']==1)
			$sql .= ' OR status_id =  '.URGENT;
		if(isset($_POST['status_inactive'])&&$_POST['status_inactive']==1)
			$sql .= ' OR status_id = '.INACTIVE;
		if(isset($_POST['status_done'])&&$_POST['status_done']==1)
			$sql .= ' OR status_id = ' .DONE;
		
		$sql .= ')';
		//end status
		
		$sql .= ' ORDER BY `Tasks`.`project_A8id` ';
		
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
				$arr[$i]['status_id'] = $rows [$i] ['status_id'];
				$arr[$i]['project_name'] = $rows [$i] ['project_name'];
			}
			//var_dump($arr);
			$db = null;
			echo json_encode ( $arr );
		} catch ( PDOException $e ) {
			echo '{"error":{"text":' . $e->getMessage () . '}}';
		}
	}
	
	function select_comment(){
		$arr = array();
		//$uzytkownik_id=$_SESSION['uzytkownik_id'];
		$sql = 'SELECT * FROM `Comments_Tasks` ';
	
		$sql .=' INNER JOIN `Projects` ON `Tasks`.`project_A8id` = `Projects`.`project_A8id`';
		$sql .= ' WHERE `id` <> -1 ';
	
		if(isset($_POST['person_id']))
			$sql .= ' AND person_id = '.$_POST['person_id'];
	
		//status
		$sql .= ' AND (status_id = 3';
	
		//if(isset($_POST['status_urgent'])&&$_POST['status_urgent']==1)
		$sql .= ' OR status_id =  '.URGENT;
		if(isset($_POST['status_inactive'])&&$_POST['status_inactive']==1)
			$sql .= ' OR status_id = '.INACTIVE;
		if(isset($_POST['status_done'])&&$_POST['status_done']==1)
			$sql .= ' OR status_id = ' .DONE;
	
		$sql .= ')';
		//end status
	
		$sql .= ' ORDER BY `Tasks`.`project_A8id` ';
	
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
				$arr[$i]['status_id'] = $rows [$i] ['status_id'];
				$arr[$i]['project_name'] = $rows [$i] ['project_name'];
			}
			//var_dump($arr);
			$db = null;
			echo json_encode ( $arr );
		} catch ( PDOException $e ) {
			echo '{"error":{"text":' . $e->getMessage () . '}}';
		}
	}

	function insert_comment(){
		$task_id = $_POST['task_id'];
		//$person_id = $_POST['person_id'];
		echo $task_id. $_SESSION['person_id'];
		
		$arr = array();
		//$uzytkownik_id=$_SESSION['uzytkownik_id'];
		//$task_id = $_POST['task_id'];
		//$task = $_POST['task'];
		$sql = 'INSERT INTO `Comments_Tasks` ( task_id, comment, person_id) values(:task_id, :comment, :person_id)';
		try {
			$db = getDBConnection();
			$stmt = $db->prepare ( $sql );
			//$stmt->bindParam ( ":id", $task);
			$stmt->bindParam ( ":task_id", $task_id);
			$stmt->bindParam ( ":comment", $_POST['comment']);
			$stmt->bindParam ( ":person_id", $_SESSION['person_id']);
			$stmt->execute();
			$db = null;
			//dodajZdarzenie(59, 'Usunięty protokół o id: '.$protokol_id, $uzytkownik_id);
			//$arr['protokol_id'] = $protokol_id;
			echo json_encode ( $arr );
		} catch ( PDOException $e ) {
			echo '{"error":{"text":' . $e->getMessage () . '}}';
		}
		
	}
	
