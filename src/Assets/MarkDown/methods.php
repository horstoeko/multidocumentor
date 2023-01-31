<h2>Methods</h2>
<?php
$parsedown = new \Parsedown();
foreach ($methods as $method) {
    $argumentNames = array();
    foreach ($method->getArguments() as $argument) {
        $argumentNames[] = $argument->getType() . ' $' . $argument->getName();
    }
    $summary = ($method->getDocBlock() !== null && $method->getDocBlock()->getSummary() !== '') ? $method->getDocBlock()->getSummary() . '<br>' : '';
    $description = ($method->getDocBlock() !== null && $method->getDocBlock()->getDescription() !== '') ? $parsedown->text($method->getDocBlock()->getDescription()) : '';
    ?>
    
    ### <a name="method:<?php echo $method->getName() ?>"><?php echo $method->getName() ?> (<?php echo $method->getVisibility() ?>)</a
    ```<?php echo $method->getName() ?>(<?php echo implode(', ', $argumentNames) ?>)<?php echo ($method->getReturnType() != 'mixed' ? ': ' . $method->getReturnType() : '') ?>```
    __<?php echo $summary ?>__
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
            echo str_replace('@throws', '', $throwsTag->render());
        }
    }
    ?>
    <br>
    <?php
}