<?php
$supabase_url = "https://jqjpupcpgzysqlzhaexa.supabase.co";
$supabase_key = "sb_secret_PE7E75kXZqOmBT96vHhxqw_QUK0otlT"; // ⚠️ Garanta que sua chave real está aqui

function conectarSupabase($endpoint, $metodo = 'GET', $dados = null, $is_storage = false)
{
  global $supabase_url, $supabase_key;

  // Garante que o método venha sempre em maiúsculas (evita erros com 'patch' ou 'post')
  $metodo = strtoupper($metodo);

  // Se for storage, muda a URL base
  $base_url = $is_storage ? $supabase_url . "/storage/v1/" : $supabase_url . "/rest/v1/";
  $url = $base_url . $endpoint;

  $ch = curl_init($url);

  $headers = [
    "apikey: " . $supabase_key,
    "Authorization: Bearer " . $supabase_key
  ];

  // Se não for envio de arquivo bruto para o Storage, define como JSON
  if (!$is_storage) {
    $headers[] = "Content-Type: application/json";
    $headers[] = "Prefer: return=representation"; // Importante para o Supabase retornar os dados alterados
  } else {
    // Se for Storage e for um upload, define o content-type genérico para binários caso não venha definido
    $headers[] = "Content-Type: application/octet-stream";
  }

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

  // Força o cURL a usar o método correto (GET, POST, PATCH, DELETE, etc.)
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $metodo);

  // Se houver dados para enviar (seja no POST, PATCH, etc.)
  if ($dados !== null) {
    if ($is_storage) {
      curl_setopt($ch, CURLOPT_POSTFIELDS, $dados); // Envia o arquivo binário bruto
    } else {
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados)); // Envia o JSON estruturado
    }
  }

  $resposta = curl_exec($ch);
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch); // Fecha a conexão cURL para liberar memória

  return [
    'codigo' => $http_code,
    'corpo' => json_decode($resposta, true) !== null ? json_decode($resposta, true) : $resposta
  ];
}
?>
