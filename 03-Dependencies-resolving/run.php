<?php
    require_once('./installator.php');
    
    use Installator\Installator;

    $installator = new Installator;
    $installator->run();
?>