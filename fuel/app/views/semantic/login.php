<!DOCTYPE html>
<html>
<head>
    <!-- Standard Meta -->
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <!-- Site Properties -->
    <title>Prihlásenie · GitLab Uni</title>
    
    <script src="/assets/jquery/jquery-1.12.3.min.js"></script>
    <script src="/assets/semantic-ui/semantic.min.js"></script>

    <link rel="stylesheet" type="text/css" class="ui" href="/assets/semantic-ui/semantic.min.css">
    <link rel="stylesheet" type="text/css" class="ui" href="/assets/opensans/opensans.css">
    <link rel="stylesheet" type="text/css" class="ui" href="/assets/gitlab-uni/login.css">
</head>
<body>

<div class="ui middle aligned center aligned grid">
    <div class="column">
        <h2 class="ui teal image header">
            <div class="content">
                Administrácia Repozitára
            </div>
        </h2>

        <?php if (isset($odhlaseny) && $odhlaseny = true) { ?>

            <div class="ui info message">Ste odhlásený z GitLab Uni Administrácie.<br>Dovidenia</div>

        <?php } else { ?>

            <?php if (isset($error) && !empty($error)) { ?>
                <div class="ui error message"><?php echo $error; ?></div>
            <?php } ?>

            <div class="ui stacked segment">
                <p>
                    Prihlásenie cez GitLab účet
                </p>

                <a href="<?php echo \Fuel\Core\Uri::create("login/gitlab"); ?>"
                   class="ui fluid large teal submit button">
                    GitLab Login
                </a>

            </div>

        <?php } ?>

        <div class="ui message">
            Správa výučbových predmetov pre GitLab
        </div>
    </div>
</div>

</body>

</html>
