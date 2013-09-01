<!doctype html>
<html>
 <head>
  <title><?=$this->__info['title']?></title>
  <link rel="stylesheet" type="text/css" href="/css/style.css">
  <!--[if lt IE 8]><link rel="stylesheet" type="text/css" href="/css/style-ie.css"><![endif]-->
  <?php foreach ($this->__assets['css'] as $css) { ?>
  <link rel="stylesheet" type="text/css" href="/css/<?=$css?>">
  <?php } ?>
  <?php foreach ($this->__assets['js'] as $js) { ?>
  <script type="text/javascript" src="/js/<?=$js?>"></script>
  <?php } ?>
 </head>
 <body>
  <div class="container">
   <?=$view_output?>
  </div>
 </body>
</html>
