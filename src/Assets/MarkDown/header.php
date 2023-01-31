# <?php echo $name ?>

__<?php echo $summary ?>

<?php
$parsedown = new \Parsedown();
echo $parsedown->text($description) ?>