<?php
    $db_config = array(
'host' => 'localhost',
 'port' => '3306',
 'user' => 'root',
 'pass' => '',
 'db' => 'chat-gallery',
 'db_type' => 'mysql',
 'encoding' => 'utf-8'
);
 
 
try {
 
    $dsn = $db_config['db_type'] .
 ':host=' . $db_config['host'] .
 ';port=' . $db_config['port'] .
 ';encoding=' . $db_config['encoding'] .
 ';dbname=' . $db_config['db'];
 
    $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
 
    $conn= new PDO($dsn, $db_config['user'],  $db_config['pass'], $options);
 
    define('DB_CONNECTED', true);
 
} catch(PDOException $e) {
    die('Unable to connect: ' . $e->getMessage());
}
 
?>