<h2>Properties</h2>

<?php
foreach ($properties as $property) {
    $summary = ($property->getDocBlock() !== null && $property->getDocBlock()->getSummary() !== '') ?$property->getDocBlock()->getSummary() . PHP_EOL : '';
    $description = ($property->getDocBlock() !== null && $property->getDocBlock()->getDescription() !== '') ? $property->getDocBlock()->getDescription() : '';
    $type = ($property->getDocBlock() !== null && $property->getDocBlock()->hasTag('var')) ? explode(' ', $property->getDocBlock()->getTagsByName('var')[0]->render(), 2)[1] : '';
    $separator = $type !== '' ? ': ' : '';
    ?>
### $<?php echo $property->getName() ?> (<?php echo $property->getVisibility() ?>)
```$<?php echo $property->getName() . $separator .  $type ?>```

__<?php echo trim($summary) ?>__

<?php echo $description;

}