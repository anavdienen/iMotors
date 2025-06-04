<?php
$con = mysqli_connect("localhost", "root", "", "a3");
if (!$con) {
    die("Erro ao conectar ao banco de dados: " . mysqli_connect_error());
}
?>