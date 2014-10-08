<?php
require_once ('processors/functions_PF.php');
require_once ('processors/functions_projects.php');

$dir = 'Public/Projekty/!Projekty';

if ($_POST) {
	header ( "Location: " . $_SERVER ['REQUEST_URI'] );
	exit ();
}

?>

<script type="text/javascript">


$(document).ready( function () {


});

</script>

<?php

$rows = getPortfolioProjectA8Ids();

	for ($j = 0; $j < count ($rows); $j++) {

		$project_A8id = $rows[$j]['project_A8id'] ;
		
		$archive_folder = "";
		

		
		echo '<hr>';
		echo " <div  class=\"columnsContainer\"> <h1>" . $rows[$j]['project_A8id'] . " </h1> " ;
		
		$rows_portfolio = getPortfolioForProjectId($project_A8id);
		
		if($rows_portfolio['archive_flag']==1)
			$archive_folder = "!ARCHIWUM/";
		
		echo "<p> ".$rows_portfolio['project_name']." ";
		
		echo '<a  href="file://///a8-chomik/Public/Projekty/!Projekty/'.$archive_folder.$rows_portfolio['project_foldername'].'/!portfolio/"></a></p>';
		//też ok tylko zależy jak zamapowane:
		//echo '<a  href="file:///z:/!Projekty/!ARCHIWUM/'.$rows_portfolio['project_foldername'].'"> </a></p>';
		
		//var_dump($rows_portfolio[101]);
		
		//pierwsza kolumna 101
		//echo " <div> <h1>" . $rows_portfolio[$j]['portfolio_column_id']  . " </h1> " ;
		echo " <div class=\"portfolioColumn1\">  Miniatura<br>";
		for ($i = 0; $i < count ($rows_portfolio[101]); $i++) {
			echo'<a  href="/dane/!Projekty/'.$archive_folder.'/'.$rows_portfolio['project_foldername'].'/!portfolio/01.miniatura/'.$rows_portfolio[101][$i].'" target=\"_blank>';
			echo ' <img width="100px" src="img/thumbs/'.$project_A8id.'/'.$rows_portfolio[101][$i].'"/> </a>';
			//if($i!=0&&$i%2==0)echo " <br> ";
		}	echo " </div> ";
		
		echo " <div class=\"portfolioColumn\"> Wizki<br>";
		for ($i = 0; $i < count ($rows_portfolio[102]); $i++) {
			echo'<a href="/dane/!Projekty/'.$archive_folder.'/'.$rows_portfolio['project_foldername'].'/!portfolio/02.najlepsze-vizki/'.$rows_portfolio[102][$i].'" target=\"_blank\">';
			echo ' <img width="100px" src="img/thumbs/'.$project_A8id.'/'.$rows_portfolio[102][$i].'"/></a>';
			//if(($i+1)%3==0)echo " <br> ";
		}echo " </div> ";
		
		echo " <div class=\"portfolioColumn\"> Fotki<br>";
		for ($i = 0; $i < count ($rows_portfolio[103]); $i++) {
			echo'<a href="/dane/!Projekty/'.$archive_folder.'/'.$rows_portfolio['project_foldername'].'/!portfolio/03.najlepsze-fotki/'.$rows_portfolio[103][$i].'" target=\"_blank\">';
			echo ' <img max-height="100px" width="100px"  src="img/thumbs/'.$project_A8id.'/'.$rows_portfolio[103][$i].'"/></a>';
		}echo " </div> ";
		
		echo " <div class=\"portfolioColumn\"> Rysunki<br>";
		echo " <div> ";
		for ($i = 0; $i < count ($rows_portfolio[104]); $i++) {
			echo'<a href="/dane/!Projekty/'.$archive_folder.'/'.$rows_portfolio['project_foldername'].'/!portfolio/04.rysunki/'.$rows_portfolio[104][$i].'" target=\"_blank\">';
			echo '<img width="100px" src="img/thumbs/'.$project_A8id.'/'.$rows_portfolio[104][$i].'"/></a>';
		}echo " </div> "; echo " </div> ";
		
		echo " <div class=\"portfolioColumn\"> Referencje<br>";
		echo " <div> ";
		
		//echo 'ref:'.file_exists ('/dane/!Projekty/!ARCHIWUM/'.$rows_portfolio['project_foldername'].'/!portfolio/05.referencje/'.'Referencje.docx') ;
		//if(file_exists ('/dane/!Projekty/!ARCHIWUM/'.$rows_portfolio['project_foldername'].'/!portfolio/05.referencje/'.'Referencje.docx') )

		
		for ($i = 0; $i < count ($rows_portfolio[105]); $i++) {
			echo'<a href="/dane/!Projekty/'.$archive_folder.'/'.$rows_portfolio['project_foldername'].'/!portfolio/05.referencje/'.$rows_portfolio[105][$i].'" target=\"_blank\">';
			
			if(endsWith($rows_portfolio[105][$i],"docx")){
				echo '<img src="img/icons/Word64.png" alt="" />'.'</a><br>';
			} else if(endsWith($rows_portfolio[105][$i],"pdf") || endsWith($rows_portfolio[105][$i],"PDF")){
				echo '<img src="img/icons/Acrobat64.jpg" alt="" />'.'</a><br>';
			} else if(endsWith($rows_portfolio[105][$i],"jpg") || endsWith($rows_portfolio[105][$i],"png")){
				echo '<img width="100px" src="/dane/!Projekty/'.$archive_folder.'/'.$rows_portfolio['project_foldername'].'/!portfolio/05.referencje/'.$rows_portfolio[105][$i].'" alt="" />'.'</a><br>';
			} else
				echo ''.$rows_portfolio[105][$i].'</a><br>';
			
			
				//echo 'ref:'.$rows_portfolio[105][$i];
			//echo '<img width="100px" src="img/thumbs/'.$project_A8id.'/'.$rows_portfolio[105][$i].'"/>';
		}echo " </div> "; echo " </div> ";
		
		echo " <div class=\"portfolioColumn\"> Detale<br>";
		echo " <div> ";
		for ($i = 0; $i < count ($rows_portfolio[106]); $i++) {
			echo'<a href="/dane/!Projekty/'.$archive_folder.'/'.$rows_portfolio['project_foldername'].'/!portfolio/06.detale/'.$rows_portfolio[106][$i].'" target=\"_blank\">';
			echo ' <img width="100px" src="img/thumbs/'.$project_A8id.'/'.$rows_portfolio[106][$i].'"/></a>';
		}echo " </div> "; echo " </div> ";
		
		echo " <div class=\"portfolioColumn\"> Wiersze wypełnione w xlsie:<br>";
		echo " <div> ";
		//echo'<a href="/dane/!Projekty/!ARCHIWUM/'.$rows_portfolio['project_foldername'].'/!portfolio/'.$project_A8id.'-Projekt-Info.xlsx">Projekt-Info.xlsx</a>';
		$xls_filled = $rows_portfolio['xls_filled'] ;
		echo $xls_filled.' z 18';
		
		echo " </div> "; echo " </div> ";
		
		
		
		//echo "  <h1>" . $rows_portfolio[$j]['portfolio_value']  . "  " . $rows[$j]['project_id']  . "  ";
		
		echo " </div> ";
	}

?>