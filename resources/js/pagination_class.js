
function Pagination(pg) {

  //First, set the pagination main id container
  var this.pg = pg;

  //After that, get the sub elements
  var first  = this.pg + ' .pg_first';
  var prev   = this.pg + ' .pg_prev';
  var stat   = this.pg + ' .pg_stat';
  var next   = this.pg + ' .pg_next';
  var last   = this.pg + ' .pg_last';
  var holder = this.pg + ' #pg_data';

  //Also, get the hidden data
  var data = $(holder).data();


  //Disable parts of the pagination in cases (style & links)
  //Total disabler, 0-1 pages
  pgDisabler(pg, data['limit'] <= 1);

  //Partial disabler, backward
  pgDisabler(next+','+last, data['current'] == data['limit']);

  //Partial disabler, forward
  pgDisabler(prev+','+first, data['current'] == 1);

  //Init

  pgRefresh();
  //Handler
  $(pg).on('click', function(e) {

    pgRefresh();
  });

}

//Recieves the element to disable and bool to disable/enable
Pagination.prototype.pgDisabler = function(elm, cond) {
  if(cond) {
    $(elm).addClass('disabled');
    $(elm).find('button').attr('disabled', true);
  } else {
    $(elm).removeClass('disabled');
    $(elm).find('button').attr('disabled', false);
  }
};

Pagination.prototype.pgRefresh = function() {
  data = $(holder).data();
  console.log(data);
  $(stat).find('a').text(data['current']+' de '+data['limit']);
};

Pagination.prototype.pgAction = function(action) {
  switch (action) {
    case 'add':
      var val = +$("#pag").val() + 1;
      if(val<=$("#last").val()) $("#pag").val(val);
      break;
    case 'sub':
      var val = +$("#pag").val() - 1;
      if(val>0) $("#pag").val(val);
      break;
    case 'first': case 'reset':
      $("#pag").val(1);
      break;
    case 'last':
      $("#pag").val($("#last").val());
      break;
    default:
      console.log("pagination: undefined action");
  }
};

