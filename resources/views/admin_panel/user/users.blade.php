@extends('admin_panel.base')

@section('content')

<div class="card-body">
	<h4 class="card-description">{{ __('User Details') }}</h4>
<div class="card-body">
<table class="table table-striped table-bordered"><thead>
<tr>
	<th>Name</th>
	<th>Surname</th>
	<th>Age</th>
	<th>Email</th>
	<th>Phone number</th>
	
	<th>Changed</th>
	<th class="action-column">Content</th></tr>
</thead>

<tbody>

  @foreach($all_users as $u)

   <tr id="part{{$u->id}}">
   	<td>
       @if(empty($u->name)) 
   		 <label style="color: red">not set</label>
            @endif
   		{{$u->name}}</td>
   	<td>
       @if(empty($u->surname)) 
   		<label style="color: red">not set</label>
            @endif
   		{{$u->surname}}</td>
   	<td>
     @if(empty($u->age)) 
   		<label style="color: red">not set</label>
            @endif

   		{{$u->age}}</td>
   	<td>

     @if(empty($u->email)) 
   		<label style="color: red">not set</label>
            @endif
   		{{$u->email}}</td>
   	
   	<td> @if(empty($u->phone)) 
   		<label style="color: red">not set</label>
            @endif
            {{$u->phone}}
   	</td>
<td>

{{ \Carbon\Carbon::parse($u->updated_at)->format('M d Y, H:m')}}


	</td>
<td>
	

	<button type="button" class="btn btn-primary edit" data-toggle="modal" data-target="#myModal" data-id="{{$u->id}}">Edit user</button>
	<button type="button" class="btn btn-primary delete" data-id="{{$u->id}}">Delete user</button>
</td>
   </tr>


  @endforeach	
</tbody>
</table>
</div></div>


<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit detais</h4>
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
  
   $.get("/admin/users/edit/"+id,function(data){
     
     $('.modal-body').html(data);
     $("#updated").validate({

       rules: {
         item: {
           required: true,
         },
         name: {
           required: true,
         },
         surname: {
           required: true,
         },
         age: {
           required: true,
         },
         mail:{
          
           required : true
         },

       },
       messages:{
           item : 'Something going wrong.',
           name : 'Name can not be empty',
           surname : 'Surname can not be empty',
           age : 'Age can not empty',
           mail:'You Must Provide Email'
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
    $.post("/admin/users/delete",{
      'id':id,
     '_token':'{{csrf_token()}}'
    },function(){
    $('#part'+id).remove();
  });
    }
   });
   

</script>
@endsection