<?php
/*MySQLi*/
$link = mysqli_connect("127.0.0.1", "root", "", "address_splitter");

if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

mysqli_query($link, "SET NAMES utf8");

// Bitcoin RPC login information:
$user = 'Lc4SYqCMaQQ5lutiVlKM';
$pass = 'N3N1zkWetpKCm3SIlrRZ6278r0xr1uJMyCVMu7pnVsZejnaMWav1zJlnkbJD';
$server = '46.28.108.48';
$port = '18332';

$debug = 1;       // Debug mode (0/1)

if ($debug) {
    ini_set('display_startup_errors',1);
    ini_set('display_errors',1);
    error_reporting(-1);
}

include __DIR__.'/wallet-driver.php';
?>