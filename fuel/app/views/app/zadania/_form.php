<?php
/** @var Model_Zadanie $zadanie */
/** @var string $formTitle */
use Fuel\Core\Form;
use Fuel\Core\Html;
use Fuel\Core\Input;

?>
<div class="ui text container">
    <h2 class="ui dividing header"><?php echo $formTitle; ?></h2>
    <?php echo Form::open(array("class" => "ui form")); ?>

    <div class="field">
        <?php echo Form::label('Výučba', 'vyucba_id'); ?>
        <?php echo Form::input('vyucba_id', Input::post('vyucba_id', isset($zadanie) ? $zadanie->vyucba_id : ''),
            array('placeholder' => 'Výučba')); ?>
    </div>

    <div class="field">
        <?php echo Form::label('Názov', 'nazov'); ?>
        <?php echo Form::input('nazov', Input::post('nazov', isset($zadanie) ? $zadanie->nazov : ''),
            array('placeholder' => 'Názov')); ?>
    </div>

    <div class="field">
        <?php echo Form::label('Dáta', 'data'); ?>
        <?php echo Form::textarea('data', Input::post('data', isset($zadanie) ? $zadanie->data : ''),
            array('placeholder' => 'Dáta')); ?>
    </div>

    <p>Pravidlá kontroly repozitárov vo formáte json!</p>

    <?php echo Form::submit('submit', 'Uložiť', array('class' => 'ui green button')); ?>
    <?php echo Html::anchor(Controller_Zadania::URLPATH, 'Späť', array('class' => 'ui orange button')); ?>

    <?php echo Form::close(); ?>
</div>
