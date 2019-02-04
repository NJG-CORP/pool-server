<div id="UpdateClubForm" data-pjax-container="" data-pjax-timeout="1000">
    <div class="the_form write_review_form three_col_form bg_eeeff3">
        <form id="club-review-form" class="frm3" action="/rate/club/{{ $club_id }}" method="post"
              data-pjax>
            {{ csrf_field() }}
            <p class="form_title">Написать отзыв о клубе</p>
            <div class="the_form_col the_form_col_third">
                <div class="the_form_div">
                    <div class="form-group field-clubreviewform-name required">
                        <input type="text" id="clubreviewform-name" class="form-control"
                               name="name" placeholder="Имя" aria-required="true">
                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="the_form_div">
                    <div class="form-group field-clubreviewform-mail required">
                        <input type="text" id="clubreviewform-mail" class="form-control"
                               name="mail" placeholder="E-mail" aria-required="true">
                        <div class="help-block"></div>
                    </div>
                </div>
            </div>
            <div class="the_form_col the_form_col_third the_form_col_textarea">
                <div class="the_form_div the_form_div_textarea">
                    <div class="form-group field-clubreviewform-reviewtext required">
                                                                                <textarea id="clubreviewform-reviewtext"
                                                                                          class="form-control"
                                                                                          name="reviewText"
                                                                                          placeholder="Текст отзыва"
                                                                                          aria-required="true"></textarea>
                        <div class="help-block"></div>
                    </div>
                </div>
            </div>
            <div class="the_form_col the_form_col_third the_form_col_submit">
                <div class="the_form_div the_form_div_submit">
                    <input type="submit" name="submit1" value="Отправить отзыв">
                    <p class="note">Ваш отзыв будет добавлен<br/>сразу после модерации<br/>администратором
                        сайта
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>