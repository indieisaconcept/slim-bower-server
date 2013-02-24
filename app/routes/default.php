<?php

function routeDefault () {
    echo "<h1>Not Found</h1>";
}

$app->get('/', 'routeDefault');

?>