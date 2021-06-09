@extends('admin.dashboard')



@section('content')
<div class="card">
<div class="row">
 	<div class="col-sm-8 offset-sm-2">
    	<h3 class="display-5">Edit Kit for order id {{$kit->order->id}}</h3>
      	<div>
            @if ($errors->any())
              <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                </ul>
              </div><br />
            @endif
    		
        	<div class="card-body edit-kit">
        	<form method="post" action="{{action('KitController@update', ['id' => $kit->id, 'type' => 'viaord'])}}">
            	@csrf
            	@method("PUT")
                <div class="form-group">    
                  <label for="sample_id" class="required">Sample ID</label>
                  <input type="text" class="form-control" name="sample_id" value="{{old('sample_id', $kit->sample_id)}}" required/>
                </div>
                
                <div class="form-group">
                  <label for="barcode">Barcode</label>
                  <input type="text" class="form-control" name="barcode" value="{{old('barcode', $kit->barcode)}}"/>
                </div>
                
                <div class="form-group form-group.required">
                  <label for="kit_dispatched_date">Kit Dispatch Date</label>
                  <input class="datepicker form-control" name="kit_dispatched_date" data-date-format="yyyy-mm-dd" 
                  value="{{old('kit_dispatched_date', $kit->kit_dispatched_date)}}">
                  <small id="kit_dispatched_dateHelp" class="form-text text-muted">yyyy-mm-dd</small>
                </div>
                
                <div class="form-group form-group.required">
                  <label for="sample_received_date">Sample Received Date</label>
                  <input class="datepicker form-control" name="sample_received_date" data-date-format="yyyy-mm-dd" 
                  value="{{old('sample_received_date', $kit->sample_received_date)}}">
                  <small id="sample_received_dateHelp" class="form-text text-muted">yyyy-mm-dd</small>
                </div>
                
            	<button type="submit" class="btn btn-primary">Edit Kit</button>
            	<a class="btn btn-secondary" href="{{url('/admin/orders')}}" role="button">Back</a>
            	
            </form>
            </div>
      	</div>
	</div>
</div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
$('.datepicker').datepicker({
	dateFormat: "yy-mm-dd",
	showWeek: true,
	firstDay: 1
});
</script>
@endsection