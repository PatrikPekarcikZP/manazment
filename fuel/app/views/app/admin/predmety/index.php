<?php
/** @var Model_Predmet[] $list */
use Fuel\Core\Html;

?>
<div class="ui container">
    <h2 class="ui header">Predmety</h2>
    <?php if ($list): ?>
        <table class="ui stripped table">
            <thead>
            <tr>
                <th>Ústav</th>
                <th>Nazov</th>
                <th>Skratka</th>
                <th>Otvorená výuka</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($list as $item): ?>
                <tr>
                    <td><?php echo $item->ustav->skratka; ?></td>
                    <td><?php echo $item->nazov; ?></td>
                    <td><?php echo $item->skratka; ?></td>
                    <td><?php
                        $vyuka = $item->getOtvorenaVyuka();
                        if ($vyuka == null) {
                            echo '<a href="' . \Fuel\Core\Uri::create(Controller_Vyucba::URLPATH . '/create?vyucba_id=' . $item->id) . '">otvoriť výuku</a>';
                        } else {
                            echo '<a href="' . \Fuel\Core\Uri::create(Controller_Vyucba::URLPATH . '/view/' . $vyuka->id) . '">' . $vyuka->rok . '</a>';
                        }
                        ?></td>
                    <td class="right aligned">
                        <?php echo Html::anchor(Controller_Admin_Predmety::URLPATH . '/view/' . $item->id,
                            'View'); ?>
                        |
                        <?php echo Html::anchor(Controller_Admin_Predmety::URLPATH . '/edit/' . $item->id,
                            'Edit'); ?>
                        |
                        <?php echo Html::anchor(Controller_Admin_Predmety::URLPATH . '/delete/' . $item->id,
                            'Delete',
                            array('onclick' => "return confirm('Are you sure?')")); ?>

                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    <?php else: ?>
        <p>Žiadne predmety.</p>
    <?php endif; ?>
    <p>
        <?php echo Html::anchor(Controller_Admin_Predmety::URLPATH . '/create', 'Vytvoriť predmet',
            array('class' => 'ui green button')); ?>

    </p>
</div>