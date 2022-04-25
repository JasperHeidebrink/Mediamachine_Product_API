<?php foreach ($exhibitorList as $exhibitors) : ?>
    <p>
        <b><?= $exhibitors->getTitle() ?></b>
        <?= $exhibitors->getUrl() ?><br>
    </p>
<?php endforeach; ?>
