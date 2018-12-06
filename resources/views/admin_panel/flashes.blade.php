@if(count($errors->all()))
	
	<ul>
		@foreach($errors->all() as $err)
	
		<li> {{ $err }} </li>
		
		@endforeach
	</ul>

@endif	

@if(\Session::has('success'))
	
	{{ \Session::get('success') }}

@endif