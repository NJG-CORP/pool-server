

	
	
<div class="card-body">
     <form action="{{route('post:rating:update')}}" method="POST" id="updated"class="forms-sample">
      {{csrf_field()}}
      <input type="hidden" name="item" value="{{$edit_data->id}}">
  <div class="form-group">
      <label for="name">Оценка</label>
      <input type="number" class="form-control" id="name" name="score" max="5" min="1" value="{{$edit_data->score}}">
    
  </div>
  <div class="form-group">
      <label for="comment">Отзыв</label>
    <input type="text" class="form-control" id="comment" name="comment" placeholder="Enter Feedback" value="{{$edit_data->comment}}">
    
  </div>
  
  
  
  
 
  <button type="Submit" class="btn btn-success" class="update">Change</button>


     </form>
</div>
