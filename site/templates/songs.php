<h1><?= $page->title() ?></h1>

<p>Template: <code>songs.php</code></p>

<ul>
  <?php foreach ($page->children() as $song): ?>
    <li><?= $song->title() ?></li>
  <?php endforeach ?>
</ul>
