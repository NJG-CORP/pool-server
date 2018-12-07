@extends('admin_panel.base')

@section('content')


<div class="news">

	<h2>List Of News </h2>
	<hr>
	<a href="{{ route('get:news:formAdd')}}">
		<button class="btn btn-success"> Add New </button>
	</a>	
		

	@if(count($news_list_data))
	
	<div class="clearfix"></div>
	<div>
	{{ $news_list_data->links()}}	
	</div>

	<table class="table">
			
		<tr>
			<th> Title </th>
			
			<th> Date </th>
			<th> Control </th>
	
		</tr>	
		
		@foreach($news_list_data as $news)
			
		<tr id="ev{{$news->id}}">		
			<td> {{ $news->title }} </td>
			
			<td> {{ \Carbon\Carbon::parse($news->date)->format('M d Y H:m')}} </td>
			<td> 
				<a href="{{ route('get:news:formEdit',['id'=>$news->id])}}">
					<button class="btn btn-primary"> Change Event </button>
				</a>	
				<button class="btn btn-danger rmEvent" type="button"  data-id="{{$news->id}}"> Delete Event </button>
			</td>
	
		</tr>

		@endforeach


	</table>	

	{{ $news_list_data->links()}}
	
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
            url  : '{{ route("post:remove:news")}}',
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