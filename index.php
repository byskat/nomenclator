<!DOCTYPE html>
<html>
<head>
  <title>Nomenclàtor</title>
  <meta http-equiv="Content-Type" content="text/html; charset=charset=UTF-8" />
  <meta name="description" content="" />
  <meta name="keywords" content="" />
  <meta name="robots" content="index,follow" />

  <link rel="stylesheet" type="text/css" href="resources/vendor/bootstrap-3.3.7-dist/css/bootstrap.min.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="resources/css/main-style.css">

</head>
<body>
  <nav class="navbar">
    <div class="container">
      <a class="navbar-brand" target="_blank" href="http://www.girona.cat">Ajuntament de Girona</a>
      <form class="navbar-search" itemscope="" method="get" action="" role="search">
        <span class="fa fa-search navbar-icon-search"></span>
        <input itemprop="query-input" type="search" name="s" placeholder="Cerca en el lloc ...">
        <input type="submit" value="Search">
      </form>
    </div>
  </nav>

  <div class="jumbotron">
    <div class="container">
      <h1>Nomenclàtor de Girona</h1>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
      tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
      quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
      consequat.</p>
      <p><a class="btn btn-primary btn-lg" href="+info.php" role="button">Comisió del Nomenclàtor »</a></p>
    </div>
  </div>

  <div class="container">
  <form id="search-form" itemscope="" method="get">
    <div class="row">     
      <div class="col-md-12">
        <div class="gazetteer-search">
          <span class="fa fa-search gazetteer-icon-search"></span>
          <div class="filter-container" style="position: relative;">
            <button id="filter-indicator" type="button" class="gazetter gazetteer-icon-indicator" onClick="tagRemove(this)"></button>
            <button id="filter-button" type="button" class="fa fa-sliders gazetteer-icon-filter" style="right: 8px;"></button>
            <div id="tag-popover" class="popover fade bottom in" role="tooltip" style="top: -14px; left: inherit; right: 0; display: hidden;">
              <div class="arrow" style="left: 91%;"></div>
              <h3 class="popover-title">Filtre</h3>
              <div class="popover-content"></div>
            </div>
          </div>
          <div class="tagsinput-container">
            <input id="queryinput" class="bootstrap-queryinput" type="text" name="q" placeholder="Cerca general de carrers ..." />
            <input id="tagsinput" type="hidden" name="t">
          </div>
          <input type="submit" value="Search">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="alphabet-filter">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <section class="abc-header">
          <div class="col-md-8 no-padding">
            <h1 class="major-letter"><span id="q">Tots</span> <span class="filterSymbol"></span> <span id="tag" class="filterTag"></span> <span class="letter-count"><span id="num">? resultats</span></span></h1>
          </div>
          <nav id="paginator" class="text-right col-md-4 no-padding">
            <ul class="pagination custom-pagination">
              <li class="page-item"><button id="first" class="page-link"><<</button></li>
              <li class="page-item"><button id="prev" class="page-link"><</button></li>
              <li class="page-item"><button id="stat" class="page-link">1 de 1</button></li>
              <li class="page-item"><button id="next" class="page-link">></button></li>
              <li class="page-item"><button id="last" val="1" class="page-link">>></button></li>
            </ul>
          </nav>
          <div style="clear: both;"></div>
        </section>
      </div>
    </div>

    <div id="resultsContainer" class="row abc-results"></div>

    <div id="view-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content view">
          <div class="modal-header">
            <h3></h3><h2></h2>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i></button>
            <a id="extend_map" type="button" class="location-map" target="_blank" href=""><i class="fa fa-map-marker" aria-hidden="true"></i></a>
            <a type="button" class="extend-map" target="_blank" href="http://www.girona.cat/planol/"><i class="fa fa-map-o" aria-hidden="true"></i></a>
          </div>
          <div class="modal-body">
            <div class="col-md-4 no-padding">
              <p><b>Any de modificació: </b><span id="data_variacio"></span></p>
              <p><b>Nom postal: </b><span id="nom_postal"></span></p>
            </div>
            <div class="col-md-4 no-padding">
              <p><b>Tipus de carrer: </b><span id="tipus_car"></span></p>
              <p><b>Nom normalitzat: </b><span id="nom_normalitzat"></span></p>
            </div>
            <div class="col-md-4 no-padding">
              <p><b>Codi: </b><span id="codi_car"></span></p>
              <p><b>Actiu: </b><span id="actiu"></span></p>
            </div>
            <div class="col-md-12 no-padding">
              <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod.</p>
            </div>
            <div class="iframe-container">
              <iframe src=""></iframe>
            </div>
          </div>
        </div>
      </div>
    </div>
    <input id="pag" type="hidden" readonly="readonly" name="pag" value="1">
  </form>
  </div> <!-- /container -->
  <footer class="container">
    <div class="col-md-12 no-padding">
      <hr>
      <p>© 2017 Ajuntament de Girona.</p>
    </div>
  </footer>

  <script type="text/javascript" src="resources/vendor/jquery-3.1.1.min.js"></script>
  <script type="text/javascript" src="resources/vendor/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>

  <!-- Custom JS -->
  <script src="resources/js/main-script.js"></script>

</body>
</html>