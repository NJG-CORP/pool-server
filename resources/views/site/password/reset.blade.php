@extends('layouts.default')

@section('content')
    <main class="main inner_page_main activities_inner_page_main" style="margin-top: 100px">
        <h2>Восстановление пароля</h2>
        <form action="{{route('password.reset.post')}}" method="post">
            {{csrf_field()}}
            Token <br>
            <input type="text" name="token" value="{{$token}}"><br>
            Password <br>
            <input type="password" name="password"><br>
            <button type="submit">Восстановить пароль</button>
        </form>
    </main>
@endsection