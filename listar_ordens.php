<?php
// listar_ordens.php
require_once __DIR__ . '/funcoes.php';
exigir_login_api();
require __DIR__ . '/conexao.php';

// Fila de produção: o que está em aberto aparece primeiro, e dentro de cada
// etapa o pedido mais antigo vem antes. Não traz o anexo (ele é baixado à parte).
$sql = "SELECT id, cliente, descricao, material, espessura, status, data_envio, arquivo_nome
        FROM ordens
        ORDER BY FIELD(status, 'recebido', 'aguardando', 'produzindo', 'pronto', 'retirado'),
                 data_envio ASC, id ASC";

$result = $conn->query($sql);
if (!$result) {
    error_log('listar_ordens: ' . $conn->error);
    responder_json(['sucesso' => false, 'erro' => 'Erro ao buscar as ordens.'], 500);
}

$ordens = [];
while ($row = $result->fetch_assoc()) {
    $row['id']          = (int)$row['id'];
    $row['espessura']   = (int)$row['espessura'];
    $row['id_visual']   = '#' . $row['id'];
    $row['data']        = date('d/m/Y H:i', strtotime($row['data_envio']));
    $row['statusTexto'] = STATUS[$row['status']] ?? ucfirst($row['status']);
    $ordens[] = $row;
}

responder_json($ordens);
