$(function () {

  // Variable to hold requests
  var request;

  //Draw the content of the filter popover
  tagDraw("filtre.json","#tag-popover .popover-content");
  
  // Load data for first time
  updateGazetteer();

  abcGeneration('.alphabet-filter');

  //HANDLERS

  $('#filter-indicator').on('click', function(e) {
    event.preventDefault();
    tagRemove(this);
  });

  $('#filter-button').on('click', function(e) {
    $(this).toggleClass('active');
    $('#tag-popover').stop().slideToggle("500", "linear", "true");
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

  /* Request handlers */

  // Bind to the submit event to form
  $("#search-form").submit(function(event){
    // Prevent default posting of form
    event.preventDefault();
    updateGazetteer(this);
  });

  // Handles more info button that loads specific info
  $('body').on('click', '.modalButton', function() {
    loadModal(this);
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
    pagination('reset');

    // Inmediate submit after click
    $('#search-form').submit();
  });

  //Pagination handlers
  $("#first").click(function(e) {
    pagination('first');
  });
  $("#last").click(function(e) {
    pagination('last');
  });

  $("#next").click(function(e) {
    pagination('add');
  });
  $("#prev").click(function(e) {
    pagination('sub');
  });

  // Intro in searchbar, if contains something reset abc and pagination.
  $('#queryinput').keypress(function (e) {
    if (e.which == 13 && $(this).val() != '') {
      e.preventDefault();

      // Unset alphabet selection
      $('.alphabet-filter .active').removeClass('active');
      $('.alphabet-filter input:checked').attr('checked', false);
      
      $('#search-form').submit();
      //updateGazetteer(this);
    }
  });

});