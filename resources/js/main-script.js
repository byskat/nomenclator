$(function () {
    "use strict";

    //Draw the content of the filter popover
    tagDraw("filtre.json", "#tag-popover .popover-content");

    // Load data for first time
    updateGazetteer();

    abcGeneration(".alphabet-filter");

    //HANDLERS

    $("#filter-indicator").on("click", function (e) {
        e.preventDefault();
        tagRemove();
    });

    $("#filter-button").on("click", function () {
        $(this).toggleClass("active");
        $("#tag-popover").stop().slideToggle("500", "linear", "true");
    });

    //Dissmisable filter popover
    $(window).click(function () {
        if ($("#tag-popover").is(":visible")) {
            $("#filter-button").trigger("click");
        }
    });
    $("#tag-popover, #filter-button").click(function (e) {
        e.stopPropagation();
    });

    /* Request handlers */

    // Bind to the submit event to form
    $("#search-form").submit(function (e) {
        // Prevent default posting of form
        e.preventDefault();
        updateGazetteer(this);
    });

    // Handles more info button that loads specific info
    $("body").on("click", ".modalButton", function () {
        loadModal($(this).attr("data-id"));
    });

    //Pagination handlers
    $("#first").click(function () {
        pagination("first");
    });
    $("#last").click(function () {
        pagination("last");
    });

    $("#next").click(function () {
        pagination("add");
    });
    $("#prev").click(function () {
        pagination("sub");
    });

    // Intro in searchbar, if contains something reset abc and pagination.
    $("#queryinput").keypress(function (e) {
        if (e.which === 13 && $(this).val() !== "") {
            e.preventDefault();

            // Unset alphabet selection
            $(".alphabet-filter .active").removeClass("active");
            $(".alphabet-filter input:checked").prop("checked", false);

            $("#search-form").submit();
        }
    });
});