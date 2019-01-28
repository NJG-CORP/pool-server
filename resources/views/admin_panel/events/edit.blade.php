@extends('admin_panel.base')

@section('content')

    <h2> Add New Event </h2>
    <hr>


    <form action="{{route('post:save:event')}}" method="post" enctype="multipart/form-data" id="frmCreateEvent">

        {{ csrf_field()}}
        <div id="tabs">
            <ul>
                <li><a href="#tabs-1">Основное</a></li>
                <li><a href="#tabs-2">SEO</a></li>
            </ul>
            <div id="tabs-1">
                <div class="form-group field-addeventform-title required">
                    <label class="control-label" for="addeventform-title">Title</label>
                    <input type="text" id="addeventform-title" class="form-control" name="title" aria-required="true"
                           value="{{ $event->title }}">
                </div>


                <div class="form-group field-addeventform-description">
                    <label class="control-label" for="addeventform-description">Description</label>
                    <textarea id="addeventform-description" class="form-control" name="description"
                              rows="6">{{ $event->description }}</textarea>
                </div>

                <div class="form-group field-addeventform-clubid required">
                    <label class="control-label" for="addeventform-clubid">Club</label>
                    <select id="addeventform-clubid" class="form-control" name="club_id">
                        @foreach($clubs as $club)
                            <option value="{{$club->id}}"
                                    @if($club->id==$event->club_id) selected="selected" @endif> {{ $club->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group field-addeventform-date required">
                    <label class="control-label" for="addeventform-date">date</label>
                    <div class="input-group date">
                    <span class="input-group-addon">
                        <i class="far fa-calendar-alt"></i>
                    </span>
                        <input type="text" id="addeventform-date" class="form-control datepicker" name="date"
                               value="{{ \Carbon\Carbon::parse($event->date)->format('Y-m-d')}}">
                    </div>
                </div>
                <div class="form-group field-addeventform-time required">
                    <label class="control-label" for="addeventform-time">Time</label>
                    <div class="input-group time">
                        <span class="input-group-addon">
                            <i class="far fa-clock"></i>
                        </span>
                        <input type="text" id="addeventform-time" class="form-control timepicker" name="time"
                               value="{{ \Carbon\Carbon::parse($event->date)->format('H:m:s')}}">
                    </div>


                </div>

                <div class="form-group field-addeventform-mainimg">
                    <label class="control-label" for="addeventform-mainimg">Main image</label>
                    <input type="hidden" name="mainImg" value=""><input type="file" id="addeventform-mainimg"
                                                                        name="mainImg">
                    @if(count($event->getMainImageEvent))



                        <div class="img" id="img{{$event->getMainImageEvent->id}}">
                            <button type="button" class="rmImg btn btn-danger btn-small"
                                    data-id="{{$event->getMainImageEvent->id}}"> Remove
                            </button>
                            <img src="/assets/images/{{$event->getMainImageEvent->path}}" style="width:100px">

                        </div>

                    @endif
                    <div class="clearfix"></div>
                </div>

                <div class="form-group field-addeventform-images">
                    <label class="control-label" for="addeventform-images">Add. </font><font
                                style="vertical-align: inherit;">Images</label>
                    <input type="file" id="addeventform-images" name="images[]" multiple="">

                    @if(count($additional_images))
                        @foreach($additional_images as $more_img)
                            <div class="img" id="img{{$more_img->id}}">
                                <button type="button" class="rmImg btn btn-danger btn-small"
                                        data-id="{{$more_img->id}}">
                                    Remove
                                </button>
                                <img src="/assets/images/{{$more_img->path}}" style="width:100px">
                            </div>
                        @endforeach
                    @endif
                    <div class="clearfix"></div>
                </div>

                <div class="form-group field-addeventform-paragraph">
                    <label class="control-label" for="addeventform-paragraph">Paragraph</label>
                    <textarea name="paragraph" id="" cols="30" rows="10"
                              class="textarea validate">{{ $event->paragraph }}</textarea>
                </div>

            </div>
            <div id="tabs-2">
                <div class="form-group field-addeventform-name required">
                    <label class="control-label" for="addeventform-name">Название</label>
                    <input type="text" id="addeventform-name" class="form-control" name="name"
                           value="{{ $event->name }}">
                </div>

                <div class="form-group field-addeventform-url required">
                    <label class="control-label" for="addeventform-url">Url</label>
                    <input type="text" id="addeventform-url" class="form-control" name="url" aria-required="true"
                           value="{{ $event->url }}">
                </div>
            </div>
        </div>
        <input type="hidden" name="event_id" value="{{$event->id}}" readonly>

        <div class="form-group">
            <button type="submit" class="btn btn-success" name="login-button">
                Update
            </button>
            <a href="{{ route('get:all:events')}}">
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
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script src="/vendor/unisharp/laravel-ckeditor/adapters/jquery.js"></script>
    <script src="/js/timepicker.js"></script>
    <script type="text/javascript"
            src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function () {

            $("#tabs").tabs();
            $('.datepicker').datepicker({"dateFormat": "yy-mm-dd", "showSecond": false, "showTime": false});

            $('.textarea').ckeditor();


            $('.timepicker').timepicker({"timeFormat": "HH:mm:ss", "showSecond": false});

            $("#frmCreateEvent").validate({
                ignore: [],
                rules: {
                    description: {
                        required: true,
                    },
                    date: {
                        required: true,
                    },
                    time: {
                        required: true,
                    },
                    url: {
                        required: true,
                    },

                },
                messages: {
                    title: 'Title Is Required',
                    date: 'Date can not be empty',
                    time: 'Time can not be empty',
                    url: 'You Must Provide URL',

                },
                errorPlacement: function (error, element) {

                    if (element.attr("name") == "date" || element.attr("name") == "time") {
                        error.appendTo(element.parent().parent("div"));
                    }

                    else {
                        error.insertAfter(element);
                    }

                }
            });
        });

        $('.rmImg').click(function () {

            if (confirm('Are You Sure You Want To Remove ?')) {

                $.ajax({

                    type: 'POST',
                    url: '{{ route("post:remove:event_image")}}',
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

    </script>



@endsection


