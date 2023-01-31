## Summary

<?php if (count($methods['public']) > 0) {?>
### Public methods

<?php
foreach ($methods['public'] as $method) {?>* <?php echo $method; echo PHP_EOL; }
}?>

<?php if (count($methods['protected']) > 0) {?>
### Protected methods

<?php
foreach ($methods['protected'] as $method) {?>* <?php echo $method; echo PHP_EOL; }
}?>

<?php if (count($methods['private']) > 0) {?>
### Private methods

<?php
foreach ($methods['private'] as $method) {?>* <?php echo $method; echo PHP_EOL; }
}?>