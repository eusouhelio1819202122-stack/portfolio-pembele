<?php
$supabase_url = "https://jqjpupcpgzysqlzhaexa.supabase.co";
$supabase_key = "sb_secret_PE7E75kXZqOmBT96vHhxqw_QUK0otlT"; // ⚠️ Garanta que sua chave real está aqui

function conectarSupabase($endpoint, $metodo = 'GET', $dados = null, $is_storage = false)
{
  global $supabase_url, $supabase_key;

  // Se for storage, muda a URL base
  $base_url = $is_storage ? $supabase_url . "/storage/v1/" : $supabase_url . "/rest/v1/";
  $url = $base_url . $endpoint;

  $ch = curl_init($url);

  $headers = [
    "apikey: " . $supabase_key,
    "Authorization: Bearer " . $supabase_key
  ];

  // Se não for envio de arquivo bruto, define como JSON
  if (!$is_storage) {
    $headers[] = "Content-Type: application/json";
    $headers[] = "Prefer: return=representation";
  }

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

  if ($metodo === 'POST') {
    curl_setopt($ch, CURLOPT_POST, true);
    if ($is_storage) {
      curl_setopt($ch, CURLOPT_POSTFIELDS, $dados); // Envia o arquivo binário
    } else {
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));
    }
  }

  $resposta = curl_exec($ch);
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

  return [
    'codigo' => $http_code,
    'corpo' => json_decode($resposta, true) ? json_decode($resposta, true) : $resposta
  ];
}
?>