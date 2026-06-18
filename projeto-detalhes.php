<?php
require_once 'conexao.php';

// Pega o ID do projeto vindo da URL
$projeto_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($projeto_id === 0) {
  header("Location: index.php");
  exit;
}

// 1. Busca os dados do projeto principal
$res_projeto = conectarSupabase("trabalhos?id=eq." . $projeto_id);
$projeto = null;
if ($res_projeto['codigo'] >= 200 && $res_projeto['codigo'] < 300 && !empty($res_projeto['corpo'])) {
  $projeto = $res_projeto['corpo'][0];
} else {
  echo "<h1>Projeto não encontrado.</h1><a href='index.php'>Voltar</a>";
  exit;
}

// 2. Busca todas as fotos secundárias da galeria deste projeto
$res_galeria = conectarSupabase("galeria_fotos?projeto_id=eq." . $projeto_id);
$fotos_galeria = [];
if ($res_galeria['codigo'] >= 200 && $res_galeria['codigo'] < 300) {
  $fotos_galeria = $res_galeria['corpo'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?php echo htmlspecialchars($projeto['titulo']); ?> | Detalhes do Projeto
  </title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

  <header class="main-header">
    <div class="logo">Pembele <span class="destaque">Pictures</span></div>
    <nav class="nav-links">
      <a href="index.php">← Voltar ao Portfólio</a>
    </nav>
  </header>

  <main class="secao-container" style="padding-top: 60px;">
    <div class="secao-header">
      <span class="categoria">
        <?php echo htmlspecialchars($projeto['servico']); ?>
      </span>
      <h2 style="font-size: 2.8rem; margin-top: 10px;">
        <?php echo htmlspecialchars($projeto['titulo']); ?>
      </h2>
      <p style="color: var(--cor-cinza); max-width: 700px; margin: 20px auto; font-size: 1.1rem;">
        <?php echo htmlspecialchars($projeto['descricao']); ?>
      </p>
    </div>

    <div class="grid-portfolio" style="margin-top: 40px; width: 100%;">
      <div class="card-projeto" style="grid-column: 1 / -1; max-height: 500px;">
        <div class="imagem-wrapper" style="padding-top: 40px; height: 450px;">
          <img src="<?php echo htmlspecialchars($projeto['imagem_url']); ?>" style="object-fit: cover;">
        </div>
      </div>

      <?php if (empty($fotos_galeria)): ?>
        <p class="aviso-vazio" style="grid-column: 1 / -1;">Imagens adicionais da galeria estão sendo carregadas pelo
          administrador...</p>
      <?php else: ?>
        <?php foreach ($fotos_galeria as $foto): ?>
          <div class="card-projeto">
            <div class="imagem-wrapper">
              <img src="<?php echo htmlspecialchars($foto['imagem_url']); ?>" alt="Foto da galeria" loading="lazy">
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </main>

  <footer class="main-footer">
    <p>&copy;
      <?php echo date('Y'); ?> Pembele Pictures. Todos os direitos reservados.
    </p>
  </footer>

</body>

</html>