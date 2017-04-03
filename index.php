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
  <form id="search-form" itemscope="" method="get">
    <div class="row">     
      <div class="col-md-12">
        <div class="gazetteer-search">
          <span class="fa fa-search gazetteer-icon-search"></span>
          <div class="filter-container" style="position: relative;">
            <button id="filter-button" type="button" class="fa fa-sliders gazetteer-icon-filter" style="right: 8px;"></button>

            <div id="tag-popover" class="popover fade bottom in" role="tooltip" style="top: -14px; left: inherit; right: 0; display: hidden;">
              <div class="arrow" style="left: 91%;"></div>
              <h3 class="popover-title">Filtre</h3>
              <div class="popover-content"></div>
            </div>
          </div>

          <div class="tagsinput-container">
            <input id="tagsinput" class="bootstrap-tagsinput" type="text" name="q" data-role="tagsinput" placeholder="Cerca general de carrers ..." />
            <input id="queryinput" type="hidden" name="t">
          </div>
          <input type="submit" value="Search">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="alphabet-filter">
          <?php
            $abc = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','Tots'];
            $arr_length = count($abc);
            $temp = '';

            if (isset($_GET['a'])) $temp = $_GET['a'];

            for($i=0;$i<$arr_length;$i++) {
              if ($temp==$abc[$i]) {
                echo "<span class='active'><input type='radio' name='a' value='".$abc[$i]."' cheked='cheked'>".$abc[$i]."</span>\n"; 
              } else {
                echo "<span><input type='radio' name='a' value='".$abc[$i]."'>".$abc[$i]."</span>\n"; 
              }
            }
          ?>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <section class="abc-header">
          <div class="col-md-6 no-padding">
            <h1 class="major-letter"><span id="q">A</span> <span class="filterSymbol"></span> <span id="tag"></span> <span class="letter-count"><span id="num">?</span> resultats </span></h1>
          </div>

          <nav id="paginator" class="text-right col-md-6 no-padding">
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

    <nav id="test" class="text-center col-md-12 no-padding">
      <ul class="pagination custom-pagination">
        <li class="page-item"><button class="first page-link"><<</button></li>
        <li class="page-item"><button class="prev page-link"><</button></li>
        <li class="page-item"><button class="stat page-link">1 de 1</button></li>
        <li class="page-item"><button class="next page-link">></button></li>
        <li class="page-item"><button class="last page-link">>></button></li>
      </ul>
    </nav>

    <hr>

    <footer>
      <p>© 2017 Ajuntament de Girona.</p>
    </footer>
    <input id="pag" type="hidden" readonly="readonly" name="pag" value="1">
  </form>
  </div> <!-- /container -->

  <script type="text/javascript" src="resources/vendor/jquery-3.1.1.min.js"></script>
  <script type="text/javascript" src="resources/vendor/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>

  <!-- Custom JS -->
  <script src="resources/js/main-script.js"></script>

</body>
</html>