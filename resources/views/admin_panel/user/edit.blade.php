

	
	
<div class="card-body">
     <form action="{{route('post:users:update')}}" method="POST" id="updated"class="forms-sample">
      {{csrf_field()}}
      <input type="hidden" name="item" value="{{$edit_data->id}}">
  <div class="form-group">
    <label for="name">Name</label>
    <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="{{$edit_data->name}}">
    
  </div>
  <div class="form-group">
    <label for="surname">Surname</label>
    <input type="text" class="form-control" id="surname" name="surname" placeholder="Enter Surname" value="{{$edit_data->surname}}">
    
  </div>
  <div class="form-group">
    <label for="age">Age</label>
    <input type="number" class="form-control" id="age" name="age" placeholder="Enter Age" value="{{$edit_data->age}}">
    
  </div>
  <div class="form-group">
    <label for="mail">Email address</label>
    <input type="email" class="form-control" id="mail" name="mail" aria-describedby="emailHelp" placeholder="Enter email" value="{{$edit_data->email}}">
    
  </div>
  <div class="form-group">
    <label for="mob">Phone No.</label>
    <input type="number" class="form-control" id="mob" name="mob" placeholder="Enter phone number" value="{{$edit_data->phone}}">
    
  </div>
  
 
  <button type="Submit" class="btn btn-success" class="update">Change</button>


     </form>
</div>
