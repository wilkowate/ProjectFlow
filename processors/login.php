<?session_start();
include('functions_PF.php');

//echo '<pre>';
//print_r ($_REQUEST);

$login=mysql_escape_string($_POST['login']);
$password=(mysql_escape_string($_POST['password']));

$db = getDBConnection();
//$sql = "SELECT distinct project_A8id FROM `Tasks` Where person_id = ".$personId;



if($db){
	// id login person_name PersonDisplayName PersonType PersonEmail PersonAddress AssignDate Password 
	$sql='SELECT id, person_name, login, password FROM `Persons` where login="'.$login.'" and password="'.$password.'"';
	
	echo $sql;
	
	$stmt = $db->prepare($sql);
	$stmt->execute ();
	$record = $stmt->fetchAll ( PDO::FETCH_ASSOC );
	
	var_dump($record);
	
	//$result_set = mysql_query($sql, $db);
	
	//$record = mysql_fetch_row($result_set); 
	//mysql_close($db);
}	

echo 'login'.$record[0]['id'];

if ($login==$record[0]['login'] && $password==$record[0]['password'] && $login!='' && $password!=''){

	$_SESSION['loggedin'] = true;
	$_SESSION['person_id'] = $record[0]['id'];
	$_SESSION['person_name'] = $record[0]['person_name'];
	$_SESSION['login'] = $record[0]['login'];

	//dodajZdarzenie(51,$login,$record[0]);
	header("Location: ../index.php?pg=tasks");
	
//	break;
}else{
	//dodajZdarzenie(54,$login,0);
	$_SESSION['zalogowany'] = false;
	//header("Location: index.php?e=1");
}
?>