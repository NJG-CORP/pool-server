@extends('admin_panel.base')

@section('content')

<div class="card-body">
	<h2 class="card-description">{{ __('Rating Details') }}</h2>
  <hr>
<div class="card-body">
  @if(count($all_rate))
  {{ $all_rate->links()}}
<table class="table table-striped table-bordered">
  <thead>
<tr>
	<th>Full Name</th>

	<th>Type of review</th>
	<th>On whom</th>
	<th>Evaluation</th>
	
	<th>Feedback</th>
  <th>Date of created</th>
	<th class="action-column">Content</th></tr>
</thead>

<tbody>

  @foreach($all_rate as $rate)

   <tr id="part{{$rate->id}}">
   	<td>
       @if(empty($rate->rater['name'])&& empty($rate->rater['surname'])) 
   		 <label style="color: red">not set</label>
            @endif
   		{{$rate->rater['name']}} {{$rate->rater['surname']}}</td>
   	
   	<td>
     @if(empty($rate->rateable_type)) 
   		<label style="color: red">not set</label>
            @endif
    @php
        
        $type=ltrim($rate->rateable_type,"'App\Models\'");
     @endphp
   		{{$type}}</td>
   	<td>

     @if(empty($rate->rateable['name'])&& empty($rate->rateable['surname'])) 
   		<label style="color: red">not set</label>
            @endif
   		{{$rate->rateable['name']}} {{$rate->rateable['surname']}}</td>
   	
   	<td> @if(empty($rate->score)) 
   		<label style="color: red">not set</label>
            @endif
            {{$rate->score}}
   	</td>
    <td> @if(empty($rate->comment)) 
      <label style="color: red">not set</label>
            @endif
            {{$rate->comment}}
    </td>
<td>

{{ \Carbon\Carbon::parse($rate->created_at)->format('Y-d-m')}}


	</td>
<td>
	

	<button type="button" class="btn btn-primary edit" data-toggle="modal" data-target="#myModal" data-id="{{$rate->id}}">Change review</button>
	<button type="button" class="btn btn-primary delete" data-id="{{$rate->id}}">Delete review</button>
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
  
   $.get("/admin/rating/edit/"+id,function(data){
     
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
   $(document).on('click','.delete' ,function(){
    if(confirm("Are you sure you want to delete.")){
      var id=$(this).data('id');
      console.log(id);
    $.post("/admin/rating/delete",{
      'id':id,
     '_token':'{{csrf_token()}}'
    },function(){
    $('#part'+id).remove();
  });
    }
   });
   

</script>
@endsection