CREATE TABLE "packages" (

    "id"            INTEGER PRIMARY KEY  NOT NULL  check(typeof("id") = 'integer'),
    "name"          VARCHAR NOT NULL  UNIQUE , 
    "url"           VARCHAR NOT NULL  UNIQUE ,
    "hits"          INTEGER NOT NULL  DEFAULT 0,
    "created_at"    DATETIME NOT NULL, 
    "updated_at"    DATETIME NOT NULL
    
);

CREATE INDEX "packages_name" ON "packages" ("name");

# INSERT INTO "packages" VALUES(1,'jquery','git://github.com/jquery/jquery.git',0,'2013-02-23 02:25:47 EST','2013-02-23 03:28:30 EST');