<!doctype html>
<html lang="nb" ng-app="bibcraft">
<head>
  <meta charset="utf-8" />
  <title>BIBCRAFT - Selvbetjening</title>
  <base href="/selfservice">
  <link href='//fonts.googleapis.com/css?family=Noto+Sans|Share+Tech+Mono' rel='stylesheet' type='text/css'>
  <link href='//fonts.googleapis.com/css?family=Noto+Sans' rel='stylesheet' type='text/css'>

  <link href="/vendor/bootstrap/css/bootstrap.min.css" media="screen" type="text/css" rel="stylesheet" />
  <link href="/app/selfservice/selfservice.css" type="text/css" rel="stylesheet" />
</head>
<body ng-cloak ng-controller="AppController">

  <div class="centralnotice" ng-show="network_error"><div style="padding:20px;">
    Nettverksforbindelsen er ustabil. Bibcraft fortsetter å prøve å få kontakt
  </div></div>

  <h2 style="padding-left:20px;">BibCraft : Realfagsbibliotekets eksperimentelle og mobile utlånssystem</h2>

  <div class="view" ng-view></div>

  <footer ng-controller="AppController" ng-click="toggleLog()">
    <div class="container">
      <p class="muted credit">
        &copy; Universitetsbiblioteket i Oslo 2013
      </p>
    </div>
  </footer>

  <audio>
    <source src="assets/robot-blip.mp3" type="audio/mpeg" />
  </audio>
  <audio>
    <source src="assets/blipp2.mp3" type="audio/mpeg" />
  </audio>
  <audio id="sfx-click">
    <source src="assets/rapid_beep3.mp3" type="audio/mpeg" />
  </audio>
  <audio id="sfx-error">
    <source src="assets/error.mp3" type="audio/mpeg" />
  </audio>

  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script src="/vendor/bootstrap/js/bootstrap.min.js"></script>
  <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">

  <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.0rc1/angular.min.js"></script>
  <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.0rc1/angular-route.min.js"></script>
  <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.0rc1/angular-touch.min.js"></script>
  <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.0rc1/angular-animate.min.js"></script>

  <script src="/app/app.js"></script>
  <script src="/app/collection/collection.js"></script>
  <script src="/app/selfservice/services.js"></script>
  <script src="/app/selfservice/index/index.js"></script>
  <script src="/app/selfservice/cart/cart.js"></script>
  <script src="/app/selfservice/checkout/checkout.js"></script>
  <script src="/app/selfservice/user/user.js"></script>

  <!--
  <script type='text/javascript'>

      $('body').on('dragstart', function (e) {
        event.preventDefault();
      });

      $('body').on('click touchend', function (e) {
        $('#sfx-click')[0].play();
      });

    });

  </script>
  -->

</body>
</html>
