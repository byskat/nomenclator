
/*
$(function () {

  $('[data-toggle="popover"]').popover()

  var content = "<button id='20' value='via' type='button' class='tag filter-tag label label-primary' onClick='addTag(this)'>Jardí</button>";


  var options = {
    "title": "Filtre",
    "content": content,
    "html": true,
    "placement": "bottom"
  };

  $("#filter-button").popover(options);

  $('#filter-button').on('click', function () {
    $(this).toggleClass("active");
  })
})

$( document ).ready(function() {

  $( ".tt-input" ).keypress(function (e) {
    if (e.which == 13) {
      $('#queryinput').val($('.tt-input').val());
      $('form.gazetteer-search').submit();
      return false;
    }
  });

	resizeGazetteer();
  $(window).on('resize', function(){
    resizeGazetteer();
  });
});


// Bloodhound ini

var filtres = new Bloodhound({
datumTokenizer: Bloodhound.tokenizers.obj.whitespace('text'),
queryTokenizer: Bloodhound.tokenizers.whitespace,
prefetch: 'resources/vendor/bootstrap-tagsinput/assets/filtres.json'
});
filtres.initialize();

var elt = $('#tagsinput');
elt.tagsinput({
confirmKeys: [],
tagClass: function(item) {
  switch (item.grup) {
    case 'via'   : return 'label label-primary';
    case 'tema'  : return 'label label-danger label-important';
    case 'temps' : return 'label label-success';
  }
},
itemValue: 'id',
itemText: 'nom',
typeaheadjs: {
  name: 'filtres',
  displayKey: 'text',
  source: filtres.ttAdapter()
}
});

// Intancing first tags

elt.tagsinput('add', { "id": 1 , "nom": "Carrer"      , "grup": "via"   });
elt.tagsinput('add', { "id": 4 , "nom": "Plaça"       , "grup": "via"   });
elt.tagsinput('add', { "id": 7 , "nom": "Segle XX"    , "grup": "temps" });
elt.tagsinput('add', { "id": 10, "nom": "Nom de Dona" , "grup": "tema"  });
elt.tagsinput('add', { "id": 13, "nom": "Remodelat"   , "grup": "tema"  });

function addTag(element) {
  var id = $(element).attr('id');
  var name = $(element).text();
  var grup = $(element).val();


  $("#tagsinput").tagsinput('add', { "id": id, "nom": name, "grup": grup});
resizeGazetteer();  
}

function resizeGazetteer() { 
  var tagsWidth = 0;

  var baseWidth = $(".bootstrap-tagsinput").width();
  $(".bootstrap-tagsinput .tag").each(function(index) {
      tagsWidth += parseInt($(this).outerWidth(), 10)+7;
  });

  $(".bootstrap-tagsinput input").width(baseWidth-tagsWidth);
}
*/


$(function () {
  var content = "<button id='20' value='via' type='button' class='tag filter-tag label label-primary' onClick='addTag(this)'>Jardí</button>";
  
  var options = {
    'title': 'Filtre',
    'content': content,
    'html': true,
    'placement': 'bottom'
  };

  $('#filter-button').popover(options);

  $('#filter-button').on('click', function () {
    $(this).toggleClass('active');
  });


  $('[data-toggle="popover"]').popover();
});

// Variable to hold request
var request;

// Bind to the submit event of our form
$("#search-form").submit(function(event){

  // Prevent default posting of form - put here to work in case of errors
  event.preventDefault();

  // Abort any pending request
  if (request) {
      request.abort();
  }
  // setup some local variables
  var $form = $(this);

  // Let's select and cache all the fields
  var $inputs = $form.find("input#tagsinput, input#queryinput");

  // Serialize the data in the form
  var serializedData = $form.serialize();

  // Let's disable the inputs for the duration of the Ajax request.
  // Note: we disable elements AFTER the form data has been serialized.
  // Disabled form elements will not be serialized.
  $inputs.prop("disabled", true);

  // Fire off the request to /form.php
  request = $.ajax({
      url: "resources/php/server.php",
      type: "post",
      data: serializedData
  });

  // Callback handler that will be called on success
  request.done(function (response, textStatus, jqXHR){
      // Log a message to the console
      console.log("Hooray, it worked!");
      console.log(response);
  });

  // Callback handler that will be called on failure
  request.fail(function (jqXHR, textStatus, errorThrown){
      // Log the error to the console
      console.error(
          "The following error occurred: "+
          textStatus, errorThrown
      );
  });

  // Callback handler that will be called regardless
  // if the request failed or succeeded
  request.always(function () {
      // Reenable the inputs
      $inputs.prop("disabled", false);
  });
});

/* Alphabet clicable text */

$('.alphabet-filter span').click(function(e) {
  $('.alphabet-filter .active').removeClass('active');
  var cur = $(this).find('input[type="radio"]').prop('checked')
  $(this).find('input[type="radio"]').prop('checked', !cur);
  $(this).addClass('active');
  //console.log($(this).text()+cur);

  $('#abc-form').submit();

});

$('input[type="radio"]').click(function(e) {
  e.stopPropagation();
});
