//document ready
$(document).ready(function () {

    //sign in button click
    $('#signBtn').click(function () {

        // get href and reqpath
        var href = $(this).attr('href');
        var reqpath = $(this).attr('reqpath');

        //Get all the values from the signin form
        var firstName = $('#firstName').val();
        var lastName = $('#lastName').val();
        var phone = $('#phone').val();
        var birthDate = $('#birthDate').val();
        var email = $('#email').val();
        var nationId = $('#nationId').val();
        var userGender = $('#userGender').val() ?? 'Gender';
        var password = $('#password').val();
        var confirmPassword = $('#confirmPassword').val();

       //if all the fields are filled
        if (firstName != '' && lastName != '' && phone != '' && birthDate != '' && email != '' && password != '' && confirmPassword != '' && nationId != '' && userGender != 'Gender')
        {
            //if the password and confirm password are equal
            if (password == confirmPassword)
            {
                //if the email is valid
                if (validateEmail(email))
                {
                    //if birth date is valid
                    if (validateBirthDate(birthDate))
                    {
                        //endocde the data to be sent to the server
                        var data = jQuery.param({ userFirstName: firstName, 
                                                  userLastName: lastName, 
                                                  userPhone: phone, 
                                                  userDateBirth: birthDate, 
                                                  userEmail: email, 
                                                  userNationGuid: nationId,
                                                  userGender: userGender,
                                                  userPassword: password 
                                                });
                        
                        //make ajax call to the server to sign in
                        var request = $.ajax({
                            contentType: 'application/x-www-form-urlencoded',
                            type: 'POST',
                            url: reqpath,
                            data: data
                        });

                        request.done(function (data, textStatus, xhr)
                        {
                            if(xhr.status == 200)
                            {
                                window.location.href = href;
                            }
                            else
                            {
                                $('#errorMessageText').text('Something went wrong');
                                $('#errorMessageText').css('opacity', '1');
                            }
                        });

                        request.fail(function(jqXHR, textStatus) 
                        {
                            if(jqXHR.status == 422)
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
                    else
                    {
                        $('#errorMessageText').text('You must be at least 18 years old to sign up.');
                        $('#errorMessageText').css('opacity', '1');
                    }
                }
                else
                {
                    $('#errorMessageText').text('Please enter a valid email');
                    $('#errorMessageText').css('opacity', '1');
                }
            }
            else
            {
                $('#errorMessageText').text('Password are not equal');
                $('#errorMessageText').css('opacity', '1');
            }
        }
        else
        {
            $('#errorMessageText').text('Please complete all fields');
            $('#errorMessageText').css('opacity', '1');
        }

    });

    //function to check birth date is higher than 18 years old
    function validateBirthDate(birthDate)
    {
        var today = new Date();
        var birthDate = new Date(birthDate);
        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();

        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate()))
        {
            age--;
        }
        if (age < 18)
        {
            $('#errorMessageText').text('You must be at least 18 years old');
            $('#errorMessageText').css('opacity', '1');
            return false;
        }
        else
        {
            return true;
        }

    }

    //function to validate the email
    function validateEmail(email) 
    {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

});