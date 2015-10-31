$(function() {

    var speed = 300;
    var active = null;
    var _this;

    $('.accordion .trigger').on('click', function() {

        _this = $(this).parent('.accordion').find('.reveal');

        if ($(this).hasClass('active')) {
            $('.active').removeClass('active');
            _this.slideUp(speed);

            active = null;

        } else {
            if (active != null)
                active.slideUp(speed);

            $('.active').removeClass('active');
            _this.slideDown(speed);
            $(this).addClass('active');

            active = _this;
        }

        return false;

    });

    $('.accordion .trigger.inital-active').trigger('click');

});

function UpdateLoadingbar(NewValue) {
    $('#progress').css('width', NewValue+'%');
}

function UpdateUrl(NewUrl) {
    $('#current-url').text(NewUrl);
}