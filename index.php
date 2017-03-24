<!DOCTYPE html>
<html>
<head>
  <title>Nomenclàtor</title>
  <meta http-equiv="Content-Type" content="text/html; charset=charset=UTF-8" />
  <meta name="description" content="" />
  <meta name="keywords" content="" />
  <meta name="robots" content="index,follow" />
  
  <!--<script type="text/javascript" src="resources/vendor/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
  <script type="text/javascript" src="resources/vendor/typeahead.bundle.js"></script>
  <link rel="stylesheet" type="text/css" href="resources/vendor/bootstrap-tagsinput/bootstrap-tagsinput.css">-->

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

    <div class="row">     
      <div class="col-md-12">

        <form id="search-form" class="gazetteer-search" itemscope="" method="get" action="" role="search">
          <span class="fa fa-search gazetteer-icon-search"></span>
          <button id="filter-button" type="button" class="fa fa-sliders gazetteer-icon-filter" data-toggle="popover"></button>
          <div class="tagsinput-container">
            <input id="tagsinput" class="bootstrap-tagsinput" type="text" name="t" data-role="tagsinput" placeholder="Cerca general de carrers ..." />
            <input id="queryinput" type="text" name="q" style="display: none;">
          </div>
          <input type="submit" value="Search">
        </form>

      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <form id="abc-form" class="alphabet-filter" itemscope="" method="get" action="" role="search">
          <?php
            $abc = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','Tots'];
            $arr_length = count($abc);
            $temp = '';

            if (isset($_GET['a'])) $temp = $_GET['a'];

            for($i=0;$i<$arr_length;$i++) {
              if ($temp==$abc[$i]) {
                echo "<span class='active'><input type='radio' name='a' value='".$abc[$i]."' cheked='cheked'>".$abc[$i]."</span>"; 
              } else {
                echo "<span><input type='radio' name='a' value='".$abc[$i]."'>".$abc[$i]."</span>"; 
              }
            }
          ?>
        </form>
      </div>
    </div>

    <?php require_once("resources/php/includes/results.php"); ?>

    <hr>

    <footer>
      <p>© 2017 Ajuntament de Girona.</p>
    </footer>
  </div> <!-- /container -->

  <script type="text/javascript" src="resources/vendor/jquery-3.1.1.min.js"></script>
  <script type="text/javascript" src="resources/vendor/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>

  <!-- Custom JS -->
  <script src="resources/js/main-script.js"></script>

</body>
</html>