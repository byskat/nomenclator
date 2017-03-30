//TAG popover
$(function () {
  /*
  var content = "<button value='via' type='button' class='tag filter-tag label label-primary' onClick='addTag(this)'>Jardí</button>";
  */

  $('#filter-button').popover({
    'title': 'Filtre',
    'content': 'Carregant...',
    'html': true,
    'placement': 'bottom'
  });

  loadTags($('#filter-button'));
  
  $('#filter-button').on('click', function () {
    $(this).toggleClass('active');
  });
  
  $('.gazetteer-search button').on('click', function(){
    console.log(this);
  });

  $('[data-toggle="popover"]').popover();
});

function loadTags(popover) {
  var requestTags;

  // Abort any pending request
  if (requestTags) {
      requestTags.abort();
  }

  requestTags = $.ajax({
      url: "resources/php/tagServer.php",
      type: "get"
  });

  // Callback handler that will be called on success
  requestTags.done(function (response, textStatus, jqXHR){
    response = JSON.parse(response);

    html = '';
    response.forEach(function(item) {
      html += '<input type="radio" id="t" ';
      html += 'class="filter-tag label label-primary" ';
      html += 'value="'+item['codi_tipus_via']+'">';
    });

    popover = popover.attr('data-content',html).data('bs.popover');
    popover.setContent();

    console.log(response);
  });

  requestTags.fail(function (jqXHR, textStatus, errorThrown){
      // Log the error to the console
      console.error(
          "LOADTAGS | The following error occurred: "+
          textStatus, errorThrown
      );
  });
}

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

    try {
      response = JSON.parse(response);  
    }
    catch (e) {
      return false;
    }
    
    $('#q').text(response['q']);
    $('#num').text(response['num']);

    html = '';

    if(!response['num']) {
      html += '<div class="col-md-12"><p class="no-items">No hi ha resultats.</p></div>';
    } else {
      response['res'].forEach(function(item) {
        (item['data_variacio']===null)? date = 'S.R.' : date = new Date(item['data_variacio']).getFullYear();
        (item['nexe']===null)? nexe = '' : nexe = item['nexe'];

        tipusVia = item['nom_composat'].split(' ')[0];

        html += '<div class="item col-md-4">';
        html += '<h3>'+tipusVia+' '+nexe+'</h3>';
        html += '<h2>'+item['nom_variant_curt']+'</h2>';
        html += '<p><span>'+date+'</span>';
        html += 'Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod.';
        html += '</p><p><a class="btn btn-default" href="#" role="button">Més detalls »</a></p>'
        html += '</div>';
      });
    }
    $('#resultsContainer').html(html);

    // Total disabler
    if(response['lim']<=1) {
      $('#paginator').addClass('disabled');
      $('#paginator').find('button').attr('disabled', true);
    } else {
      $('#paginator').removeClass('disabled');
      $('#paginator').find('button').attr('disabled', false);
    }

    // Partial Disabler
    if(response['pag']==response['lim']) {
      $('#next, #last').addClass('disabled');
      $('#next, #last').attr('disabled', true);
    } else {
      $('#next, #last').removeClass('disabled');
      $('#next, #last').attr('disabled', false);
    }

    if(response['pag']==1) {
      $('#prev, #first').addClass('disabled');
      $('#prev, #first').attr('disabled', true);
    } else {
      $('#prev, #first').removeClass('disabled');
      $('#prev, #first').attr('disabled', false);
    }

    $('#stat').text(response['pag']+' de '+response['lim']);
    $('#stat').attr('disabled', true);
    $('#last').val(response['lim']);

    console.log(response);
  });

  // Callback handler that will be called on failure
  request.fail(function (jqXHR, textStatus, errorThrown){
      // Log the error to the console
      console.error(
          "UPDATEGAZETTEER | The following error occurred: "+
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


// Pagination

  function add() {
    var val = +$("#pag").val() + 1;
    if(val<=$("#last").val()) $("#pag").val(val);
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


function paginator(element, hidden, limit) {
  // selector with nav container
  this.element = element;
  // selector with hidden input with page valor
  this.hidden = hidden;
  // page valor
  this.current = $(this.hidden).val();
  // max number of valors
  this.limit = limit;

  // nav local selectors (create them with draw function?)
  this.first = element + " .first";
  this.prev = element + " .prev";
  this.stat = element + " .stat";
  this.next = element + " .next";
  this.last = element + " .last";

  this.add = function() {
    if(this.current <= $(this.last).val()) this.apply(this.current+1);
    console.log(this.last);
    console.log("Current: "+this.current);
  }

  this.sub = function() {
    if(this.current>0) this.apply(this.current--);
    console.log("prev");
  }


  this.click = function(e) {
    if ($(e['currentTarget']).hasClass('next')) this.add();
    if ($(e['currentTarget']).hasClass('prev')) this.sub();
    //console.log(e['currentTarget']);
    //console.log($(e).attr('class'));
  }

  this.apply = function(newValor) {
    console.log(newValor);
    $(this.hidden).val(newValor);
  }
}

var testPag = new paginator("#test", "#pag", 10);

$('#test').on('click', 'button', function(e){
  testPag.click(e);
});
