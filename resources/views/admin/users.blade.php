@extends('admin.dashboard')

@section('dashboard_title')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Users</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
          	<a href='{{action('UserController@create')}}'>
          		<button type="button" class="btn btn-sm btn-outline-secondary">Add User</button>
          	</a>
            <a href='{{action('UserController@import')}}'>
            	<button type="button" class="btn btn-sm btn-outline-secondary">Import Users</button>
            </a>
          </div>
        {{--
          <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
            <span data-feather="calendar"></span>
            This week
          </button>
        --}}
        </div>
        
</div>
@endsection



@section('content')
@if(session('user_created'))
	<div class="alert alert-success">{{ session('user_created') }}</div>
@endif
@if(session('user_updated'))
	<div class="alert alert-success">{{ session('user_updated') }}</div>
@endif
@if(session()->has('user_deleted'))
    <div class="alert alert-success">{{ session('user_deleted') }}</div>
@endif
@if(session('user_not_deleted'))
	<div class="alert alert-warning">{{ session('user_not_deleted') }}</div>
@endif
    <table id="users_table" class="table table-striped table-bordered" width="100%">
        <thead>
            <tr>
                <th class="noexport">S.N</th>
                <th>User ID</th>
                <th>Name</th>
                <th>Personnummer</th>
                <th>Phone</th>
                <th>Roles</th>
                <th>Street</th>
                <th>Zipcode</th>
                <th>City</th>
                <th>Country</th>
                <th>No. of Orders</th>
                <th>Consent to Study</th>
                <th>Date Created</th>
                <th>Date Updated</th>
                <th class="noexport">Actions</th>
            </tr>
        </thead>
        {{--
        <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{$user->id}}</td>
                <td>{{$user->first_name." ".$user->last_name}}</td>
                <td>{{$user->pnr}}</td>
                <td>{{$user->phonenumber}}</td>
                <td>{{$user->roles}}</td>
                <td>{{$user->street}}</td>
                <td>{{$user->zipcode}}</td>
                <td>{{$user->city}}</td>
                <td>{{$user->country}}</td>
                <td>{{$user->orders->count()}}</td>
                <td>{{(is_null($user->consent))?"":(($user->consent==1)?"Yes":"No")}}</td>
                <td>{{Carbon\Carbon::parse($user->created_at)->timezone('Europe/Stockholm')->toDateTimeString()}}</td>
                <td>{{Carbon\Carbon::parse($user->updated_at)->timezone('Europe/Stockholm')->toDateTimeString()}}</td>
                <td>
                <a href="{{url("/admin/users/".$user->id."/edit")}}" >
                <button class="btn btn-outline-primary" type="button" data-toggle="tooltip" title="Edit User">
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  				<path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
  				<path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
				</svg>
				</button>
				</a>
				
				<form action="{{action('UserController@destroy', ['id' => $user->id])}}" method="post" onsubmit="return confirm('Are you sure you want to delete the user? All data related with the user will be deleted!');">
				@csrf
				@method("DELETE")
				<button class="btn btn-outline-danger" type="submit" data-toggle="tooltip" title="Delete User">
				<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                </svg>
				</button>
				</form>
				
                </td>
            </tr>
        @endforeach
        </tbody>
        --}}
    </table>
    
@endsection

@section('scripts')
<script type="text/javascript">

    $(document).ready(function() {
		/*
    	$.ajax({
            type        : 'GET', // define the type of HTTP verb we want to use (POST for our form)
            url         : "{{action('UserController@getUsers')}}", // the url where we want to POST
            success:function(response){
                console.log(response);

            }
    	});
    	*/
        $('#users_table').DataTable({
            dom: 'Blfrtip',
            "scrollX": true,
            "deferRender": true,
            "ajax": {
				"url" : "{{action('UserController@getUsers')}}",
				/*
				By default DataTables will look for the property 'data' 
				(or aaData for compatibility with DataTables 1.9-) when obtaining data 
				from an Ajax source. Option 'dataSrc' allows that property to be changed and named
				anything else. Note that if your Ajax source simply returns an array of data 
				to display, rather than an object or array in an object, set this parameter to be an 
				empty string. 
				*/
				"dataSrc" : ""
            },
            "columns": [
            	{ 
                	"data": null,
                	"sortable": false, 
            	    "render": function (data, type, row, meta) {
            	                 return meta.row + meta.settings._iDisplayStart + 1;
            	     }  
            	},
                { "data": "id" },
                { 
                    "data": null,
                    "render": function (data, type, row, meta) {
                        var first_name = (row.first_name !== null)?row.first_name:'';
                        var last_name = (row.last_name !== null)?row.last_name:'';
                        return first_name+' '+last_name;
                    }
                	
                },
                { "data": "pnr" },
                { 
                    "data": "phone",
                    "defaultContent": ""
                },
                { 
                    "data": "roles",
                    "defaultContent": ""
                },
                { 
                    "data": "street",
                    "defaultContent": ""
                },
                { 
                    "data": "zipcode",
                    "defaultContent": ""
                },
                { 
                    "data": "city",
                    "defaultContent": ""
                },
                { 
                    "data": "country",
                    "defaultContent": ""
                },
                { "data": "orders_count" },
                { 
                    "data": "consent",
                    "render": function (data, type, row, meta) {
						return (data == null)?"":((data == 1)?"Yes":"No");
                	}
                },
                { "data": "created_at" },
                { "data": "updated_at" },
                { 
                    "data": null,
                    "render": function (data, type, row, meta) {
                        var baseurl = "{{url("/")}}";
                        var editurl = baseurl+'/admin/users/'+row.id+'/edit';
                        var deleteurl = baseurl+'/admin/users/'+row.id;
	
                        
                        return '<a href="'+editurl+'">\
                        <button class="btn btn-outline-primary" type="button" data-toggle="tooltip" title="Edit User">\
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">\
          				<path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>\
          				<path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>\
        				</svg>\
                        </button>\
                        </a>\
                        <form action="'+deleteurl+'" method="post" onsubmit="return confirm(\'Are you sure you want to delete the user? All data related with the user will be deleted!\');">\
        				@csrf\
        				@method("DELETE")\
        				<button class="btn btn-outline-danger" type="submit" data-toggle="tooltip" title="Delete User">\
        				<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">\
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>\
                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>\
                        </svg>\
        				</button>\
        				</form>';
                        
                        
                    }
                },
                
            ],
             
            buttons: [
                'colvis', 
                {
                	extend: 'copy',
                	title: 'Users Export',
                    exportOptions: {
                        columns: [1, ':visible:not(.noexport)']
                    }
                },
                {
                	extend: 'csv',
                	title: 'Users Export',
                    exportOptions: {
                        columns: [1, ':visible:not(.noexport)']
                    }

                 },
                 {
                 	extend: 'excel',
                 	title: 'Users Export',
                     exportOptions: {
                         columns: [1, ':visible:not(.noexport)']
                     }

                  },
                  {
                  	extend: 'pdf',
                  	title: 'Users Export',
                      exportOptions: {
                          columns: [1, ':visible:not(.noexport)']
                      }

                   },
                   {
                   	extend: 'print',
                   	title: 'Users Export',
                       exportOptions: {
                           columns: [1, ':visible:not(.noexport)']
                       }

                    }
            ],
            "columnDefs": [
                { "visible": false, "targets": 1 }
            ]
        });
    });

</script>
@endsection

