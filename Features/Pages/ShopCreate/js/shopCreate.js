$(document).ready(function () {

    $("#createShop").click(function () {

        var href = $(this).attr("href");
        var reqpath = $(this).attr("reqpath");

        var shopName = $("#shopName").val();
        var shopShortDescription = $("#shopShortDescription").val();
        var shopDescription = $("#shopDescription").val();
        var shopImage = $("#shopImage").prop('files')[0];

        var tags = [];
        $(".tag").each(function () {
        	tags.push($(this).val());
        });
		
        var tagsString = "";
        for (var i = 0; i < tags.length; i++) {
            tagsString += tags[i] + ";";
        }
		
        var shopCategory_1nd = $("#shopCategory0 option:selected").text();
        var shopCategory_2nd = $("#shopCategory1 option:selected").text();
        var shopCategory_3nd = $("#shopCategory2 option:selected").text();

        var formData = new FormData();
        
        formData.append("shopName", shopName);
        formData.append("shopShortDescription", shopShortDescription);
        formData.append("shopDescription", shopDescription);
        formData.append("shopImage", shopImage);
        formData.append("shopTag", tagsString);
		
        var categoryArray = [];

        if (shopCategory_1nd != null && shopCategory_1nd != "Select category") {
            categoryArray.push(shopCategory_1nd);
        }

        if (shopCategory_2nd != null && shopCategory_2nd != "Select category") {
            categoryArray.push(shopCategory_2nd);
        }

        if (shopCategory_3nd != null && shopCategory_3nd != "Select category") {
            categoryArray.push(shopCategory_3nd);
        }

        //remove duplicates elements
        var uniqueCategoryArray = categoryArray.filter(function (item, pos) {
            return categoryArray.indexOf(item) == pos;
        });

        for (var i = 0; i < uniqueCategoryArray.length; i++) {
        	var categoryIndex = i;
            formData.append("shopCategory_" + parseInt(categoryIndex + 1) + "nd", uniqueCategoryArray[i]);
        }
        
        var jwt = getCookie("UusJvalUs");
		
        var request = $.ajax({
            processData: false,
            contentType: false,
            headers: {
                "Authorization": jwt
            },
            cache: false,
            enctype: 'multipart/form-data',
            url: reqpath,
            type: "POST",
            data: formData,
        });

        request.done(function (data) {
            window.location.href = href;
        });

        request.fail(function (jqXHR, textStatus) {
            if(jqXHR.status == 422 || jqXHR.status == 401){
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

    });

    function getCookie(name) {
        var value = "; " + document.cookie;
        var parts = value.split("; " + name + "=");
        if (parts.length == 2) return parts.pop().split(";").shift();
    }

});