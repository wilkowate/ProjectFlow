<?php
require_once ('processors/functions_PF.php');

if ($_POST) {
	header ( "Location: " . $_SERVER ['REQUEST_URI'] );
	exit ();
}

?>

<script type="text/javascript">

function dodajFakture(){
	window.location.replace('index.php?pg=acceptance_of_goods');
}

$(document).ready( function () {

	// --------------------------------------- main list of orders table ----------------------------------------------------
	function createTable(){
		
		$('#list_of_invoices').dataTable( {
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
			"sAjaxSource": "controlers/datatables/projects_dt.php",		
			"drawCallback": function( settings ) {

	            $('.popup_edycja').click(function(){
	                id = $(this).attr('id');
	               // alert('id '+id);
	            });

	            $('.popup_usun').click(function(){
	                id = $(this).attr('id');
	               // alert('id '+id);
	            });

	       }
		} );
	}
	//--------------------------------------- END main list of orders table ----------------------------------------------------

		createTable();

});

</script>

<div>
<table id="list_of_invoices">
	<thead>
		<tr>
			<th></th>			<th></th>			<th></th>
			<th></th>			<th></th>			<th></th>	
								
		</tr>

	</thead>
</table>
</div>