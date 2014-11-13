<?php

//echo getcwd() . "\n";

//chdir('../');
//require_once ('../processors/functions_PF.php');
require_once ('../processors/functions_tasks.php');

	if (isset($_GET['task_id'])){
		//echo  ('task details '.task_id);
		//$row_task = getTasksForPersonId(5);
		$row_task = getTaskDetails($_GET['task_id']);
	}
	else
		$row_task = getTaskDetails(1);
?>

<script>
var task_id = <?php echo (isset($_GET['task_id']) ? $_GET['task_id'] : -1);?>;
var project_A8id = <?php echo (isset($_GET['project_A8id']) ? ''.$_GET['project_A8id'] : '-1');?>;
//var project_A8id = 'A240';

function close_div(){
		//alert ('close_div close_div '+task_id);
		 $(".lastColumn_hidden").removeClass( "lastColumn_visible" );
		 $(".middleColumn").removeClass( "middleColumn_part" );
}

$( document ).ready(function() {
		$("#task").val(<?php echo('\''.$row_task [0] ['task'].'\'') ?> );
	if(task_id>0){
		$("#e1").html('Szczegóły zadania o id: '+task_id);
	} else
		$("#e1").html('Nowe zadanie dla projektu: '+project_A8id);
});

function save_task(){
	//alert ('save task '+$("#task").val()+" "+task_id);
	   var data_array = { method: 'insert_task',project_A8id: project_A8id, person_id: 2 };
	   data_array['task'] = $("#task").val();
	   data_array['task_id'] = task_id;
		$.ajax({
			type: 'post',
			  url: "processors/functions_tasks.php",
			  data: data_array ,
			  //dataType: 'json', // Set the data type so jQuery can parse it for you
			   success: function( data ) {
				   window.location.reload(); 
					 //document.getElementById("produkty_tabela").innerHTML=data;
			  }
		});
}

function change_status(status_id){
	//alert ('status has changed '+status_id);
 	   var data_array = { method: 'update_status',task_id: task_id, status_id: status_id };
		$.ajax({
			type: 'post',
			  url: "processors/functions_tasks.php",
			  data: data_array ,
			  //dataType: 'json', // Set the data type so jQuery can parse it for you
			   success: function( data ) {
					 //document.getElementById("produkty_tabela").innerHTML=data;
			  }
		});
}
		
</script>
		
	<span id="extend" task_id="12" onclick="javascript:close_div();" class=" task-details-close ui-icon ui-icon-circle-close"></span>
	
	<h2 id = "e1">Zadanie</h2>
	
	<img class="filterButton ui-widget-content ui-corner-all" title="Oznacz jako zrobione."  onclick="javascript:change_status(1);" src="img/icons/set-status-done.png"/>
	<img class="filterButton ui-widget-content ui-corner-all" title="Oznacz jako pilne."  onclick="javascript:change_status(2);" src="img/icons/set-status-urgent.png"/>
	<img class="filterButton ui-widget-content ui-corner-all" title="Oznacz jako nieaktywne." onclick="javascript:change_status(3);" src="img/icons/set-status-inactive.png"/>
	<br><br>
	Treść zadania: <br><br>
	<textarea rows="4" cols="50" id = "task" size="55" ></textarea>
	

	Zapisz:<img title="Zapisz" width="16px" onclick="javascript:save_task();" src="img/icons/tick.png"/>
	<br>


<!-- <div> -->
<!-- Komentarze: -->
<!-- <br> -->
<!-- <img title="Dodaj komentarz." width="16px" src="img/icons/balloon-white-left.png"/> -->

<!-- <div class="comment_container_class ui-widget-content ui-corner-all"> -->
<!-- <img title="Szymon Karaś." width="16px" src="img/icons/animal.png"/> -->
<!-- Zrobiłem wizki na stronę. -->
<!-- </div> -->
<!-- <br> -->

<!-- <div class="comment_container_class ui-widget-content ui-corner-all"> -->

<!-- <img title="Szymon Karaś." width="16px" src="img/icons/animal-dog.png"/> -->

<!-- Bardzo ładne wizki. Trzeba tylko dodać trochę ognia. -->
<!-- </div> -->
<!-- <br> -->

<!-- <div class="comment_container_class ui-widget-content ui-corner-all"> -->

<!-- <img title="Szymon Karaś." width="16px" src="img/icons/animal.png"/> -->
<!-- O! Słusznie, ognia nigdy za wiele. -->

<!-- </div> -->


<!-- </div> -->

