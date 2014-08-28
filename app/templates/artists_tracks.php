<h1>Artists Tracks</h1>

<?php if ( sizeof($tracks) > 0 ): ?>
    <hr />
<?php endif ?>

<?php foreach($tracks as $track): ?>
    <div>
        <p>Title:&nbsp;<?= $track->title; ?></p>
        <p>Duration:&nbsp;<?= round(($track->duration) / 1000); ?> seconds</p>
        <p>Permalink:&nbsp;<?= $track->permalink_url; ?></p>
        <p>Original format:&nbsp;<?= $track->original_format; ?></p>
        <hr />
    </div>
<?php endforeach ?>