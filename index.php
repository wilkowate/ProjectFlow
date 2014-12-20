<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-us" lang="en-us">
<head>
	<title>Project Flow</title>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<style type="text/css" >
		@import "css/style.css";
		@import "//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css";

		@import "//cdn.datatables.net/plug-ins/a5734b29083/integration/jqueryui/dataTables.jqueryui.css";
	</style>


  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
  <script src="//cdn.datatables.net/1.10.3/js/jquery.dataTables.min.js"></script>
  <script src="//cdn.datatables.net/plug-ins/a5734b29083/integration/jqueryui/dataTables.jqueryui.js"></script>
  
</head>

<body>

<?php
session_start();
//include ('lib/db.php');
//include 'views/template/header.php';
?>


  <body>
   
	<div class="header">
  		<img src="img/icons/a8-logo.jpg" alt="" align="left"/> 
  		<?php 
  		if(($_SESSION['loggedin'])== true){ 
			echo 'Login: '.$_SESSION['person_name'].'<br><a href="processors/logout.php">Wyloguj się</a>';
		}?> 
   	</div>
	   	
  	<div class="columnsContainer">

	  	<div class="leftColumn">
	  		<?php if(($_SESSION['loggedin'])== true){ include "views/template/left-panel.php";}?>
	  	</div>
	  	
	  	<div class="middleColumn">
	  	 <br>  <br>  <br>  <br>  <br> <br> <br> 
<?php
//include ('lib/db.php');

//include 'views/template/header.php';

if (isset($_REQUEST['pg'])){
	$action = $_REQUEST['pg'];
}
else {$action='login_form';}
//echo $action;
$file = "views/$action.php";
if((($_SESSION['loggedin'])== true)||($action=='logowanie')){
	if (file_exists($file)) {
		include "$file";
	}else{
	echo '<div class="brakStrony">Podana strona nie istnieje!!!</div>';
	}
}
else include "views/login_form.php";

//include 'lib/footer.php'?>        

  	</div>
  	
	  	<div class="lastColumn_hidden ui-corner-all ui-widget-content">
	  		<?php //include "views/task_details1.php"?>
	  	</div>

  	</div>

    <script>
      (function($) {
        $(document).ready(function() {
         
        });
      }) (jQuery);
    </script>
    
    </body>