<?php
session_start();
$tituloPagina = 'Painel Admin - Tour Connect';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $tituloPagina ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/pages/admin/admin.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h1><i class="fas fa-compass"></i> Tour Connect</h1>
            <nav>
                <a href="#" class="ativo" data-tab="passeios"><i class="fas fa-mountain"></i> Passeios</a>
                <a href="#" data-tab="guias"><i class="fas fa-user-tie"></i> Guias</a>
                <a href="#" data-tab="agendamentos"><i class="fas fa-calendar-alt"></i> Agendamentos</a>
                <a href="#" data-tab="solicitacoes"><i class="fas fa-user-plus"></i> Solicitações <span id="badge-solicitacoes" class="badge-notif" style="display:none;">0</span></a>
                <a href="../inicio.php"><i class="fas fa-home"></i> Ver Site</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Passeios Tab -->
            <div id="tab-passeios" class="tab-content ativo">
                <div class="page-header">
                    <h2><i class="fas fa-mountain"></i> Passeios</h2>
                    <button class="btn btn-primary" onclick="abrirModalPasseio()">
                        <i class="fas fa-plus"></i> Novo Passeio
                    </button>
                </div>
                <div id="lista-passeios" class="cards-grid">
                    <div class="empty-state">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p>Carregando passeios...</p>
                    </div>
                </div>
            </div>

            <!-- Guias Tab -->
            <div id="tab-guias" class="tab-content">
                <div class="page-header">
                    <h2><i class="fas fa-user-tie"></i> Guias</h2>
                    <button class="btn btn-primary" onclick="abrirModalGuia()">
                        <i class="fas fa-plus"></i> Novo Guia
                    </button>
                </div>
                <div id="lista-guias" class="cards-grid">
                    <div class="empty-state">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p>Carregando guias...</p>
                    </div>
                </div>
            </div>

            <!-- Agendamentos Tab -->
            <div id="tab-agendamentos" class="tab-content">
                <div class="page-header">
                    <h2><i class="fas fa-calendar-alt"></i> Agendamentos</h2>
                </div>
                <div id="lista-agendamentos" class="agendamentos-lista-admin">
                    <div class="empty-state">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p>Carregando agendamentos...</p>
                    </div>
                </div>
            </div>

            <!-- Solicitações Tab -->
            <div id="tab-solicitacoes" class="tab-content">
                <div class="page-header">
                    <h2><i class="fas fa-user-plus"></i> Solicitações para ser Guia</h2>
                </div>
                <div id="lista-solicitacoes" class="solicitacoes-lista">
                    <div class="empty-state">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p>Carregando solicitações...</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Passeio -->
    <div id="modal-passeio" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modal-passeio-titulo">Novo Passeio</h3>
                <button class="modal-close" onclick="fecharModal('modal-passeio')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="form-passeio" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="passeio-id">
                    <input type="hidden" name="acao" id="passeio-acao" value="criar">
                    
                    <div class="form-group">
                        <label>Imagens (até 5) * - A primeira será a principal</label>
                        <input type="file" name="imagens[]" id="passeio-imagens" accept="image/*" multiple>
                        <small style="color: #8892b0; margin-top: 5px; display: block;">Segure Ctrl para selecionar várias imagens</small>
                        <div id="preview-imagens"></div>
                    </div>
                    
                    <div class="form-group">
                        <label>Título *</label>
                        <input type="text" name="titulo" id="passeio-titulo" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Descrição Curta *</label>
                        <input type="text" name="descricao_curta" id="passeio-descricao-curta" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Descrição Completa *</label>
                        <textarea name="descricao" id="passeio-descricao" required></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Localização *</label>
                            <input type="text" name="localizacao" id="passeio-localizacao" placeholder="Cidade - UF" required>
                        </div>
                        <div class="form-group">
                            <label>Preço (R$) *</label>
                            <input type="number" name="preco" id="passeio-preco" step="0.01" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Duração *</label>
                            <input type="text" name="duracao" id="passeio-duracao" placeholder="Ex: 4 horas" required>
                        </div>
                        <div class="form-group">
                            <label>Dificuldade *</label>
                            <select name="dificuldade" id="passeio-dificuldade" required>
                                <option value="Fácil">Fácil</option>
                                <option value="Moderado">Moderado</option>
                                <option value="Difícil">Difícil</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-user-tie"></i> Guias deste passeio</label>
                        <div class="guias-search-container">
                            <div id="guias-selecionados" class="guias-tags"></div>
                            <input type="text" id="busca-guia" class="input-busca-guia" placeholder="Digite o nome do guia..." autocomplete="off">
                            <div id="guias-sugestoes" class="guias-dropdown"></div>
                        </div>
                        <small style="color: #8892b0; margin-top: 5px; display: block;">Digite para buscar e clique para adicionar</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-save"></i> Salvar Passeio
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Guia -->
    <div id="modal-guia" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modal-guia-titulo">Novo Guia</h3>
                <button class="modal-close" onclick="fecharModal('modal-guia')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="form-guia" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="guia-id">
                    <input type="hidden" name="acao" id="guia-acao" value="criar">
                    
                    <div class="form-group">
                        <label>Foto *</label>
                        <input type="file" name="imagem" id="guia-imagem" accept="image/*">
                    </div>
                    
                    <div class="form-group">
                        <label>Nome *</label>
                        <input type="text" name="nome" id="guia-nome" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Especialidade *</label>
                        <input type="text" name="especialidade" id="guia-especialidade" placeholder="Ex: Trilhas e Ecoturismo" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-save"></i> Salvar Guia
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div id="toast" class="toast"></div>

    <!-- Scripts do Admin (separados por funcionalidade) -->
    <script src="../../assets/js/pages/admin/admin.js"></script>
    <script src="../../assets/js/pages/admin/busca-guias.js"></script>
    <script src="../../assets/js/pages/admin/passeios.js"></script>
    <script src="../../assets/js/pages/admin/guias.js"></script>
    <script src="../../assets/js/pages/admin/agendamentos.js"></script>
    <script src="../../assets/js/pages/admin/solicitacoes.js"></script>
</body>
</html>
