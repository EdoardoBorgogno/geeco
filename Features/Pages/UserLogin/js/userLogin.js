$(document).ready(function () {
    
    // When document is ready, initialize the login page
    // If the user is already logged in, ask them to log out

    //loginBtn click
    $('#loginBtn').click(function() {

        //get href and reqpath
        var href = $(this).attr('href');
        var reqpath = $(this).attr('reqpath');

        //get username and password
        var email = $('#emailInput').val();
        var password = $('#passwordInput').val();
        
        //check if username and password are not empty
        if (email == '' || password == '') 
        {
            $('#errorMessageText').text('Please complete all fields');
            $('#errorMessageText').css('opacity', '1');
        }
        else
        {            
            //send login request to server
            var request = $.ajax({
                type: 'POST',
                contentType: 'application/x-www-form-urlencoded',
                url: reqpath,
                data: jQuery.param({ email: email, password: password })
            });

            request.done(function(data, textStatus, xhr) 
            {
                if (xhr.status == "200")
                {
                    setcookie('UusJvalUs', data.token, 365);

                    //check if login was successful
                    window.location.href = href;
                }
                else
                {
                    $('#errorMessageText').text(data.message);
                    $('#errorMessageText').css('opacity', '1');
                }
            });

            request.fail(function(jqXHR) 
            {
                if(jqXHR.status == 401 || jqXHR.status == 422)
                {
                    var errorMessage = jqXHR.responseJSON.Message;

                    $('#errorMessageText').text(errorMessage);
                    $('#errorMessageText').css('opacity', '1');
                }
                else
                {
                    $('#errorMessageText').text('Something went wrong');
                    $('#errorMessageText').css('opacity', '1');
                }
            });
        }
    });

    //logoutBtn click
    $('#logoutBtn').click(function() {

        //remove cookie
        removecookie('UusJvalUs');

        //refresh page
        window.location.href = window.location.href;

    });

    function removecookie(name)
    {
        setcookie(name, "", -1);
    }

    function setcookie(name, value, days)
    {
        if (days)
        {
            var date = new Date();
            date.setTime(date.getTime()+days*24*60*60*1000); // ) removed
            var expires = "; expires=" + date.toGMTString(); // + added
        }
        else
            var expires = "";
        
        document.cookie = name+"=" + value+expires + ";path=/;sameSite=strict;"; // + and " added
    }

});