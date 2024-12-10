<?php
$senha = '123'; // Substitua pela senha desejada
$hash = password_hash($senha, PASSWORD_DEFAULT);
echo "Hash da senha: " . $hash;
?>
