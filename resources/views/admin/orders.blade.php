@extends('admin.dashboard')

@section('dashboard_title')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Orders</h1>
        
        
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
          	<a href='{{action('OrderController@create')}}'>
          		<button type="button" class="btn btn-sm btn-outline-secondary">Create Order</button>
          	</a>
            <a href='{{action('OrderController@import')}}'>
            	<button type="button" class="btn btn-sm btn-outline-secondary">Import Orders</button>
            </a>
          </div>
		</div>
		
@endsection



@section('content')
@if(session('order_created'))
	<div class="alert alert-success">{{ session('order_created') }}</div>
@endif
@if(session('order_updated'))
	<div class="alert alert-success">{{ session('order_updated') }}</div>
@endif
@if(session()->has('order_deleted'))
    <div class="alert alert-success">{{ session('order_deleted') }}</div>
@endif
@if(session('order_not_deleted'))
	<div class="alert alert-warning">{{ session('order_not_deleted') }}</div>
@endif
@if(session('kit_registered'))
	<div class="alert alert-success">{{ session('kit_registered') }}</div>
@endif
@if(session('kit_updated'))
	<div class="alert alert-success">{{ session('kit_updated') }}</div>
@endif
@if(session('kit_deleted'))
	<div class="alert alert-success">{{ session('kit_deleted') }}</div>
@endif
@if(session('kit_not_deleted'))
	<div class="alert alert-warning">{{ session('kit_not_deleted') }}</div>
@endif
    <table id="orders_table" class="table table-striped table-bordered" width="100%">
        <thead>
            <tr>
                <th class="noexport">S.N</th>
                <th>Order ID</th>
                <th>Name</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Personnummer</th>
                <th>Phone</th>
                <th>Street</th>
                <th>Zipcode</th>
                <th>City</th>
                <th>Country</th>
                <th>Status</th>
                <th>Order Created By</th>
                <th>Date Created</th>
                <th>Date Updated</th>
                <th class="noexport">Actions</th>
            </tr>
        </thead>
        <tbody>
        {{--
        @foreach ($orders as $order)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{$order->id}}</td>
                <td>{{$order->user->first_name." ".$order->user->last_name}}</td>
                <td>{{$order->user->pnr}}</td>
                <td>{{$order->user->phonenumber}}</td>
                <td>{{$order->user->street}}</td>
                <td>{{$order->user->zipcode}}</td>
                <td>{{$order->user->city}}</td>
                <td>{{$order->user->country}}</td>
                <td>
                @if ($order->status===config('constants.kits.KIT_REGISTERED'))
                {{$order->status}}<br>{{$order->kit->created_at}}
                @elseif ($order->status===config('constants.kits.KIT_DISPATCHED'))
                {!!$order->status."<br>".$order->kit->kit_dispatched_date!!}
                @elseif ($order->status===config('constants.samples.SAMPLE_RECEIVED'))
                {!!$order->status."<br>".$order->kit->sample_received_date!!}
                @elseif ($order->status===config('constants.samples.SAMPLE_REGISTERED'))
                {!!$order->status."<br>".$order->kit->sample->sample_registered_date!!}
                @elseif ($order->status===config('constants.results.RESULT_RECEIVED'))
                {!!$order->status."<br>".$order->kit->sample->reporting_date!!}
                @else
                {{$order->status}}
                @endif
                </td>
                <td>{{$order->order_created_by}}</td>
                <td>{{Carbon\Carbon::parse($order->created_at)->timezone('Europe/Stockholm')->toDateTimeString()}}</td>
                <td>{{Carbon\Carbon::parse($order->updated_at)->timezone('Europe/Stockholm')->toDateTimeString()}}</td>
                <td>
                
                @if($order->kit)
                
                <a href="{{url("/admin/kits/".$order->kit->id."/edit/viaord")}}" >
                <button class="btn btn-outline-primary" type="button" data-toggle="tooltip" title="Edit Kit Information">
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  				<path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
  				<path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
				</svg>
				</button>
				</a>
				
				<form action="{{action('KitController@destroy', ['id' => $order->kit->id])}}" method="post" onsubmit="return confirm('Are you sure you want to delete the kit for this order?');">
				@csrf
				@method("DELETE")
				<button class="btn btn-outline-danger" type="submit" data-toggle="tooltip" title="Delete Kit for this order">
                	<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-file-minus" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  		<path fill-rule="evenodd" d="M4 0h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H4z"/>
                  		<path fill-rule="evenodd" d="M5.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H6a.5.5 0 0 1-.5-.5z"/>
                	</svg>
                </button>
                </form>
                
                @else
                
                <a href="{{url("/admin/orders/".$order->id."/registerKit")}}" >
                <button class="btn btn-outline-primary" type="button" data-toggle="tooltip" title="Register Kit">
                	<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-file-plus" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                		<path fill-rule="evenodd" d="M4 0h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H4z"/>
                  		<path fill-rule="evenodd" d="M8 5.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 .5-.5z"/>
                	</svg>
				</button>
				</a>
				@endif
				
				<form action="{{action('OrderController@destroy', ['id' => $order->id])}}" method="post" onsubmit="return confirm('Are you sure you want to delete the order?');">
				@csrf
				@method("DELETE")
				<button class="btn btn-outline-danger" type="submit" data-toggle="tooltip" title="Delete Order">
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
        //var orders = {{--{!!$order!!};--}}
        $('#orders_table').DataTable({
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
				"url" : "{{action('OrderController@getOrders')}}",
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
            /*
            Heads Up! When searching/sorting for eager loaded models, 
            your column name must be declared like relation.column/table.column

            Important! To avoid ambiguous column name error, it is advised to declare your column name 
            as table.column just like on how you declare it when using a join statements.

            Example:
            columns: [
                {data: 'id', name: 'posts.id'},
                {data: 'title', name: 'posts.title'},
                {data: 'user.name', name: 'user.name'},
                {data: 'user.email', name: 'user.email'},
                {data: 'created_at', name: 'posts.created_at'},
                {data: 'updated_at', name: 'posts.updated_at'}
            ]
            */
            "columns": [
            	{data: 'DT_RowIndex', name: 'DT_RowIndex' , orderable: false, searchable: false},
                {data: 'id', name: 'orders.id'},
                {data: 'name', name: 'name'},
                {data: 'user.first_name', name: 'user.first_name'},
                {data: 'user.last_name', name: 'user.last_name'},
                {data: 'user.pnr', name: 'user.pnr'},
                {data: 'user.phonenumber', name: 'user.phonenumber'},
                {data: 'user.street', name: 'user.street'},
                {data: 'user.zipcode', name: 'user.zipcode'},
                {data: 'user.city', name: 'user.city'},
                {data: 'user.country', name: 'user.country'},
                {data: 'order_status', name: 'orders.status'},
                {data: 'order_created_by', name: 'orders.order_created_by'},
                {data: 'created_at', name: 'orders.created_at'},
                {data: 'updated_at', name: 'orders.updated_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            buttons: [
                'colvis', 
                {
                	extend: 'copy',
                	title: 'Orders Export',
                    exportOptions: {
                        columns: [1, ':visible:not(.noexport)']
                    }
                },
                {
                	extend: 'csv',
                	title: 'Orders Export',
                    exportOptions: {
                        columns: [1, ':visible:not(.noexport)']
                    }

                 },
                 {
                 	extend: 'excel',
                 	title: 'Orders Export',
                     exportOptions: {
                         columns: [1, ':visible:not(.noexport)']
                     }

                  },
                  {
                  	extend: 'pdf',
                  	title: 'Orders Export',
                      exportOptions: {
                          columns: [1, ':visible:not(.noexport)']
                      }

                   },
                   {
                   	extend: 'print',
                   	title: 'Orders Export',
                       exportOptions: {
                           columns: [1, ':visible:not(.noexport)']
                       }

                    }
            ],
            "lengthMenu": [ [10, 25, 50, 100, 500, 1000, 5000, -1], [10, 25, 50, 100, 500, 1000, 5000, "All"] ],
            "columnDefs": [
                { "visible": false, "targets": [3, 4] }
            ]
        });
    });

</script>
@endsection

