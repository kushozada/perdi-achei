<?php
/**
 * listar.php — Endpoint para retornar todos os registros cadastrados.
 *
 * Fluxo esperado:
 * 1. O JavaScript (script.js) envia uma requisição GET para este script.
 * 2. O script lê o arquivo registros.json do servidor.
 * 3. Retorna o conteúdo como um array JSON para o frontend.
 *
 * Este arquivo não modifica dados; apenas os lê e os expõe via HTTP.
 */

// Define que a resposta será sempre um JSON com codificação UTF-8,
// garantindo que acentos e caracteres especiais sejam transmitidos corretamente.
header("Content-Type: application/json; charset=UTF-8");

// Garante que apenas requisições GET sejam aceitas.
// Isso é uma boa prática: endpoints de leitura não devem aceitar métodos que alterem dados.
if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405); // 405 = Method Not Allowed
    echo json_encode([
        "erro" => "Método não permitido. Use GET."
    ], JSON_UNESCAPED_UNICODE);
    exit; // Encerra o script aqui, sem continuar a execução
}

// Caminho absoluto para o arquivo JSON onde os registros estão armazenados.
// __DIR__ garante que o caminho seja relativo ao diretório deste script,
// funcionando independentemente de onde o servidor Apache estiver instalado.
$arquivo = __DIR__ . "/registros.json";

// Se o arquivo ainda não existir (ex: nenhum registro foi cadastrado ainda),
// retorna um array vazio [] para que o JavaScript não receba um erro.
if (!file_exists($arquivo)) {
    echo json_encode([], JSON_UNESCAPED_UNICODE);
    exit;
}

// Lê todo o conteúdo do arquivo como uma string.
$conteudo = file_get_contents($arquivo);

// Converte a string JSON em um array PHP associativo (true = array, não objeto).
$registros = json_decode($conteudo, true);

// Proteção: se o arquivo estiver vazio ou corrompido, retorna um array vazio
// em vez de propagar um erro para o JavaScript.
if (!is_array($registros)) {
    echo json_encode([], JSON_UNESCAPED_UNICODE);
    exit;
}

// Retorna todos os registros como JSON.
// JSON_UNESCAPED_UNICODE preserva caracteres acentuados sem escaping desnecessário (ex: ã, ç, é).
echo json_encode($registros, JSON_UNESCAPED_UNICODE);