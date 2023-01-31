<h2>Methods</h2>
<?php
$parsedown = new \Parsedown();
foreach ($methods as $method) {
    $argumentNames = array();
    foreach ($method->getArguments() as $argument) {
        $argumentNames[] = $argument->getType() . ' $' . $argument->getName();
    }
    $summary = ($method->getDocBlock() !== null && $method->getDocBlock()->getSummary() !== '') ? $method->getDocBlock()->getSummary() . PHP_EOL : '';
    $description = ($method->getDocBlock() !== null && $method->getDocBlock()->getDescription() !== '') ? $parsedown->text($method->getDocBlock()->getDescription()) : '';
    ?>

### <?php echo trim($method->getName()) ?> (<?php echo $method->getVisibility() ?>)
```<?php echo trim($method->getName()) ?>(<?php echo implode(', ', $argumentNames) ?>)<?php echo ($method->getReturnType() != 'mixed' ? ': ' . $method->getReturnType() : '') ?>```
  <?php echo PHP_EOL ?> 
__<?php echo trim($summary) ?>__
<?php echo $description;
if (!empty($method->getArguments())) {
    ?>
#### Parameters
<?php
}
foreach ($method->getArguments() as $argument) {
    echo $argument->getType() ?> _<?php echo $argument->getName() ?>_
    <?php
}
if ($method->getDocBlock() !== null) {
    if (!empty($method->getDocBlock()->getTagsByName('throws'))) {
        ?>
#### Throws
<?php
    }
    foreach ($method->getDocBlock()->getTagsByName('throws') as $throwsTag) {
        echo str_replace('@throws', '', $throwsTag->render()) . PHP_EOL;
    }
    echo PHP_EOL;
}
?>
<?php
}