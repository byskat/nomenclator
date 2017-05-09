<!DOCTYPE html>
<html lang="ca">
<head>
  <title>Nomenclàtor</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="description" content="" />
  <meta name="keywords" content="" />
  <meta name="robots" content="index,follow" />

  <link rel="stylesheet" type="text/css" href="resources/vendor/bootstrap-3.3.7-dist/css/bootstrap.min.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="resources/css/main-style.css">

</head>

<?php 
  function getLegalFiles($path) {
    $phpfiles = glob($path . "*.pdf");
    usort($phpfiles, create_function('$a,$b', 'return filemtime($a) - filemtime($b);'));

    foreach($phpfiles as $phpfiles) {
      //$phpfiles = utf8_encode($phpfiles);
      echo '<a class="btn btn-default" target="_blank" href="'.$phpfiles.'"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> '.basename($phpfiles,".pdf").'</a>';
    }
  }
?>

<body>
  <nav class="navbar">
    <div class="container">
      <a class="navbar-brand" href="http://www.girona.cat" target="_blank">
      <img src="resources/img/logo.png">
      Ajuntament de Girona</a>
      <div class="navbar-aside">
        <a class="navbar-button" href="http://www.girona.cat/planol" target="_blank">Mapa de Girona</a>
      </div>
    </div>
  </nav>

  <!-- Main jumbotron for a primary marketing message or call to action -->
  <div class="jumbotron more-info">
    <div class="container">
      <h1><span class="previous">Nomenclàtor de Girona</span><span class="style-slash">/</span>Comisió del Nomenclàtor</h1>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
      tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
      quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
      consequat.</p>
      <p><a class="btn btn-primary btn-lg" href="/nomenclator" role="button">« Tornar al Nomenclàtor</a></p>
    </div>
  </div>

  <div class="container">
    <div class="row">
    <div class="col-md-8">
      <h3>Procediment per sol·licitar un nom de vial</h3>
      <hr>
      <p>Presentar la sol·licitud per registre virtual o presencial, degudament argumentada:</p>

      <a class="btn btn-default" target="_blank" href="https://seu.girona.cat/portal/girona_ca/serveis/e-registre/AaZ/1582.html">Enllaç a la seu electrònica <i class="fa fa-external-link" aria-hidden="true"></i></a>
      <br>
      <br>

      <h4>Article 10. Procediment de denominació</h4>

      <p><b>Inici de l’expedient</b>: L’expedient s’iniciarà per part del serve
      i corresponent dins la Regidoria que sigui competent en la matèria de 
      Nomenclàtor, a partir d’una proposta de denominació de via pública, que 
      podrà ser:</p>
       
      <ul>
        <li>
          <p><b>D’ofici</b>: Comissió del Nomenclàtor, grups municipals, tècnics 
          municipals, etc.</p>
        </li>
        <li>
          <p><b>A instància de part</b>: Associacions o entitats cíviques 
          inscrites en el registre municipal d’entitats, col·lectius de ciutadans 
          i, en general, qualsevol persona amb relació de veïnatge al municipi de
          Girona. Només seran preses en consideració les propostes formalitzades
          d’acord amb el reglament regulador de les institucions de participança
          i de la gestió de conflictes de l’Ajuntament de Girona.</p>
        </li>
      </ul>

      <p><b>Informe de la Comissió</b>: d’acord amb els criteris establerts en 
      aquest reglament, la Comissió Tècnica del Nomenclàtor valorarà inicialment 
      les propostes que presentarà a la Comissió del Nomenclàtor com a part 
      integrant de l’expedient.</p>

      <p>La Comissió del Nomenclàtor, un cop estudiat l’expedient, emetrà el 
      seu informe, que pot ser favorable o desfavorable, justificant-ne els 
      motius i que presentarà a la Junta de Govern Local per a la seva resolució
      i, si escau, la posterior elevació al Ple municipal. Aquest informe 
      contindrà necessàriament la denominació del carrer/espai i el text 
      informatiu que ha d’acompanyar el topònim a la retolació viària, si és el
      cas.</p>

      <p><b>Resolució de l’expedient</b>: El servei responsable de la tramitació
      de l’expedient administratiu notificarà la resolució de la Junta de Govern 
      Local a les àrees i serveis tècnics municipals per a la correcta 
      identificació dels espais, així com a les institucions que es considerin 
      oportunes.</p>

      <p>En cas d’una resolució desfavorable, l’expedient s’arxivarà, un cop 
      notificada a les parts interessades que hagin comparegut a l’expedient.</p>
    </div>
    <div class="col-md-4 documents">
      <h3>Documents</h3>
      <hr>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
      tempor incididunt ut labore et dolore magna aliqua.</p>
      <p>
        <?php getLegalFiles("documents/reglament/"); ?>
      </p>
      <hr>
      <h3>Actes</h3>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
      tempor incididunt ut labore et dolore magna aliqua.</p>
      <p>
        <?php getLegalFiles("documents/actes/"); ?>
      </p>
    </div>
    </div>
  </div> <!-- /container -->
  <footer class="container">
    <div class="col-md-12 no-padding">
      <hr>
      <p>© 2017 Ajuntament de Girona.</p>
    </div>
  </footer>

  <script type="text/javascript" src="resources/vendor/jquery-3.1.1.min.js"></script>
  <script type="text/javascript" src="resources/vendor/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>

</body>
</html>