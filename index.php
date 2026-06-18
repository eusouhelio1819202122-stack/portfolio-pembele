<?php
require_once 'conexao.php';

// 1. Busca as configurações de texto do site
$res_config = conectarSupabase("configuracoes?select=*");
$config = [];
if ($res_config['codigo'] >= 200 && $res_config['codigo'] < 300) {
  // Organiza o array para podermos usar como $config['chave']
  foreach ($res_config['corpo'] as $item) {
    $config[$item['chave']] = $item['valor'];
  }
}

// 2. Busca os trabalhos do portfólio
$resposta = conectarSupabase("trabalhos?select=*&order=id.desc");
$trabalhos = [];
if ($resposta['codigo'] >= 200 && $resposta['codigo'] < 300) {
  $trabalhos = $resposta['corpo'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pembele Pictures | Agência Audiovisual & Design Internacional</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

  <a href="https://wa.me/<?php echo $config['contacto_whatsapp'] ?? ''; ?>" class="whatsapp-float" target="_blank"
    rel="noopener noreferrer">
    <i class="fab fa-whatsapp"></i>
  </a>

  <header class="main-header">
    <div class="logo">Pembele <span class="destaque">Pictures</span></div>
    <nav class="nav-links">
      <a href="#home">Home</a>
      <a href="#servicos">Serviços</a>
      <a href="#sobre">Sobre</a>
      <a href="#portfolio">Portfólio</a>
      <a href="#orcamento" class="btn-orcamento-nav">Pedir Orçamento</a>
      <a href="login.php" class="btn-admin"><i class="fa-solid fa-lock"></i></a>
    </nav>
  </header>

  <section id="home" class="hero-section">
    <div class="hero-conteudo">
      <span class="Tagline">Agência Criativa Global</span>
      <h1>
        <?php echo htmlspecialchars($config['hero_titulo'] ?? 'Título Padrão'); ?>
      </h1>
      <p>
        <?php echo htmlspecialchars($config['hero_subtitulo'] ?? 'Subtítulo Padrão'); ?>
      </p>
      <div class="hero-botoes">
        <a href="#orcamento" class="btn-principal">Iniciar Projeto</a>
        <a href="#portfolio" class="btn-secundario">Ver Portfólio</a>
      </div>
    </div>
  </section>

  <section id="servicos" class="secao-container">
    <div class="secao-header"><span>O que fazemos</span>
      <h2>Nossos Serviços</h2>
    </div>
    <div class="grid-servicos">
      <div class="card-servico"><i class="fas fa-video"></i>
        <h3>Produção Audiovisual</h3>
        <p>Comerciais, videoclipes e produções com qualidade de cinema.</p>
      </div>
      <div class="card-servico"><i class="fas fa-bezier-curve"></i>
        <h3>Design & Branding</h3>
        <p>Criação de identidades visuais fortes e posicionamento de marca.</p>
      </div>
      <div class="card-servico"><i class="fas fa-camera"></i>
        <h3>Fotografia Profissional</h3>
        <p>Fotografia corporativa, produtos e publicidade de alto padrão.</p>
      </div>
      <div class="card-servico"><i class="fas fa-film"></i>
        <h3>Motion Design</h3>
        <p>Animações em 2D/3D e efeitos visuais de alto engajamento.</p>
      </div>
    </div>
  </section>

  <section id="sobre" class="secao-container fundo-alternativo">
    <div class="grid-sobre">
      <div class="sobre-texto">
        <span>Quem Somos</span>
        <h2>
          <?php echo htmlspecialchars($config['sobre_titulo'] ?? 'Sobre Nós'); ?>
        </h2>
        <p>
          <?php echo htmlspecialchars($config['sobre_texto'] ?? 'Texto sobre a empresa...'); ?>
        </p>
        <div class="metricas">
          <div class="metrica-item"><strong>+100</strong>
            <p>Projetos</p>
          </div>
          <div class="metrica-item"><strong>100%</strong>
            <p>Foco</p>
          </div>
        </div>
      </div>
      <div class="sobre-decoracao">
        <div class="caixa-decorativa">Estética & Performance</div>
      </div>
    </div>
  </section>

  <section id="portfolio" class="secao-container">
    <div class="secao-header"><span>Explorar Galeria</span>
      <h2>Trabalhos Recentes</h2>
    </div>
    <?php if (empty($trabalhos)): ?>
      <p class="aviso-vazio">Nenhum projeto foi publicado recentemente.</p>
    <?php else: ?>
      <div class="grid-portfolio">
        <?php foreach ($trabalhos as $trabalho): ?>
          <article class="card-projeto" style="cursor: pointer;"
            onclick="location.href='projeto-detalhes.php?id=<?php echo $trabalho['id']; ?>'">
            <div class="imagem-wrapper">
              <img src="<?php echo htmlspecialchars($trabalho['imagem_url']); ?>"
                alt="<?php echo htmlspecialchars($trabalho['titulo']); ?>" loading="lazy">
            </div>
            <div class="info-projeto">
              <span class="categoria"><?php echo htmlspecialchars($trabalho['servico']); ?></span>
              <h3><?php echo htmlspecialchars($trabalho['titulo']); ?></h3>
              <p><?php echo htmlspecialchars($trabalho['descricao']); ?></p>
              <span
                style="font-size: 0.8rem; color: #fff; margin-top: 15px; display: inline-block; text-decoration: underline;">Ver
                galeria completa →</span>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>

  <section id="orcamento" class="secao-container fundo-alternativo">
    <div class="grid-contacto">
      <div class="contacto-info">
        <span>Contacto</span>
        <h2>Pronto para criar algo incrível?</h2>
        <div class="lista-contactos">
          <div class="item-contacto"><i class="fas fa-envelope"></i>
            <?php echo htmlspecialchars($config['contacto_email'] ?? ''); ?>
          </div>
          <div class="item-contacto"><i class="fas fa-phone"></i>
            <?php echo htmlspecialchars($config['contacto_telefone'] ?? ''); ?>
          </div>
          <div class="item-contacto"><i class="fas fa-location-dot"></i>
            <?php echo htmlspecialchars($config['contacto_localizacao'] ?? ''); ?>
          </div>
        </div>
      </div>
      <div class="formulario-orcamento">
        <form action="https://wa.me/<?php echo $config['contacto_whatsapp'] ?? ''; ?>" method="GET" target="_blank">
          <div class="form-group-site"><label>Seu Nome</label><input type="text" placeholder="Ex: João" required
              class="input-site"></div>
          <div class="form-group-site">
            <label>Serviço Pretendido</label>
            <select class="input-site" name="text" required>
              <option value="Olá! Quero um orçamento para Audiovisual.">Produção Audiovisual</option>
              <option value="Olá! Quero um orçamento para Design.">Design & Branding</option>
            </select>
          </div>
          <button type="submit" class="btn-submit-site">Solicitar Proposta via WhatsApp</button>
        </form>
      </div>
    </div>
  </section>

  <footer class="main-footer">
    <p>&copy;
      <?php echo date('Y'); ?> Pembele Pictures. Todos os direitos reservados.
    </p>
  </footer>

</body>

</html>