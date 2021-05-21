<img class="mr-md-auto" src="{{ asset('img/KI_logo.png') }}" alt="Karolinska HPV Self Sampling"
	title="Karolinska HPV Self Sampling" width="62" height="62">
<h5 class="my-0 mr-md-auto font-weight-normal site-title">International Human
	Papillomavirus Reference Center</h5>
<nav class="my-2 my-md-0 mr-md-3">
	<a class="p-2 text-primary {{ (request()->is('/')) ? 'active' : '' }}" href="{{url('/')}}" title="Hem">Hem</a>
	<a class="p-2 text-primary {{ (request()->is('*profile')) ? 'active' : '' }}" href="{{url('/profile')}}" title="Mina Sidor">Mina Sidor</a>
	<a class="p-2 text-primary {{ (request()->is('*faqs')) ? 'active' : '' }}" href="{{url('/faqs')}}" title="Vanliga Frågor">Vanliga Frågor</a>
</nav>



@if(session()->has('userattributes') && session()->has('role') && session()->get('role')===config('constants.roles.USER_ROLE'))

<div class="dropdown">
  <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    {{session('userattributes')['name']}}
  </a>

  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
    <a class="dropdown-item" href="{{action('BankIDController@bankidlogout', ['sessionId' => session('grandidsession'), 'type' => 'user'])}}">Sign out</a>
  </div>
</div>
@endif

