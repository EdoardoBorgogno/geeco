// document ready
$('#document').ready(function() {
    
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