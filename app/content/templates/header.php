<html>

<head>
  <title><?= (isset($config['title'])) ? $config['title'] : "Your Company" ?></title>
  <link rel="stylesheet" type="text/css" href=<?= '/app/content/styles/' . $config['css'] . '.css' ?>>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
</head>

<body>
  <header>
    <div class="hdrlogo"></div><?= ($config['menu'] == "main") ? '<div class="hdrmenu">
    <ul><li><a href="#">PARTIC</a></li>
    <li><a href="#">DRAW</a></li>
    <li><a href="#">COMPETITION</a></li></ul></div>' : '<div class="CONF_header"><span>' . $config['header'] . '</span></div>' ?>
  </header>