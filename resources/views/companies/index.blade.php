@extends('layouts.master')
@section('title','Company Listings')
@section('content')
    <div class="row mt-3">
        <div class="col-lg-12">
            <a href="{{route('companiesCreate')}}" class="btn btn-success">Add Company</a>
        </div>
        <div class="col-lg-12 mt-3">
			<table class="table table-striped table-bordered table-hover responsive nowrap mb-2" id="companyDatatable" width="100%">
		        <thead>
		        <tr>
		          <th class="tb-center nowrap">#</th>
		          <th class="tb-center nowrap">Name</th>
		          <th class="tb-center nowrap">Actions</th>
		        </tr>
		        </thead>
		        <tbody>
		        </tbody>
		    </table>
		</div>
    </div>
@endsection
@section('script')
@parent
  	<script type="text/javascript">
	    $(document).ready(function() {
            //Begin::Get company records functionality
            var table = $('#companyDatatable').DataTable({
                "processing": true,
                "serverSide": true,
                "lengthMenu": [2, 3, 5, 40, 50, 60, 80, 100],
                
                "ajax":{
                    "url": "{{ route('companiesRecords') }}",
                    "type": "GET",  
                },  

                "columns": [
                    { "data": "index"},
                    { "data": "name"},
                    { "data": "options", filter:false, sortable:false}
                ],
            });
            //End::Get company records functionality


            //Begin::Delete company functionality
            $(document).on('click','.deleteCompany', function(){
                var id = $(this).attr('data-id');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                Swal.fire({
                    title: "Delete Company Details",
                    text: "Are You Sure?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "Yes",
                    cancelButtonText: "Cancel",
                    icon: 'warning',
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: "{{route('companiesDelete')}}",
                            method: 'POST',
                            data: { 'id': id, _token : $('meta[name="csrf-token"]').attr('content')},
                            success: function(result) {
                                if(result.status == 'true'){
                                    Swal.fire({
                                        type: 'success',
                                        icon: 'success',
                                        text: result.message,
                                    })
                                    table.ajax.reload();
                                }
                                else {
                                    Swal.fire({
                                        type: 'error',
                                        icon: 'error',
                                        text: result.message,
                                    })
                                }
                            }
                        });
                    }
                });  
            });
            //End::Delete company functionality
        });
    </script>
@append