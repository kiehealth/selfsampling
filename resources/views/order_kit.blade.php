@extends('home')


@section('content')

@if(session()->has('user_id') && session()->has('role') && session()->get('role')===config('constants.roles.USER_ROLE'))

<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto research">
	<h1 class="display-4 text-center">Du är inloggad med Mobilt BankID</h1>
	
	@if(session()->has('order_created'))
    <div class="alert alert-success text-justify">
      {!! session('order_created') !!}  
    </div>
	@endif
	
	@if(!session()->has('order_created'))
	<p class="lead">Beställa provtagningsmaterial med svarskuvert samt instruktioner genom knappen nedan.
	</p>
	<form class="text-center" action="{{action('OrderController@orderKit', ['user_id' => session('user_id')])}}" method="post">
        @csrf
        <input type="hidden" name="user_id" value = "{{ session('user_id') }}" >
        <button type="submit" class="btn btn-primary btn-lg">Beställa</button>
    </form>
    @endif
</div>


@else
<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto research">
	<h1 class="display-4 text-center">Beställa via logga in med Mobilt BankID</h1>
	<p class="lead">Om du loggar in med Mobilt BankID kan du kontrollera dina uppgifter, beställningar, samt se
		dina provsvar.
	</p>
	
	@if(session('user_not_found'))
	<div class="alert alert-danger text-center">{{ session('user_not_found') }}</div>
	@endif
    
	<p class="lead text-center">
	<a class="btn btn-primary consent-btn" href="{{action('BankIDController@bankidlogin', ['type' => 'user'])}}">Beställa via Mobilt BankID</a>
	</p>
</div>
<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto research border-top">	
	<h1 class="display-4 text-center">Beställa utan inlognning</h1>

    @if(session()->has('order_created'))
    <div class="alert alert-success text-justify">
      {!! session('order_created') !!}  
    </div>
	@endif
	
    @if (session('error'))
    <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif
    
    @if(!session()->has('order_created'))
    <p class="lead">Om du inte har Mobilt BankID eller vill beställa utan inloggning 
		kan du vänligen ange ditt personnummer i fältet nedan.
	</p>
	<form class="form-inline justify-content-center" action="{{url('orders')}}" method="post">
        @csrf
        <input type="text" name="pnr" class="form-control mb-2 mr-sm-2" id="pnr" 
        value = "{{ old('pnr') }}" placeholder="ÅÅÅÅMMDDNNNN" required>
        <button type="submit" class="btn btn-primary mb-2">Beställa</button>
    </form>
    @endif
    
</div>

@endif
@endsection