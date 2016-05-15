<?php
/** @var Model_Vyucba[] $vyucba */
/** @var Model_Predmet[] $predmety */
/** @var Model_Ustav[] $ustavy */
?>
<div class="ui three column doubling stackable grid container">
    <div class="column">
        <div class="ui segment red center aligned">
            <div class="ui statistic">
                <div class="label">
                    Počet predmetov s otvorenou výučbou
                </div>
                <div class="value">
                    <?php echo count($vyucba); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="column">
        <div class="ui segment blue center aligned">
            <div class="ui statistic">
                <div class="label">
                    Počet predmetov
                </div>
                <div class="value">
                    <?php
                    echo count($predmety);
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="column">
        <div class="ui segment black center aligned">
            <div class="ui statistic">
                <div class="label">
                    Počet ústavov
                </div>
                <div class="value">
                    <?php
                    echo count($ustavy);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>