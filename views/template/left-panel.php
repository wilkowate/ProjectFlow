<?php
?>

<script type="text/javascript">
function userConfirm(){

var retVal = confirm("Czy napewno?");

if( retVal == true ){
	return true;
}else{
   //alert("User does not want to continue!");
	  return false;
}
}

 function backToPortfolio() {
	 if(userConfirm()){

		 var data_array = { method: 'notused' };
	 	$.ajax({
			type: 'post',
			url: "../projectflow/cron_scripts/create_projects.php",
			data: data_array ,
			//dataType: 'json', // Set the data type so jQuery can parse it for you
			success: function( data ) {
			alert('success');
			//window.location.replace('index.php?pg=portfolio');
				//$('#list_of_orders').dataTable().fnDraw();
				//createTable();
	 		}
		});


		//window.location.replace('/projectflow/cron_scripts/create_projects.php');
		//window.location.replace('index.php?pg=portfolio');
		}
		

 }

</script>

 <br>  <br>  <br>  <br>  <br> <br> <br> 

<div class="page">
		<input type="button" value="Portfolio" onclick="window.location.replace('index.php?pg=portfolio');" />
</div>
<br>

<div class="page">
		<input type="button" value="Projekty" onclick="window.location.replace('index.php?pg=projects');" />
</div>
<br>
<div class="page">
		<input type="button" value="Kontakty" onclick="window.location.replace('index.php?pg=contacts');" />
</div>

<br>

<div class="page">
		<input type="button" value="Zadania" onclick="window.location.replace('index.php?pg=tasks');" />
</div>

<br>

<div class="page">
		<input type="button" value="Uwagi do PF" onclick="window.location.replace('index.php?pg=pf_comments');" />
</div>

<br>


<div >
		<input type="button" value="Import projektów" onclick="backToPortfolio();" />  
</div>



<br>
