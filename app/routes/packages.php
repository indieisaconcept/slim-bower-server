<?php

// isValid
// Is the data valid for database insertion?
function isValid ($keys = null, $attributes = null) {

    function validate ($keys = null, $attributes = null) {

        function validateURL ($url) {
            return preg_match('/^git\:\/\//', $url);
        }

        $valid = FALSE;

        if (isset($keys) && isset($attributes)) {

            $valid = TRUE;

            foreach ($keys as $key) {
                
                $func = 'validate' . strtoupper($key);
                $valid = array_key_exists($key, $attributes) && !empty($attributes[$key]);

                if (function_exists($func)) {
                    $valid = $func($attributes[$key]);
                }

                if (!$valid) {
                    break;
                }

            }

        }

        return $valid;

    }    

    $valid = FALSE;

    if (validate($keys, $attributes)) {

        $count = ORM::for_table('packages')->where_raw('(`name` = ? OR `url` = ?)', array($attributes['name'], $attributes['url']))->count();

        if ($count == 0) {
            $valid = TRUE;
        }

    }

    return $valid;

}

// process
// Porcess a phpactiverecord recordset
function process ($packages) {

    // attributes
    // Return require attributes from record
    function attributes ($pkg) {

        return array(
            'name' => $pkg->name,
            'url' => $pkg->url
        );

    };

    $results = null;

    if (isset($packages) && !empty($packages)) { 

        $results = array();

        if (is_array($packages)) {

            foreach ($packages as $package) {
                array_push($results, attributes($package));
            }

        } else {
            $results = attributes($packages);
        }

        $results = json_encode($results);

    }

    return $results;

};

// POST /packages
// > Accept: application/json
// 
//      {
//          name: 'package-name',
//          url: 'git-url'
//      }
//
// < 200

function routePackagesPost () {

    $app = Slim::getInstance();

    $requestBody = $app->request()->getBody();
    $attributes = json_decode($requestBody, true);
    
    if (isValid(array('url', 'name'), $attributes)) {

        $timestamp = date('Y-m-d H:i:s');

        $package = ORM::for_table('packages')->create();
        $package->name = $attributes['name'];
        $package->url = $attributes['url'];
        $package->created_at = $timestamp;
        $package->updated_at = $timestamp;        
        $package->save();

    } else {
        $app->response()->status(400);
    }

}

$app->post('/packages', 'routePackagesPost');

// GET /packages
// < 200
// < Content-Type: application/json
// 
//      [{
//          name: 'package-name',
//          url: 'git-url'
//      }]

function routePackagesGet () {

    $app = Slim::getInstance();    

    $packages = ORM::for_table('packages')->order_by_desc('name')->find_many();
    $result = process($packages);

    if (isset($result)) {
        $app->response()->header('Content-Type', 'application/json');
        echo $result;
    }

}

$app->get('/packages', 'routePackagesGet');

// GET /packages/{parameters}
// < 200
// < Content-Type: application/json
// 
//      {
//          name: 'package-name',
//          url: 'git-url'
//      }
//

function routePackagesName ($name) {

    $app = Slim::getInstance();    

    $package = ORM::for_table('packages')->where('name', $name)->find_one();

    $result = process($package);

    if (isset($result)) {

        $package->set('hits', $package->hits + 1);
        $package->save();

        $app->response()->header('Content-Type', 'application/json');
        echo $result;

    } else {
        $app->response()->status(404);
    }

}

$app->get('/packages/:name', 'routePackagesName');

// GET /packages/search/{parameters}
// < 200
// 
//      [{
//          name: 'package-name',
//          url: 'git-url'
//      }]

function routePackagesSearch ($name) {

    $app = Slim::getInstance();    

    $packages = ORM::for_table('packages')->where_like('name', '%' . $name . '%')->order_by_desc('name')->find_many();
    $result = process($packages);

    if (isset($result)) {
        $app->response()->header('Content-Type', 'application/json');
        echo $result;
    } else {
        $app->response()->status(404);
    }

}

$app->get('/packages/search/:name', 'routePackagesSearch');

?>