@extends('admin_panel.base')

@section('content')

    <h2> Add New Blog </h2>
    <hr>


    <form action="{{route('post:save:blog')}}" method="post" enctype="multipart/form-data" id="frmCreateBlog">

        {{ csrf_field()}}

        <div class="form-group field-addeventform-title required">
            <label class="control-label" for="addeventform-title">Заголовок</label>
            <input type="text" id="addeventform-title" class="form-control" name="title" aria-required="true">
        </div>

        <div class="form-group field-addeventform-title required">
            <label class="control-label" for="addeventform-name">Название</label>
            <input type="text" id="addeventform-name" class="form-control" name="name">
        </div>

        <div class="form-group field-addeventform-url required">
            <label class="control-label" for="addeventform-url">Url</label>
            <input type="text" id="addeventform-url" class="form-control" name="url" aria-required="true">


        </div>
        <div class="form-group field-addeventform-description">
            <label class="control-label" for="addeventform-description">Description</label>
            <textarea id="addeventform-description" class="form-control" name="description" rows="6"></textarea>


        </div>


        <div class="form-group field-addeventform-mainimg">
            <label class="control-label" for="addeventform-mainimg">Main image</label>
            <input type="hidden" name="mainImg" value=""><input type="file" id="addeventform-mainimg" name="mainImg">


        </div>
        <div class="form-group field-addeventform-images">
            <label class="control-label" for="addeventform-images">Add. </font><font style="vertical-align: inherit;">Images</label>
            <input type="file" id="addeventform-images" multiple="" name="images[]">


        </div>

        <div class="form-group field-addeventform-url required">
            <label class="control-label" for="addeventform-url">Gallery Title</label>
            <input type="text" id="addeventform-url" class="form-control" name="gallery_title" aria-required="true">


        </div>


        <div class="form-group field-addeventform-images">
            <label class="control-label" for="addeventform-images">Gallery Images</font></label>

            <input type="file" id="addeventform-images" multiple="" name="gallery_images[]">


        </div>


        <div class="form-group field-addeventform-paragraph">
            <label class="control-label" for="addeventform-paragraph">Paragraph</label>
            <textarea name="paragraph" id="" cols="30" rows="10" class="textarea"></textarea>


        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-success" name="login-button">
                Create
            </button>
            <a href="{{ route('get:all:blogs')}}">
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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
          integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">


@endsection


@section('js')

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>

    <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script src="/vendor/unisharp/laravel-ckeditor/adapters/jquery.js"></script>
    <script src="/js/timepicker.js"></script>
    <script type="text/javascript"
            src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function () {


            $('.textarea').ckeditor();


            $("#frmCreateBlog").validate({

                rules: {

                    title: {
                        required: true,
                    },
                    description: {
                        required: true,
                    },

                    url: {
                        required: true,
                    },
                    paragraph: {
                        maxlength: 200,
                        minlength: 200,
                        required: true
                    },

                },
                messages: {
                    title: 'Title Is Required',
                    date: 'Date can not be empty',
                    time: 'Time can not be empty',
                    url: 'You Must Provide URL',

                },
                errorPlacement: function (error, element) {


                    error.insertAfter(element);


                }
            });
        })

    </script>



@endsection


