<?php

ini_set('memory_limit', -1);

require_once ('../processors/functions_PF.php');

require_once ('../lib/PHPExcel/importProjektInfoXLS.php');


$dir = '../../../Public/Projekty/!Projekty';
echo 'dir: '.$dir;

truncateTables();

if ($handle = opendir($dir)) {
	echo '<table border =1>';
	while (false !== ($file = readdir($handle))) {
		echo '<tr><td>';
		echo $file.'<br>';
		if ($file != "!ARCHIWUM")	{
			handleProjectFolder(0, $file,$dir);
		}else if ($file == "!ARCHIWUM"){	}		
		//echo '<td><img src = "'.$dir.'/volvoolatest.jpg" height="42" width="42">dd </td>';
		echo '</td></tr>';
	}
	closedir($handle);
	echo '</table>';
}

if ($handle_arch = opendir($dir."/!ARCHIWUM")) {
	while (false !== ($file = readdir($handle_arch))) {
		if ($file != "!ARCHIWUM"  )	{
			handleProjectFolder(1, $file,$dir."/!ARCHIWUM");
		}
	}
	closedir($handle_arch);
}


function handleProjectFolder($archive, $projectDir, $fullPath){
	
	echo $projectDir.'<hr />';
			
	$project_name1 =  substr($projectDir, strrpos(strrev($projectDir), '-') + 1);
	$project_A8id =  substr($projectDir, 0,4);
	$project_name = substr($projectDir, 5);
	
	//echo '<td>FN:'.$project_name1.' ID: '.$projectDir.' </td>';
	insertProject($archive, $project_name, $projectDir, $project_A8id);
	if(is_dir($fullPath.'/'.$projectDir)){
		scanProjectFolder($project_A8id,$fullPath.'/'.$projectDir);
		//echo '<td>DIRRRR'.$projectDir.'</td>';
	}
	else 
		echo '<td>not '.$projectDir.'</td>';
}

/**
 * Inside !portfolio folder
 * 
 * @param unknown $path
 */
function handlePortfolioFolder($project_A8id,$path){
	
	//echo "  PATH: ".$path." <br>";
	
	if ($handle = opendir($path)) {
		while (false !== ($file = readdir($handle))){

			//ignore 
			if($file=='..'||$file=='.')
				continue;
			
			$translator_id = getTranslatorIdFromName($file);
			if($translator_id>0){
				//we met a portfolio folder that should has it's id in the translator table
				//$id = getTranslatorIdFromName($file);
				echo 'id:'.$translator_id.' path: '.$path.'/'.$file.'|<br>';
			
				handlePortfolioSubFolder($project_A8id,$path,$file,$translator_id);
			} else{
				echo "---not a translator folder: ".$file." to check if:".$project_A8id."-"."Projekt-Info.xlsx"." <br>";
				if($file == $project_A8id."-"."Projekt-Info.xlsx"){
					//if(endsWith($path.'/'.$file,"Projekt-Info.xlsx")){
					echo $path.'/'.$file."   ---- Projekt-Info.xlsx <br>";
					readProjektXLS($path.'/'.$file,$project_A8id);
				}
			}
			
			//echo'$translator_id'.$translator_id.'|<br>';
			
			//if(is_dir($path.'/'.$file)){

		}
	}
	//insertToPortfolio( $project_name, $project_folderName, $project_A8id);
	//insertProject(0, $project_name, $projectDir, $project_A8id);
	//scanProjectFolder($projectDir);
}

/**
 * Inside !portfolio folder
 *
 * @param unknown $path
 */
function handlePortfolioSubFolder($project_A8id,$path,$file,$translator_id){
	
	if ($handle_subfolder = opendir($path.'/'.$file)) {
		while (false !== ($file_sub = readdir($handle_subfolder))){
	
			//echo '<td>$file_sub:'.$file_sub.'| '.endsWith($file_sub,"jpg".'   '.$translator_id);
	
			if(trim($file_sub)!='' && ( $file_sub != 'Thumbs.db' &&
					$file_sub != '.' && $file_sub != '..' &&  $file_sub != '@eaDir' &&
					$file_sub != 'Info_o_folderze.docx' )) {
				
				$size = getimagesize($file);
				
				//echo '$size'.$size.'| ';
				if($file=='05.referencje'){
					insertToPortfolioRef($file_sub, $translator_id, $project_A8id);
				}else	if(endsWith($file_sub,"jpg")){
					
					createThumb($path.'/'.$file.'/'.$file_sub,$project_A8id);
					insertToPortfolio($file_sub, $translator_id, $project_A8id);
				}
			}
			//echo '|  </td>';
		}
	}
	
	
}

function scanProjectFolder($project_A8id,$path){
	if ($handle = opendir($path)) {
		while (false !== ($file = readdir($handle)))	{
			//echo 'File:'.$file.'<br>';
			if ($file == "!portfolio"){
				//echo $file.'!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!';
				handlePortfolioFolder($project_A8id,$path.'/'.$file);
				updatePortfolioFlag($project_A8id);
				return;
			}
		}
	}
}

function createThumb($imgname,$project_A8id){

	//echo '<br>img:'.$imgname.'<br>';
try {

	$thumbWidth = 150;
	$img = open_image($imgname); //imagecreatefromjpeg( $imgname );

	$width = imagesx( $img );
	$height = imagesy( $img );
	
	//echo $width.'<br>';

	// calculate thumbnail size
	$new_width = $thumbWidth;
	$new_height = floor( $height * ( $thumbWidth / $width ) );


	// create a new temporary image
	$tmp_img = imagecreatetruecolor( $new_width, $new_height );

	// copy and resize old image into new image
	imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
	//if (!file_exists("/thumb/".$rows[$i]["smallphotourl"])) {
	//	mkdir("/thumb/".$rows[$i]["smallphotourl"], 0777, true);
	//}
	/// save thumbnail into a file
	$file = strrchr ($imgname,'/');
	//echo '<br>F: '.substr($file, 1).' w:'.$width.' h:'.$height.'<br>';
	
	if (!file_exists('../img/thumbs/'.$project_A8id)) {
		mkdir('../img/thumbs/'.$project_A8id, 0777, true);
	}
	
	imagejpeg( $tmp_img, '../img/thumbs/'.$project_A8id.'/'.$file );
	
	} catch (Exception $e) {
		echo 'ERROR'.$e->getMessage ();
	}
}

/**
 * imagecreatefromjpeg does not throw exception!
 * 
 * @param unknown $file
 * @return boolean
 */
function open_image ($file) {
	$size = getimagesize($file);
	switch($size["mime"]){
		case "image/jpeg":
			$im = imagecreatefromjpeg($file); //jpeg file
			break;
		case "image/gif":
			$im = imagecreatefromgif($file); //gif file
			break;
		case "image/png":
			$im = imagecreatefrompng($file); //png file
			break;
		default:
			$im=false;
			break;
	}
	return $im;
}

////////////////////////////////// DB methods ////////////////////////////////////////////

function truncateTables(){
	
	//$sql = 'INSERT INTO `Projects`  (`project_A8id`,`project_name`,`project_folderName`,`archive_flag`) VALUES (:project_A8id,:project_name,:project_folderName,:archive_flag) ';
	try {
		$db = getDBConnection();
		
		$sql = "TRUNCATE TABLE  `Projects`";
		$stmt = $db->prepare ( $sql );
		$stmt->execute();
		
		$sql = "TRUNCATE TABLE  `Projects_portfolio`";
		$stmt = $db->prepare ( $sql );
		$stmt->execute();
		
		$db = null;

		echo json_encode ( $arr );
	} catch ( PDOException $e ) {
		echo '{"error":{"text":' . $e->getMessage () . '}}';
	}
	
}

/**
 * 
 * @param unknown $archive
 * @param unknown $project_name
 * @param unknown $project_folderName
 * @param unknown $project_A8id
 */
function insertProject($archive, $project_name,$project_folderName,$project_A8id){
		//$uzytkownik_id=$_SESSION['uzytkownik_id'];
	
		$sql = 'INSERT INTO `Projects`  (`project_A8id`,`project_name`,`project_folderName`,`archive_flag`) VALUES (:project_A8id,:project_name,:project_folderName,:archive_flag) ';
		try {
			$db = getDBConnection();
			$stmt = $db->prepare ( $sql );
			$stmt->bindParam ( ":project_A8id", $project_A8id);
			$stmt->bindParam ( ":project_name", $project_name);
			$stmt->bindParam ( ":project_folderName", $project_folderName);
			$stmt->bindParam ( ":archive_flag", $archive);
			
			$stmt->execute();
			$project_id = $db->lastInsertId();
			//echo $project_id;
			$arr['project_id'] = $project_id;
	
			$db = null;

			echo json_encode ( $arr );
		} catch ( PDOException $e ) {
			echo '{"error":{"text":' . $e->getMessage () . '}}';
		}
}

function updatePortfolioFlag( $project_A8id){
	//$uzytkownik_id=$_SESSION['uzytkownik_id'];

	try {
		$db = getDBConnection();

		$flag = 1;
		$sql1 = 'UPDATE `Projects`  SET `portfolio_flag` = :portfolio_flag WHERE `project_A8id` = :project_A8id ';
		$stmt1 = $db->prepare ( $sql1 );
		$stmt1->bindParam ( ":portfolio_flag", $flag);
		$stmt1->bindParam ( ":project_A8id", $project_A8id);
		$stmt1->execute();

		$db = null;
		//echo json_encode ( $arr );
	} catch ( PDOException $e ) {
		echo '{"error":{"text":' . $e->getMessage () . '}}';
	}
}


function insertToPortfolioRef( $portfolio_value, $portfolio_column_id, $project_A8id){
	//$uzytkownik_id=$_SESSION['uzytkownik_id'];

	$sql = 'INSERT INTO `Projects_portfolio`  (`portfolio_value`,`portfolio_column_id`,`project_A8id`,`referencje_file`) 
			VALUES (:portfolio_value,:portfolio_column_id,:project_A8id,:referencje_file) ';

	try {
		$db = getDBConnection();
		$stmt = $db->prepare ( $sql );
		$stmt->bindParam ( ":portfolio_value", $portfolio_value);
		$stmt->bindParam ( ":referencje_file", $portfolio_value);
		$stmt->bindParam ( ":portfolio_column_id", $portfolio_column_id);
		$stmt->bindParam ( ":project_A8id", $project_A8id);
		$stmt->execute();

		// 		$flag = 1;
		// 		$sql1 = 'INSERT INTO `Projects`  (`portfolio_flag`,`project_A8id`) VALUES (:portfolio_flag,:project_A8id) ';
		// 		$stmt1 = $db->prepare ( $sql1 );
		// 		$stmt1->bindParam ( ":portfolio_flag", $flag);
		// 		$stmt1->bindParam ( ":project_A8id", $project_A8id);
		// 		$stmt1->execute();

		$project_portfolio_id = $db->lastInsertId();
		//echo $project_portfolio_id;
		$arr['project_portfolio_id'] = $project_portfolio_id;

		$db = null;
		echo json_encode ( $arr );
	} catch ( PDOException $e ) {
		echo '{"error":{"text":' . $e->getMessage () . '}}';
	}
}



function insertToPortfolio( $portfolio_value, $portfolio_column_id, $project_A8id){
	//$uzytkownik_id=$_SESSION['uzytkownik_id'];

	$sql = 'INSERT INTO `Projects_portfolio`  (`portfolio_value`,`portfolio_column_id`,`project_A8id`) VALUES (:portfolio_value,:portfolio_column_id,:project_A8id) ';
	
	//echo $sql.'<br>';
	try {
		$db = getDBConnection();
		$stmt = $db->prepare ( $sql );
		$stmt->bindParam ( ":portfolio_value", $portfolio_value);
		$stmt->bindParam ( ":portfolio_column_id", $portfolio_column_id);
		$stmt->bindParam ( ":project_A8id", $project_A8id);
		$stmt->execute();
		
		$portfolio_id = $db->lastInsertId();
		$arr['portfolio_id'] = $portfolio_id;

		$db = null;
		echo json_encode ( $arr );
	} catch ( PDOException $e ) {
		echo '{"error":{"text":' . $e->getMessage () . '}}';
	}
}
	

	
// function scanFoto($projectFolder){
	
// 	echo '<br><br><br>'.$projectFolder;
	
// 	if ($handle1 = opendir($projectFolder)) {
// 		while (false !== ($file = readdir($handle1)))
				
// 		{
// 	if ($file != "." && $file != ".." && strtolower(substr($file, strrpos($file, '.') + 1)) == 'jpg')
// 	{
// 		echo '<td><img src = "file:///'.$projectFolder.''.$file.'" height="42" width="42">dd </td>';
// 	}
// 		}
// }
// }

// function scanProject($projectFolder){
// 	echo '<br><br><br>'.$projectFolder;

// 	if ($handle = opendir($projectFolder)) {

// 		while (false !== ($file = readdir($handle))){
			
// 			if ($file == "foto" )
// 			{
// 				echo '<td>'.$file.'</td>';
// 				if ($handle1 = opendir($projectFolder.'/foto/')) {
// 					while (false !== ($file = readdir($handle1)))
					
// 					{
// 						echo '<td>'.$projectFolder.'foto/'.$file.'</td>';
// 						if ($file != "." && $file != ".." && strtolower(substr($file, strrpos($file, '.') + 1)) == 'jpg')
// 						{
// 						echo '<td><img src = "file://'.$projectFolder.'foto/'.$file.'" height="42" width="42">dd </td>';
// 						}
// 					}
// 				}
// 				}
// 			}
// 		//	echo $file.'<br>';
		
// 	}
// }


	?>