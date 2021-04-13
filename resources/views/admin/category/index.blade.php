@extends('admin.admin_layout.main')
@section('title', 'Category')
@section('page_title', 'Category')
@section('customcss')
<link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<style>
.hidden{
    display:none;
}
</style>
@endsection
@section('content')
<div class="row mb-3">
    <div class="col-lg-12">
        <!-- Form Basic -->
        <div class="card mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Add Category</h6>
            </div>
            <div class="card-body">
                <form method="POST" id="submitForm" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="service_name">Service</label> <span  style="color:red" id="service_err"> </span>
                                <select name="service_name" class="form-control" id="service_name">
                                    <option value="">-Select Service-</option>
                                    @foreach($services as $s)
                                    <option value="{{ $s->id }}">{{ $s->service_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="type">Category</label> <span  style="color:red" id="cat_err"> </span>
                                <input type="text" name="category" id="category" class="form-control">
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="customControlAutosizing" value="1">
                                    <label class="custom-control-label" for="customControlAutosizing">Is Parent?</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 hidden" id="showDiv">
                            <label for="status">Parent Category</label> <span  style="color:red" id="parent_err"> </span>
                            <select name="parent_id" class="form-control" id="parent_id">
                                <option value="">-Select Category-</option>
                                @foreach($categories as $c)
                                <option value="{{ $c->id }}">{{ $c->cat_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" id="submitButton" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
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
                <h6 class="m-0 font-weight-bold text-primary">Category List</h6>
            </div>
            <div class="table-responsive p-3">
            <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                        <tr>
                            <th>Sr. No.</th>
                            <th>Service</th>
                            <th>Category</th>
                            <th>Parent Category</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Service</th>
                            <th>Category</th>
                            <th>Parent Category</th>
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
                        <label for="service_name">Service</label> <span  style="color:red" id="service_err"> </span>
                        <select name="service_name" class="form-control" id="edit_service_name">
                            <option value="">-Select Service-</option>
                            @foreach($services as $s)
                            <option value="{{ $s->id }}">{{ $s->service_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="type">Category</label> <span  style="color:red" id="cat_err"> </span>
                        <input type="text" name="category" id="edit_category" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label> <span  style="color:red" id="status_err"> </span>
                        <select name="status" class="form-control" id="status">
                            <option value="">-Select Status-</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_parent" value="1">
                            <label class="custom-control-label" for="is_parent">Is Parent?</label>
                        </div>
                    </div>
                    <div class="form-group hidden" id="showDiv1">
                        <label for="status">Parent Category</label> <span  style="color:red" id="parent_err"> </span>
                        <select name="parent_id" class="form-control" id="parent_id">
                            <option value="">-Select Category-</option>
                            @foreach($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->cat_name }}</option>
                            @endforeach
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

var SITEURL = '{{ route('admin.categories.index')}}';
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
            { data: 'cat_name', name: 'cat_name' },
            { data: 'parent_id', name: 'parent_id' },
            { data: 'status', name: 'status' },
            {data: 'action', name: 'action', orderable: false},
        ],
    order: [[0, 'desc']]
});

$('body').on('click', '#submitButton', function () {
    var service_name = $("#service_name").val();
    var category = $("#category").val();
    var status = $("#status").val();
    var parent_id = $("#parent_id").val();
    var is_parent = $("#customControlAutosizing").val();
    // alert(is_parent);
    if (service_name=="") {
        $("#service_err").fadeIn().html("Required");
        setTimeout(function(){ $("#service_err").fadeOut(); }, 3000);
        $("#service_name").focus();
        return false;
    }
    if (category=="") {
        $("#cat_err").fadeIn().html("Required");
        setTimeout(function(){ $("#cat_err").fadeOut(); }, 3000);
        $("#category").focus();
        return false;
    }
    if(is_parent == 0){
        if(parent_id == ""){
            $("#parent_err").fadeIn().html("Required");
            setTimeout(function(){ $("#parent_err").fadeOut(); }, 3000);
            $("#parent_id").focus();
            return false;
        }
    }
    if (status=="") {
        $("#status_err").fadeIn().html("Required");
        setTimeout(function(){ $("#status_err").fadeOut(); }, 3000);
        $("#status").focus();
        return false;
    }
    else
    { 
        var datastring="service_name="+service_name+"&status="+status+"&category="+category+"&is_parent="+is_parent+"&parent_id="+parent_id;
        // alert(datastring);
        $.ajax({
            type:"POST",
            url:"{{ route('admin.categories.store') }}",
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
        url:"{{ route('admin.get.category') }}",
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

$(function() {
  
  $(document).on('click', '#customControlAutosizing', function() {
  
    if ($(this).val() == 1) {
        $("#showDiv").show();
        $(this).val(0);
    } 
    else {
        $("#showDiv").hide();
        $(this).val(1);
    }
  });
  
});
</script>
@endsection