@extends('admin_panel.base')

@section('content')


<div class="events">

	<h2>List Of Events </h2>
	<hr>
	<a href="{{ route('get:event:formAdd')}}">
		<button class="btn btn-success"> Add New </button>
	</a>	
		

	@if(count($events_list_data))
	
	<div class="clearfix"></div>
	<div>
	{{ $events_list_data->links()}}	
	</div>

	<table class="table">
			
		<tr>
			<th> Title </th>
			<th> Club </th>
			<th> Date </th>
			<th> Control </th>
	
		</tr>	
		
		@foreach($events_list_data as $event)
			
		<tr id="ev{{$event->id}}">		
			<td> {{ $event->title }} </td>
			<td> {{ $event->club->name}} </td>
			<td> {{ \Carbon\Carbon::parse($event->date)->format('M d Y H:m')}} </td>
			<td> 
				<a href="{{ route('get:event:formEdit',['id'=>$event->id])}}">
					<button class="btn btn-primary"> Change Event </button>
				</a>	
				<button class="btn btn-danger rmEvent" type="button"  data-id="{{$event->id}}"> Delete Event </button>
			</td>
	
		</tr>

		@endforeach


	</table>	

	{{ $events_list_data->links()}}
	
	@else 

	<h4> Nothing To Show </h4>

	@endif

</div>


@endsection




@section('js')
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>

	<script>
		$(document).ready(function(){

			

	$('.rmEvent').click(function(){

      if(confirm('Are You Sure You Want To Remove Event ?'))
      {

          $.ajax({

            type : 'POST',
            url  : '{{ route("post:remove:event")}}',
            data :{
              'id' : $(this).data('id'),
              '_token' : '{{csrf_token()}}'
            },
            success:function(id){
              $('#ev'+id).fadeOut();
            }


          })


      } 

  });




		});	


</script>

@endsection