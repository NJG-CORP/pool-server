<div id="UpdateClubForm" data-pjax-container="" data-pjax-timeout="1000">
    <div class="the_form write_review_form three_col_form bg_eeeff3">
        <form id="club-review-form" class="frm3" action="/rating/add-club-review" method="post"
              data-pjax>
            <input type="hidden" name="_csrf-frontend"
                   value="i9GkvJv82eMyZvxnrUjlzrR0gVANkFhAqm5fd7tjQYjfttOIyqif13gOlQLYI52Ngx62BEejCQ6HNxofjzwT2Q==">
            <p class="form_title">Написать отзыв о клубе</p>
            <div class="form-group field-clubreviewform-clubid required">
                <input type="hidden" id="clubreviewform-clubid" class="form-control"
                       name="ClubReviewForm[clubId]" value="1">
                <div class="help-block"></div>
            </div>
            <div class="form-group field-clubreviewform-ip required">
                <input type="hidden" id="clubreviewform-ip" class="form-control"
                       name="ClubReviewForm[ip]" value="141.136.79.1">
                <div class="help-block"></div>
            </div>
            <div class="the_form_col the_form_col_third">
                <div class="the_form_div">
                    <div class="form-group field-clubreviewform-name required">
                        <input type="text" id="clubreviewform-name" class="form-control"
                               name="ClubReviewForm[name]" placeholder="Имя" aria-required="true">
                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="the_form_div">
                    <div class="form-group field-clubreviewform-mail required">
                        <input type="text" id="clubreviewform-mail" class="form-control"
                               name="ClubReviewForm[mail]" placeholder="E-mail" aria-required="true">
                        <div class="help-block"></div>
                    </div>
                </div>
            </div>
            <div class="the_form_col the_form_col_third the_form_col_textarea">
                <div class="the_form_div the_form_div_textarea">
                    <div class="form-group field-clubreviewform-reviewtext required">
                                                                                <textarea id="clubreviewform-reviewtext"
                                                                                          class="form-control"
                                                                                          name="ClubReviewForm[reviewText]"
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