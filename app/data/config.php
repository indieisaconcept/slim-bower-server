<?php

function bootstrap ($query = null) {

    if (isset($query) && !empty($query)) {
        $db = ORM::get_db();
        $db->exec($query); 
    }

};

ORM::configure($config['database']['connection']);

bootstrap('

    CREATE TABLE IF NOT EXISTS "packages" (

        "id"            INTEGER PRIMARY KEY,
        "name"          VARCHAR NOT NULL UNIQUE, 
        "url"           VARCHAR NOT NULL UNIQUE,
        "hits"          INTEGER NOT NULL DEFAULT 0,
        "created_at"    DATETIME NOT NULL DEFAULT (DATETIME("now", "localtime")),
        "updated_at"    DATETIME NOT NULL DEFAULT (DATETIME("now", "localtime"))
        
    );

    CREATE INDEX IF NOT EXISTS "packages_name" ON "packages" ("name");
    INSERT OR IGNORE INTO "packages" (name, url) VALUES ("jquery", "git://github.com/jquery/jquery.git");

');

?>