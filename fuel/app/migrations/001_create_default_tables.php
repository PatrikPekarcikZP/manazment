<?php

namespace Fuel\Migrations;

use Fuel\Core\DB;
use Fuel\Core\DBUtil;

class Create_default_tables
{
    public function up()
    {
        DB::query("CREATE TABLE predmety (
    id INT NOT NULL AUTO_INCREMENT,
    ustav_id INT NOT NULL,
    nazov VARCHAR(256) NOT NULL,
    skratka VARCHAR(256) NOT NULL,
    created_at INT(11),
    updated_at INT(11),
    CONSTRAINT predmety_pk PRIMARY KEY (id)
);")->execute();

        DB::query("CREATE TABLE ustavy (
    id INT NOT NULL AUTO_INCREMENT,
    nazov VARCHAR(256) NOT NULL,
    skratka VARCHAR(256) NOT NULL,
    created_at INT(11),
    updated_at INT(11),
    CONSTRAINT ustavy_pk PRIMARY KEY (id)
);")->execute();

        DB::query("CREATE TABLE vyucby (
    id INT NOT NULL AUTO_INCREMENT,
    predmet_id INT NOT NULL,
    rok INT NOT NULL,
    otvorena BOOL NOT NULL,
    created_at INT(11),
    updated_at INT(11),
    CONSTRAINT vyucby_pk PRIMARY KEY (id)
);")->execute();

        DB::query("CREATE TABLE vyucby_uzivatelia (
    id INT NOT NULL AUTO_INCREMENT,
    vyucba_id INT NOT NULL,
    mail VARCHAR(256) NOT NULL,
    vyucujuci BOOL NOT NULL,
    created_at INT(11),
    updated_at INT(11),
    CONSTRAINT vyucby_uzivatelia_pk PRIMARY KEY (id)
);")->execute();

        DB::query("CREATE TABLE zadania (
    id INT NOT NULL AUTO_INCREMENT,
    vyucba_id INT NOT NULL,
    nazov VARCHAR(256) NOT NULL,
    data BLOB NOT NULL,
    created_at INT(11),
    updated_at INT(11),
    CONSTRAINT zadania_pk PRIMARY KEY (id)
);")->execute();

        DB::query("CREATE TABLE zadania_stav (
    id INT NOT NULL AUTO_INCREMENT,
    zadanie_id INT NOT NULL,
    vyucba_uzivatel_id INT NOT NULL,
    cas TIMESTAMP NOT NULL,
    stav VARCHAR(10) NOT NULL,
    informacie TEXT NOT NULL,
    hodnotenie VARCHAR(2) NOT NULL,
    hodnotenie_text TEXT NOT NULL,
    created_at INT(11),
    updated_at INT(11),
    CONSTRAINT zadania_stav_pk PRIMARY KEY (id)
);")->execute();

        DB::query("ALTER TABLE predmety ADD CONSTRAINT predmety_ustavy FOREIGN KEY predmety_ustavy (ustav_id)
    REFERENCES ustavy (id);")->execute();

        DB::query("ALTER TABLE vyucby ADD CONSTRAINT vyuka_predmety FOREIGN KEY vyuka_predmety (predmet_id)
    REFERENCES predmety (id);")->execute();

        DB::query("ALTER TABLE vyucby_uzivatelia ADD CONSTRAINT vyucby_uzivatelia_vyucby FOREIGN KEY vyucby_uzivatelia_vyucby (vyucba_id)
    REFERENCES vyucby (id);")->execute();

        DB::query("ALTER TABLE zadania ADD CONSTRAINT vyucby_zadania_vyucby FOREIGN KEY vyucby_zadania_vyucby (vyucba_id)
    REFERENCES vyucby (id);")->execute();

        DB::query("ALTER TABLE zadania_stav ADD CONSTRAINT zadania_stav_vyucby_uzivatelia FOREIGN KEY zadania_stav_vyucby_uzivatelia (vyucba_uzivatel_id)
    REFERENCES vyucby_uzivatelia (id);")->execute();

        DB::query("ALTER TABLE zadania_stav ADD CONSTRAINT zadania_stav_zadania FOREIGN KEY zadania_stav_zadania (zadanie_id)
    REFERENCES zadania (id);")->execute();

        DB::query("ALTER TABLE `vyucby_uzivatelia` ADD UNIQUE( `vyucba_id`, `mail`);")->execute();
    }

    public function down()
    {
        DBUtil::drop_table('predmety');
        DBUtil::drop_table('ustavy');
        DBUtil::drop_table('vyucby');
        DBUtil::drop_table('vyucby_uzivatelia');
        DBUtil::drop_table('zadania');
        DBUtil::drop_table('zadania_stav');
    }
}