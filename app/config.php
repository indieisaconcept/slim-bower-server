<?php

// CONFIG

$config = array(

    'database' => array(
        'connection' => 'sqlite://' . dirname(__FILE__) . '/data/bower.db.sqlite'
    )

);

require_once dirname(__FILE__) . '/models/Package.php';

?>