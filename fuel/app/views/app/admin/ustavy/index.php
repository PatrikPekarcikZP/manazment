<?php
/** @var Model_Ustav[] $list */
use Fuel\Core\Html;

?>
<div class="ui container">
    <h2 class="ui header">Ústavy univerzity</h2>
    <?php if ($list): ?>
        <table class="ui stripped table">
            <thead>
            <tr>
                <th>Nazov</th>
                <th>Skratka</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($list as $item): ?>
                <tr>
                    <td><?php echo $item->nazov; ?></td>
                    <td><?php echo $item->skratka; ?></td>
                    <td class="right aligned">
                        <?php echo Html::anchor(Controller_Admin_Ustavy::URLPATH . '/view/' . $item->id,
                            'View'); ?>
                        |
                        <?php echo Html::anchor(Controller_Admin_Ustavy::URLPATH . '/edit/' . $item->id,
                            'Edit'); ?>
                        |
                        <?php echo Html::anchor(Controller_Admin_Ustavy::URLPATH . '/delete/' . $item->id,
                            'Delete',
                            array('onclick' => "return confirm('Are you sure?')")); ?>

                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    <?php else: ?>
        <p>Žiadne ústavy.</p>
    <?php endif; ?>
    <p>
        <?php echo Html::anchor(Controller_Admin_Ustavy::URLPATH . '/create', 'Vytvoriť ústav',
            array('class' => 'ui green button')); ?>

    </p>
</div>