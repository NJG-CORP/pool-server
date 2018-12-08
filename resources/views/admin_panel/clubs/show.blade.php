@extends('admin_panel.base')

@section('content')

<div class="card-body">
	<h2 class="card-description">{{ __('Clubs Details') }}</h2>
  <hr>
<div class="card-body">

  <a href="{{route('get:club:create')}}"><button type="button" class="btn btn-primary" >Add</button></a>
  @if(count($all_club))
  {{ $all_club->links()}}
  
<table class="table table-striped table-bordered">
  <thead>
<tr>
	<th>Title</th>

	<th>Working time</th>
	<th>Tables</th>
	<th>Kitchen</th>
	
	<th>Location</th>
  <th>Phone</th>
  <th>Changed</th>
	<th class="action-column">Content</th></tr>
</thead>

<tbody>

  @foreach($all_club as $club)

   <tr id="part{{$club->id}}">
   	<td>
       @if(empty($club->name)) 
   		 <label style="color: red">not set</label>
            @endif
   		{{$club->name}} </td>
   	
   	<td>
     @if(empty($club->worktime_id)) 
   		<label style="color: red">not set</label>
            @endif
    
   		{{$club->worktime_id}}</td>
   	<td>

     @if(empty($club->gametype_id)) 
   		<label style="color: red">not set</label>
            @endif
   		{{$club->gametype_id}} </td>
   	
   	<td> @if(empty($club->kitchens_id)) 
   		<label style="color: red">not set</label>
            @endif
            {{$club->kitchens_id}}
   	</td>
    <td> @if(empty($club->location_id)) 
      <label style="color: red">not set</label>
            @endif
            {{$club->location_id}}
    </td>
    <td> @if(empty($club->phone)) 
      <label style="color: red">not set</label>
            @endif
            {{$club->phone}}
    </td>
<td>

{{ \Carbon\Carbon::parse($club->updated_at)->format('M d Y, H:m')}}


	</td>
<td>
	
{{--
	<button type="button" class="btn btn-primary edit" data-toggle="modal" data-target="#myModal" data-id="{{$club->id}}">Change event</button>--}}
  <a href="{{route('get:club:edit',['id'=>$club->id])}}"><button type="button" class="btn btn-primary" >Change event</button></a>
	<button type="button" class="btn btn-primary delete" data-id="{{$club->id}}">Delete review</button>

</td>
   </tr>


  @endforeach	
</tbody>
</table>
@else
<h4>Nothing To Show</h4>
@endif
</div></div>


<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Change event</h4>
      </div>
      <div class="modal-body">
       	
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


@endsection
@section('js')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
<script>
  
  
	 $(document).on('click','.edit',function(){


    
  var id=$(this).data('id');
  
   $.get("/admin/clubs/edit/"+id,function(data){
     
     $('.modal-body').html(data);
     $("#updated").validate({

       rules: {
         item: {
           required: true,
         },
         comment: {
           required: true,
         },
        

       },
       messages:{
           item : 'Something going wrong.',
           comment : 'Feedback can not be empty',
           
       },
       errorPlacement: function(error, element) {

              
                   error.insertAfter(element);
               

       }
     });
   });


  

});
   $(document).on('click','#new',function(){


    
  
  
   $.get("/admin/clubs/create",function(data){
     
     $('.modal-body').html(data);

     //$('.worktime').timepicker({"dateFormat":"H:i","showSecond":false});
     $('.worktime').timepicker({"timeFormat":"HH:mm:ss","showSecond":false});
     $("#updated").validate({

       rules: {
         item: {
           required: true,
         },
         comment: {
           required: true,
         },
        

       },
       messages:{
           item : 'Something going wrong.',
           comment : 'Feedback can not be empty',
           
       },
       errorPlacement: function(error, element) {

              
                   error.insertAfter(element);
               

       }
     });
   });


  

});
   $(document).on('click','.delete' ,function(){
    if(confirm("Are you sure you want to delete.")){
      var id=$(this).data('id');
      console.log(id);
    $.post("/admin/clubs/delete",{
      'id':id,
     '_token':'{{csrf_token()}}'
    },function(){
    $('#part'+id).remove();
  });
    }
   });
   
$(document).ready(function(){
 $('.worktime').timepicker({"timeFormat":"HH:mm:ss","showSecond":false});
});
</script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
   <script src="/js/timepicker.js"></script>

@endsection
@section('css')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

        
        <link rel="stylesheet" href="/css/timepicker.css">
@endsection