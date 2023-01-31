<h2>Properties</h2>
<?php
$parsedown = new \Parsedown();
foreach ($properties as $property) {
    $summary = ($property->getDocBlock() !== null && $property->getDocBlock()->getSummary() !== '') ?$property->getDocBlock()->getSummary() . '<br>' : '';
    $description = ($property->getDocBlock() !== null && $property->getDocBlock()->getDescription() !== '') ? $parsedown->text($property->getDocBlock()->getDescription()) : '';
    $type = ($property->getDocBlock() !== null && $property->getDocBlock()->hasTag('var')) ? explode(' ', $property->getDocBlock()->getTagsByName('var')[0]->render(), 2)[1] : '';
    $separator = $type !== '' ? ': ' : '';
    ?>
    ### <a name="property:<?php echo $property->getName() ?>">$<?php echo $property->getName() ?> (<?php echo $property->getVisibility() ?>)</a>
    ```$<?php echo $property->getName() . $separator .  $type ?>```
    __<?php echo $summary ?>__
    <?php echo $description . ($type !== '' ? ('#### ' . $type) : '');
}