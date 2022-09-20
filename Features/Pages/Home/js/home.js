$(document).ready(function () {

    var productsSlide = new Swiper(".slide-products", {
        autoplay: {
            delay: 2000,
        },
        slidesPerView: 3,
        spaceBetween: 25,
        loop: true,
        centerSlide: 'true',
        fade: 'true',
        grabCursor: 'true',
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
            dynamicBullets: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    
        breakpoints:{
            0: {
                slidesPerView: 1,
            },
            520: {
                slidesPerView: 2,
            },
            950: {
                slidesPerView: 3,
            },
        },
    });

    var shopsSlide = new Swiper(".slide-shops", {
        autoplay: {
            delay: 5000,
        },
        slidesPerView: 3,
        spaceBetween: 25,
        loop: true,
        centerSlide: 'true',
        fade: 'true',
        grabCursor: 'true',
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
            dynamicBullets: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    
        breakpoints:{
            0: {
                slidesPerView: 1,
            },
            520: {
                slidesPerView: 2,
            },
            950: {
                slidesPerView: 3,
            },
        },
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

});
