var filterList = {};

/* -- Functions -- */

// Simple function that shows/hides the loader in the searchbar
function loader(elm, state) {
    "use strict";

    switch (state) {
    case "show":
        $(elm).addClass("loading");
        break;
    case "hide":
        $(elm).removeClass("loading");
        break;
    }
}

// MODEL LOAD
function loadModal(elm) {
    "use strict";

    $("#view-modal").modal("toggle");
    var itemId = $(elm).attr("data-id");

    // Fire off the request to /server.php
    var request = $.ajax({
        url: "resources/php/server.php",
        type: "get",
        data: {"id": itemId}
    });

    // Callback handler that will be called on success
    request.done(function (response) {

        try {
            response = JSON.parse(response);
            console.log(response);
        } catch (e) {
            return e;
        }

        var date = "Sense registre (S.R.)";

        if (response.canvi_any !== null) {
            date = response.canvi_any;
        }

        var nexe = "";

        if (response.nexe !== null) {
            nexe = response.nexe;
        }

        var tipusVia = response.nom_composat.split(" ")[0];
        var linkMap = "http://www.girona.cat/planol/?q=";
        linkMap += encodeURI(response.nom_composat);

        $("#view-modal").find("h3").text(tipusVia + " " + nexe);
        $("#view-modal").find("h2").text(response.nom_variant_curt);
        $("#view-modal").find("#extend_map").attr("href", linkMap);
        $("#view-modal").find("#data_variacio").text(date);
        $("#view-modal").find("#nom_postal").text(response.nom_postal);
        $("#view-modal").find("#codi_car").text(response.codi_car);
        $("#view-modal").find("iframe").attr("src", linkMap);

        if (response.canvi_nota || response.canvi_fet) {
            var html = "<p><b>Observacions: </b>";

            if (response.canvi_fet) {
                html += "<i>(" + response.canvi_fet.trim() + ")</i> ";
            }

            html += response.canvi_nota.trim() + "</p>";
            $("#view-modal").find("#observacions").html(html);
        }
    });

    // Callback handler that will be called on failure
    request.fail(function (textStatus, errorThrown) {
        // Log the error to the console
        console.error(
            "LOADMODAL | The following error occurred: " + textStatus,
            errorThrown
        );
    });
}

// SEARCH FORM
function updateGazetteer(form) {
    "use strict";

    var serializedData = "";

    // Let"s select and cache all the fields
    var $inputs = $(form).find("input#queryinput, input#tagsinput, input#pag");

    // Serialize the data in the form
    serializedData = $(form).serialize();

    // Disable the inputs for the duration of the Ajax request.
    $inputs.prop("disabled", true);

    loader(".icon-search", "show");

    // Fire off the request
    var request = $.ajax({
        url: "resources/php/server.php",
        type: "get",
        data: serializedData
    });

    // Callback handler that will be called on success
    request.done(function (response) {

        var html = "";

        // Try parsing the response, if don"t work retry (200 times max)
        // or give error
        try {
            //console.log(response);
            response = JSON.parse(response);
            console.log(response);
        } catch (e) {
            console.log(response);
            html = "<div class='col-md-12'><p class='no-items'>";
            html += "Error en rebre els resultats.</p>";
            html += "<a class='btn btn-default' ";
            html += "onClick='window.location.reload()'>Refrescar ";
            html += "<i class='fa fa-refresh' aria-hidden='true'>";
            html += "</i></a></div>";
            $("#resultsContainer").html(html);
            loader(".icon-search", "hide");
            return e;
        }

        // Ensures that the selected button and server response is the same.
        // Useful for the first instance (when "Tots" is set by default).
        if (response.abc) {
            // Find new checkbox to be checked
            $(".alphabet-filter #abc-" + response.abc)
                .find("input[type='radio']")
                .attr("checked", true);
            // Add active visuals to current checked span
            $(".alphabet-filter #abc-" + response.abc).addClass("active");
        }

        if (response.abc) {
            $("#q").text(response.abc);
        } else {
            $("#q").text(response.q);
        }

        if (response.num === 1) {
            $("#num").text(response.num + " resultat");
        } else {
            $("#num").text(response.num + " resultats");
        }

        if (response.tag) {
            var t = [];

            $.each(response.tag, function (field, value) {
                t.push(filterList[field].valors[value]);
            });

            $(".filterSymbol").text("+");
            $("#tag").text(t.join(", "));
        } else {
            $(".filterSymbol").text("");
            $("#tag").text("");
        }

        html = "";

        if (!response.num) {
            html += "<div class='col-md-12'><p class='no-items'>";
            html += "No hi ha resultats.</p></div>";
        } else {
            response.res.forEach(function (item) {
                var date = "S.R.";
                if (item.canvi_any !== null) {
                    date = item.canvi_any;
                }

                var nexe = "";
                if (item.nexe !== null) {
                    nexe = item.nexe;
                }

                var tipusVia = item.nom_composat.split(" ")[0];

                html += "<div class='item col-md-4'>";
                html += "<h3>" + tipusVia + " " + nexe + "</h3>";
                html += "<h2>" + item.nom_variant_curt + "</h2>";
                html += "<p><span>" + date + "</span>";

                html += "Donec id elit non mi porta gravida at eget metus. ";
                html += "Fusce dapibus, tellus ac cursus commodo, tortor ";
                html += "mauris condimentum nibh, ut fermentum massa justo ";
                html += "sit amet risus. Etiam porta sem malesuada magna ";
                html += "mollis euismod.";

                html += "</p><p><button class='btn btn-default modalButton' ";
                html += "type='button' ";
                html += "data-toggle='modal' ";
                html += "data-id='" + item.codi_car + "'>";
                html += "Més detalls »</button></p>";
                html += "</div>";
            });
        }
        $("#resultsContainer").html(html);

        // Total disabler
        if (response.lim <= 1) {
            $("#paginator").addClass("disabled");
            $("#paginator").find("button").attr("disabled", true);
        } else {
            $("#paginator").removeClass("disabled");
            $("#paginator").find("button").attr("disabled", false);
        }

        // Partial Disabler
        if (response.pag === response.lim) {
            $("#next, #last").addClass("disabled");
            $("#next, #last").attr("disabled", true);
        } else {
            $("#next, #last").removeClass("disabled");
            $("#next, #last").attr("disabled", false);
        }

        if (response.pag === 1) {
            $("#prev, #first").addClass("disabled");
            $("#prev, #first").attr("disabled", true);
        } else {
            $("#prev, #first").removeClass("disabled");
            $("#prev, #first").attr("disabled", false);
        }

        $("#stat").text(response.pag + " de " + response.lim);
        $("#stat").attr("disabled", true);
        $("#last").attr("data-last", response.lim);

        loader(".icon-search", "hide");

    });

    // Callback handler that will be called on failure
    request.fail(function (textStatus, errorThrown) {
        // Log the error to the console
        console.error(
            "UPDATEGAZETTEER | The following error occurred: " + textStatus,
            errorThrown
        );
    });

    // Callback handler that will be called regardless
    // if the request failed or succeeded (but only if form is passed)
    if (form !== "undefined") {
        request.always(function () {
            // Reenable the inputs
            $inputs.prop("disabled", false);
        });
    }
    return request;
}

function pagination(action) {
    "use strict";

    var val;

    switch (action) {
    case "add":
        val = +$("#pag").val() + 1;
        if (val <= $("#last").attr("data-last")) {
            $("#pag").val(val);
        }
        break;
    case "sub":
        val = +$("#pag").val() - 1;
        if (val > 0) {
            $("#pag").val(val);
        }
        break;
    case "first":
    case "reset":
        $("#pag").val(1);
        break;
    case "last":
        $("#pag").val($("#last").attr("data-last"));
        break;
    default:
        console.log("pagination: undefined action");
    }
}

// Generation of abc filter
function abcGeneration(elm) {
    "use strict";

    var abc = [
        "Tots", "a", "b", "c", "d", "e", "f", "g", "h",
        "i", "j", "k", "l", "m", "n", "o", "p", "q", "r",
        "s", "t", "u", "v", "w", "x", "y", "z"
    ];

    var html = "";

    abc.forEach(function (letter) {
        html += "<span id='abc-" + letter + "' onclick='abcActivation(this)'>";
        html += "<input type='radio' name='a' value='";
        html += letter + "'>";
        html += letter + "</span>\n";
    });
    $(elm).append(html);
}

/* Alphabet clicable text */
function abcActivation(elm) {
    "use strict";

    var lastLetter = $(elm).closest("div").find(".active");

    // Remove previus active letter (visual)
    lastLetter.removeClass("active");

    // Find previus checked item
    lastLetter.find("input:checked").prop("checked", false);

    // Find new checkbox to be checked
    $(elm).find("input").prop("checked", true);

    // Add active visuals to current checked span
    $(elm).addClass("active");

    //Reset pagination
    pagination("reset");

    // Inmediate submit after click
    $("#search-form").submit();
}

// New tag filler
function tagDraw(filepath, elm) {
    "use strict";

    var html = "";

    $.getJSON(filepath, function (filter) {
        filterList = filter;
        $.each(filter, function (field, properties) {
            html += "<div>";
            html += "<h4 class='filter-title'>" + properties.nom + "</h4>";

            $.each(properties.valors, function (valor, name) {
                html += "<span class='tag' onclick='tagActivation(this)'>";
                html += "<input type='radio' name='" + field + "' ";
                html += "value='" + valor + "'>" + name;
                html += "</span>\n";
            });

            html += "</div>\n";
        });
        $(elm).append(html);
    });
}

function tagRemove(elm) {
    "use strict";

    $("#tagsinput").val("");
    $("#tag-popover").find("input[type='radio']").attr("checked", false);
    $("#tag-popover").find("span").removeClass("active");

    $(elm).removeClass("active");

    // Inmediate submit after click
    $("#search-form").submit();
}

function tagActivation(elm) {
    "use strict";

    //Get all sibling tags
    var siblings = $(elm).siblings();

    //Remove active
    siblings.removeClass("active");

    //Uncheck functionality
    if ($(elm).find("input[type='radio']:checked").length) {
        //If the same tag is selected, then, uncheck it
        $(elm).removeClass("active");
        $(elm).find("input[type='radio']").attr("checked", false);

    } else {
        // Find previus checked item
        $(siblings).find("input").attr("checked", false);

        // Find new checkbox to be checked
        $(elm).find("input[type='radio']").attr("checked", true);

        // Add active visuals to current cheked span
        $(elm).addClass("active");

        // Add active visuals to tag indicator
        $("#filter-indicator").addClass("active");
    }

    // Fills in a hidden input the names of the filled checkboxes
    var listTags = [];

    $("#tag-popover input:checked").each(function () {
        listTags.push($(this).attr("name"));
    });
    $("#tagsinput").val(listTags);

    if (!listTags.length) {
        $("#filter-indicator").removeClass("active");
    }

    //Reset pagination
    pagination("reset");

    // Inmediate submit after click
    $("#search-form").submit();
}