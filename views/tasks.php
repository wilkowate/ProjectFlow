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

	    person_id=2;
	    loadTasksForFilter('person_id', '2');
  	    
	});

  $(function() {
	 // alert('accordion');
	   // $( "#accordion" ).accordion();
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

  

  function loadTasksForFilter(param, value){
	  //first apply all checkboxes

	  var data_array = { method: 'select_tasks' };
	  data_array[param] = value;
	  
	  //var data[param]=value;
	  
	  //alert('param_array'+param+" "+data_array[param]+" "+data_array['param1']);
		$.ajax({
			type: 'post',
			  url: "processors/functions_tasks.php",
			  data: data_array ,
			  dataType: 'json', // Set the data type so jQuery can parse it for you
			   success: function( data ) {
				 //  alert(data[0].length+"  "+data.length);
				var html ='';//'<img style="vertical-align:middle" width="32px" src="img/icons/person'+person_id+'.png"/>';
				var lastProjectA8Id = '0';   
				for(var i=0;i<data.length;i++){

					//alert('projid '+lastProjectA8Id+' '+data[i]['project_A8id']);
						
					if(lastProjectA8Id!=data[i]['project_A8id']){

						if(lastProjectA8Id!=0)
							html += " </div> ";	
								
						html += '<h3>'+data[i]['project_A8id']+'</h3>';
						html += " <div> ";
						html += '<button project_A8id="'+data[i]['project_A8id']+'" id="new_task_button"  ><img src="img/icons/plus-button.png" /></button>';
						html += '<br><br>';
						lastProjectA8Id=data[i]['project_A8id']
					}

					html += '<div  task_id="'+data[i]['id']+'" class="task_container_class ui-widget-content ui-corner-all">';
					
					html += '<img  width="32px" src="img/icons/person'+person_id+'.png"/>';
					html +='<span id="extend" task_id="'+data[i]['id']+'" class=" task_container_desc ">['+data[i]["assign_date"]+']  </span>';

					//echo'<span id="extend" task_id="'.$rows[$i]['id'].'" class=" ui-icon ui-icon-circlesmall-plus"></span>';
					html += data[i]['task'] ;

					html += " <span  style=\"float:right;\" > ";
						
					html += '<img title="Oznacz jako zrobione." width="16px" onclick="javascript:change_status();" src="img/icons/tick.png"/>';
					html += '<img title="Oznacz jako pilne." width="16px" onclick="javascript:change_status();" src="img/icons/fire.png"/>';
					html += '<img title="Oznacz jako nieaktywne." width="16px" onclick="javascript:change_status();" src="img/icons/water.png"/>';
						
					html += " </span> </div> ";
				}
				if(lastProjectA8Id!=0)
					html += " </div> ";	
				$("#accordion").html(html );
				//alert('accordion1');
				//$( "#accordion" ).accordion();
				   //window.location.reload(); 
					 //document.getElementById("produkty_tabela").innerHTML=data;

				  
			  }
		});

	  
  }
  
  </script>
  
    <div class="filterContainer">
    Pokaż zadania o statusie:
	  		<button project_A8id="'.$project_A8id.'" id="new_task_button" onclick="javascript:loadTasksForFilter('person_id', '2');" >Gotowe</button>
	  		<button project_A8id="'.$project_A8id.'" id="new_task_button"  >Nieaktywne</button>
	  		<button project_A8id="'.$project_A8id.'" id="new_task_button"  >Pilne</button>
	  		 dla osób:
		<img class="filterButton ui-widget-content ui-corner-all"  onclick="javascript:loadTasksForFilter('person_id', '1');person_id=1;" src="img/icons/person1.png"/>
		<img class="filterButton ui-widget-content ui-corner-all"  onclick="javascript:loadTasksForFilter('person_id', '2');person_id=2;" src="img/icons/person2.png"/>
		<img class="filterButton ui-widget-content ui-corner-all"  onclick="javascript:loadTasksForFilter('person_id', '3');person_id=3;" src="img/icons/person3.png"/>
  		<img class="filterButton ui-widget-content ui-corner-all"  onclick="javascript:loadTasksForFilter('person_id', '4');person_id=4;" src="img/icons/person4.png"/>
  		<img class="filterButton ui-widget-content ui-corner-all"  onclick="javascript:loadTasksForFilter('person_id', '5');person_id=5;" src="img/icons/person5.png"/>
	  	<img class="filterButton ui-widget-content ui-corner-all"  onclick="javascript:loadTasksForFilter('person_id', '6');person_id=6;" src="img/icons/person6.png"/>
	  	<img class="filterButton ui-widget-content ui-corner-all"  onclick="javascript:loadTasksForFilter('person_id', '7');person_id=7;" src="img/icons/person7.png"/>
	  	<img class="filterButton ui-widget-content ui-corner-all"  onclick="javascript:loadTasksForFilter('person_id', '8');person_id=8;" src="img/icons/person8.png"/>
	</div>

  <h2>Zadania  </h2>
  <div>
  Dodaj zadanie ogólne:<img title="Dodaj zadanie ogólne." width="16px" onclick="javascript:new_taskG001_button();" src="img/icons/plus-button.png"/>
</div>
 
<div id="accordion">


</div>



<script type="text/javascript">

$(document).ajaxComplete(function() {
	//alert('ajaxComplete');
	$( "#accordion" ).accordion();

	
	$( ".task_container_class" ).click(function() {
		  var task_id = $(this).attr( 'task_id' );
	     // alert('task_container'+task_id);
	      $(".lastColumn_hidden").addClass( "lastColumn_visible" );
	      $(".lastColumn_hidden").load("views/task_details.php?"	  + $.param({    task_id: task_id}));
	});
	  //$('a').address(); //simply rebind missing links
	});

</script>
