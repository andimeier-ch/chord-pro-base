<h1><?= $page->title() ?></h1>

<p>Template: <code>sets.php</code></p>

<ul>
  <?php foreach ($page->children() as $set): ?>
    <li><?= $set->title() ?></li>
  <?php endforeach ?>
</ul>
