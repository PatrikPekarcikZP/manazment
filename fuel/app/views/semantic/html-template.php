<?php
/** @var string[] $title */
use Fuel\Core\Session;

/** @var string $content */
if (!is_array($title)) {
    $title = [$title];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title><?php echo implode(" · ", $title); ?></title>
    <script src="/assets/jquery/jquery-1.12.3.min.js"></script>
    <script src="/assets/semantic-ui/semantic.min.js"></script>

    <link rel="stylesheet" type="text/css" class="ui" href="/assets/semantic-ui/semantic.min.css">
    <link rel="stylesheet" type="text/css" class="ui" href="/assets/opensans/opensans.css">
    <link rel="stylesheet" type="text/css" class="ui" href="/assets/gitlab-uni/styles.css">
</head>
<body>
<div class="ui attached segment pushable" id="page">
    <div class="ui visible inverted left vertical sidebar menu">
        <?php echo render("semantic/menu"); ?>
    </div>
    <div class="pusher" style="margin-right: 260px">
        <div class="ui basic segment">
            <?php if (Session::get_flash('success')): ?>
                <div class="ui success message" id="success-message">
                    <i class="close icon"></i>
                    <div class="header">
                        Úspech
                    </div>
                    <p><?php echo implode('</p><p>', (array) Session::get_flash('success')); ?></p>
                </div>
            <?php endif; ?>
            <?php if (Session::get_flash('error')): ?>
                <div class="ui negative message" id="negative-message">
                    <i class="close icon"></i>
                    <div class="header">
                        Chyba
                    </div>
                    <p><?php echo implode('</p><p>', (array) Session::get_flash('error')); ?></p>
                </div>
            <?php endif; ?>
            <?php echo $content; ?>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#success-message').find('.close').on('click', function () {
            $(this)
                .closest('#success-message')
                .transition('fade');
        });
        $('#negative-message').find('.close').on('click', function () {
            $(this)
                .closest('#negative-message')
                .transition('fade');
        });
    });
</script>
</body>
</html>