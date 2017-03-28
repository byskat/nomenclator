
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

// SEARCH FORM
function updateGazetteer(request, form) {

  // Abort any pending request
  if (request) {
      request.abort();
  }

  var serializedData = '';

  // If form isn't defined means default request
  if (typeof form === 'undefined') {
    // Default inputs
    serializedData = 'q=&t=&a=&pag=1';
  } else {
    // Get inputs from form
    var $form = $(form);

    // Let's select and cache all the fields
    var $inputs = $form.find("input#tagsinput, input#queryinput, input#pag");

    // Serialize the data in the form
    serializedData = $form.serialize();

    // Disable the inputs for the duration of the Ajax request.
    $inputs.prop("disabled", true);
  }
  
  // Fire off the request to /form.php
  request = $.ajax({
      url: "resources/php/server.php",
      type: "get",
      data: serializedData
  });

  // Callback handler that will be called on success
  request.done(function (response, textStatus, jqXHR){
    response = JSON.parse(response);

    $('#q').text(response['q']);
    $('#num').text(response['num']);

    html = '';
    response['res'].forEach(function(item) {
      date = new Date(item['_date_modified']);

      html += '<div class="item col-md-4">';
      html += '<h3>'+item['nom_tip_comple']+'</h3>';
      html += '<h2>'+item['nom_tip_comple']+'</h2>';
      html += '<p><span>'+date.getFullYear()+'</span>';
      html += 'Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod.';
      html += '</p><p><a class="btn btn-default" href="#" role="button">Més detalls »</a></p>'
      html += '</div>';
      
    });
    $('#resultsContainer').html(html);

    $('#stat').text(response['pag']+' de '+response['lim']);
    $('#last').val(response['lim']);

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
  // if the request failed or succeeded (but only if form is passed)
  if (typeof form !== 'undefined') {
    request.always(function () {
        // Reenable the inputs
        $inputs.prop("disabled", false);
    });
  }
  return request;
}

// If focus on search input -> uncheck abc radios
$("#tagsinput").focus(function() {
  $('.alphabet-filter .active').removeClass('active');
  $('.alphabet-filter input:checked').attr('checked', false);
}); 

// Variable to hold request
var request;

// Bind to the submit event to form
$("#search-form").submit(function(event){
  // Prevent default posting of form
  event.preventDefault();
  request = updateGazetteer(request, this);
});

// Load data for first time
$(document).ready(function() {
  updateGazetteer(request);
  $('.alphabet-filter span').first().addClass('active');
});

/* Alphabet clicable text */
$('.alphabet-filter span').click(function(e) {
  
  // Remove previus active letter (visual) 
  $('.alphabet-filter .active').removeClass('active');
  
  // Find previus checked item
  $('.alphabet-filter input:checked').attr('checked', false);
  
  // Find new checkbox to be checked
  $(this).find('input[type="radio"]').attr('checked', true);
  
  // Add active visuals to current checked span
  $(this).addClass('active');

  //Reset pagination
  paginationReset();

  // Inmediate submit after click
  $('#search-form').submit();
});

$('input[type="radio"]').click(function(e) {
  e.stopPropagation();
});


//test pagination

  function add() {
    var val = +$("#pag").val() + 1;
    $("#pag").val(val);
  }
  
  function sub() {
    var val = +$("#pag").val() - 1;
    if(val>0) $("#pag").val(val);
  }

  function first() {
    $("#pag").val(1);
  }

  function last() {
    $("#pag").val($("#last").val());
  }

  $("#first").click(function(e) {
    first()
  });
  $("#last").click(function(e) {
    last()
  });

  $("#next").click(function(e) {
    add()
  });
  $("#prev").click(function(e) {
    sub()
  });

function paginationReset() {
  $('#pag').val('1');
}

$('#tagsinput').keypress(function (e) {
  if (e.which == 13) {
    paginationReset();
  }
});