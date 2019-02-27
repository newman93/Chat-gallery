<?php
require_once 'vendor/autoload.php';
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients = [];
    protected $clientsMap;
/*
    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }
*/
    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        //$this->clients->attach($conn);
        $this->clients[$conn->resourceId] = $conn;
        //echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        /*
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }
        */
     $data = json_decode($msg, true); 
     switch ($data['type']) { 
      case "login": 
                   //if(!in_array($data['username'], array_column($clientsMap, 'username'))) {
                   if (!(isset($this->clientsMap[$data['username']]))) {
                    $this->clientsMap[$data['username']] = $from->resourceId;
                    //$this->clients[$this->clientsMap[$data['username']]]->send(json_encode(['type'=>'login', 'state'=> 'success']));
                    $from->send(json_encode(['type'=>'login', 'state'=> 'success']));
                    $users = json_decode($data['users'], true);
                    if (!(isset($users[0]['empty']))) {
                     foreach ($users as $key=>$user) {
                      if (isset($this->clientsMap[$user['username']])) {
                       $this->clients[$this->clientsMap[$user['username']]]->send(json_encode(['type' => 'new-login', 'username' => $data['username']]));
                       }
                     }
                    }
                    //array_push(['resource_id' => $from->resourceId, 'username' => $data['username']], $clientsMap);                    
                    
                   }
                break;
     /*  case "leave":
                  echo "lol0";
                  if (isset($this->clientsMap[$data['username']])) {
                    //$this->clients[$this->clientsMap[$data['username']]]->send(json_encode(['type'=>'login', 'state'=> 'success']));
                    $users = json_decode($data['users'], true);
                    if (!(isset($users[0]['empty']))) {
                     foreach ($users as $key=>$user) {
                      if (isset($this->clientsMap[$user['username']])) {
                       echo "lol";
                       $this->clients[$this->clientsMap[$user['username']]]->send(json_encode(['type' => 'user-leave', 'username' => $data['username']]));
                       }
                     }
                    }
                    unset($this->clients[$this->clientsMap[$data['username']]]);
                    unset($this->clientsMap[$data['username']]);
                    //array_push(['resource_id' => $from->resourceId, 'username' => $data['username']], $clientsMap);                    
                   }
                  break;
      * 
      */
    /*  case "offer": 
                  $toClient = isset($this->clientsMap[$data['toUsername']])? $this->clients[$this->clientsMap[$data['toUsername']]] : null;
				
                  if($toClient != null) { 
                   $toClient->send(json_encode(['type' => 'offer', 'fromUsername' => $data['username']]));
                  } 
                  break;
      case "accepted": 
                  $toClient = isset($this->clientsMap[$data['toUsername']])? $this->clients[$this->clientsMap[$data['toUsername']]] : null;
				
                  if($toClient != null) { 
                   $toClient->send(json_encode(['type' => 'accepted', 'fromUsername' => $data['fromUsername']]));
                  } 
                  break;            
      
      case "answer": 
                    $toClient = isset($this->clientsMap[$data['toUsername']])? $this->clients[$this->clientsMap[$data['toUsername']]] : null;

                    if($toClient != null) { 
                     $toClient->send(json_encode(['type' => 'answer', 'message'=> $data['message'], 'fromUsername' => $data['username']])); 
                    }
                    break;  	
      case "leave": 
                   $toClient = isset($this->clientsMap[$data['toUsername']])? $this->clients[$this->clientsMap[$data['toUsername']]] : null;

                   if($toClient != null) { 
                     $toClient->send(json_encode(['type' => 'leave', 'fromUsername' => $data['username']])); 
                   }
                   break;
     */ 
      case "message":
                  $toClient = isset($this->clientsMap[$data['toUsername']])? $this->clients[$this->clientsMap[$data['toUsername']]] : null;
                  if($toClient != null) { 
                               echo "lol"; 
                    $toClient->send(json_encode(['type' => 'message', 'message'=> $data['message'], 'fromUsername' => $data['username'],
                            'fromName' => $data['name'], 'fromSurname' => $data['surname'],'time' => $data['time'], 'date' => $data['date']])); 
                  } /*else {
                    $from->send(json_encode(['type' => "access", "access" => "false", "username" => array_search($from->resourceId,$this->clientsMap)]));
                  }*/
                  break;  
      }  
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        //$this->clients->detach($conn);
        $user_leave = array_search($conn->resourceId, $this->clientsMap);
        foreach($this->clients as $client)
         $client->send(json_encode(['type' => 'user-leave', 'username' => $user_leave]));
        unset($this->clients[$conn->resourceId]);
        unset($this->clientsMap[$user_leave]); 
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
?>

