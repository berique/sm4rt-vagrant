<h1>Pessoas cadastradas</h1>
<?php
$servername = "192.168.50.5";
$username = "root";
$password = "123456";

try {
    $conn = new PDO("mysql:host=$servername;dbname=sm4rtchange", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT id, nome FROM pessoas");
    if ( $stmt->execute() ) {
      echo "<ul>";
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>". $row['nome'] ."</li>";
      }
      echo "</ul>";
    } else {
      echo "Nenhum registro encontrado.";
    }
    $conn->close();
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
