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
	var person_id = <?php echo (isset($_GET['person_id']) ? $_GET['person_id'] : -1);?>;
	
  function change_status(){
		alert ('status has changed '+task_id);
	 	   var data_array = { method: 'update_status',task_id: task_id, status_id: status_id };
			$.ajax({
				type: 'post',
				  url: "../lib/functions_task.php",
				  data: data_array ,
				  //dataType: 'json', // Set the data type so jQuery can parse it for you
				   success: function( data ) {
						 //document.getElementById("produkty_tabela").innerHTML=data;
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


      $( ".task_container_class" ).click(function() {
    	  var task_id = $(this).attr( 'task_id' );
	      //  alert('task_container'+task_id);
	        $(".lastColumn_hidden").addClass( "lastColumn_visible" );

	        //$(".lastColumn_hidden").load("task_details.php?task_id="+task_id);
	        
	        $(".lastColumn_hidden").load("views/task_details.php?"	  + $.param({    task_id: task_id}));

// 	        $.ajax({
// 	        	  url: "task_details.php?"
// 	        		  + $.param({
// 	        			  task_id: 2009,
// 	        		        country: "Canada"})
	        		
// 	        	}).done(function(data) { // data what is sent back by the php page
// 	        	  $('.lastColumn_hidden').html(data); // display data
// 	        	});
	    	        

	      //  if(task_id==1)
	       
	        
      });

      $( "#new_task_button" ).click(function() {
    	  var project_A8id = '"'+$(this).attr( 'project_A8id' )+'"';
	      //  alert('task_container'+task_id);
	        $(".lastColumn_hidden").addClass( "lastColumn_visible" );

	        //$(".lastColumn_hidden").load("task_details.php?task_id="+task_id);
	        
	        $(".lastColumn_hidden").load("views/task_details.php?"+ $.param({project_A8id: project_A8id,task_id:-1}));
      
      });

	    $( ".olae" ).click(function() {
			var task_id = $(this).attr( 'task_id' );
	        alert('extend'+task_id);
	    	//$("[href$='.jpg']").
	    	$("#task_container[task_id='"+task_id+"']").addClass( "task_container_3" );
	    	//$("#task_container[task_id$='1']").addClass( "task_container_1" );
	    });

  	    
	});

  $(function() {
	    $( "#accordion" ).accordion();
	  });
  
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
  </script>

  <h2>Zadania  </h2>
  <div>
  Dodaj zadanie ogólne:<img title="Dodaj zadanie ogólne." width="16px" onclick="javascript:new_taskG001_button();" src="img/icons/plus-button.png"/>
</div>
 
<div id="accordion">

<?php

//$tasks_rows = getTasksForPerson(5);

//var_dump($tasks_rows);

$projectIds_rows = getProjectA8IdsWithTasksForPersonId(2);

//var_dump($projectIds_rows);

	for ($j = 0; $j < count ($projectIds_rows); $j++) {

		$project_A8id = $projectIds_rows[$j]['project_A8id'] ;
				
		
		//$person_id = $rows[$j]['person_id'] ;
		echo '<h3>'.$project_A8id.'</h3>';
		echo " <div> ";
		echo '<button project_A8id="'.$project_A8id.'" id="new_task_button"  ><img src="img/icons/plus-button.png" /></button>';
		echo '<br>';
		echo '<br>';
		$rows =  getTasksForPersonProjectA8id(2,$project_A8id);
		//var_dump($rows);
		for ($i = 0; $i < count ($rows); $i++) {

			echo '<div id="task_container_class" task_id="'.$rows[$i]['id'].'" class="task_container_class ui-widget-content ui-corner-all">';
			
			echo '<img title="Szymon Karaś." width="16px" src="img/icons/animal.png"/>';
			echo'<span id="extend" task_id="'.$rows[$i]['id'].'" class=" task_container_desc ">['.$rows[$i]["assign_date"].'] </span>';

			//echo'<span id="extend" task_id="'.$rows[$i]['id'].'" class=" ui-icon ui-icon-circlesmall-plus"></span>';
			echo '<br>';
			echo ''.$rows[$i]['task'] ;

			echo " <span style=\"float:right;\" > ";
				
			echo '<img title="Oznacz jako zrobione." width="16px" onclick="javascript:change_status();" src="img/icons/tick.png"/>';
			echo '<img title="Oznacz jako pilne." width="16px" onclick="javascript:change_status();" src="img/icons/fire.png"/>';
			echo '<img title="Oznacz jako nieaktywne." width="16px" onclick="javascript:change_status();" src="img/icons/water.png"/>';
				
			echo " </span> ";
			
			//echo '</p>';
			echo " </div> ";
			echo '<br>';
		}
		echo " </div> ";
	}

?>

</div>

