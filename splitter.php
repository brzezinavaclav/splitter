<?php
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

$wallet = new BitcoinWallet($user, $pass, $server, $port);
print_r($wallet->listtransactions());
?>