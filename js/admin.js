function adminimg() {
    var elem = $('.aux_images_new .aux-image').clone();
    $(document).on('click', '.newimg a', function(event){
        event.preventDefault();
        var newel = elem.clone();
        $('#imgcontrols').before(newel);
    })
    $(document).on('click', '.delimg a', function(event){
        event.preventDefault();
        $(this).parents('.aux-image').remove();
    })
}
function editorder(razdel) {
    editorder.razdel = '&razdel='+razdel;
    $.ajaxSetup({
        url: 'saveorder',
        type: 'POST',
        //processData: false,
        complete: function(data){
            $('.rightside').prepend('<div class="status-message">Операция выполненна успешно, порядок сохранен</div>');
            $(".status-message").oneTime("5s", function() {
                $(this).fadeOut(2500, function(){ $(this).remove();});
            });
        }
    });
    $(document).on('click', 'a.editorder', function(event){
        event.preventDefault();
        $('#adminpage .order input').removeAttr('disabled');
        $(this).replaceWith('<a class="saveorder" href=#save-order>Сохранить</a>');
    });
    $(document).on('click', 'a.saveorder', function(event){
        event.preventDefault();
        tosend = $('#order').serialize() + editorder.razdel;
        $.ajax({
            data: tosend
        });
        $('#adminpage .order input').attr('disabled', 'disabled');
        $(this).replaceWith('<a a class="editorder" href=#edit-order>Изменить порядок</a>');
    });
}