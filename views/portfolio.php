<?php
require_once ('processors/functions_PF.php');
require_once ('processors/functions_projects.php');

$dir = 'Public/Projekty/!Projekty';

if ($_POST) {
	header ( "Location: " . $_SERVER ['REQUEST_URI'] );
	exit ();
}

$priority = (isset($_GET['priority']) ? $_GET['priority'] : -1);

?>

<script type="text/javascript">

$(document).ready( function () {


});

</script>

	
	<div class="filterContainer">
	&nbsp;&nbsp;&nbsp; priorytet prezentacji:&nbsp;&nbsp;&nbsp;
	<input type="radio"  <?php if($priority==-1) echo'checked'?> onclick="javascript:window.location.replace('index.php?pg=portfolio&priority=-1');"> wszystko
	<input type="radio"  <?php if($priority==1) echo'checked'?> onclick="javascript:window.location.replace('index.php?pg=portfolio&priority=1');"> 1+
	<input type="radio"  <?php if($priority==3) echo'checked'?>   onclick="javascript:window.location.replace('index.php?pg=portfolio&priority=3');">3+
	<input type="radio"  <?php if($priority==5) echo'checked'?> onclick="javascript:window.location.replace('index.php?pg=portfolio&priority=5');">5
	</div>

<?php


$rows = getPortfolioProjectA8Ids($priority);

	for ($j = 0; $j < count ($rows); $j++) {

		$project_A8id = $rows[$j]['project_A8id'] ;
		
		$archive_folder = "";
		
		echo '<br>';
		echo " <div  class=\"columnsContainer\"> <h1>" . $rows[$j]['project_A8id'] . "  " ;
		
		$rows_portfolio = getPortfolioForProjectId($project_A8id);
		
		if($rows_portfolio['archive_flag']==1)
			$archive_folder = "!ARCHIWUM/";
		
		echo " ".$rows_portfolio['project_name']." ";
		
		echo '<a  href="file://///a8-chomik/Public/Projekty/!Projekty/'.$archive_folder.$rows_portfolio['project_foldername'].'/!portfolio/"></a></h1>';
		//też ok tylko zależy jak zamapowane:
		//echo '<a  href="file:///z:/!Projekty/!ARCHIWUM/'.$rows_portfolio['project_foldername'].'"> </a></p>';
		
		//var_dump($rows_portfolio[101]);
		
		//pierwsza kolumna 101
		//echo " <div> <h1>" . $rows_portfolio[$j]['portfolio_column_id']  . " </h1> " ;
		echo " <div class=\"portfolioColumn1\">  Miniatura<br><br>";
		for ($i = 0; $i < count ($rows_portfolio[101]); $i++) {
			echo'<a  href="/dane/!Projekty/'.$archive_folder.'/'.$rows_portfolio['project_foldername'].'/!portfolio/01.miniatura/'.$rows_portfolio[101][$i].'" target=\"_blank>';
			echo ' <img width="100px" src="img/thumbs/'.$project_A8id.'/'.$rows_portfolio[101][$i].'"/> </a>';
			//if($i!=0&&$i%2==0)echo " <br> ";
		}	echo " </div> ";
		
		echo " <div class=\"portfolioColumn\"> Wizki<br><br>";
		for ($i = 0; $i < count ($rows_portfolio[102]); $i++) {
			echo'<a href="/dane/!Projekty/'.$archive_folder.'/'.$rows_portfolio['project_foldername'].'/!portfolio/02.najlepsze-vizki/'.$rows_portfolio[102][$i].'" target=\"_blank\">';
			echo ' <img width="100px" src="img/thumbs/'.$project_A8id.'/'.$rows_portfolio[102][$i].'"/></a>';
			//if(($i+1)%3==0)echo " <br> ";
		}echo " </div> ";
		
		echo " <div class=\"portfolioColumn\"> Fotki<br><br>";
		for ($i = 0; $i < count ($rows_portfolio[103]); $i++) {
			echo'<a href="/dane/!Projekty/'.$archive_folder.'/'.$rows_portfolio['project_foldername'].'/!portfolio/03.najlepsze-fotki/'.$rows_portfolio[103][$i].'" target=\"_blank\">';
			echo ' <img max-height="100px" width="100px"  src="img/thumbs/'.$project_A8id.'/'.$rows_portfolio[103][$i].'"/></a>';
		}echo " </div> ";
		
		echo " <div class=\"portfolioColumn\"> Rysunki<br><br>";
		echo " <div> ";
		for ($i = 0; $i < count ($rows_portfolio[104]); $i++) {
			echo'<a href="/dane/!Projekty/'.$archive_folder.'/'.$rows_portfolio['project_foldername'].'/!portfolio/04.rysunki/'.$rows_portfolio[104][$i].'" target=\"_blank\">';
			echo '<img width="100px" src="img/thumbs/'.$project_A8id.'/'.$rows_portfolio[104][$i].'"/></a>';
		}echo " </div> "; echo " </div> ";
		
		echo " <div class=\"portfolioColumn2\"> Referencje<br><br>";
		echo " <div> ";
		
		//echo 'ref:'.file_exists ('/dane/!Projekty/!ARCHIWUM/'.$rows_portfolio['project_foldername'].'/!portfolio/05.referencje/'.'Referencje.docx') ;
		//if(file_exists ('/dane/!Projekty/!ARCHIWUM/'.$rows_portfolio['project_foldername'].'/!portfolio/05.referencje/'.'Referencje.docx') )

		
		for ($i = 0; $i < count ($rows_portfolio[105]); $i++) {
			echo'<a href="/dane/!Projekty/'.$archive_folder.'/'.$rows_portfolio['project_foldername'].'/!portfolio/05.referencje/'.$rows_portfolio[105][$i].'" target=\"_blank\">';
			
			if(endsWith($rows_portfolio[105][$i],"docx") || endsWith($rows_portfolio[105][$i],"doc")){
				echo '<img src="img/icons/Word64.png" alt="" />'.'</a>';
			} else if(endsWith($rows_portfolio[105][$i],"pdf") || endsWith($rows_portfolio[105][$i],"PDF")){
				echo '<img src="img/icons/Acrobat64.jpg" alt="" />'.'</a>';
			} else if(endsWith($rows_portfolio[105][$i],"jpg") || endsWith($rows_portfolio[105][$i],"png") || endsWith($rows_portfolio[105][$i],"tif")){
				echo '<img width="100px" src="/dane/!Projekty/'.$archive_folder.'/'.$rows_portfolio['project_foldername'].'/!portfolio/05.referencje/'.$rows_portfolio[105][$i].'" alt="" />'.'</a><br>';
			} else
				echo ''.$rows_portfolio[105][$i].'</a>';
			
			
				//echo 'ref:'.$rows_portfolio[105][$i];
			//echo '<img width="100px" src="img/thumbs/'.$project_A8id.'/'.$rows_portfolio[105][$i].'"/>';
		}echo " </div> "; echo " </div> ";
		
		echo " <div class=\"portfolioColumn1\"> Detale<br><br>";
		echo " <div> ";
		for ($i = 0; $i < count ($rows_portfolio[106]); $i++) {
			echo'<a href="/dane/!Projekty/'.$archive_folder.'/'.$rows_portfolio['project_foldername'].'/!portfolio/06.detale/'.$rows_portfolio[106][$i].'" target=\"_blank\">';
			echo ' <img width="100px" src="img/thumbs/'.$project_A8id.'/'.$rows_portfolio[106][$i].'"/></a>';
		}echo " </div> "; echo " </div> ";
		
		echo " <div class=\"portfolioColumn1\"> XLS:<br><br>";
		echo " <div> ";
		//echo'<a href="/dane/!Projekty/!ARCHIWUM/'.$rows_portfolio['project_foldername'].'/!portfolio/'.$project_A8id.'-Projekt-Info.xlsx">Projekt-Info.xlsx</a>';
		$xls_filled = $rows_portfolio['xls_filled'] ;
		
		$xls_proc = round($xls_filled/18*100);
		
		//echo $xls_filled.' z 18';
		
		echo $xls_proc .'%';
		
		echo " </div> "; echo " </div> ";
		
		
		
		//echo "  <h1>" . $rows_portfolio[$j]['portfolio_value']  . "  " . $rows[$j]['project_id']  . "  ";
		
		echo " </div> ";
	}

?>