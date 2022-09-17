//document ready
$(document).ready(function () {

    //
    // CATEGORY
    //

    /* Add category */
    $('#add-first-category').click(function () {
        $('#no-category').remove();
        $('#add-category-form').css('display', 'block');
    });

    $('#add-category').click(function () {
        if($('#add-category-form').css('display') == 'none')
            $('#add-category-form').css('display', 'block');
        else
            $('#add-category-form').css('display', 'none');

        $('#edit-category-form').css('display', 'none');
    });

    $('#add-category-btn').click(function () {
       
        var reqpath = $('#add-category-btn').attr('reqpath');

        var categoryName = $('#categoryName').val();
        var categoryDescription = $('#categoryDescription').val();
        var categoryShortDescription = $('#categoryShortDescription').val();
        var categoryImage = $("#categoryImage").prop('files')[0];

        var formData = new FormData();
        formData.append("categoryName", categoryName);
        formData.append("categoryDescription", categoryDescription);
        formData.append("categoryShortDescription", categoryShortDescription);
        formData.append("categoryImage", categoryImage);

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
            location.reload();
        });

        request.fail(function (jqXHR, textStatus) {
            if (jqXHR.status == 422 || jqXHR.status == 401) {
                var errorMessage = jqXHR.responseJSON.Message;

                $('#errorMessageText').text(errorMessage);
                $('#errorMessageText').css('opacity', '1');
            }
            else {
                $('#errorMessageText').text('Something went wrong');
                $('#errorMessageText').css('opacity', '1');
            }
        });

    });

    /* Edit Category */
    var updatedInputBoxes = [];

    $('#edit-category-btn').click(function () {
        var categoryId = $(this).attr('category');
        var reqpath = $(this).attr('reqpath') + "/" + categoryId;

        var jwt = getCookie("UusJvalUs");

        var categoryName = $('#edit-categoryName');
        var categoryDescription = $('#edit-categoryDescription');
        var categoryShortDescription = $('#edit-categoryShortDescription');
        var categoryImage = $("#edit-categoryImage").prop('files')[0];

        var formData = new FormData();

        if (updatedInputBoxes.includes('categoryName'))
            formData.append("categoryName", categoryName.val());

        if (updatedInputBoxes.includes('categoryDescription'))
            formData.append("categoryDescription", categoryDescription.val());

        if (updatedInputBoxes.includes('categoryShortDescription'))
            formData.append("categoryShortDescription", categoryShortDescription.val());

        if (categoryImage != null) {
            formData.append("categoryImage", categoryImage);
        }

        formData.append('_method', 'patch');

        var request = $.ajax({
            headers: {
                "Authorization": jwt
            },
            url: reqpath,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            enctype: 'multipart/form-data'
        });

        request.done(function (data) {
            location.reload();
        });

        request.fail(function (jqXHR, textStatus) {
            $('#edit-errorMessageText').text(jqXHR.responseJSON.Message);
            $('#edit-errorMessageText').css('opacity', '1');
        });
    });

    $('.editCategory_btn').click(function () {
        var categoryId = $(this).attr('category');
        var reqpath = $(this).attr('reqpath') + "/" + categoryId;

        var jwt = getCookie("UusJvalUs");

        var request = $.ajax({
            headers: {
                "Authorization": jwt
            },
            url: reqpath,
            type: "GET"
        });

        request.done(function (data) {
            $('#edit-category-form').css('display', 'block');

            $('#edit-categoryName').val(data.categoryName);
            $('#edit-categoryDescription').val(data.categoryDescription);
            $('#edit-categoryShortDescription').val(data.categoryShortDescription);

            $('#edit-category-btn').attr('category', categoryId);

            $('html, body').animate({
                scrollTop: $("#edit-category-form").offset().top - 20
            }, 300);

            $('#add-category-form').css('display', 'none');
        });

        request.fail(function (jqXHR, textStatus) {
            alert(jqXHR.responseJSON.Message);
        });

    });   

    $('#edit-categoryName').change(function () {
        if (!updatedInputBoxes.includes('categoryName'))
            updatedInputBoxes.push('categoryName');
    });
    
    $('#edit-categoryShortDescription').change(function () {
        if (!updatedInputBoxes.includes('categoryShortDescription')) {
            updatedInputBoxes.push('categoryShortDescription');
        } 
    });

    $('#edit-categoryDescription').change(function () {
        if (!updatedInputBoxes.includes('categoryDescription')) {
            updatedInputBoxes.push('categoryDescription');
        }
    });

    $('#edit-category-btn-remove-changes').click(function () {
        updatedInputBoxes = [];

        $('#edit-categoryName').val('');
        $('#edit-categoryDescription').val('');
        $('#edit-categoryShortDescription').val('');
        $('#edit-errorMessageText').text('ErrorMessage');
        $('#edit-errorMessageText').css('opacity', '0');

        $('#edit-category-form').css('display', 'none');
    });

    /* Delete category */
    $('.deleteCategory_btn').click(function () {
        var categoryId = $(this).attr('category');
        var reqpath = $(this).attr('reqpath') + "/" + categoryId;

        var jwt = getCookie("UusJvalUs");

        var request = $.ajax({
            headers: {
                "Authorization": jwt
            },
            url: reqpath,
            type: "DELETE"
        });

        request.done(function (data) {
            location.reload();
        });

        request.fail(function (jqXHR, textStatus) {
            $('#errorMessageText-category').text(jqXHR.responseJSON.Message);
            $('#errorMessageText-category').css('opacity', '1');
        });
    });

    //
    // PRODUCT
    //

    /* Add Product */
    $('#add-first-product').click(function (){
        $('#no-products').remove();
        $('#add-product-form').css('display', 'block');
    });
    
    $('#add-product-btn').click(function () {
        if($('#add-product-form').css('display') == 'none')
            $('#add-product-form').css('display', 'block');
        else
            $('#add-product-form').css('display', 'none');
    });

    $('#add-product').click(function () {
        var reqpath = $(this).attr('reqpath');

        var productName = $('#productName').val();
        var productDescription = $('#productDescription').val();
        var productPrice = $('#productPrice').val();
        var category = $( "#productCategory option:selected").text();
        var quantity = $('#productQuantity').val();

        var bookable;
        if($('#productBookable').is(":checked"))
            bookable = 1;
        else
            bookable = 0;

        var availableFrom = $('#productAvailableFrom').val();
        var productImage_1 = $("#productImage1").prop('files')[0];
        var productImage_2 = $("#productImage2").prop('files')[0];
        var productImage_3 = $("#productImage3").prop('files')[0];
        var productImage_4 = $("#productImage4").prop('files')[0];
        var productImage_5 = $("#productImage5").prop('files')[0];

        var formData = new FormData();

        formData.append("productName", productName);
        formData.append("productDescription", productDescription);
        formData.append("productPrice", productPrice);
        formData.append("category", category);
        formData.append("quantity", quantity);
        formData.append("bookable", bookable);
        formData.append("availableFrom", availableFrom);

        formData.append("productImage_1", productImage_1);
        formData.append("productImage_2", productImage_2);
        formData.append("productImage_3", productImage_3);
        formData.append("productImage_4", productImage_4);

        if (productImage_5 != null) {
            formData.append("productImage_5", productImage_5);
        }

        var jwt = getCookie("UusJvalUs");

        var request = $.ajax({
            headers: {
                "Authorization": jwt
            },
            url: reqpath,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            enctype: 'multipart/form-data'
        });

        request.done(function (data) {
            location.reload();
        });

        request.fail(function (jqXHR, textStatus) {
            $('#add-errorMessageText-product').text(jqXHR.responseJSON.Message);
            $('#add-errorMessageText-product').css('opacity', '1');
        });

    });

    /* Delete Product */
    $('.deleteProduct_btn').click(function () {
        var productId = $(this).attr('product');
        var reqpath = $(this).attr('reqpath') + "/" + productId;

        var jwt = getCookie("UusJvalUs");

        var request = $.ajax({
            headers: {
                "Authorization": jwt
            },
            url: reqpath,
            type: "DELETE"
        });

        request.done(function (data) {
            location.reload();
        });

        request.fail(function (jqXHR, textStatus) {
            $('#errorMessageText-product').text(jqXHR.responseJSON.Message);
            $('#errorMessageText-product').css('opacity', '1');
        });
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