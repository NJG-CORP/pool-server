@if(count($errors->all()))
	
	<ul>
		@foreach($errors->all() as $err)
	
		<li class="alert alert-danger"> {{ $err }} </li>
		
		@endforeach
	</ul>

@endif	

@if(\Session::has('success'))
	
	
	<p class="alert alert-success"> {{ \Session::get('success') }} </p>

@endif