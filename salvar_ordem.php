<?php
// salvar_ordem.php
// Recebe multipart/form-data (campos + arquivo opcional "plano_corte").
require_once __DIR__ . '/funcoes.php';
exigir_login_api();
exigir_metodo('POST');
require __DIR__ . '/conexao.php';

function falhar(string $mensagem, int $codigo = 400): void
{
    responder_json(['sucesso' => false, 'erro' => $mensagem], $codigo);
}

// ---------- 1. Validar os campos (nunca confiar só no JavaScript) ----------

// Só aceita texto simples (ignora campos enviados como array, ex.: cliente[]=x).
function campo(string $nome): string
{
    return isset($_POST[$nome]) && is_string($_POST[$nome]) ? trim($_POST[$nome]) : '';
}

$cliente   = campo('cliente');
$descricao = campo('descricao');
$material  = MATERIAIS[campo('material')] ?? null;
$espessura = filter_var(campo('espessura'), FILTER_VALIDATE_INT);

if ($cliente === '' || mb_strlen($cliente) > 120)     falhar('Informe o cliente (até 120 caracteres).');
if ($descricao === '' || mb_strlen($descricao) > 255) falhar('Informe a descrição (até 255 caracteres).');
if ($material === null)                               falhar('Material inválido.');
if (!in_array($espessura, ESPESSURAS_MM, true))       falhar('Espessura inválida.');

// ---------- 2. Validar e guardar o anexo (opcional) ----------

$arquivoNome = null;
$arquivoPath = null;

if (isset($_FILES['plano_corte']) && $_FILES['plano_corte']['error'] !== UPLOAD_ERR_NO_FILE) {
    $f = $_FILES['plano_corte'];

    if ($f['error'] === UPLOAD_ERR_INI_SIZE || $f['error'] === UPLOAD_ERR_FORM_SIZE) {
        falhar('O arquivo é muito grande. O limite é 5 MB.');
    }
    if ($f['error'] !== UPLOAD_ERR_OK) {
        falhar('Não foi possível receber o arquivo.');
    }
    if ($f['size'] > TAMANHO_MAX_ANEXO) {
        falhar('O arquivo é muito grande. O limite é 5 MB.');
    }

    $extensao = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
    if (!in_array($extensao, EXTENSOES_ANEXO, true)) {
        falhar('Apenas arquivos PDF ou DXF são permitidos.');
    }

    // PDF de verdade começa com "%PDF" (a extensão sozinha não prova nada).
    if ($extensao === 'pdf') {
        $inicio = file_get_contents($f['tmp_name'], false, null, 0, 4);
        if ($inicio !== '%PDF') {
            falhar('O arquivo não parece ser um PDF válido.');
        }
    }

    if (!is_dir(DIR_ANEXOS) && !mkdir(DIR_ANEXOS, 0755, true)) {
        error_log('salvar_ordem: não foi possível criar ' . DIR_ANEXOS);
        falhar('Erro ao salvar o arquivo.', 500);
    }

    // Nome aleatório no disco: evita colisão e impede nomes maliciosos.
    $arquivoPath = bin2hex(random_bytes(16)) . '.' . $extensao;
    if (!move_uploaded_file($f['tmp_name'], DIR_ANEXOS . '/' . $arquivoPath)) {
        error_log('salvar_ordem: move_uploaded_file falhou');
        falhar('Erro ao salvar o arquivo.', 500);
    }

    $arquivoNome = mb_substr(basename($f['name']), 0, 255);
}

// ---------- 3. Inserir no banco ----------

$stmt = $conn->prepare(
    'INSERT INTO ordens (cliente, descricao, material, espessura, arquivo_nome, arquivo_path)
     VALUES (?, ?, ?, ?, ?, ?)'
);
$stmt->bind_param('sssiss', $cliente, $descricao, $material, $espessura, $arquivoNome, $arquivoPath);

if (!$stmt->execute()) {
    error_log('salvar_ordem: ' . $stmt->error);
    if ($arquivoPath !== null) {
        @unlink(DIR_ANEXOS . '/' . $arquivoPath); // não deixa arquivo órfão
    }
    falhar('Erro ao salvar a ordem.', 500);
}

responder_json(['sucesso' => true, 'id' => $conn->insert_id]);
