<?php
// config.example.php
// 1. Copie este arquivo para config.local.php
// 2. Preencha com os dados do SEU MySQL
// O config.local.php não vai para o GitHub (está no .gitignore).

return [
    'host' => '127.0.0.1',       // use 127.0.0.1 para forçar conexão pela rede (TCP)
    'port' => 3306,              // porta do seu MySQL
    'user' => 'root',
    'pass' => 'COLOQUE_SUA_SENHA_AQUI',
    'name' => 'sistema_ordens',  // nome do banco onde você rodou o schema.sql
];
