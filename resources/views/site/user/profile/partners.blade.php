@extends('layouts.default')

@section('content')

    <main class="main" style="padding-bottom: 0">

        <section class="the_content_section">

            <div class="breadcrumbs inner_section">
                <p><a href="/">Главная</a> / <a href="{{ route('profile.index') }}">
                        Мой профиль
                    </a> / <span>Мои Партнеры</span></p>
            </div>

            <div class="profile2_wrap clearfix">
                <div class="profile2_wrap_inner clearfix">
                    <div class="players_table_wrap">
                        <div class="inner_section">
                            <?php if(!empty($partners)) { ?>
                            <div class="players_table">
                                <div class="players_table_head">
                                    <p>Игрок</p>
                                    <p>Рейтинг</p>
                                    <p>Вид игры</p>
                                </div>

                                <div class="players_table_content">
                                    <?php
                                    foreach ($partners as $partner) {
                                        echo $this->renderFile("@frontend/views/players/partner.php", [
                                            'partner' => $partner
                                        ]);
                                    }
                                    ?>
                                </div>
                            </div>

                            <?php
                            } else {
                                echo "<p>У вас пока нет партнеров</p>";
                            }
                            /*
                             * <!--/players_table-->
                            */
                            ?>
                        </div>
                    </div>
                </div>
            </div>

        </section>

    </main>

@endsection