<?php
  // Variables
  $node   = getenv('NODE_NAME');
  $pod    = getenv('HOSTNAME');
  
  // Couleur stable par node
  $hash   = crc32($node);
  $h      = $hash % 360;
  $color  = "hsl($h,70%,45%)";
  
  // Gestion compteur local (par pod)
  // Chaque pod garde son compteur dans un fichier temporaire
  $counter_file = "/tmp/counter.txt";
  if (!file_exists($counter_file))
    file_put_contents($counter_file, "0");
  
  $count = (int)file_get_contents($counter_file);
  $count++;
  
  file_put_contents($counter_file, $count);

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Load Balancing Dashboard</title>
    <meta http-equiv="refresh" content="2">
    <link rel="stylesheet" type="text/css" href="cloud.css">
    <style>
      .bar{
        height:20px;
        border-radius:10px;
        background: <?php echo $color ?>;
        width:<?php echo min($count,100) ?>%;
        transition: width 0.1s;
      }
    </style>
  </head>
  <body>
    <div class="header">Load Balancer Mini Dashboard</div>
    <div class="container">
      <div class="card">
          <div class="node" style="background: <?php echo $color ?>">
              Node : <?php echo $node ?>
          </div>
          <div class="info">
            <table>
             <tr><th>Pod</th><td><?php echo $pod ?></td></tr>
             <tr><th>Requêtes</th><td><?php echo $count ?></td></tr>
            </table>
          </div>
          <div class="bar"></div>
      </div>
    </div>
    <div class="footer">Auto refresh toutes les 2s</div>
  </body>
</html>
