<?php
if(($_SERVER["REQUEST_METHOD"] == "GET")){
  if(!isset($_GET["token"])){
      echo '{"msg": "Token invalido", "error": true}';
      exit;
  }
} else {
  echo '{"msg": "Request method invalido", "error": true}';
  exit;
}
require 'C://xampp/vendor/autoload.php'; 
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable('C:\\xampp\htdocs\sesi\env');
$dotenv->load();


$apiKey = $_ENV["SUPABASE_APIKEY"];
$host = $_ENV["POSTGRE_HOST"];
$db = $_ENV["POSTGRE_DB"];
$user = $_ENV["POSTGRE_USER"];
$pwd = $_ENV["POSTGRE_PASSWORD"];



$token = $_GET["token"];
$dbconn = new PDO("pgsql:host=$host;dbname=$db;port=5432", $user, $pwd);
$dbconn->exec("LISTEN autorizacao_$token");   // those doublequotes are very important

$conn = pg_connect("user=$user password=$pwd host=$host port=5432 dbname=$db");
set_time_limit(100000);

header("X-Accel-Buffering: no"); // disable ngnix webServer buffering
header("Content-Type: text/event-stream");
header("Cache-Control: no-cache");
ob_end_flush();  // close PHP output buffering

// $query = pg_query($conn, "SELECT * FROM alunos");
// $a = pg_fetch_object($query);
// echo json_encode($a);

while (1) {
  $result = "";
  
  // wait for one Notify 10seconds instead of using sleep(10)
  $result = $dbconn->pgsqlGetNotify(PDO::FETCH_ASSOC, 1000); 
  
  if ( $result ) { 
        echo stripslashes(json_encode($result));
        exit;
        break;
  }

  flush();
}
?>

