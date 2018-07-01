<?PHP
    define('REDIS_SERVER','localhost');

    define('REDIS_PORT', 6379);

    define('REDIS_PASSWORD','qwerty');

    //up - redis defined

    define('DB_SERVER_M', 'localhost:3306');
    
    define('DB_SERVER_S', '192.168.100.10:3306');

    define('DB_USERNAME', 'testuser');

    define('DB_PASSWORD', 'qwerty');

    define('DB_DATABASE', 'users');

   // up - db defined

    require_once __DIR__ . '/vendor/autoload.php';

    define('RABBIT_SRV','192.168.47.130');

    define('RABBIT_PORT',5672);

    define('RABBIT_USER','admin');

    define('RABBIT_PASS','admin');

   // up - rabbitmq credientals
/*    $dbm = mysqli_connect(DB_SERVER_M,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

    $dbs = mysqli_connect(DB_SERVER_S,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

    if(!$dbm and !dbs){

        die("ERROR: Could not connect. " . mysqli_connect_error());

    } */

?>
