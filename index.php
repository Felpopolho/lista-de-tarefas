<?php
require 'config.php';
include 'modais.php';

try {
    $sql = "SELECT * FROM Tarefas ORDER BY ordem_apresentacao ASC";
    $stmt = $pdo->query($sql);
    $tarefas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tarefas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* --- Identidade Visual Energy Jr --- */
        :root {
            --bg-body: #121214;       
            --bg-card: #202024;       
            --energy-green: #00B37E;  
            --energy-green-hover: #00875F;
            --text-color: #E1E1E6;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-color);
            font-family: sans-serif;
            padding-bottom: 100px;
        }

        .card-energy {
            background-color: var(--bg-card);
            border: 1px solid #323238;
            border-radius: 8px;
        }

        /* Botões */
        .btn-icon {
            background: transparent; border: 1px solid #323238; color: #8d8d99;
            padding: 4px 8px; border-radius: 4px; transition: 0.2s;
        }
        .btn-icon:hover { background: #323238; color: #fff; }
        
        .btn-move {
            border: none; background: transparent; color: #00B37E;
            padding: 0; line-height: 1; font-size: 1.2rem; /* Aumentei um pouco as setas */
        }
        .btn-move:hover { color: #fff; }
        .btn-move:disabled { color: #323238; cursor: not-allowed; }

        /* Tabela */
        .table { --bs-table-bg: transparent; --bs-table-color: var(--text-color); --bs-table-border-color: #323238; }
        .table-head-energy { background-color: #29292E !important; color: #fff; }
        
        .form-control.edit-mode { background-color: #121214; border-color: #00B37E; color: #fff; }

        /* Drag Handle (Alça de Arrastar) */
        .drag-handle { cursor: grab; padding: 10px; } /* Área de toque maior */
        .drag-handle:active { cursor: grabbing; color: #fff !important; }

        /* Custo Alto */
        .custo-alto td { color: #FBA94C !important; font-weight: bold; }
        .custo-alto input { color: #fff !important; }

        /* Footer */
        .footer-fixo {
            background-color: #202024; border-top: 1px solid #323238;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.5); z-index: 1000;
        }

        /* Mobile */
        @media (max-width: 768px) {
            .hide-mobile { display: none; }
            .table th, .table td { padding: 0.75rem 0.5rem; }
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-dark bg-transparent pt-4 mb-3">
        <div class="container d-flex align-items-center gap-2">
            <span class="h4 mb-0 fw-bold">Lista de Tarefas</span>
        </div>
    </nav>

    <div class="container">
        <div class="card card-energy shadow-lg">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-head-energy">
                            <tr>
                                <th style="width: 80px;">Ord.</th>
                                <th class="hide-mobile">ID</th>
                                <th>Tarefa</th>
                                <th>Custo</th>
                                <th class="hide-mobile">Data</th>
                                <th class="text-end pe-3">Ações</th>
                            </tr>
                        </thead>
                        
                        <tbody id="lista-tarefas">
                            <?php 
                                $soma_total = 0;
                                if (count($tarefas) > 0):
                                    foreach ($tarefas as $index => $tarefa): 
                                        $soma_total += $tarefa['custo'];
                                        $classe_linha = ($tarefa['custo'] >= 1000) ? 'custo-alto' : '';
                                        
                                        $is_first = ($index === 0) ? 'disabled' : '';
                                        $is_last  = ($index === count($tarefas) - 1) ? 'disabled' : '';
                                ?>
                                    <tr class="item-tabela <?= $classe_linha ?>" data-id="<?= $tarefa['id'] ?>" id="linha-<?= $tarefa['id'] ?>">
                                        
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <i class="bi bi-grip-vertical text-muted fs-3 drag-handle" title="Arraste para mover"></i>

                                                <div class="d-flex flex-column gap-1">
                                                    <button class="btn-move" onclick="moverItem(<?= $tarefa['id'] ?>, 'cima')" <?= $is_first ?>>
                                                        <i class="bi bi-caret-up-fill"></i>
                                                    </button>
                                                    <button class="btn-move" onclick="moverItem(<?= $tarefa['id'] ?>, 'baixo')" <?= $is_last ?>>
                                                        <i class="bi bi-caret-down-fill"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="hide-mobile"><span class="badge bg-dark border border-secondary"><?= $tarefa['id'] ?></span></td>
                                        
                                        <td>
                                            <span class="d-block view-mode text-truncate" style="max-width: 200px;"><?= htmlspecialchars($tarefa['nome']) ?></span>
                                            <input type="text" class="form-control form-control-sm edit-mode d-none" value="<?= htmlspecialchars($tarefa['nome']) ?>">
                                            
                                            <small class="d-md-none text-muted d-block mt-1 view-mode">
                                                <?= date('d/m/Y', strtotime($tarefa['data_limite'])) ?>
                                            </small>
                                            <input type="date" class="form-control form-control-sm edit-mode d-none mt-1 d-md-none" value="<?= $tarefa['data_limite'] ?>">
                                        </td>
                                        
                                        <td>
                                            <span class="d-block view-mode">R$ <?= number_format($tarefa['custo'], 2, ',', '.') ?></span>
                                            <input type="number" step="0.01" class="form-control form-control-sm edit-mode d-none" value="<?= $tarefa['custo'] ?>" style="min-width: 80px;">
                                        </td>
                                        
                                        <td class="hide-mobile">
                                            <span class="d-block view-mode"><?= date('d/m/Y', strtotime($tarefa['data_limite'])) ?></span>
                                            <input type="date" class="form-control form-control-sm edit-mode d-none" value="<?= $tarefa['data_limite'] ?>">
                                        </td>
                                        
                                        <td class="text-end pe-3">
                                            <div class="d-flex justify-content-end gap-1">
                                                <button class="btn-icon view-mode" onclick="toggleEdicao(<?= $tarefa['id'] ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                
                                                <button class="btn btn-sm btn-success edit-mode d-none" onclick="salvarEdicao(<?= $tarefa['id'] ?>)">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-secondary edit-mode d-none" onclick="toggleEdicao(<?= $tarefa['id'] ?>)">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>

                                                <a href="acoes.php?acao=excluir&id=<?= $tarefa['id'] ?>" class="btn-icon text-danger view-mode" onclick="return confirm('Excluir?')" style="border-color: #442; color: #f75a68;">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="6" class="text-center py-4 text-muted">Nenhuma tarefa.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="fixed-bottom footer-fixo py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex flex-column">
                <span class="text-muted small">Total</span>
                <span class="h5 fw-bold text-success mb-0">
                    R$ <?= number_format($soma_total, 2, ',', '.') ?>
                </span>
            </div>
            <button type="button" class="btn text-white shadow-sm px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalIncluir" style="background-color: #00B37E; font-weight: bold;">
                <i class="bi bi-plus-lg me-1"></i> Nova
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>

    <script>
        var el = document.getElementById('lista-tarefas');
        var sortable = Sortable.create(el, {
            handle: '.drag-handle', // MUDANÇA IMPORTANTE: Só arrasta se pegar no ícone
            animation: 150,
            ghostClass: 'bg-dark',
            onEnd: function () { salvarOrdemNoBanco(); }
        });

        function salvarOrdemNoBanco() {
            var ordem = sortable.toArray();
            fetch('acoes.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'acao=reordenar&ordem=' + ordem.join(',')
            });
            setTimeout(() => window.location.reload(), 300);
        }

        function moverItem(id, direcao) {
            const linha = document.getElementById('linha-' + id);
            const pai = linha.parentNode;
            
            if (direcao === 'cima') {
                const anterior = linha.previousElementSibling;
                if (anterior) {
                    pai.insertBefore(linha, anterior);
                    salvarOrdemNoBanco();
                }
            } else {
                const proximo = linha.nextElementSibling;
                if (proximo) {
                    pai.insertBefore(proximo, linha);
                    salvarOrdemNoBanco();
                }
            }
        }

        function toggleEdicao(id) {
            const linha = document.getElementById('linha-' + id);
            linha.querySelectorAll('.view-mode, .edit-mode').forEach(el => el.classList.toggle('d-none'));
        }

        function salvarEdicao(id) {
            const linha = document.getElementById('linha-' + id);
            const inputs = linha.querySelectorAll('input');
            
            if (!inputs[0].value || !inputs[1].value || (!inputs[2].value && !inputs[3].value)) {
                alert("Preencha todos os campos!"); return;
            }

            let dataFinal = inputs[3].value ? inputs[3].value : inputs[2].value;

            const dados = new URLSearchParams({
                acao: 'editar', id: id, nome: inputs[0].value, custo: inputs[1].value, data_limite: dataFinal
            });

            fetch('acoes.php', { method: 'POST', body: dados })
            .then(res => res.json())
            .then(data => {
                if(data.sucesso) window.location.reload();
                else alert(data.mensagem);
            });
        }
    </script>
</body>
</html>