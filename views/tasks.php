<?php
require_once ('processors/functions_PF.php');
require_once ('processors/functions_tasks.php');




$dir = 'Public/Projekty/!Projekty';

if ($_POST) {
	header ( "Location: " . $_SERVER ['REQUEST_URI'] );
	exit ();
}

$person_id = (isset($_GET['person_id']) ? $_GET['person_id'] : -1);
$tasks_rows = getTasksForPerson($person_id);

?>

  <script>
	var task_id = <?php echo (isset($_GET['task_id']) ? $_GET['task_id'] : -1);?>;
	var person_id = <?php echo (isset($_GET['person_id']) ? $_GET['person_id'] : 2);?>;
	
	function change_status(status_id,task_id){
		//alert ('status has changed '+status_id);
	 	   var data_array = { method: 'update_status',task_id: task_id, status_id: status_id };
			$.ajax({
				type: 'post',
				  url: "processors/functions_tasks.php",
				  data: data_array ,
				  //dataType: 'json', // Set the data type so jQuery can parse it for you
				   success: function( data ) {
						//refreshTasks();
					   window.location.reload(); 
				  }
			});
	}

  function new_taskG001_button(){
	  var project_A8id = '"DlaOli"';//+$(this).attr( 'project_A8id' )+'"';
      //  alert('task_container'+task_id);
        $(".lastColumn_hidden").addClass( "lastColumn_visible" );

        //$(".lastColumn_hidden").load("task_details.php?task_id="+task_id);
        
        $(".lastColumn_hidden").load("views/task_details.php?"+ $.param({project_A8id: project_A8id,task_id:-1}));
        }


		  
//A $( document ).ready() block.
  $( document ).ready(function() {
      console.log( "ready!" );






	    $( ".olae" ).click(function() {
			var task_id = $(this).attr( 'task_id' );
	        alert('extend'+task_id);
	    	//$("[href$='.jpg']").
	    	$("#task_container[task_id='"+task_id+"']").addClass( "task_container_3" );
	    	//$("#task_container[task_id$='1']").addClass( "task_container_1" );
	    });

	    //person_id=2;
	    loadTasksForFilter('person_id', '2');
  	    
	});

  //$(function() {
	 // alert('accordion');
	   // $( "#accordion" ).accordion();
	  //});
  
  $(function() {
    // run the currently selected effect
    function runEffect() {
      // get effect type from
      var selectedEffect = $( "#effectTypes" ).val();
 
      // most effect types need no options passed by default
      var options = {};
      // some effects have required parameters
      if ( selectedEffect === "scale" ) {
        options = { percent: 0 };
      } else if ( selectedEffect === "transfer" ) {
        options = { to: "#button", className: "ui-effects-transfer" };
      } else if ( selectedEffect === "size" ) {
        options = { to: { width: 200, height: 60 } };
      }
 
      // run the effect
      $( "#effect" ).effect( selectedEffect, options, 500, callback ).sortable();
    };
 
    // callback function to bring a hidden box back
    function callback() {
      setTimeout(function() {
        $( "#effect" ).removeAttr( "style" ).hide().fadeIn();
      }, 1000 );
    };


 
    // set effect from select menu value
    $( "#button" ).click(function() {
      runEffect();
      return false;
    });
  });

  function refreshTasks(){
	  loadTasksForFilter('person_id', person_id);
	  }

  function loadTasksForFilter(param, value){
	  //first apply all checkboxes
	  
	  var data_array = { method: 'select_tasks' };
	  
	  if($( "#status_urgent_chk").is( ":checked" )){
		  data_array['status_urgent'] = '1';
	  } if($( "#status_inactive_chk").is( ":checked" )){
		  data_array['status_inactive'] = '1';
	  } if($( "#status_done_chk").is( ":checked" )){
		  data_array['status_done'] = '1';
	  }
	  
	  data_array[param] = value;

	  //alert('param_array'+param+" "+data_array[param]+" "+data_array['param1']);
		$.ajax({
			type: 'post',
			  url: "processors/functions_tasks.php",
			  data: data_array ,
			  dataType: 'json', // Set the data type so jQuery can parse it for you
			   success: function( data ) {
				 //  alert(data[0].length+"  "+data.length);
				var html ='';//'<img style="vertical-align:middle" width="32px" src="img/icons/person'+person_id+'.png"/>';

				html += ' <h2>Zadania  <img class="filterButton" src="img/icons/person'+person_id+'.png"/> </h2>';
				 // <div>
				 // Dodaj zadanie ogólne:<img title="Dodaj zadanie ogólne." width="16px" onclick="javascript:new_taskG001_button();" src="img/icons/plus-button.png"/>
				//</div>
				
				var lastProjectA8Id = '0';   
				for(var i=0;i<data.length;i++){

					//alert('projid '+lastProjectA8Id+' '+data[i]['project_A8id']);
						
					if(lastProjectA8Id!=data[i]['project_A8id']){

						if(lastProjectA8Id!=0)
							html += " </div> ";	
								
						html += '<h3>'+data[i]['project_A8id']+'</h3>';
						html += " <div> ";
						html += '<button project_A8id="'+data[i]['project_A8id']+'" id="new_task_button" class="new_task_button"  ><img src="img/icons/plus-button.png" /></button>';
						html += '<br><br>';
						lastProjectA8Id=data[i]['project_A8id']
					}

					var color = '#fff';
					if(data[i]['status_id']==<?php echo URGENT?>)
						color = '#FFE6E0';
					if(data[i]['status_id']==<?php echo DONE?>)
						color = '#FFFF99';
					if(data[i]['status_id']==<?php echo INACTIVE?>)
						color = '#E6E6E6';
					
					html += '<div style="background:'+color+';" task_id="'+data[i]['id']+'" class="task_container_class ui-widget-content ui-corner-all">';
					
					html += '<img class="filterButton" src="img/icons/person'+person_id+'.png"/>';
					//html +='<span id="extend" task_id="'+data[i]['id']+'" class=" task_container_desc ">['+data[i]["assign_date"]+']  </span>';

					//echo'<span id="extend" task_id="'.$rows[$i]['id'].'" class=" ui-icon ui-icon-circlesmall-plus"></span>';
					html += '&nbsp;<span  class=" task_container_task ">'+data[i]["task"]+'  </span>' ;

					html += " <span  style=\"float:right;\" > ";
						
					html += '<img class="filterButton ui-widget-content ui-corner-all" title="Oznacz jako zrobione."  onclick="javascript:change_status(5,'+data[i]['id']+');" src="img/icons/set-status-done.png"/>';
					html += '&nbsp;<span class=" task_container_desc ">['+data[i]["assign_date"]+']  </span>';
					html += '&nbsp;<img class="filterButton ui-widget-content ui-corner-all" title="Oznacz jako pilne."  onclick="javascript:change_status(2,'+data[i]['id']+');" src="img/icons/set-status-urgent.png"/>';
					html += '&nbsp;<img class="filterButton ui-widget-content ui-corner-all" title="Oznacz jako nieaktywne."  onclick="javascript:change_status(6,'+data[i]['id']+');" src="img/icons/set-status-inactive.png"/>';
					html += '&nbsp;<img class="filterButton ui-widget-content ui-corner-all" title="Oznacz jako do zrobienia."  onclick="javascript:change_status(3,'+data[i]['id']+');" src="img/icons/set-status-todo.png"/>';
					
					html += " </span> </div> ";
				}
				if(lastProjectA8Id!=0)
					html += " </div> ";	
					
				$("#accordion").html(html );
			  
			  }
		});

  }
  
  </script>
  
    <div class="filterContainer">
    
    Pokaż też zadania o statusie: &nbsp;&nbsp;&nbsp;
	<span class="filterButton ui-widget-content ui-corner-all">
			<input type="checkbox" id="status_urgent_chk" name="status_urgent_chk" onclick="javascript:refreshTasks();">Pilny
			<input type="checkbox" id="status_done_chk" name="status_done_chk" onclick="javascript:refreshTasks();">Zrobiony
			<input type="checkbox" id="status_inactive_chk" name="status_inactive_chk" onclick="javascript:refreshTasks();">Nieaktywny
	</span>
	  		&nbsp;&nbsp;&nbsp; dla osoby:&nbsp;&nbsp;&nbsp;
		<img class="filterButton ui-widget-content ui-corner-all"  onclick="javascript:loadTasksForFilter('person_id', '3');person_id=3;" src="img/icons/person3.png"/>
  		<img class="filterButton ui-widget-content ui-corner-all"  onclick="javascript:loadTasksForFilter('person_id', '4');person_id=4;" src="img/icons/person4.png"/>
  		<img class="filterButton ui-widget-content ui-corner-all"  onclick="javascript:loadTasksForFilter('person_id', '5');person_id=5;" src="img/icons/person5.png"/>
	  	<img class="filterButton ui-widget-content ui-corner-all"  onclick="javascript:loadTasksForFilter('person_id', '6');person_id=6;" src="img/icons/person6.png"/>
	  	<img class="filterButton ui-widget-content ui-corner-all"  onclick="javascript:loadTasksForFilter('person_id', '7');person_id=7;" src="img/icons/person7.png"/>
	  	<img class="filterButton ui-widget-content ui-corner-all"  onclick="javascript:loadTasksForFilter('person_id', '8');person_id=8;" src="img/icons/person8.png"/>
	  	<img class="filterButton ui-widget-content ui-corner-all"  onclick="javascript:loadTasksForFilter('person_id', '2');person_id=2;" src="img/icons/person2.png"/>
	</div>


 
<div id="accordion">


</div>



<script type="text/javascript">
  

$(document).ajaxComplete(function() {

    $( ".new_task_button" ).click(function() {
  	  var project_A8id = '"'+$(this).attr( 'project_A8id' )+'"';
	  //alert('task_container'+project_A8id);
	  $(".lastColumn_hidden").addClass( "lastColumn_visible" );
	  $(".lastColumn_hidden").load("views/task_details.php?"+ $.param({project_A8id: project_A8id,task_id:-1}));
    });
	
	$( ".task_container_class" ).click(function() {
		  var task_id = $(this).attr( 'task_id' );
	     // alert('task_container'+task_id);
	      $(".lastColumn_hidden").addClass( "lastColumn_visible" );
	      $(".lastColumn_hidden").load("views/task_details.php?"	  + $.param({    task_id: task_id}));
	});
	  //$('a').address(); //simply rebind missing links
	});

</script>
