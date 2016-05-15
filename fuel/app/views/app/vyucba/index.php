<?php
/** @var Model_Vyucba[] $list */
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
                <th>Otvorená výuka</th>
                <th>Počet študentov</th>
                <th>Počet zadaní</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($list as $item): ?>
                <tr>
                    <td><?php echo $item->predmet->nazov; ?></td>
                    <td><?php echo $item->rok; ?></td>
                    <td><?php echo $item->otvorena == 1 ? 'áno' : 'nie'; ?></td>
                    <td><?php echo $item->getPocetStudentov(); ?></td>
                    <td><?php echo $item->getPocetZadani(); ?></td>
                    <td class="right aligned">
                        <?php echo Html::anchor(Controller_Vyucba::URLPATH . '/view/' . $item->id,
                            'View'); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    <?php else: ?>
        <p>Žiadna výučba.</p>
    <?php endif; ?>
</div>