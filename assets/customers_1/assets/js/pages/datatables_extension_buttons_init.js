/* ------------------------------------------------------------------------------
*
*  # Buttons extension for Datatables. Init examples
*
*  Specific JS code additions for datatable_extension_buttons_init.html page
*
*  Version: 1.0
*  Latest update: Nov 9, 2015
*
* ---------------------------------------------------------------------------- */

$(function() {


    // Table setup
    // ------------------------------

    // Setting datatable defaults
    $.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span></span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        }
    });


    // Basic initialization
    $('.datatable-button-init-basic').DataTable({
        buttons: {
            dom: {
                button: {
                    className: 'btn btn-default'
                }
            },
            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel'},
                {extend: 'pdf'},
                {extend: 'print'}
            ]
        }
    });


    // Custom button
    $('.datatable-button-init-custom').DataTable({
       dom: 'l<"toolbar">frtip',
         initComplete: function(){
          $("div.toolbar")
             .html('<div class="btn-group"><button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Filter Status <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right"><li onclick="filterSearch(4)" ><a href="#"><span class="status-mark bg-primary position-left"></span> All</a></li><li onclick="filterSearch(0)" ><a href="#"><span class="status-mark bg-warning position-left"></span> Unsent</a></li><li onclick="filterSearch(1)"  ><a href="#"><span class="status-mark bg-danger position-left"></span> Sent</a></li><li onclick="filterSearch(2)" ><a href="#"><span class="status-mark bg-success position-left"></span> Paid</a></li>  <li onclick="filterSearch(3)" ><a href="#"><span class="status-mark bg-till position-left"></span> Partial</a></li>    </ul>&nbsp;&nbsp;</div> <div class="btn-group">   <button type="button" disabled="disabled" class="btn btn-success dropdown-toggle" data-toggle="dropdown" id="bulk_status_change" >Change Status <span class="caret"></span></button>   <ul class="dropdown-menu dropdown-menu-right">      <li onclick="bulkStatusChange(0)" ><a href="#"><span class="status-mark bg-warning position-left"></span> Unsent</a></li>      <li onclick="bulkStatusChange(1)"  ><a href="#"><span class="status-mark bg-danger position-left"></span> Sent</a></li>      <li onclick="bulkStatusChange(2)" ><a href="#"><span class="status-mark bg-success position-left"></span> Paid</a></li>   </ul></div> &nbsp;&nbsp;<button type="submit"  disabled="disabled" data-toggle="modal" data-target="#modal_theme_primary" class="btn btn-success" id="allMessage">Send By Email</button>&nbsp;&nbsp;<button type="submit"  disabled="disabled"  class="btn btn-success" id="allPrint">Print</button>&nbsp;&nbsp;<button type="submit"  class="btn btn-danger" id="deletebutton" onclick="deletemultiple()" disabled >Delete</button>');           
       }       
    });

      
  


    // Buttons collection
    $('.datatable-button-init-collection').DataTable({
        buttons: [
            {
                extend: 'collection',
                text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
                className: 'btn bg-blue btn-icon',
                buttons: [
                    {
                        text: 'Toggle first name',
                        action: function ( e, dt, node, config ) {
                            dt.column( 0 ).visible( ! dt.column( 0 ).visible() );
                        }
                    },
                    {
                        text: 'Toggle status',
                        action: function ( e, dt, node, config ) {
                            dt.column( -2 ).visible( ! dt.column( -2 ).visible() );
                        }
                    }
                ]
            }
        ]
    });


    // Page length
    $('.datatable-button-init-length').DataTable({
        dom: '<"datatable-header"fB><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
        ],
        buttons: [
            {
                extend: 'pageLength',
                className: 'btn bg-slate-600'
            }
        ]
    });



    // External table additions
    // ------------------------------

    // Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Search');


    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
    
});
