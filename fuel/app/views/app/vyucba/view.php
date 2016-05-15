<?php
/** @var Model_Vyucba $vyucba */
use Fuel\Core\Html;

$edit = $vyucba->otvorena == 1;

?>
<div class="ui container">
    <h2 class="ui dividing header">Výučba #<?php echo $vyucba->id; ?></h2>

    <p>
        <strong>Ústav:</strong>
        <?php echo $vyucba->predmet->ustav->nazov; ?>
    </p>
    <p>
        <strong>Predmet:</strong>
        <?php echo $vyucba->predmet->nazov; ?>
    </p>
    <p>
        <strong>Rok výuky:</strong>
        <?php echo $vyucba->rok; ?>
    </p>
    <p>
        <strong>Výuka otvorená:</strong>
        <?php echo $vyucba->otvorena == 1 ? 'áno' : 'nie'; ?>
    </p>
    <p></p>
</div>
<div class="ui three column doubling stackable grid container">
    <div class="column">
        <h3 class="header">Vyučujúci</h3>
        <table class="ui striped single line table">
            <thead>
            <tr>
                <th>E-mail</th>
                <?php if ($edit) { ?>
                    <th class="right aligned">
                        <a href="#" class="ui mini icon green button" id="novyVyucujuciButton"
                           title="pridať vyučujúcich">
                            <i class="icon plus"></i>
                        </a>
                    </th>
                <?php } ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($vyucba->getVyucujuci() as $vyucujuci) { ?>
                <tr class="vyucujuciRow" data-id="<?php echo $vyucujuci->id; ?>">
                    <td><?php echo $vyucujuci->mail; ?></td>
                    <?php if ($edit) { ?>
                        <td class="right aligned">
                            <a href="#" class="ui mini icon red button deleteButton" title="zmazať vyučujúceho">
                                <i class="icon minus"></i>
                            </a>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="column">
        <h3 class="header">Študenti</h3>
        <table class="ui striped single line table">
            <thead>
            <tr>
                <th>E-mail</th>
                <?php if ($edit) { ?>
                    <th class="right aligned">
                        <a href="#" class="ui mini icon green button deleteButton" id="novyStudentiButton"
                           title="pridať študentov">
                            <i class="icon plus"></i>
                        </a>
                    </th>
                <?php } ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($vyucba->getStudenti() as $student) { ?>
                <tr class="studentRow" data-id="<?php echo $student->id; ?>">
                    <td><?php echo $student->mail; ?></td>
                    <?php if ($edit) { ?>
                        <td class="right aligned">
                            <a href="#" class="ui mini red icon button deleteButton" title="zmazať študenta">
                                <i class="icon minus"></i>
                            </a>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="column">
        <h3 class="header">Zadania</h3>

        <table class="ui striped single line table">
            <thead>
            <tr>
                <th>Názov</th>
                <th><abbr title="Vypracovaných / S hodnotením">Vyp.</abbr></th>
                <th class="right aligned">
                    <?php if ($edit) { ?>
                        <a href="<?php echo \Fuel\Core\Uri::create(Controller_Zadania::URLPATH . '/create', [],
                            ['vyucba_id' => $vyucba->id]); ?>" class="ui mini green icon button"
                           title="vytvoriť zadanie">
                            <i class="icon plus"></i>
                        </a>
                    <?php } ?>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($vyucba->zadania as $zadanie) { ?>
                <tr>
                    <td><?php echo $zadanie->nazov; ?></td>
                    <td><?php echo $zadanie->getPocetVypracovanych() . "/" . $zadanie->getPocetSHodnotenim(); ?></td>
                    <td class="right aligned">
                        <a href="<?php echo \Fuel\Core\Uri::create(Controller_Zadania::URLPATH . '/view/' . $zadanie->id); ?>">
                            zobraziť
                        </a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <?php if ($edit) { ?>

        <?php echo Html::anchor(Controller_Vyucba::URLPATH . '/delete/' . $vyucba->id,
            'Delete',
            array('onclick' => "return confirm('Naozaj chcete zmazat vyucbu?')", 'class' => 'ui red button')); ?>

        <?php echo Html::anchor(Controller_Vyucba::URLPATH . '/close/' . $vyucba->id,
            'Close', ['onclick' => "return confirm('Naozaj chcete ukoncit vyucbu?')", 'class' => 'ui blue button']); ?>

    <?php } ?>

    <?php echo Html::anchor(Controller_Vyucba::URLPATH, 'Back', ['class' => 'ui orange button']); ?>
</div>


<div class="ui modal" id="novyVyucujuciModal">
    <div class="header">
        Pridanie vyučujúcich
    </div>
    <div class="content">
        <div class="ui form">
            <div class="field">
                <label for="novyVyucujuciTextarea">E-maily:</label>
                <textarea name="novyVyucujuciTextarea" id="novyVyucujuciTextarea"
                          placeholder="každá adresa na nový riadok"></textarea>
            </div>
        </div>
        <p>Zadávajte e-mailové adresy vyučujúcich, každá adresa na nový riadok.</p>
    </div>
    <div class="actions">
        <a class="ui red cancel button">zrušiť</a>
        <button class="ui green ok right labeled icon button">
            uložiť
            <i class="icon checkmark"></i>
        </button>
    </div>
</div>

<div class="ui modal" id="novyStudentiModal">
    <div class="header">
        Pridanie študentov
    </div>
    <div class="content">
        <div class="ui form">
            <div class="field">
                <label for="novyStudentiTextarea">E-maily:</label>
            <textarea name="novyStudentiTextarea" id="novyStudentiTextarea"
                      placeholder="každá adresa na nový riadok"></textarea>
            </div>
        </div>
        <p>Zadávajte e-mailové adresy študentov, každá adresa na nový riadok.</p>
    </div>
    <div class="actions">
        <a class="ui red cancel button">zrušiť</a>
        <button class="ui green ok right labeled icon button">
            uložiť
            <i class="icon checkmark"></i>
        </button>
    </div>
</div>

<script>

    $(function () {
        function saveEmailAdresses(adresses, vyucujuci) {
            if (adresses == "") {
                alert("Zadajte emailové adresy!");
                return;
            }
            console.log("ulozenie", adresses, vyucujuci);
            $.ajax({
                url: "<?php echo \Fuel\Core\Uri::create(Controller_Vyucba_Uzivatelia::URLPATH . '/create'); ?>",
                data: {
                    vyucba_id: <?php echo $vyucba->id; ?>,
                    mail: adresses,
                    vyucujuci: vyucujuci
                },
                type: 'POST',
                dataType: 'json',
                success: function (data) {
                    if (data.status == "ok") {
                        window.location = data.goto;
                    } else {
                        alert(data.message);
                    }
                },
                error: function (error) {
                    alert("Nastala neznáma chyba spojenia so serverom.");
                }
            });
        }

        // region vyucujuci
        var novyVyucujuciTextarea = $("#novyVyucujuciTextarea");
        var novyVyucujuciModal = $("#novyVyucujuciModal").modal({
            closable: false,
            onDeny: function () {
                novyVyucujuciTextarea.val("");
                return true;
            },
            onApprove: function () {
                saveEmailAdresses(novyVyucujuciTextarea.val(), true);
            }
        });
        $("#novyVyucujuciButton").click(function (e) {
            e.preventDefault();
            novyVyucujuciModal.modal('show');
        });
        // endregion

        // region studenti
        var novyStudentiTextarea = $("#novyStudentiTextarea");
        var novyStudentiModal = $("#novyStudentiModal").modal({
            closable: false,
            onDeny: function () {
                novyStudentiTextarea.val("");
                return true;
            },
            onApprove: function () {
                saveEmailAdresses(novyStudentiTextarea.val(), false);
            }
        });
        $("#novyStudentiButton").click(function (e) {
            e.preventDefault();
            novyStudentiModal.modal('show');
        });
        // endregion

        $(".vyucujuciRow,.studentRow").each(function () {
            var activeRow = $(this);
            activeRow.find(".deleteButton").click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "<?php echo \Fuel\Core\Uri::create(Controller_Vyucba_Uzivatelia::URLPATH . '/delete/'); ?>" + activeRow.attr("data-id"),
                    dataType: 'json',
                    success: function (data) {
                        if (data.status == 'ok') {
                            activeRow.remove();
                        } else {
                            alert(data.message);
                        }
                    },
                    error: function () {
                        alert("Nastala neznáma chyba spojenia!");
                    }
                });
            });
        });

    });

</script>
