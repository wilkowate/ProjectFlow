<?php
require_once ('processors/functions_PF.php');

if ($_POST) {
	header ( "Location: " . $_SERVER ['REQUEST_URI'] );
	exit ();
}

$rows_persons = getPersons();

?>

<script type="text/javascript">

var project_A8id = '0000';

$(document).ready( function () {

	// --------------------------------------- main list of orders table ----------------------------------------------------
	function createTable(){
		
		$('#list_of_projects').dataTable( {
			"bDestroy": true,
			"bProcessing": true,
			"bServerSide": true,
			"sPaginationType": "full_numbers",
			"aoColumnDefs": [		
	                         { "sTitle": "Lp.", "aTargets": [0],"sWidth":"10px"},
	                         { "sTitle": "A8Id", "aTargets": [1],"sWidth":"10px"},
	                         { "sTitle": "Nazwa", "aTargets": [2],"sWidth":"10px"},
	                         { "sTitle": "Archive ", "aTargets": [3],"sWidth":"10px"},
	                         { "sTitle": "Portfolio ", "aTargets": [4],"sWidth":"10px"},
	                         { "sTitle": "Edycja", "aTargets": [5],"sWidth":"10px"}
	                  ],
			"sAjaxSource": "processors/datatables/projects_dt.php",		
			"drawCallback": function( settings ) {

	            $('.popup_edycja').click(function(){
	            	project_A8id = $(this).attr('project_A8id');
	               // alert('id '+id);
	                $( "#dialog-edit-person" ).dialog( "open" );
	            });

	            $('.popup_usun').click(function(){
	            	project_A8id = $(this).attr('project_A8id');
	               // alert('id '+id);
	            });

	       }
		} );
	}

	$( "#dialog-edit-person" ).dialog({
	    autoOpen: false,
	    height: 370,
	    width: 390,
	    modal: true,
	    buttons: {
	      "Ok": function() {

	        var bValid = true;
	        //bValid = bValid && validateNumber(ilosc.val());
	        
	        if ( bValid ) {
	        	var data_array = { method: 'insert_task' };
	  			data_array['person_id'] = $("#person").val();
	  			data_array['task'] = $("#task").val();
	  			data_array['project_A8id'] = project_A8id;
	  			
	  		   //var data_array = { method: 'insert_task',project_A8id: project_A8id, person_id: person_id };
	  		   //data_array['task'] = $("#task").val();
	  		  // data_array['task_id'] = task_id;
	 		
	       	 	$.ajax({
	       			type: 'post',
	       			url: "processors/functions_tasks.php",
	       			data: data_array ,
	       			//dataType: 'json', // Set the data type so jQuery can parse it for you
	       			success: function( data ) {
	       				//wyczyscFiltr();
	       				//createTable();
	       				window.location.replace('index.php?pg=tasks&person_id='+$("#person").val());
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
	  
	//--------------------------------------- END main list of orders table ----------------------------------------------------
		createTable();

});

</script>

<div>
<table id="list_of_projects">
	<thead>
		<tr>
			<th></th>			<th></th>			<th></th>
			<th></th>			<th></th>			<th></th>	
		</tr>
	</thead>
</table>
</div>

<div class="ui-widget-content" id="dialog-edit-person" title="Dodaj zadanie do projektu.">
	<form>
		<fieldset>
		<label for="person">Osoba</label> 
		<select style="width:200px" id="person" name="person" >
					<?php 
					echo " <option value='0'> brak </option> ";
					for ($j = 0; $j < count ($rows_persons); $j++) {
						echo " <option value='" . $rows_persons[$j]['id']  . "'> " . $rows_persons[$j]['person_name']  . " </option> ";
					}?>
			</select>
			<br><br>
				Treść zadania: <br><br>
			<textarea rows="4" cols="30" id = "task" size="50" ></textarea>
		</fieldset>
	</form>
</div>