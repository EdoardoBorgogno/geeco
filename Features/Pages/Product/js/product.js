/* document ready */
$(document).ready(function() {

    $('.selectable').click(function() {
        var src = $(this).find('img').attr('src');
        
        $('#img-div').css('background', 'url(' + src + ') center center no-repeat');
        $('#img-div').css('background-size', 'contain');
    });

});