/* document ready */
$(document).ready(function() {

    $('.selectable').click(function() {
        var src = $(this).find('img').attr('src');
        
        $('#img-div').css('background', 'url(' + src + ') center center no-repeat');
        $('#img-div').css('background-size', 'contain');
    });

    $('#add-to-cart').click(function() {

        var productId = $(this).attr('product');      
        var quantity = $('#quantity-input').val();
        var href = $(this).attr('href');
        
        if (quantity > 0 && !isNaN(quantity)) {

            var jwt = getCookie("UusJval");

            var request = $.ajax({
                url: href,
                type: 'POST',
                headers: {
                    "Authorization": jwt
                },
                data: {
                    productId: productId,
                    quantity: quantity,
                    listType: 'cart'
                }
            });

            request.done(function(data) {
                window.location.href = window.location.href;
            });

            request.fail(function(jqXHR, textStatus) {
                if (jqXHR.status == 409)
                    alert('Product is already in cart');
                else if (jqXHR.status == 401)
                {
                    $('body').css('position', 'relative');
                    var modal = $(`
                                    <div class="container">
                                        <div class="loginContent" id="loginPopup">
                                        <button class="close" id="close-loginPopup">✖</button>
                                        <img src="https://img.freepik.com/free-vector/sign-page-abstract-concept-illustration_335657-3875.jpg" alt="img" />
                                        <p>Please login to use all Geeco services.</p>
                                        <a class="btn btn-outline px-4" id="login-btn">Login!</a>
                                        </div>
                                    </div>
                                `);

                    $('body').append(modal);
                    $('#login-modal').modal('show');
                    $('body').css('pointer-events', 'none');
                    $('#loginPopup').css('pointer-events', 'auto');

                    $('html, body').animate({
                        scrollTop: $("#loginPopup").offset().top + - 80
                    }, 200);

                    $('body').css('overflow', 'hidden');

                    $('#loginPopup').css('z-index', '10');

                    //body darken effect
                    $('body').append('<div class="darken"></div>');
                    $('.darken').css('opacity', '0.5');
                    $('.darken').css('position', 'fixed');
                    $('.darken').css('top', '0');
                    $('.darken').css('left', '0');
                    $('.darken').css('width', '100%');
                    $('.darken').css('height', '100%');
                    $('.darken').css('background-color', 'black');
                    $('.darken').css('z-index', '5');

                    $('#close-loginPopup').click(function() {
                        $('#loginPopup').remove();
                        $('body').css('pointer-events', 'auto');
                        $('body').css('filter', 'brightness(100%)');
                        $('body').css('overflow', 'auto');
                        $('.darken').remove();
                    });

                    $('#login-btn').click(function() {
                        window.location.href = 'login';
                    });
                }
                else
                    alert('Sorry, something went wrong. Please try again later.');
            });
        }

    });

    $("#search-btn").click(function () {
        
        var searchValue = $("#search-input").val();

        if (searchValue != "") {
            //get only the first 10 words of the search input
            var searchValue = searchValue.split(" ").slice(0, 10).join(" ");

            window.location.href = "explore?q=" + searchValue;
        }

    });

    $("#search-input").keypress(function (e) {
        if (e.which == 13) {
            $("#search-btn").click();
        }
    });

    //
    // GENERAL FUNCTION
    //
    function getCookie(name) {
        var value = "; " + document.cookie;
        var parts = value.split("; " + name + "=");
        if (parts.length == 2) return parts.pop().split(";").shift();
    }

});