<?php
$manifestJson = file_get_contents('https://kainotomo.github.io/phmoney_assets/dist/manifest.json');
$manifest = json_decode($manifestJson, true);
?>

  <head>
    <!-- Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

    <!-- Google Charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
    </script>

    <!-- Google Captcha -->
    <script src="https://www.google.com/recaptcha/api.js"></script>

    <script type="module" crossorigin src="https://kainotomo.github.io/phmoney_assets/dist/{{ $manifest['src/main.ts']['file'] }}"></script>
    <link rel="stylesheet" href="https://kainotomo.github.io/phmoney_assets/dist/{{ $manifest['src/main.ts']['css'][0] }}" />
  </head>
  <body>
    <div id="app_gnucash_component"></div>
  </body>
</html>
