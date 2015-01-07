<?php
require_once ('../processors/functions_tasks.php');

	if (isset($_GET['task_id']) ? $_GET['task_id'] : -1){	
		$row_task = getTaskDetails($_GET['task_id']);
	}

	function js_str($s)	{	
		return '"' . addcslashes($s, "\0..\37\"\\") . '"';
	}
	
	function js_array($array)	{
		$temp = array_map('js_str', $array);
		return '[' . implode(',', $temp) . ']';
	}
?>

<script>

<?php  
echo 'var comments;';
echo 'var html="";';
for($i=0;$i<count($row_task[0]['comments']);$i++){
	echo ' comments = ', js_array($row_task[0]['comments'][$i]), ';';
	echo ' html += "<div class=\"comment_container_class ui-widget-content ui-corner-all\">";';
	echo ' html += "<img title=\"Szymon Karaś.\" width=\"16px\" src=\"img/icons/person"+comments[3]+".png\"/>";';
	echo ' html += comments[2];';
	echo ' html += "</div>";';
}
echo ' $("#comments").html(html);';
?>

var task_id = <?php echo (isset($_GET['task_id']) ? $_GET['task_id'] : -1);?>;
var project_A8id = <?php echo (isset($_GET['project_A8id']) ? ''.$_GET['project_A8id'] : '-1');?>;
var person_id = <?php echo (isset($_GET['person_id']) ? ''.$_GET['person_id'] : '2');?>;

function add_comment(){
	$( "#dialog-add-comment" ).dialog( "open" );
}

function close_div(){
	 $(".lastColumn_hidden").removeClass( "lastColumn_visible" );
	 $(".middleColumn").removeClass( "middleColumn_part" );
}

$( document ).ready(function() {
		
	if(task_id>0){
		$("#e1").html('Szczegóły zadania o id: '+task_id);
		$("#task").val(<?php if (isset($row_task [0] ['task'])) echo('"'.escapeDBText($row_task [0] ['task']).'"') ?> );
	} else
		$("#e1").html('Nowe zadanie dla projektu: '+project_A8id);


	$( "#dialog-add-comment" ).dialog({
	    autoOpen: false,
	    height: 330,
	    width: 390,
	    modal: true,
	    buttons: {
	      "Ok": function() {

	        var bValid = true;
	        //bValid = bValid && validateNumber(ilosc.val());
	        
	        if ( bValid ) {
	        	var data_array = { method: 'insert_comment' };
	  			data_array['comment'] = $("#comment").val();
	  			data_array['project_A8id'] = project_A8id;
	  			data_array['task_id'] = task_id;
				alert('insert_comment');
	 		
	       	 	$.ajax({
	       			type: 'post',
	       			url: "processors/functions_tasks.php",
	       			data: data_array ,
	       			//dataType: 'json', // Set the data type so jQuery can parse it for you
	       			success: function( data ) {
	       				//wyczyscFiltr();
	       				//createTable();
	       				//window.location.replace('index.php?pg=tasks&person_id='+$("#person").val());
	      	 		}
	       		});
	          $( this ).dialog( "close" );
	        }
	        
	      },
	      "Cancel": function() {
	        $( this ).dialog( "close" );
	      }
	    },
	    
	    close: function() {
	      //allFields.val( "" ).removeClass( "ui-state-error" );
	    }
	  });
});

function save_task(){
	//alert ('save task '+$("#task").val()+" "+task_id);
	   var data_array = { method: 'insert_task',project_A8id: project_A8id, person_id: person_id };
	   data_array['task'] = $("#task").val();
	   data_array['task_id'] = task_id;
		$.ajax({
			type: 'post',
			  url: "processors/functions_tasks.php",
			  data: data_array ,
			  //dataType: 'json', // Set the data type so jQuery can parse it for you
			   success: function( data ) {
				   window.location.replace('index.php?pg=tasks&person_id='+person_id);
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
	<textarea rows="4" cols="50" id = "task"  ></textarea>
	

<br>
	<button  onclick="javascript:save_task();"  >Zapisz</button>
	<button  onclick="javascript:add_comment();" class="add_comment_button"  >Dodaj komentarz</button>

<div id='comments'>
Komentarze:
<br>
<img title="Dodaj komentarz." width="16px" src="img/icons/balloon-white-left.png"/>


</div>


<div class="ui-widget-content " id="dialog-add-comment" title="Dodaj komentarz do zadania.">
	<form>
			Treść komentarza: <br><br>
			<textarea rows="4" cols="30" id = "comment" size="50" ></textarea>
	</form>
</div>
