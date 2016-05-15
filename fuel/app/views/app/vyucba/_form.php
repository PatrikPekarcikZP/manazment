<?php
/** @var Model_Vyucba $predmet */
/** @var string $formTitle */
use Fuel\Core\Form;
use Fuel\Core\Html;
use Fuel\Core\Input;

?>
<div class="ui text container">
    <h2 class="ui dividing header"><?php echo $formTitle; ?></h2>
    <?php echo Form::open(array("class" => "ui form")); ?>
    <div class="field">
        <?php echo Form::label('Predmet', 'predmet_id'); ?>
        <?php echo Form::input('predmet_id', Input::post('predmet_id', isset($predmet) ? $predmet->predmet_id : ''),
            array('placeholder' => 'Predmet')); ?>
    </div>
    <div class="field">
        <?php echo Form::label('Rok', 'rok'); ?>
        <?php echo Form::input('rok', Input::post('rok', isset($predmet) ? $predmet->rok : date("Y")),
            array('placeholder' => 'Rok')); ?>
    </div>
    <?php echo Form::submit('submit', 'Otvoriť výučbu', array('class' => 'ui green button')); ?>
    <?php echo Html::anchor(Controller_Vyucba::URLPATH, 'Späť', array('class' => 'ui orange button')); ?>

    <?php echo Form::close(); ?>
</div>
