@extends('admin.dashboard')

@section('dashboard_title')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Kits</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
            <a href='{{action('KitController@import')}}'>
            	<button type="button" class="btn btn-sm btn-outline-secondary">Import Kits/Samples</button>
            </a>
          </div>
		</div>
@endsection



@section('content')
@if(session('kit_updated'))
	<div class="alert alert-success">{{ session('kit_updated') }}</div>
@endif
@if(session('kit_deleted'))
	<div class="alert alert-success">{{ session('kit_deleted') }}</div>
@endif
@if(session('kit_not_deleted'))
	<div class="alert alert-warning">{{ session('kit_not_deleted') }}</div>
@endif
@if(session('sample_registered'))
	<div class="alert alert-success">{!! session('sample_registered') !!}</div>
@endif
    <table id="kits_table" class="table table-striped table-bordered" width="100%">
        <thead>
            <tr>
                <th class="noexport">S.N</th>
                <th>Kit ID</th>
                <th>Order ID</th>
                <th>Sample ID</th>
                <th>Barcode</th>
                <th>Name</th>
                <th>Personnummer</th>
                <th>Phone</th>
                <th>Street</th>
                <th>Zipcode</th>
                <th>City</th>
                <th>Country</th>
                <th>Kit Dispatched Date</th>
                <th>Sample Received Date</th>
                <th>Date Created</th>
                <th>Date Updated</th>
                <th class="noexport">Actions</th>
            </tr>
        </thead>
        <tbody>
        {{--
        @foreach ($kits as $kit)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{$kit->id}}</td>
                <td>{{$kit->order->id}}</td>
                <td>{{$kit->sample_id}}</td>
                <td>{{$kit->barcode}}</td>
                <td>{{$kit->user->first_name." ".$kit->user->last_name}}</td>
                <td>{{$kit->user->pnr}}</td>
                <td>{{$kit->user->phonenumber}}</td>
                <td>{{$kit->user->street}}</td>
                <td>{{$kit->user->zipcode}}</td>
                <td>{{$kit->user->city}}</td>
                <td>{{$kit->user->country}}</td>
                <td>{{$kit->kit_dispatched_date}}</td>
                <td>{{$kit->sample_received_date}}</td>
                <td>{{Carbon\Carbon::parse($kit->created_at)->timezone('Europe/Stockholm')->toDateTimeString()}}</td>
                <td>{{Carbon\Carbon::parse($kit->updated_at)->timezone('Europe/Stockholm')->toDateTimeString()}}</td>
                <td>
                
                @if(!$kit->sample)
                
                <a href="{{url("/admin/kits/".$kit->id."/registerSample")}}" >
                <button class="btn btn-outline-primary" type="button" data-toggle="tooltip" title="Register Sample">
                <i class="fas fa-flask"></i>
                </button>
                </a>
                
                @endif
                
                <a href="{{url("/admin/kits/".$kit->id."/edit/kits")}}" >
                <button class="btn btn-outline-primary" type="button" data-toggle="tooltip" title="Edit Kit Information">
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  				<path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
  				<path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
				</svg>
				</button>
				</a>
				
				<form action="{{action('KitController@destroy', ['id' => $kit->id])}}" method="post" onsubmit="return confirm('Are you sure you want to delete the kit?');">
				@csrf
				@method("DELETE")
				<button class="btn btn-outline-danger" type="submit" data-toggle="tooltip" title="Delete Kit">
    				<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    	<path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                    	<path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                    </svg>
				</button>
				</form>
                </td>
            </tr>
        @endforeach
        --}}
        </tbody>
    </table>
    
@endsection

@section('scripts')
<script type="text/javascript">

    $(document).ready(function() {
        $('#kits_table').DataTable({
            "dom": 'Blfrtip',
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "stateSave": true,
            "stateSaveParams": function(settings, data) {
            	//do not stateSave invisible columns, i.e. let the invisible columns remain invisible 
            	//even after reload.
            	data.columns.forEach(function(column) {
            		delete column.visible;
            	});
            },
            "ajax": {
				"url" : "{{action('KitController@getKits')}}",
				/*
				By default DataTables will look for the property 'data' 
				(or aaData for compatibility with DataTables 1.9-) when obtaining data 
				from an Ajax source. Option 'dataSrc' allows that property to be changed and named
				anything else. Note that if your Ajax source simply returns an array of data 
				to display, rather than an object or array in an object, set this parameter to be an 
				empty string. 
				*/
				//"dataSrc" : ""
            },
            "columns": [
            	{data: 'DT_RowIndex', name: 'DT_RowIndex' , orderable: false, searchable: false},
            	{data: 'id', name: 'id'},
                {data: 'order_id', name: 'order_id'},
                {data: 'sample_id', name: 'sample_id'},
                {data: 'barcode', name: 'barcode'},
                {data: 'name', name: 'name'},
                {data: 'user.pnr', name: 'user.pnr'},
                {data: 'user.phonenumber', name: 'user.phonenumber'},
                {data: 'user.street', name: 'user.street'},
                {data: 'user.zipcode', name: 'user.zipcode'},
                {data: 'user.city', name: 'user.city'},
                {data: 'user.country', name: 'user.country'},
                {data: 'kit_dispatched_date', name: 'kit_dispatched_date'},
                {data: 'sample_received_date', name: 'sample_received_date'},
                {data: 'created_at', name: 'created_at'},
                {data: 'updated_at', name: 'updated_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            buttons: [
                'colvis', 
                {
                	extend: 'copy',
                	title: 'Kits Export',
                    exportOptions: {
                        columns: [1, ':visible:not(.noexport)']
                    }
                },
                {
                	extend: 'csv',
                	title: 'Kits Export',
                    exportOptions: {
                        columns: [1, ':visible:not(.noexport)']
                    }

                 },
                 {
                 	extend: 'excel',
                 	title: 'Kits Export',
                     exportOptions: {
                         columns: [1, ':visible:not(.noexport)']
                     }

                  },
                  {
                  	extend: 'pdf',
                  	title: 'Kits Export',
                      exportOptions: {
                          columns: [1, ':visible:not(.noexport)']
                      }

                   },
                   {
                   	extend: 'print',
                   	title: 'Kits Export',
                       exportOptions: {
                           columns: [1, ':visible:not(.noexport)']
                       }

                    }
            ],
            "lengthMenu": [ [10, 25, 50, 100, 500, 1000, 5000, -1], [10, 25, 50, 100, 500, 1000, 5000, "All"] ],
            "columnDefs": [
                { "visible": false, "targets": 1 }
            ]
        });
    });

</script>
@endsection

