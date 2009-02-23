<?php
 $dom = new DOMDocument();
 $dom->load('http://www.zoopy.com/search/rss/hoi');
 $domItems = $dom->getElementsByTagName('item');
 $items = array();
 for ($i = 0; $i < $domItems->length; $i++) {
  $domItem = $domItems->item($i);
  $item = array();
  $item['title'] = htmlspecialchars($domItem->getElementsByTagName('title')->item(0)->textContent);
  $item['link'] = htmlspecialchars($domItem->getElementsByTagName('link')->item(0)->textContent);
  $item['image'] = $domItem->getElementsByTagName('content')->item(0)->getAttribute('url');
  preg_match_all('#/([0-9]+)/thumb#i', $item['image'], $matches);
  $item['image'] = 'http://www.zoopy.com/data/media/' . $matches[1][0] . '/thumb-150x150f.jpg';
  $items[] = $item;
 }
?>
<!DOCTYPE HTML>
<html lang="en">
 <head>
  <title>Charl van Niekerk: Zoopy</title>
 </head>
 <body>
  <h1>Zoopy</h1>
  <ul>
<?php foreach ($items as $item) : ?>
   <li><a href="<?php echo $item['link']; ?>"><img src="<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>"></a></li>
<?php endforeach; ?>
  </ul>
 </body>
</html>
