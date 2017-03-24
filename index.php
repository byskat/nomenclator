<!DOCTYPE html>
<html>
<head>
  <title>Nomenclàtor</title>
  <meta http-equiv="Content-Type" content="text/html; charset=charset=UTF-8" />
  <meta name="description" content="" />
  <meta name="keywords" content="" />
  <meta name="robots" content="index,follow" />
  
  <script type="text/javascript" src="resources/vendor/jquery-3.1.1.min.js"></script>
  <script type="text/javascript" src="resources/vendor/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="resources/vendor/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
  <script type="text/javascript" src="resources/vendor/typeahead.bundle.js"></script>

  <link rel="stylesheet" type="text/css" href="resources/vendor/bootstrap-3.3.7-dist/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="resources/vendor/bootstrap-tagsinput/bootstrap-tagsinput.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/main-style.css">

  <!-- Custom JS -->
  <script href="js/main-script.js"></script>
</head>
<body>
  <nav class="navbar">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Ajuntament de Girona</a>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        
        <form class="navbar-search navbar-form navbar-right" itemscope="" method="get" action="" role="search">
          <meta itemprop="target" content="/?s={s}">
          <input itemprop="query-input" type="search" name="s" placeholder="Cerca en el lloc ...">
          <input type="submit" value="Search">
        </form>
      </div><!--/.navbar-collapse -->
    </div>
  </nav>

  <!-- Main jumbotron for a primary marketing message or call to action -->
  <div class="jumbotron">
    <div class="container">
      <h1>Nomenclàtor de Girona</h1>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
      tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
      quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
      consequat.</p>
      <p><a class="btn btn-primary btn-lg" href="#" role="button">Més informació »</a></p>
    </div>
  </div>

  <div class="container">

    <div class="row">     
      <div class="col-md-12">

        <form class="gazetteer-search" itemscope="" method="get" action="" role="search">
          <span class="fa fa-search gazetteer-icon-search"></span>
          <button id="filter-button" type="button" class="fa fa-sliders gazetteer-icon-filter" data-toggle="popover"></button>
          <div class="tagsinput-main">
            <input id="tagsinput" type="text" name="t" data-role="tagsinput" placeholder="Cerca general de carrers ..." />
            <input id="queryinput" type="text" name="q" style="display: none;">
          </div>
          <input type="submit" value="Search">
        </form>
    
        <script type="text/javascript">

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

        </script>

      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="alphabet-filter">
          <span class="active">a</span>
          <span>b</span>
          <span>c</span>
          <span>d</span>
          <span>e</span>
          <span>f</span>
          <span>g</span>
          <span>h</span>
          <span>i</span>
          <span>j</span>
          <span>k</span>
          <span>l</span>
          <span>m</span>
          <span>n</span>
          <span>s</span>
          <span>t</span>
          <span>u</span>
          <span>c</span>
          <span>w</span>
          <span>x</span>
          <span>y</span>
          <span>z</span>
          <span>Tots</span>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        
        <section id="A">
          <h1 class="major-letter">A <span class="letter-count">23 resultats </span></h1>
          <table>
            
          </table>
        </section>

      </div>
    </div>

    <!-- Example row of columns -->
    <div class="row abc-results">
      <div class="col-md-4">
        <h3>Carrer de l'</h3><h2>Abat Escarré</h2>
        <p><span>2015</span>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. </p>
        <p><a class="btn btn-default" href="#" role="button">Més detalls »</a></p>
      </div>
      <div class="col-md-4">
        <h3>Carrer dels</h3><h2>Abeuradors</h2>
        <p><span>2015</span>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh. </p>
        <p><a class="btn btn-default" href="#" role="button">Més detalls »</a></p>
      </div>
      <div class="col-md-4">
        <h3>Carrer de l'</h3><h2>Acàcia</h2>
        <p><span>2015</span>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
        <p><a class="btn btn-default" href="#" role="button">Més detalls »</a></p>
      </div>
      <div class="col-md-4">
        <h3>Carrer d'</h3><h2>Enric Adroher i Pascual</h2>
        <p><span>2015</span>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo. </p>
        <p><a class="btn btn-default" href="#" role="button">Més detalls »</a></p>
      </div>
      <div class="col-md-4">
        <h3>Carrer de les</h3><h2>Agudes</h2>
        <p><span>2015</span>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
        <p><a class="btn btn-default" href="#" role="button">Més detalls »</a></p>
      </div>
    </div>

    <hr>

    <footer>
      <p>© 2017 Ajuntament de Girona.</p>
    </footer>
  </div> <!-- /container -->

  <script type="text/javascript">
    
    $(function () {

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

  </script>

</body>
</html>

