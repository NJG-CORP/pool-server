@extends('admin_panel.base')

@section('content')

    <div class="card-body">
        <form action="{{route('post:club:update')}}" method="POST" id="updated" class="forms-sample"
              enctype="multipart/form-data">
            {{csrf_field()}}
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">Основное</a></li>
                    <li><a href="#tabs-2">Кухни, столы, время</a></li>
                    <li><a href="#tabs-3">SEO</a></li>
                </ul>
                <div id="tabs-1">
                    <input type="hidden" name="item" value="{{$data->id}}">
                    <div class="form-group">
                        <label for="title">Заголовок</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title"
                               value="{{$data->title}}" required>
                    </div>
                    <div class="form-group">
                        <label for="des">Description</label>
                        <textarea name="des" id="des" class="form-control" rows="6">{{$data->description}}</textarea>

                    </div>

                    <div class="form-group">
                        <label for="img">Picture</label>
                        @if(isset($data->images[0]))
                            <img src="/assets/images/{{$data->images[0]->path}}" height="100" width="100">
                        @endif
                        <input type="file" class="form-control" id="img" name="img" placeholder="Enter">

                    </div>
                    <div class="form-group field-addeventform-url required">
                        <label class="control-label" for="addeventform-url">Gallery Title</label>
                        <input type="text" id="addeventform-url" class="form-control" name="gallery_title"
                               aria-required="true"
                               value="{{$data->gallery_title}}">


                    </div>


                    <div class="form-group field-addeventform-images">
                        <label class="control-label" for="addeventform-images">Gallery Images</label>

                        <input type="file" id="addeventform-images" multiple="" name="gallery_images[]">

                        @if(count($gallery_images))

                            @foreach($gallery_images as $more_img)
                                <div class="img" id="img{{$more_img->id}}">
                                    <button type="button" class="rmImg btn btn-danger btn-small"
                                            data-id="{{$more_img->id}}">
                                        Remove
                                    </button>
                                    <img src="/assets/images/{{$more_img->path}}" style="width:100px">

                                </div>
                            @endforeach

                        @endif
                    </div>
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="number" class="form-control" id="location" name="location"
                               placeholder="Enter Location"
                               value="{{$data->location_id}}" required>

                    </div>

                    <div class="form-group">
                        <label for="mob">Phone No.</label>
                        <input type="text" class="form-control" id="mob" name="mob" placeholder="Enter phone number"
                               value="{{$data->phone}}" required>

                    </div>
                </div>
                <div id="tabs-2">
                    <label>Working hours</label>
                    @foreach($days as $day)

                        <div class="form-group">
                            <label>{{$day->name}}</label>
                            <div class="input-group time">
                                <span class="input-group-addon">
                                    <i class="far fa-clock"></i>
                                </span>
                                <input type="text" name="worktime[{{$day->id}}][from]" class="worktime form-control"
                                       @if(isset($data->getWorkTime->keyBy('weekday_id')[$day->id])) value="{{$data->getWorkTime->keyBy('weekday_id')[$day->id]->from}}" @endif>
                                <span class="input-group-addon">
                                    <i class="far fa-clock"></i>
                                </span>
                                <input type="text" name="worktime[{{$day->id}}][to]" class="worktime form-control"
                                       @if(isset($data->getWorkTime->keyBy('weekday_id')[$day->id])) value="{{$data->getWorkTime->keyBy('weekday_id')[$day->id]->to}}" @endif>
                            </div>
                        </div>

                        <div class="clearfix"></div>

                    @endforeach
                    <label>Number of tables</label>
                    <div class="form-group">
                        <label for="pool">Pool</label>
                        <input type="number" class="form-control" id="pool" name="pool" placeholder="Enter pool"
                               required
                               value="{{$data->gametype->pool}}">

                    </div>
                    <div class="form-group">
                        <label for="russian">Russian</label>
                        <input type="number" class="form-control" id="russian" name="russian"
                               placeholder="Enter Russian"
                               required value="{{$data->gametype->Russian}}">

                    </div>
                    <div class="form-group">
                        <label for="snooker">Snooker</label>
                        <input type="number" class="form-control" id="snooker" name="snooker"
                               placeholder="Enter Snooker"
                               required value="{{$data->gametype->Snooker}}">

                    </div>
                    <div class="form-group">
                        <label for="cannon">Cannon</label>
                        <input type="number" class="form-control" id="cannon" name="cannon" placeholder="Enter Cannon"
                               required
                               value="{{$data->gametype->Cannon}}">

                    </div>
                    <label>Kitchens</label>
                    <select name="kitchen[]" class="form-control" multiple="multiple">
                        @foreach($kitchens as $k)

                            <option value="{{$k->id}}"
                                    @if(in_array($k->id,$data->kitchens_id)) selected @endif >{{$k->name}}</option>
                        @endforeach

                    </select>
                </div>
                <div id="tabs-3">
                    <div class="form-group">
                        <label for="title">Название</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Название"
                               value="{{$data->name}}">
                    </div>

                    <div class="form-group">
                        <label for="title">Url</label>
                        <input type="text" class="form-control" id="url" name="url" placeholder="Enter Url"
                               value="{{$data->url}}"
                               required>
                    </div>
                </div>
            </div>

            <button type="Submit" class="btn btn-success update">Change</button>
            <a href="{{route('get:club:data')}}">
                <button type="button" class="btn btn-danger">Cancel</button>
            </a>

        </form>
    </div>
@endsection
@section('js')
    <script type="text/javascript"
            src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $("#updated").validate({

            rules: {
                title: {
                    required: true,
                },
                pool: {
                    required: true,
                },
                russian: {
                    required: true,
                },
                snooker: {
                    required: true,
                },
                cannon: {
                    required: true,
                },
                des: {
                    required: true,
                },
                mob: {
                    required: true,
                },
                location: {
                    required: true,
                },


            },
            messages: {
                title: 'Title can not empty.',
                pool: 'You must complete the "Game".',
                russian: 'You must complete the "Game".',
                snooker: 'You must complete the "Game".',
                cannon: 'You must complete the "Game".',
                des: 'Description can not empty.',
                mob: 'Phone number can not empty.',
                location: 'Location can not empty.',
            },
            errorPlacement: function (error, element) {


                error.insertAfter(element);


            }
        });

        $('.rmImg').click(function () {

            if (confirm('Are You Sure You Want To Remove ?')) {

                $.ajax({

                    type: 'POST',
                    url: '{{ route("post:remove:blog_image")}}',
                    data: {
                        'id': $(this).data('id'),
                        '_token': '{{csrf_token()}}'
                    },
                    success: function (id) {
                        $('#img' + id).fadeOut();
                    }


                })


            }

        });

        $(document).ready(function () {

            $("#tabs").tabs();
            $('.worktime').timepicker({"timeFormat": "HH:mm:ss", "showSecond": false});
        });
    </script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="/js/timepicker.js"></script>

@endsection
@section('css')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


    <link rel="stylesheet" href="/css/timepicker.css">
@endsection