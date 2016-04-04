<?php
header("Content-Type: application/json");
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
$data = '';
/*Vypsání adres*/
if(isset($_GET['get_addresses'])){
    if($result = mysqli_query($link, "SELECT * FROM `addresses` ORDER BY `id` DESC")){
        while($row = mysqli_fetch_assoc($result)) {
            $data .= '<tr><td>'.$row['address'].'</td><td><a href ="javascript:md_resend_adresses('.$row['id'].');" class="btn btn-xs btn-success">Manage Resend Addresses</a></td></tr>';
        }
        echo json_encode(array('data' => $data));
    }
    else echo json_encode(array('error' => 'yes', 'message' => '<b>SQL error! </b>' . mysqli_error($link)));
}
/*Generování adresy*/
if(isset($_GET['get_address'])){
    $wallet = new BitcoinWallet($user, $pass, $server, $port);
    if($result = mysqli_query($link, "INSERT INTO `addresses` VALUES (NULL,'".$wallet-> getnewaddress()."')")) echo json_encode(array('error' => 'no'));
    else echo json_encode(array('error' => 'yes', 'message' => '<b>SQL error! </b>' . mysqli_error($link)));
}
/*Vypsání resend adres*/
if(isset($_GET['get_resend_addresses'])){
    if(!empty(isset($_GET['address_id']))){
        if($result = mysqli_query($link, "SELECT * FROM `resend_addresses` WHERE `address_id`=".$_GET['address_id'])){
            while($row = mysqli_fetch_assoc($result)) {
                $data .= '<tr><td>'.$row['address'].'</td><td>'.$row['share'] .' %</td><td><a href ="javascript:delete_resend_addresses('.$_GET['address_id'].','.$row['id'].');" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i></a></td></tr>';
            }
            echo json_encode(array('data' => $data));
        }
        else echo json_encode(array('error' => 'yes', 'message' => '<b>SQL error! </b>' . mysqli_error($link)));
    }
    else echo json_encode(array('error' => 'yes', 'message' => '<b>Error! </b>Incorrect address'));
}
/*Přidání resend adresy*/
if(isset($_GET['add_resend_address'])){
    if(!empty(isset($_GET['resend_address']))){
        if(!empty(isset($_GET['address_id']))){
            if(!empty(isset($_GET['share']))){
                $share = 0;
                if($result = mysqli_query($link, "SELECT `share` FROM `resend_addresses`")){
                    while($row = mysqli_fetch_array($result)) {
                        $share += $row[0];
                    }
                }
                if($share + $_GET['share'] <=100){
                    if($result = mysqli_query($link, "INSERT INTO `resend_addresses` (`id`, `address_id`, `address`, `share`) VALUES (NULL, '".$_GET['address_id']."', '".$_GET['resend_address']."', '".$_GET['share']."');")) echo json_encode(array('error' => 'no'));
                    else echo json_encode(array('error' => 'yes', 'message' => '<b>SQL error! </b>' . mysqli_error($link)));
                }
                else echo json_encode(array('error' => 'yes', 'message' => '<b>Error! </b>The count of all shares cannot be over 100%!'));
            }
            else echo json_encode(array('error' => 'yes', 'message' => '<b>Error! </b>Incorrect share'));
        }
        else echo json_encode(array('error' => 'yes', 'message' => '<b>Error! </b>Incorrect address'));
    }
    else echo json_encode(array('error' => 'yes', 'message' => '<b>Error! </b>Incorrect resend address'));
}
/*Odstranění resend adresy*/
if(isset($_GET['delete_resend_addresses'])){
    if(!empty(isset($_GET['id']))){
        if($result = mysqli_query($link, "DELETE FROM `resend_addresses` WHERE `id`=".$_GET['id'])) echo json_encode(array('error' => 'no'));
        else echo json_encode(array('error' => 'yes', 'message' => '<b>SQL error! </b>' . mysqli_error($link)));
    }
    else echo json_encode(array('error' => 'yes', 'message' => '<b>Error! </b>Incorrect address'));
}
?>