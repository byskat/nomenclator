//TAG popover
$(function () {

  //loadTags($('#filter-button'));
  
  $('#filter-button').on('click', function () {
    $(this).toggleClass('active');
    $('#tag-popover').stop().slideToggle("500", "linear", "true");
  });
  
});

//Dissmisable filter popover
$(window).click(function() {
  if($('#tag-popover').is(':visible')) {
    $('#filter-button').trigger('click');
  }
});

$('#tag-popover, #filter-button').click(function(event){
    event.stopPropagation();
});

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
    serializedData = 'q=&t=&a=&pag=1';
  } else {
    // Get inputs from form
    var $form = $(form);

    // Let's select and cache all the fields
    var $inputs = $form.find("input#queryinput, input#tagsinput, input#pag");

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
      responseTag = response['tag'];
      t = [];

      $.each(responseTag, function(key, value) {
        filterList.forEach(function(a) {
          if(a['valor']==value) t.push(a['nom']);
        });
      });

      $('.filterSymbol').text('+');
      $('#tag').text(t.join(', '));
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
$("#queryinput").focus(function() {
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

abcGeneration('.alphabet-filter');

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

function tagRemove(elm) {

  $('#tagsinput').val('');
  $('#tag-popover').find('input[type="radio"]').attr('checked', false);
  $('#tag-popover').find('span').removeClass('active');

  $(elm).removeClass('active');

  //Reset pagination
  paginationReset();

  // Inmediate submit after click
  $('#search-form').submit();
}

// Generation of abc filter
function abcGeneration(elm) {
  abc = ['Tots','a','b','c','d','e','f','g','h',
         'i','j','k','l','m','n','o','p','q','r',
         's','t','u','v','w','x','y','z'];
  html = '';
  for (var i = 0; i < abc.length; i++) {
    html += '<span><input type="radio" name="a" value="';
    html += abc[i] + '">';
    html += abc[i]+'</span>\n';
  }
  $(elm).append(html);
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

$('#queryinput').keypress(function (e) {
  if (e.which == 13) {
    paginationReset();
  }
});

// New tag filler

$.getJSON("filtre.json", function(filter) {
  html = '';
  filterList = [];

  filter.forEach(function(element) {
    
    element['filtres'].forEach(function(a) {
      filterList.push(a);
    });

    html += '<div>';
    html += '<h4 class="filter-title">'+element['nom']+'</h4>'; 

    element['filtres'].forEach(function(fields) {
      html += '<span class="tag" onclick="tagActivation2(this)"><input type="radio" name="'+element['camp']+'" value="'+fields['valor']+'">'+fields['nom']+'</span>';
    });

    html += '</div>';
    
  });

  $('#tag-popover .popover-content').append(html);
});

function tagActivation2 (elm) {
  //Get all sibling tags
  siblings = $(elm).siblings();
  
  //Remove active
  siblings.removeClass('active');
  
  //Uncheck functionality
  if($(elm).find('input[type="radio"]:checked').length) {
    //If the same tag is selected, then, uncheck it
    $(elm).removeClass('active');
    $(elm).find('input[type="radio"]').attr('checked', false);

  } else {
    // Find previus checked item
    $(siblings).find('input').attr('checked', false);
    
    // Find new checkbox to be checked
    $(elm).find('input[type="radio"]').attr('checked', true);
  
    // Add active visuals to current cheked span
    $(elm).addClass('active');

    // Add active visuals to tag indicator
    $('#filter-indicator').addClass('active');
  }

  // Fills in a hidden input the names of the filled checkboxes
  listTags = [];

  $("#tag-popover input:checked").each(function(index) {
    listTags.push($(this).attr('name'));
  });
  $('#tagsinput').val(listTags);

  if(listTags==0) $('#filter-indicator').removeClass('active');

  //Reset pagination
  paginationReset();

  // Inmediate submit after click
  $('#search-form').submit();
}