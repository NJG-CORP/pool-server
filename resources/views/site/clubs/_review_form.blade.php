<div class="the_form write_review_form three_col_form bg_eeeff3">
    <form method="post" class="frm3" id="frm33" action="/rate/club/{{$club->id}}">

        {{csrf_field()}}
        <p class="form_title">Написать отзыв о клубе</p>

        <div class="the_form_col the_form_col_third the_form_col_textarea">
            <div class="the_form_div the_form_div_textarea">
                <textarea name="review_text" placeholder="Текст отзыва"></textarea>
            </div>
        </div>

        <div class="the_form_col the_form_col_third">
            <!--
                                                <div class="the_form_div">
                                                    <input type="text" name="place" placeholder="Ваше имя">
                                                </div>

                                                <div class="the_form_div">
                                                    <input type="text" name="mail" placeholder="E-mail">
                                                </div>
            -->
            <div class="the_form_div rating">
                <label class="control-label">Рейтинг клуба</label>
                <div class="stars stars_big dynamic_stars">
                    <div>
                        <div><span class=""></span></div>
                    </div>
                </div>
                <input type="hidden" name="rating" class="ratings">
            </div>
        </div>

        <div class="the_form_col the_form_col_third the_form_col_submit">
            <div class="the_form_div the_form_div_submit">
                <input type="submit" name="submit1" value="Отправить отзыв">

                <p class="note">Ваш отзыв будет добавлен<br/>сразу после модерации<br/>администратором сайта</p>
            </div>
        </div>

    </form>
</div><!--/write_review_form-->