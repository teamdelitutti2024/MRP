
/* ========================================================
*
* Londinium - premium responsive admin template
*
* ========================================================
*
* File: application_blank.js;
* Description: Minimum of necessary js code for blank page.
* Version: 1.0
*
* ======================================================== */



$(function() {


/* # Data tables
================================================== */


	//===== Default datatable =====//

	oTable = $('.datatable table').dataTable({
		"bJQueryUI": false,
		"bAutoWidth": false,
		"bSort": false,
		"pageLength": 25,
		"sPaginationType": "full_numbers",
		"sDom": '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
		"oLanguage": { 
			"sUrl": "//cdn.datatables.net/plug-ins/1.10.22/i18n/Spanish.json" 
		}
	});


/* # Bootstrap Plugins
================================================== */


	//===== Add fadeIn animation to dropdown =====//

	$('.dropdown, .btn-group').on('show.bs.dropdown', function(e){
		$(this).find('.dropdown-menu').first().stop(true, true).fadeIn(100);
	});


	//===== Add fadeOut animation to dropdown =====//

	$('.dropdown, .btn-group').on('hide.bs.dropdown', function(e){
		$(this).find('.dropdown-menu').first().stop(true, true).fadeOut(100);
	});





/* # Interface Related Plugins
================================================== */


	//===== Collapsible navigation =====//
	
	$('.expand').collapsible({
		defaultOpen: 'second-level,third-level',
		cssOpen: 'level-opened',
		cssClose: 'level-closed',
		speed: 150
	});





/* # Default Layout Options
================================================== */

	//===== Hiding sidebar =====//

	$('.sidebar-toggle').click(function () {
		$('.page-container').toggleClass('hidden-sidebar');
	});


});