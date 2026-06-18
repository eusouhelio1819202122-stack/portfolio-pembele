<?php
session_start();

// Se já estiver logado, pula direto para o painel
if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
  header("Location: admin.php");
  exit;
}

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $senha = $_POST['senha'] ?? '';

  if ($senha === "pembele123") {
    $_SESSION['logado'] = true;
    header("Location: admin.php");
    exit;
  } else {
    $erro = "Senha incorreta! Tente novamente.";
  }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Pembele Pictures</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>

  <header class="main-header">
    <div class="logo">Pembele Pictures</div>
    <nav class="nav-links">
      <a href="index.php">← Voltar ao Site</a>
    </nav>
  </header>

  <main class="form-container" style="margin-top: 80px;">
    <h2>Acesso Restrito</h2>
    <p style="color: var(--cor-mutada); font-size: 0.9rem; margin-bottom: 20px;">Introduza a chave de acesso do
      administrador.</p>

    <?php if (!empty($erro)): ?>
      <div class="alerta alerta-erro"><?php echo $erro; ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
      <div class="form-group">
        <label for="senha">Chave Administrador</label>
        <input type="password" id="senha" name="senha" class="form-input" required autofocus>
      </div>
      <button type="submit" class="btn-submit">Entrar no Painel</button>
    </form>
  </main>

</body>

</html>