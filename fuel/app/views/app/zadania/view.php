<?php
/** @var Model_Zadanie $zadanie */
use Fuel\Core\Html;

?>
<div class="ui container">
    <h2 class="ui dividing header">Zadanie #<?php echo $zadanie->id; ?></h2>

    <p>
        <strong>Ústav:</strong>
        <?php echo $zadanie->vyucba->predmet->ustav->nazov; ?>
    </p>
    <p>
        <strong>Predmet:</strong>
        <?php echo $zadanie->vyucba->predmet->nazov; ?>
    </p>
    <p>
        <strong>Rok výučby:</strong>
        <?php echo $zadanie->vyucba->rok; ?>
    </p>
    <p>
        <strong>Názov zadania:</strong>
        <?php echo $zadanie->nazov; ?>
    </p>

    <?php // TODO: dopisat vypracovane zadania + uprava hodnotenia a vypis ?>

    <?php echo Html::anchor(Controller_Zadania::URLPATH, 'Back', ['class' => 'ui orange button']); ?>
</div>
