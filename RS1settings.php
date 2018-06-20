<?PHP
    define('REDIS_SERVER','localhost');

    define('REDIS_PORT', 6379);

    define('REDIS_PASSWORD','qwerty');

    

    define('DB_SERVER', 'localhost:3306');

    define('DB_USERNAME', 'testuser');

    define('DB_PASSWORD', 'qwerty');

    define('DB_DATABASE', 'users');

    $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    
    define('DB_SER', '192.168.100.10:3306');

    define('DB_USER', 'testuser');

    define('DB_PASS', 'qwerty');

    define('DB_DB', 'users');

    $db1 = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

    if(!$db and !db1){

        die("ERROR: Could not connect. " . mysqli_connect_error());

    }
?>
