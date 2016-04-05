<?php
include __DIR__.'/init.php';

$wallet = new BitcoinWallet($user, $pass, $server, $port);

foreach ($wallet->listtransactions('', 10000) as $transaction) {
    /*Je adresa v databázi?*/
    $resend = mysqli_query($link, "SELECT * FROM `addresses` WHERE `address`='" . $transaction['address'] . "' LIMIT 1");
    /*Byla už platba rozeslána?*/
    $resendet = mysqli_query($link, "SELECT * FROM `received` WHERE `id`='" . $transaction['txid'] . "'");
    if ($transaction['category'] == 'receive' && mysqli_num_rows($resend) && !mysqli_num_rows($resendet)) {
        /*Vybere adresy pro rozelání*/
        $address = mysqli_fetch_assoc($resend);
        $resend_addresses = mysqli_query($link, "SELECT * FROM `resend_addresses` WHERE `address_id`='" . $address['id'] . "' ");
        /*Rozeslání na adresy*/
        while ($row = mysqli_fetch_assoc($resend_addresses)) {
            $q = mysqli_query($link, "INSERT INTO `received` VALUES('" . $transaction['txid'] . "')");
            $wallet->sendtoaddress($row['address'], $row['share']/100*$transaction['amount']);
            echo 'Sent <b>' . $row['share']/100*$transaction['amount'] . '</b> BTC to <code>' . $row['address'] . '</code></br>';
        }
    }
}
echo '<h1>Transakce</h1><pre>';
$transactions = $wallet->listtransactions('', 1000);
krsort($transactions);
print_r($transactions);
?>
</pre>
