<?php
/** @var Model_Ustav $ustav */
/** @var string $formTitle */
use Fuel\Core\Form;
use Fuel\Core\Html;
use Fuel\Core\Input;

?>
<div class="ui text container">
    <h2 class="ui dividing header"><?php echo $formTitle; ?></h2>
    <?php echo Form::open(array("class" => "ui form")); ?>
    <div class="field">
        <?php echo Form::label('Názov', 'nazov'); ?>
        <?php echo Form::input('nazov', Input::post('nazov', isset($ustav) ? $ustav->nazov : ''),
            array('placeholder' => 'Nazov')); ?>
    </div>
    <div class="field">
        <?php echo Form::label('Skratka', 'skratka'); ?>
        <?php echo Form::input('skratka', Input::post('skratka', isset($ustav) ? $ustav->skratka : ''),
            array('placeholder' => 'Skratka')); ?>
    </div>
    <?php echo Form::submit('submit', 'Uložiť', array('class' => 'ui green button')); ?>
    <?php echo Html::anchor(Controller_Admin_Ustavy::URLPATH, 'Späť', array('class' => 'ui orange button')); ?>

    <?php echo Form::close(); ?>
</div>
