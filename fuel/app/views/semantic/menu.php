<div class="item">
    <a href="/" style="margin-right: 10px">
        <span class="ui logo icon image">
            <img src="/assets/gitlab-uni/img/logo-square.png" style="margin-right: 10px; width: 40px;"/>
        </span>
        GitLab Uni
    </a>
</div>

<div class="item">
    <div class="header">Výučba</div>
    <div class="menu">
        <a class="item" href="/vyucba">
            <i class="icon university"></i>
            Výučba
        </a>
        <a class="item" href="/vyucba/archive">
            <i class="icon history"></i>
            Ukončená výučba
        </a>
        <a class="item" href="/vyucba/create">
            <i class="icon plus"></i>
            Nová výučba
        </a>
    </div>
</div>

<div class="item">
    <div class="header">Zadania</div>
    <div class="menu">
        <a class="item" href="/zadania">
            <i class="icon lab"></i>
            Zadania
        </a>
        <a class="item" href="/zadania/archive">
            <i class="icon history"></i>
            Archív zadaní
        </a>
        <a class="item" href="/zadania/create">
            <i class="icon plus"></i>
            Nové zadanie
        </a>
    </div>
</div>

<div class="item">
    <div class="header">Administrácia</div>
    <div class="menu">

        <a class="item" href="/admin/predmety">
            <i class="icon location arrow"></i>
            Predmety
        </a>

        <a class="item" href="/admin/ustavy">
            <i class="icon building outline"></i>
            Ústavy
        </a>

        <a class="item" href="/admin">
            Administrácia
        </a>
    </div>
</div>

<div class="item">
    <?php $user = \Auth\Auth::instance()->get_user_array(); ?>
    <div class="header">
        <i class="user icon"></i>
        <?php echo $user['profile_fields']['fullname']; ?>
    </div>
    <div class="menu">
        <a class="item" href="<?php echo \Fuel\Core\Uri::create("logout"); ?>">
            Odhlásiť
        </a>
    </div>
</div>
