<?php
require_once ('processors/functions_PF.php');

if ($_POST) {
	header ( "Location: " . $_SERVER ['REQUEST_URI'] );
	exit ();
}

$rows_persons = getPersons();

?>

<script type="text/javascript">

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
	                id = $(this).attr('id');
	               // alert('id '+id);
	                $( "#dialog-edit-person" ).dialog( "open" );
	            });

	            $('.popup_usun').click(function(){
	                id = $(this).attr('id');
	               // alert('id '+id);
	            });

	       }
		} );
	}

	$( "#dialog-edit-person" ).dialog({
	    autoOpen: false,
	    height: 350,
	    width: 350,
	    modal: true,
	    buttons: {
	      "Ok": function() {

	        var bValid = true;
	        //bValid = bValid && validateNumber(ilosc.val());
	        
	        if ( bValid ) {
	        	var data_array = { method: 'update_person' };
	  			data_array['person'] = $("#person").val();
	 		
	       	 	$.ajax({
	       			type: 'post',
	       			url: "../lib/functions_projects.php",
	       			data: data_array ,
	       			dataType: 'json', // Set the data type so jQuery can parse it for you
	       			success: function( data ) {
	       				wyczyscFiltr();
	       				createTable();
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

<div id="dialog-edit-person" title="Przyporządkuj osobę do projektu.">
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
		</fieldset>
	</form>
</div>