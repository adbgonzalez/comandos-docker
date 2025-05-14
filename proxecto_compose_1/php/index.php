<?php
$mysqli = new mysqli("db", "usuario", "contrasinal", "proxecto");

if ($mysqli->connect_error) {
    die("Erro de conexión: " . $mysqli->connect_error);
}
echo "Conexión á base de datos correcta!<br>";

$result = $mysqli->query("SHOW DATABASES;");
while ($row = $result->fetch_assoc()) {
    echo "Base de datos: " . $row['Database'] . "<br>";
}
?>