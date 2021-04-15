@extends('admin.admin_layout.main')
@section('title', 'Services')
@section('page_title', 'Add Service')
@section('customcss')
<link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
@endsection
@section('content')
<div class="row mb-3">
    <div class="col-lg-12">
        <!-- Form Basic -->
        <div class="card mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Add Service</h6>
            </div>
            <div class="card-body">
                <form method="POST" id="submitForm" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="service_name">Service Name</label> <span  style="color:red" id="service_err"> </span>
                                <input type="text" name="service_name" class="form-control" id="service_name"
                                placeholder="Enter Service">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="type">Service Type</label> <span  style="color:red" id="type_err"> </span>
                                <select name="service_type" class="form-control" id="type">
                                    <option value="">-Select Service Type-</option>
                                    <option value="Bookable">Bookable</option>
                                    <option value="Buyable">Buyable</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status">Status</label> <span  style="color:red" id="status_err"> </span>
                                <select name="status" class="form-control" id="status">
                                    <option value="">-Select Status-</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="submitButton" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!--Row-->
<div class="row mb-3">
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Services List</h6>
            </div>
            <div class="table-responsive p-3">
                <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                        <tr>
                            <th>Sr. No.</th>
                            <th>Service</th>
                            <th>Service Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Service</th>
                            <th>Service Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Service</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" >
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_service_name">Service Name<span style="color:red;">*</span></label><span  style="color:red" id="edit_service_err"> </span>
                        <input type="text" name="service_name" id="edit_service_name" class="form-control" value="">
                    </div>
                    <div class="form-group">
                        <label for="edit_type">Service Type</label> <span  style="color:red" id="edit_type_err"> </span>
                        <select name="service_type" class="form-control" id="edit_type">
                            <option value="">-Select Service Type-</option>
                            <option value="Bookable">Bookable</option>
                            <option value="Buyable">Buyable</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_status">Status<span style="color:red;">*</span></label><span  style="color:red" id="edit_status_err"> </span>
                        <select name="status" id="edit_status" class="form-control">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="id" value="">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="editService" onclick="return checkSubmit()">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('customjs')
<!-- Page level plugins -->
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var SITEURL = '{{ route('admin.services.index')}}';
$('#dataTableHover').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
    url: SITEURL,
    type: 'GET',
    },
    columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false,searchable: false},
            { data: 'service_name', name: 'service_name' },
            { data: 'service_type', name: 'service_type' },
            { data: 'status', name: 'status' },
            {data: 'action', name: 'action', orderable: false},
        ],
    order: [[0, 'desc']]
});

$('body').on('click', '#submitButton', function () {
    var service_name = $("#service_name").val();
    var service_type = $("#type").val();
    var status = $("#status").val();
    if (service_name=="") {
        $("#service_err").fadeIn().html("Required");
        setTimeout(function(){ $("#service_err").fadeOut(); }, 3000);
        $("#service_name").focus();
        return false;
    }
    if (service_type=="") {
        $("#type_err").fadeIn().html("Required");
        setTimeout(function(){ $("#type_err").fadeOut(); }, 3000);
        $("#type").focus();
        return false;
    }
    if (status=="") {
        $("#status_err").fadeIn().html("Required");
        setTimeout(function(){ $("#status_err").fadeOut(); }, 3000);
        $("#status").focus();
        return false;
    }
    else
    { 
        var datastring="service_name="+service_name+"&status="+status+"&service_type="+service_type;
        // alert(datastring);
        $.ajax({
            type:"POST",
            url:"{{ route('admin.services.store') }}",
            data:datastring,
            cache:false,        
            success:function(returndata)
            {
                document.getElementById("submitForm").reset();
                var oTable = $('#dataTableHover').dataTable(); 
                oTable.fnDraw(false);
                toastr.success(returndata.success);
            
            // location.reload();
            // $("#pay").val("");
            }
        });
    }
})
function EditModel(obj,bid)
{
    var datastring="bid="+bid;
    // alert(datastring);
    $.ajax({
        type:"POST",
        url:"{{ route('admin.get.service') }}",
        data:datastring,
        cache:false,        
        success:function(returndata)
        {
            // alert(returndata);
        if (returndata!="0") {
            $("#exampleModal").modal('show');
            var json = JSON.parse(returndata);
            $("#id").val(json.id);
            $("#edit_service_name").val(json.service_name);
            $("#edit_type").val(json.service_type);
            $("#edit_status").val(json.status);
        }
        }
    });
}

function checkSubmit()
{
    var service_name = $("#edit_service_name").val();
    var service_type = $("#edit_type").val();
    var status = $("#edit_status").val();
    var id = $("#id").val().trim();
    if (service_name=="") {
        $("#edit_service_err").fadeIn().html("Required");
        setTimeout(function(){ $("#edit_service_err").fadeOut(); }, 3000);
        $("#edit_service_name").focus();
        return false;
    }
    if (service_type=="") {
        $("#edit_type_err").fadeIn().html("Required");
        setTimeout(function(){ $("#edit_type_err").fadeOut(); }, 3000);
        $("#edit_type").focus();
        return false;
    }
    if (status=="") {
        $("#edit_status_err").fadeIn().html("Required");
        setTimeout(function(){ $("#edit_status_err").fadeOut(); }, 3000);
        $("#edit_status").focus();
        return false;
    }
    else
    { 
        $('#editService').attr('disabled',true);
        var datastring="service_name="+service_name+"&status="+status+"&id="+id+"&service_type="+service_type;
        // alert(datastring);
        $.ajax({
            type:"POST",
            url:"{{ url('/admin/service/update') }}",
            data:datastring,
            cache:false,        
            success:function(returndata)
            {
            $('#editService').attr('disabled',false);
            $("#exampleModal").modal('hide');
            var oTable = $('#dataTableHover').dataTable(); 
            oTable.fnDraw(false);
            toastr.success(returndata.success);
            
            // location.reload();
            // $("#pay").val("");
            }
        });
    }
}

$('body').on('click', '#delete', function () {
    var id = $(this).data("id");

    if(confirm("Are You sure want to delete !")){
        $.ajax({
            type: "delete",
            url: "{{ url('admin/services') }}"+'/'+id,
            success: function (data) {
            var oTable = $('#dataTableHover').dataTable(); 
            oTable.fnDraw(false);
            toastr.success(data.success);
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    }
});
</script>
@endsection