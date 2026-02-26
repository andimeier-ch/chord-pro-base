<h1><?= $page->title() ?></h1>

<p>Template: <code>set.php</code></p>

<ul>
  <?php foreach ($page->songs()->toPages() as $song): ?>
    <li><?= $song->title() ?></li>
  <?php endforeach ?>
</ul>
