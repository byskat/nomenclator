//TAG popover
$(function () {

  loadTags($('#filter-button'));
  
  $('#filter-button').on('click', function () {
    $(this).toggleClass('active');
    $('#tag-popover').stop().slideToggle("500", "linear", "true");
  });
  
});

function loadTags(popover) {
  var requestTags;

  requestTags = $.ajax({
      url: "resources/php/tagServer.php",
      type: "get"
  });

  // Callback handler that will be called on success
  requestTags.done(function (response, textStatus, jqXHR){
    response = JSON.parse(response);

    filterArray = [];
    html = '';
    response.forEach(function(item) {      
      html += '<span class="tag" onClick="tagActivation(this)">'
      html += '<input type="radio" name="t" ';
      html += 'value="'+item['codi_tipus_via']+'">';
      html += item['nom_tag']+'</span>';

      filterArray[item['codi_tipus_via']] = item['nom_tag'];
    });

    $('#tag-popover .popover-content').append(html);
  });

  requestTags.fail(function (jqXHR, textStatus, errorThrown){
      // Log the error to the console
      console.error(
          "LOADTAGS | The following error occurred: "+
          textStatus, errorThrown
      );
  });
}

// MODEL LOAD
function loadModal(elm) {
  // Abort any pending request
  if (request) {
      request.abort();
  }

  itemId = $(elm).attr('data');

  // Fire off the request to /form.php
  request = $.ajax({
      url: "resources/php/viewServer.php",
      type: "get",
      data: {'id': itemId}
  });

  // Callback handler that will be called on success
  request.done(function (response, textStatus, jqXHR){

    try {
      response = JSON.parse(response);  
      console.log(response);
    }
    catch (e) {
      return false;
    }

    (response['data_variacio']===null)? date = 'Sense registre (S.R.)' : date = new Date(response['data_variacio']).getFullYear();
    (response['nexe']===null)? nexe = '' : nexe = response['nexe'];

    tipusVia = response['nom_composat'].split(' ')[0];
    linkMap = 'http://www.girona.cat/planol/?q='+response['nom_composat'];

    $('#view-modal').find('h3').text(tipusVia+' '+nexe);
    $('#view-modal').find('h2').text(response['nom_variant_curt']);
    $('#view-modal').find('#extend_map').attr('href',linkMap);
    $('#view-modal').find('#data_variacio').text(date);
    $('#view-modal').find('#nom_postal').text(response['nom_postal']);
    $('#view-modal').find('#tipus_car').text(response['tipus_car']);
    $('#view-modal').find('#nom_normalitzat').text(response['nom_normalitzat']);
    $('#view-modal').find('#codi_car').text(response['codi_car']);
    $('#view-modal').find('#actiu').text(response['actiu']);
    $('#view-modal').find('iframe').attr('src',linkMap);
  });

  // Callback handler that will be called on failure
  request.fail(function (jqXHR, textStatus, errorThrown){
      // Log the error to the console
      console.error(
          "LOADMODAL | The following error occurred: "+
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
    serializedData = 'q=&t=&a=a&pag=1';
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
      console.log(response);
    }
    catch (e) {
      return false;
    }
    
    $('#q').text(response['q']);

    if(response['num']==1) {
      $('#num').text(response['num']+" resultat");  
    } else {
      $('#num').text(response['num']+" resultats");
    }

    if(response['tag']) {
      $('.filterSymbol').text('+');
      $('#tag').text(filterArray[response['tag']]);
    } else {
      $('.filterSymbol').text('');
      $('#tag').text('');
    }

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
        html += '</p><p><a class="btn btn-default" href="#" role="button" ';
        html += 'data-toggle="modal" data-target="#view-modal" ';
        html += 'data="'+item['codi_car']+'" onClick="loadModal(this)">';
        html += 'Més detalls »</a></p>';
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
  $('.alphabet-filter span').find('input[type="radio"]').first().attr('checked', true);
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
  //e.stopPropagation();
});

function tagRemove(elm) {

  $('#queryinput').val('');
  $('#tag-popover').find('input[type="radio"]').attr('checked', false);
  $('#tag-popover').find('span').removeClass('active');

  $(elm).removeClass('active');

  //Reset pagination
  paginationReset();

  // Inmediate submit after click
  $('#search-form').submit();
}

function tagActivation(elm) {
  //Get all sibling tags
  siblings = $(elm).siblings();
  
  //Remove active
  siblings.removeClass('active');
  $('#filter-indicator').removeClass('active');

  //Uncheck functionality
  if($(elm).find('input[type="radio"]:checked').length) {
    //If the same tag is selected, then, uncheck it
    $(elm).removeClass('active');

    $(elm).find('input[type="radio"]').attr('checked', false);

    $('#queryinput').val('');
  } else {
    // Find previus checked item
    $(siblings).find('input').attr('checked', false);
    
    // Find new checkbox to be checked
    $(elm).find('input[type="radio"]').attr('checked', true);

    $('#queryinput').val($(elm).find('input').val());

    // Add active visuals to current cheked span
    $(elm).addClass('active');

    // Add active visuals to tag indicator
    $('#filter-indicator').addClass('active');
  }

  //Reset pagination
  paginationReset();

  // Inmediate submit after click
  $('#search-form').submit();
}

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
