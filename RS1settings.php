<?PHP
    define('REDIS_SERVER','192.168.100.10');

    define('REDIS_PORT', 6379);

    define('REDIS_PASSWORD','qwerty');

    

    define('DB_SERVER_M', 'localhost:3306');
    
    define('DB_SERVER_S', '192.168.100.10:3306');

    define('DB_USERNAME', 'testuser');

    define('DB_PASSWORD', 'qwerty');

    define('DB_DATABASE', 'users');

/*    $dbm = mysqli_connect(DB_SERVER_M,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

    $dbs = mysqli_connect(DB_SERVER_S,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

    if(!$dbm and !dbs){

        die("ERROR: Could not connect. " . mysqli_connect_error());

    } */

?>
