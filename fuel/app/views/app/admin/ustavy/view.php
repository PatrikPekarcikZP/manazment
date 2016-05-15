<?php
/** @var Model_Ustav $ustav */
use Fuel\Core\Html;

?>

<div class="ui container">
    <h2 class="ui dividing header">Ústav #<?php echo $ustav->id; ?></h2>

    <p>
        <strong>Nazov:</strong>
        <?php echo $ustav->nazov; ?>
    </p>
    <p>
        <strong>Skratka:</strong>
        <?php echo $ustav->skratka; ?>
    </p>

    <h3 class="ui header">Predmety ústavu</h3>

    <table class="ui selectable single line table">
        <thead>
        <tr>
            <th>Názov</th>
            <th>Skratka</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($ustav->predmety as $predmet) { ?>
            <tr>
                <td class="selectable">
                    <a href="<?php echo \Fuel\Core\Uri::create(Controller_Admin_Predmety::URLPATH . '/view/' . $predmet->id); ?>"><?php echo $predmet->nazov; ?></a>
                </td>
                <td><?php echo $predmet->skratka; ?></td>
            </tr>
        <?php } ?>
        </tbody>

    </table>

    <p>    <?php echo Html::anchor(Controller_Admin_Ustavy::URLPATH . '/edit/' . $ustav->id, 'upraviť',
            ['class' => 'ui button']); ?>
        <?php echo Html::anchor(Controller_Admin_Ustavy::URLPATH, 'späť', ['class' => 'ui orange button']); ?>
    </p>
</div>
