<?php


class BitcoinWallet {
  
  private $conn;
  
  public function __construct($user, $pass, $server, $port, $https = false) {
    
    $this->conn = 'http'.($https?'s':'').'://'.$user.':'.$pass.'@'.$server.':'.$port.'/';
    
  }
  
  public function __call($method, $params) {
    $data = array(
      'method' => $method,
      'params' => array_values($params),
      'id' => $method
    );

    $options = array(
      'http' => array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/json',
        'content' => json_encode($data)
      )
    );
    
    
    $context = stream_context_create($options);
    if ($response = @file_get_contents($this->conn, false, $context)) {
      $return = json_decode($response, true);
      return $return['result'];
    }
    else return null;
  
  }
  
}


?>