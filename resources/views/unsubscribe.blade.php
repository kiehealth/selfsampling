@extends('home')


@section('content')

@if(session()->has('user_id') && session()->has('role') && session()->get('role')===config('constants.roles.USER_ROLE'))

<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto research">
	<h1 class="display-4 text-center">Du är inloggad med Mobilt BankID</h1>
	
	@if(session()->has('unsubscribed'))
    <div class="alert alert-success text-justify">
      {!! session('unsubscribed') !!}  
    </div>
	@endif
	
	@if(!session()->has('unsubscribed'))
	<p class="lead">Om du är säker att du vill inte delta, avsluta deltagandet genom knappen nedan.
	</p>
	<form class="text-center" action="{{action('UserController@unsubscribe', ['user_id' => session('user_id')])}}" method="post">
        @csrf
        <input type="hidden" name="user_id" value = "{{ session('user_id') }}" >
        <button type="submit" class="btn btn-primary btn-lg">Avsluta</button>
    </form>
    @endif
</div>


@else
<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto research">
	<h1 class="display-4 text-center">Avsluta deltagandet genom att logga in med Mobilt BankID</h1>
	<p class="lead">Om du avslutar kommer vi inte kontakta dig längre. Däremot om du ångrar dig, behöver du bara
		samtycka igen på hemsidan och beställa självprovtagningskit.
	</p>
	
	@if(session('user_not_found'))
	<div class="alert alert-danger text-center">{{ session('user_not_found') }}</div>
	@endif
    
	<p class="lead text-center">
	<a class="btn btn-primary consent-btn" href="{{action('BankIDController@bankidlogin', ['type' => 'user'])}}">Avsluta via Mobilt BankID</a>
	</p>
</div>
<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto research border-top">	
	<h1 class="display-4 text-center">Avsluta utan inloggning</h1>

    @if(session()->has('unsubscribed'))
    <div class="alert alert-success text-justify">
      {!! session('unsubscribed') !!}  
    </div>
	@endif
	
    @if (session('error'))
    <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif
    
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
        </ul>
      </div><br />
    @endif
    
    @if(!session()->has('unsubscribed'))
    <p class="lead">Om du inte har Mobilt BankID eller vill avsluta utan inloggning 
		kan du vänligen ange ditt personnummer i fältet nedan. Om du avslutar kommer vi inte kontakta dig längre. 
		Däremot om du ångrar dig, behöver du bara samtycka igen på hemsidan och beställa självprovtagningskit.
	</p>
	<form class="form-inline justify-content-center" action="{{action('UserController@unsubscribe', ['type' => 'pnr'])}}" method="post">
        @csrf
        <input type="text" name="pnr" class="form-control mb-2 mr-sm-2" id="pnr" 
        value = "{{ old('pnr') }}" placeholder="ÅÅÅÅMMDDNNNN" required>
        <button type="submit" class="btn btn-primary mb-2">Avsluta</button>
    </form>
    @endif
    
</div>

@endif
@endsection