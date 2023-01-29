<h1 class="header">
    <?php echo $name ?>
</h1>
<i>
    <?php echo $summary ?>
</i>
<br>
<?php
$parsedown = new \Parsedown();
echo $parsedown->text($description) ?>