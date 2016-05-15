<?php
/** @var Model_Zadanie[] $list */
/** @var string $title */
use Fuel\Core\Html;

?>
<div class="ui container">
    <h2 class="ui header"><?php echo $title; ?></h2>
    <?php if ($list): ?>
        <table class="ui stripped table">
            <thead>
            <tr>
                <th>Predmet</th>
                <th>Rok Výuky</th>
                <th>Názov</th>
                <th>Počet študentov</th>
                <th>Vypracovaných</th>
                <th>S hodnotením</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($list as $item): ?>
                <tr>
                    <td><?php echo $item->vyucba->predmet->nazov; ?></td>
                    <td><?php echo $item->vyucba->rok; ?></td>
                    <td><?php echo $item->nazov; ?></td>
                    <td><?php echo $item->vyucba->getPocetStudentov(); ?></td>
                    <td><?php echo $item->getPocetVypracovanych(); ?></td>
                    <td><?php echo $item->getPocetSHodnotenim(); ?></td>
                    <td class="right aligned">
                        <?php echo Html::anchor(Controller_Zadania::URLPATH . '/view/' . $item->id,
                            'View'); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    <?php else: ?>
        <p>Žiadne zadania.</p>
    <?php endif; ?>
</div>