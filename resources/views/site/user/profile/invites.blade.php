@extends('layouts.default')

@section('content')
    <main class="main" style="padding-bottom: 0">

        <section class="the_content_section">

            <div class="breadcrumbs inner_section">
                <p><a href="/">Главная</a> / <a href="{{ route('profile.index') }}">
                        Мой профиль
                    </a> / <span>Приглашения</span></p>
            </div>

            <div class="profile2_wrap clearfix">
                <div class="profile2_wrap_inner clearfix">
                    <div class="players_table_wrap">
                        <div class="inner_seфction">
                            <?php if(!empty($result)): ?>
                                <div class="players_table">
                                    <div class="players_table_head">
                                        <p>Игрок</p>
                                        <p>Рейтинг</p>
                                        <p>Вид игры</p>
                                    </div>

                                    <div class="players_table_content">
                                        @foreach ($result as $invite)
                                            @include('site.user.profile._inviteRow', ['invite' => $invite])
                                        @endforeach
                                    </div>
                                </div>
                            <?php else: ?>
                                <p>У вас пока нет приглашений</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection