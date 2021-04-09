<x-riscc-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="{{ url('/') }}">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-label for="name" :value="__('Name')" />

                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

			<!-- SSN -->
            <div class="mt-4">
                <x-label for="ssn" :value="__('SSN')" />

                <x-input id="ssn" class="block mt-1 w-full" type="text" name="ssn" :value="old('ssn')" required autofocus />
            </div>
            
            <!-- Phonenumber -->
            <div class="mt-4">
                <x-label for="phonenumber" :value="__('Phonenumber')" />

                <x-input id="phonenumber" class="block mt-1 w-full" type="text" name="phonenumber" :value="old('phonenumber')" autofocus />
            </div>
            
            <!-- Street -->
            <div class="mt-4">
                <x-label for="street" :value="__('Street')" />

                <x-input id="street" class="block mt-1 w-full" type="text" name="street" :value="old('street')" autofocus />
            </div>
            
            <!-- Zipcode -->
            <div class="mt-4">
                <x-label for="zipcode" :value="__('Zipcode')" />

                <x-input id="zipcode" class="block mt-1 w-full" type="text" name="zipcode" :value="old('zipcode')" autofocus />
            </div>
            
            <!-- City -->
            <div class="mt-4">
                <x-label for="city" :value="__('City')" />

                <x-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" autofocus />
            </div>
			
			<!-- Country -->
            <div class="mt-4">
                <x-label for="country" :value="__('Country')" />

				<select class="form-control" name="country" id="country">
              		<option value="">---Select---</option>
                      @foreach(config('countries') as $key=>$value)
    					<option class="block mt-1 w-full" value="{{$value['name']}}" {{$value['name'] === old('country')?'selected':''}} autofocus>{{$value['name']}}</option>
      				  @endforeach
			  	</select>
                {{--<x-dropdown id="country" class="block mt-1 w-full" type="text" name="country" :value="old('country')" autofocus />--}}
            </div>
            
            
            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required />
            </div>
			
			


            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login', ['type' => 'user']) }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ml-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-riscc-layout>
