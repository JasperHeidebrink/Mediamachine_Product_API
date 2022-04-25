<?php foreach ($activityList as $activity) : ?>
    <p>
        <b><?= $activity->getTitle() ?></b>
        <?= $activity->getDateActive() ?><br>
    </p>
<?php endforeach; ?>
