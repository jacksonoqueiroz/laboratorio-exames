<!-- SIDEBAR -->

<nav id="sidebar">

    <div class="sidebar text-center">
        <img src="<?= URL ?>assets/images/logo.png" class="logo-sidebar">
        <h5 class="mt-2">LabSystem</h5>
        <small><?= $_SESSION['perfil'] ?></small>
    </div>

    <ul class="list-unstyled components">

        <!--ESSE SELECT PARA QUANTIDADE DE AGENDA HOJE-->
        <?php
            $stmt = $pdo->prepare("
                SELECT COUNT(*) 
                FROM agendamentos_exames
                WHERE status = 'Agendado'
                AND data_exame = CURDATE()
            ");
            $stmt->execute();
            $totalHoje = $stmt->fetchColumn();
            ?>


        <li class="<?= ($paginaAtual == 'home') ? 'active-menu' : '' ?>">

            <a href="<?= URL ?>home">
                <span><i class="bi bi-speedometer2"></i> <span>Dashboard</span>
            </a>
        </li>

        <li>
            <a href="<?= URL ?>cadastro">
                <i class="bi bi-person-plus-fill"></i> <span>Cadastros</span>
            </a>
        </li>

        <li>
            <a href="<?= URL ?>agendar_exames">
                <i class="bi bi-calendar"></i> <span>Agendar</span>
            </a>
        </li>

        <li>
            <a href="<?= URL ?>pesquisar-agendamentos">
                <i class="bi bi-search"></i> <span>Pesquisar</span>
            </a>
        </li>

        
        <li>
            <a href="#submenuExames" 
               data-bs-toggle="collapse" 
               role="button" 
               aria-expanded="false" 
               class="d-flex justify-content-between align-items-center">

                <span>
                    <i class="bi bi-clipboard-data"></i> Exames
                </span>

                <span>
                    <?php if ($totalHoje > 0): ?>
                        <span class="badge bg-danger me-2"><?= $totalHoje ?></span>
                    <?php endif; ?>
                    <i class="bi bi-chevron-down"></i>
                </span>
            </a>

            <ul class="collapse list-unstyled ps-3" id="submenuExames">

                <li>
                    <a href="<?= URL ?>exame-sangue-atendimento">
                        <i class="bi bi-calendar-plus"></i> Atendimento sangue
                    </a>
                </li>

                <li>
                    <a href="<?= URL ?>agendar_exames">
                        <i class="bi bi-calendar-check"></i> Agendar
                    </a>
                </li>

                <li>
                    <a href="<?= URL ?>pesquisar-agendamentos">
                        <i class="bi bi-search"></i> Pesquisar
                    </a>
                </li>

                <li>
                    <a href="<?= URL ?>agenda-exames">
                        <i class="bi bi-clipboard-check"></i> Abrir Exames
                    </a>
                </li>

                <li>
                    <a href="<?= URL ?>exame-sangue-fila-coleta">
                        <i class="bi bi-flask-florence"></i> Coleta sangue
                    </a>
                </li>

                <li>
                    <a href="<?= URL ?>exame-sangue-analise">
                        <i class="bi bi-measuring-cup"></i> Analise sangue
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="<?= URL ?>coleta-exames">
                <i class="bi bi-bar-chart-line-fill"></i> <span>Coleta</span>
            </a>
        </li>

        <li>
            <a href="<?= URL ?>relatorios">
                <i class="bi bi-bar-chart-line-fill"></i> <span>Relatórios</span>
            </a>
        </li>

        <li>
            <a href="<?= URL ?>perfil">
                <i class="bi bi-person-circle"></i> <span>Meu Perfil</span>
            </a>
        </li>

        <li>
            <a href="<?= URL ?>sair" class="text-danger">
                <i class="bi bi-box-arrow-right"></i> <span>Sair</span>
            </a>
        </li>

    </ul>

</nav>

<!-- CONTENT -->
<div id="content">

    <!-- TOPBAR -->
    <nav class="navbar navbar-expand-lg navbar-light topbar shadow-sm">

        <button type="button" id="sidebarCollapse" class="btn btn-toggle">
            <i class="bi bi-list"></i>
        </button>

        <div class="ml-auto d-flex align-items-center">

            <span class="mr-3">
                <i class="bi bi-person-badge"></i>
                <?= $_SESSION['nome'] ?>
            </span>

            <img src="<?= URL ?>assets/images/<?=$_SESSION['imagem']?>" class="avatar-top">

        </div>

    </nav>

    <div class="container-fluid mt-4">
