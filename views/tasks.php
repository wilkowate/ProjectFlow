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

    function Print()
    {
        var mywindow = window.open('', 'print div', 'scrollbars=1');
        mywindow.document.write('<html><head><title>zadania</title>');
       
        /*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
        
        mywindow.document.write('<style>.noprint  {   display: none !important;    }</style>');
        
        mywindow.document.write('<link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css" type="text/css" />');
        //mywindow.document.write('<link rel="stylesheet" href="css/style.css" type="text/css" />');

        mywindow.document.write('<link rel="stylesheet" type="text/css" media="print" href="css/styleprint.css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write('<div class="page" > <div class="subpage">');

        $('.printdiv').each(function(index, value){
            mywindow.document.write($(this).html());
        });
        
        mywindow.document.write('</div></div>');
        mywindow.document.write('</body></html>');
        mywindow.print();
    }

 	 $( document ).ready(function() {
  	  	console.log( "tasks.php document ready!" );
    	loadTasksForFilter('person_id', person_id);
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
				
				var lastProjectA8Id = '0';   
				for(var i=0;i<data.length;i++){

					//alert('projid '+lastProjectA8Id+' '+data[i]['project_A8id']);
						
					if(lastProjectA8Id!=data[i]['project_A8id']){

						if(lastProjectA8Id!=0)
							html += " </div> ";	
								
						
						html += " <div class='printdiv'> ";
						html += '<h3>'+data[i]['project_A8id']+"   "+data[i]['project_name']+'</h3>';
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

					//html += '<br>';
					
					html += '<div  style="background:'+color+';" task_id="'+data[i]['id']+'" class="task_container_class ui-widget-content ui-corner-all">';
					
					html += '<img class="filterButton" src="img/icons/person'+person_id+'.png"/>';
					//html +='<span id="extend" task_id="'+data[i]['id']+'" class=" task_container_desc ">['+data[i]["assign_date"]+']  </span>';

					//echo'<span id="extend" task_id="'.$rows[$i]['id'].'" class=" ui-icon ui-icon-circlesmall-plus"></span>';
					html += '&nbsp;<span class=" task_container_task " task_id='+ data[i]["id"]+'>'+data[i]["task"]+'  </span>' ;

					html += " <span class=\"noprint\" style=\"float:right;\" > ";
						
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
			<input type="checkbox" id="status_done_chk" name="status_done_chk" onclick="javascript:refreshTasks();">Zrobiony
			<input type="checkbox" id="status_inactive_chk" name="status_inactive_chk" onclick="javascript:refreshTasks();">Nieaktywny
	</span>
	  		&nbsp;&nbsp;&nbsp; dla osoby:&nbsp;&nbsp;&nbsp;
		<img class="filterButton ui-widget-content ui-corner-all"  onclick="javascript:loadTasksForFilter('person_id', '3');person_id=3;" src="img/icons/person3.png"/>
  		<img class="filterButton ui-widget-content ui-corner-all"  onclick="javascript:loadTasksForFilter('person_id', '5');person_id=5;" src="img/icons/person5.png"/>
		<img class="filterButton ui-widget-content ui-corner-all"  onclick="javascript:loadTasksForFilter('person_id', '4');person_id=4;" src="img/icons/person4.png"/>
	  	<img class="filterButton ui-widget-content ui-corner-all"  onclick="javascript:loadTasksForFilter('person_id', '6');person_id=6;" src="img/icons/person6.png"/>
	  	<img class="filterButton ui-widget-content ui-corner-all"  onclick="javascript:loadTasksForFilter('person_id', '7');person_id=7;" src="img/icons/person7.png"/>
	  	<img class="filterButton ui-widget-content ui-corner-all"  onclick="javascript:loadTasksForFilter('person_id', '8');person_id=8;" src="img/icons/person8.png"/>
	  	<img class="filterButton ui-widget-content ui-corner-all"  onclick="javascript:loadTasksForFilter('person_id', '2');person_id=2;" src="img/icons/person2.png"/>
	</div>
 
<div id="accordion">

</div>

<div >

		<input type="button" value=" drukuj " onclick="Print()" />
</div>


<script type="text/javascript">

$(document).ajaxComplete(function() {

    $( ".new_task_button" ).click(function() {
  	  var project_A8id = '"'+$(this).attr( 'project_A8id' )+'"';
	  //alert('task_container'+project_A8id);
	  $(".lastColumn_hidden").addClass( "lastColumn_visible" );

	  $(".lastColumn_hidden").load("views/task_details.php?"+ $.param({project_A8id: project_A8id,task_id:-1,person_id: person_id}));

		$(".middleColumn").addClass( "middleColumn_part" );
    });
	
	$( ".task_container_task" ).click(function() {
		  var task_id = $(this).attr( 'task_id' );
	     // alert('task_container'+task_id);
	      $(".lastColumn_hidden").addClass( "lastColumn_visible" );
	      $(".lastColumn_hidden").load("views/task_details.php?"	  + $.param({ task_id: task_id,person_id: person_id}));
	      $(".middleColumn").addClass( "middleColumn_part" );
	});
	  //$('a').address(); //simply rebind missing links
});

</script>
