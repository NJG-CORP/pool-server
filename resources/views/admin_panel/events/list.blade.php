@extends('admin_panel.base')

@section('content')



<div class="events">

	<h2>List Of Events </h2>
	<hr>
	<a href="{{ route('get:event:formAdd')}}">
		<button class="btn btn-success"> Add New </button>
	</a>	
		
	@if(count($events_list))
		
	<table class="table">
			
		<tr>
			<th> Title </th>
			<th> Club </th>
			<th> Date </th>
			<th> Control </th>
	
		</tr>	
		
		@foreach($event_list as $event)
			
		<tr>		
			<td> {{ $event->title }} </td>
			<td> {{ $event->getClubName->name}} </td>
			<td> {{ \Carbon\Carbon::parse($event->date)->format('M d Y H:m')}} </td>
			<td> 
				<button class="btn btn-primary"> Change Event </button>
				<button class="btn btn-danger"> Delete Event </button>
			</td>
	
		</tr>

		@endforeach


	</table>	
	
	@else 

	<h4> Nothing To Show </h4>

	@endif

</div>


@endsection