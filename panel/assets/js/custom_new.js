/**
 * Resize function without multiple trigger
 * 
 * Usage:
 * $(window).smartresize(function(){  
 *     // code here
 * });
 */
(function($,sr){
    // debouncing function from John Hann
    // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
    var debounce = function (func, threshold, execAsap) {
      var timeout;

        return function debounced () {
            var obj = this, args = arguments;
            function delayed () {
                if (!execAsap)
                    func.apply(obj, args); 
                timeout = null; 
            }

            if (timeout)
                clearTimeout(timeout);
            else if (execAsap)
                func.apply(obj, args);

            timeout = setTimeout(delayed, threshold || 100); 
        };
    };

    // smartresize 
    jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };


/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var CURRENT_URL = window.location.href.split('#')[0].split('?')[0],
    $BODY = $('body'),
    $MENU_TOGGLE = $('#menu_toggle'),
    $SIDEBAR_MENU = $('#sidebar-menu'),
    $SIDEBAR_FOOTER = $('.sidebar-footer'),
    $LEFT_COL = $('.left_col'),
    $RIGHT_COL = $('.right_col'),
    $NAV_MENU = $('.nav_menu'),
    $FOOTER = $('footer');

// // Table
// $('table input').on('ifChecked', function () {
//     checkState = '';
//     $(this).parent().parent().parent().addClass('selected');
//     countChecked();
// });
// $('table input').on('ifUnchecked', function () {
//     checkState = '';
//     $(this).parent().parent().parent().removeClass('selected');
//     countChecked();
// });

// var checkState = '';

// $('.bulk_action input').on('ifChecked', function () {
//     checkState = '';
//     $(this).parent().parent().parent().addClass('selected');
//     countChecked();
// });
// $('.bulk_action input').on('ifUnchecked', function () {
//     checkState = '';
//     $(this).parent().parent().parent().removeClass('selected');
//     countChecked();
// });
// $('.bulk_action input#check-all').on('ifChecked', function () {
//     checkState = 'all';
//     countChecked();
// });
// $('.bulk_action input#check-all').on('ifUnchecked', function () {
//     checkState = 'none';
//     countChecked();
// });

// function countChecked() {
//     if (checkState === 'all') {
//         $(".bulk_action input[name='table_records']").iCheck('check');
//     }
//     if (checkState === 'none') {
//         $(".bulk_action input[name='table_records']").iCheck('uncheck');
//     }

//     var checkCount = $(".bulk_action input[name='table_records']:checked").length;

//     if (checkCount) {
//         $('.column-title').hide();
//         $('.bulk-actions').show();
//         $('.action-cnt').html(checkCount + ' Records Selected');
//     } else {
//         $('.column-title').show();
//         $('.bulk-actions').hide();
//     }
// }

// Accordion
// $(document).ready(function() {
//     $(".expand").on("click", function () {
//         $(this).next().slideToggle(200);
//         $expand = $(this).find(">:first-child");

//         if ($expand.text() == "+") {
//             $expand.text("-");
//         } else {
//             $expand.text("+");
//         }
//     });
// });
   	
		/* DATA TABLES */
			
			function init_DataTables() {
				
				console.log('run_datatables');
				
				if( typeof ($.fn.DataTable) === 'undefined'){ return; }

				console.log('init_DataTables');
				
				var handleDataTableButtons = function() {
				  if ($(".datatable-buttons").length) {
					$(".datatable-buttons").DataTable({
					  dom: '<"col-md-6"B><"col-md-6"f><"#tblreserva"t><"col-sm-12"i>',
					  buttons: [
						// {
						//   extend: "copy",
						//   className: "btn-sm"
						// },
						{
						  extend: "csv",
						  className: "btn-sm"
						},
						{
						  extend: "excel",
						  className: "btn-sm"
						},
						{
						  extend: "excelHtml5",
						  className: "btn-sm"
						},
						{
						  extend: "pdfHtml5",
						  className: "btn-sm"
						},
						{
						  extend: "print",
						  className: "btn-sm"
						},					  ],
					  
					  bPaginate: false,
					  responsive: false
					});
				  }
				};

				TableManageButtons = function() {
				  "use strict";
				  return {
					init: function() {
					  handleDataTableButtons();
					}
				  };
				}();

				$('#datatable').dataTable();

				$('#datatable-keytable').DataTable({
				  keys: true
				});

				$('#datatable-responsive').DataTable();

				$('#datatable-scroller').DataTable({
				  ajax: "js/datatables/json/scroller-demo.json",
				  deferRender: true,
				  scrollY: 380,
				  scrollCollapse: true,
				  scroller: true
				});

				$('#datatable-fixed-header').DataTable({
				  fixedHeader: true
				});

				var $datatable = $('#datatable-checkbox');

				$datatable.dataTable({
				  'order': [[ 1, 'asc' ]],
				  'columnDefs': [
					{ orderable: true, targets: [0] }
				  ]
				});
				$datatable.on('draw.dt', function() {
				  $('checkbox input').iCheck({
					checkboxClass: 'icheckbox_flat-green'
				  });
				});

				TableManageButtons.init();
				
			};
	   
	$(document).ready(function() {
		init_DataTables();	

	});	
	


})(jQuery,'smartresize');
