<?php
/** @var Model_Predmet $predmet */
use Fuel\Core\Html;

?>
<div class="ui container">
    <h2 class="ui dividing header">Predmet #<?php echo $predmet->id; ?></h2>

    <p>
        <strong>Ústav:</strong>
        <?php echo $predmet->ustav->nazov; ?>
    </p>
    <p>
        <strong>Nazov:</strong>
        <?php echo $predmet->nazov; ?>
    </p>
    <p>
        <strong>Skratka:</strong>
        <?php echo $predmet->skratka; ?>
    </p>


    <h3 class="ui header">Výučba predmetu</h3>

    <table class="ui selectable single line table">
        <thead>
        <tr>
            <th>Rok</th>
            <th>Otvorená</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($predmet->vyucby as $vyucba) { ?>
            <tr>
                <td class="selectable">
                    <a href="<?php echo \Fuel\Core\Uri::create(Controller_Vyucba::URLPATH . '/view/' . $vyucba->id); ?>"><?php echo $vyucba->rok; ?></a>
                </td>
                <td><?php echo $vyucba->otvorena == 1 ? 'áno' : 'nie'; ?></td>
            </tr>
        <?php } ?>
        </tbody>

    </table>

    <?php echo Html::anchor(Controller_Admin_Predmety::URLPATH . '/edit/' . $predmet->id, 'Edit',
        ['class' => 'ui button']); ?>
    <?php echo Html::anchor(Controller_Admin_Predmety::URLPATH, 'Back', ['class' => 'ui orange button']); ?>
</div>
