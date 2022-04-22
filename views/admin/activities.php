<div class="wrap">
    <h2>Overview of activities:</h2>

    <div class="card" style="min-width:50%;">
        <?php foreach ($activityList as $activity) : ?>
            <h3><?= $activity->getTitle()?></h3>
            <?= $activity->getDateActive()?><br>
        <?php endforeach; ?>
    </div>
</div>
