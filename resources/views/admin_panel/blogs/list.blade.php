@extends('admin_panel.base')

@section('content')


<div class="blogs">

	<h2>List Of Blogs </h2>
	<hr>
	<a href="{{ route('get:blog:formAdd')}}">
		<button class="btn btn-success"> Add New </button>
	</a>	
		

	@if(count($blogs_list_data))
	
	<div class="clearfix"></div>
	<div>
	{{ $blogs_list_data->links()}}	
	</div>

	<table class="table">
			
		<tr>
			<th> Title </th>
			
			<th> Date </th>
			<th> Control </th>
	
		</tr>	
		
		@foreach($blogs_list_data as $blog)
			
		<tr id="ev{{$blog->id}}">		
			<td> {{ $blog->title }} </td>
			
			<td> {{ \Carbon\Carbon::parse($blog->date)->format('M d Y H:m')}} </td>
			<td> 
				<a href="{{ route('get:blog:formEdit',['id'=>$blog->id])}}">
					<button class="btn btn-primary"> Change Blog </button>
				</a>	
				<button class="btn btn-danger rmBlog" type="button"  data-id="{{$blog->id}}"> Delete Blog </button>
			</td>
	
		</tr>

		@endforeach


	</table>	

	{{ $blogs_list_data->links()}}
	
	@else 

	<h4> Nothing To Show </h4>

	@endif

</div>


@endsection




@section('js')
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>

	<script>
		$(document).ready(function(){

			

	$('.rmBlog').click(function(){

      if(confirm('Are You Sure You Want To Remove Blog ?'))
      {

          $.ajax({

            type : 'POST',
            url  : '{{ route("post:remove:blog")}}',
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