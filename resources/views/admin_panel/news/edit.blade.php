@extends('admin_panel.base')

@section('content')

<h2> Edit Blog</h2>
<hr>


<form action="{{route('post:save:news')}}" method="post" enctype="multipart/form-data" id="frmCreateBlog">

{{ csrf_field()}}
<input type="hidden" name="news_id" value="{{$news->id}}" readonly>
<div class="form-group field-addeventform-title required">
<label class="control-label" for="addeventform-title">Title</label>
<input type="text" id="addeventform-title" class="form-control" name="title" aria-required="true" value="{{ $news->title }}">



</div>

<div class="form-group field-addeventform-url required">
<label class="control-label" for="addeventform-url">Url</label>
<input type="text" id="addeventform-url" class="form-control" name="url" aria-required="true" value="{{ $news->url }}">


</div>
<div class="form-group field-addeventform-description">
<label class="control-label" for="addeventform-description">Description</label>
<textarea id="addeventform-description" class="form-control" name="description" rows="6">{{ $news->description }}</textarea>


</div>


<div class="form-group field-addeventform-mainimg">
<label class="control-label" for="addeventform-mainimg">Main image</label>
<input type="hidden" name="mainImg" value=""><input type="file" id="addeventform-mainimg" name="mainImg">
@if(count($news->getMainImageEvent))
    
    
  
    <div class="img" id="img{{$news->getMainImageEvent->id}}">
    <button type="button" class="rmImg btn btn-danger btn-small" data-id="{{$news->getMainImageEvent->id}}"> Remove</button>  
    <img src="/assets/images/{{$news->getMainImageEvent->path}}" style="width:100px">
    
    </div>

  @endif
  <div class="clearfix"></div>

</div>
<div class="form-group field-addeventform-images">
<label class="control-label" for="addeventform-images">Add. </font><font style="vertical-align: inherit;">Images</label>
<input type="file" id="addeventform-images" name="images[]" multiple="">

@if(count($additional_images))
    
    @foreach($additional_images as $more_img)
    <div class="img" id="img{{$more_img->id}}">
    <button type="button" class="rmImg btn btn-danger btn-small" data-id="{{$more_img->id}}"> Remove</button>  
    <img src="/assets/images/{{$more_img->path}}" style="width:100px">
    
    </div>
    @endforeach

@endif

<div class="clearfix"></div>

</div>


<div class="form-group field-addeventform-url required">
<label class="control-label" for="addeventform-url">Gallery Title</label>
<input type="text" id="addeventform-url" class="form-control" name="gallery_title" aria-required="true" value="{{$news->gallery_title}}">


</div>


<div class="form-group field-addeventform-images">
<label class="control-label" for="addeventform-images">Gallery Images</font></label>

<input type="file" id="addeventform-images"  multiple="" name="gallery_images[]">

@if(count($gallery_images))
    
    @foreach($gallery_images as $more_img)
    <div class="img" id="img{{$more_img->id}}">
    <button type="button" class="rmImg btn btn-danger btn-small" data-id="{{$more_img->id}}"> Remove</button>  
    <img src="/assets/images/{{$more_img->path}}" style="width:100px">
    
    </div>
    @endforeach

@endif

<div class="clearfix"></div>



</div>



<div class="form-group field-addeventform-paragraph">
<label class="control-label" for="addeventform-paragraph">Paragraph</label>
<textarea name="paragraph" id="" cols="30" rows="10" class="textarea">{{ $news->paragraph }}</textarea>


</div>
    <div class="form-group">
        <button type="submit" class="btn btn-success" name="login-button">
        Update
    </button> 
    <a href="{{ route('get:all:news')}}">
      <button type="button" class="btn btn-danger">
        Cancel
    </button>    
</a>
</div>

</form>


@endsection




@section('css')
    
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    
    <link rel="stylesheet" href="/css/timepicker.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">


@endsection


@section('js')

  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
   <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script src="/vendor/unisharp/laravel-ckeditor/adapters/jquery.js"></script>
    <script src="/js/timepicker.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script> 
  <script>
    $(document).ready(function(){
      

      $('.textarea').ckeditor();

      
      

   $("#frmCreateBlog").validate({
        
        rules: {
          
          date: {
            required: true,
          },
          time: {
            required: true,
          },
          url: {
            required: true,
          },
          paragraph:{
            maxlength : 200,
            minlength : 200,
            required : true
          },
          
        },
        messages:{
          title : 'Title Is Required',
          date : 'Date can not be empty',
          time : 'Time can not be empty',
          url : 'You Must Provide URL',

        },
        errorPlacement: function(error, element) {
        
              
                error.insertAfter(element);
              
           
        }
      });
    })  

  $('.rmImg').click(function(){

      if(confirm('Are You Sure You Want To Remove ?'))
      {

          $.ajax({

            type : 'POST',
            url  : '{{ route("post:remove:news_image")}}',
            data :{
              'id' : $(this).data('id'),
              '_token' : '{{csrf_token()}}'
            },
            success:function(id){
              $('#img'+id).fadeOut();
            }


          })


      } 

  });

  </script> 



@endsection


