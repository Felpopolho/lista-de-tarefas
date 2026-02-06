<?php
require 'config.php';
include 'modais.php';

// Busca tarefas ordenadas
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
            /* IMPORTANTE: Espaço no final para o footer fixo não cobrir o conteúdo */
            padding-bottom: 100px; 
            padding-top: 100px;
        }

        .card-energy {
            background-color: var(--bg-card);
            border: 1px solid #323238;
            border-radius: 8px;
        }

        .btn-energy {
            background-color: var(--energy-green);
            color: white;
            font-weight: bold;
            border: none;
            transition: 0.2s;
        }
        .btn-energy:hover {
            background-color: var(--energy-green-hover);
            color: white;
        }

        .table {
            --bs-table-bg: transparent;
            --bs-table-color: var(--text-color);
            --bs-table-border-color: #323238;
        }
        
        .table-head-energy {
            background-color: #29292E !important;
            color: #fff;
        }
        
        .form-control.edit-mode {
            background-color: #121214;
            border-color: #00B37E;
            color: #fff;
        }

        .draggable-item { cursor: grab; }
        .draggable-item:active { cursor: grabbing; }

        .custo-alto td { 
            color: #FBA94C !important; 
            font-weight: bold;
        }
        .custo-alto input { color: #fff !important; }

        .d-none { display: none !important; }
        .text-muted { color: #8d8d99 !important; }
        
        /* Estilo do Footer Fixo */
        .footer-fixo {
            background-color: #202024;
            border-top: 1px solid #323238;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.5);
        }
    </style>
</head>

<body>

    <div class="fixed-top footer-fixo py-3">
        <div class="container d-flex justify-content-between align-items-center">
            
            <div class="d-flex flex-column">
                <span class="h4 mb-0 fw-bold">Lista de Tarefas</span>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card card-energy shadow-lg">
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-head-energy">
                        <tr>
                            <th></th>
                            <th>Tarefa</th>
                            <th>Custo (R$)</th>
                            <th>Data Limite</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    
                    <tbody id="lista-tarefas">
                        <?php 
                            $soma_total = 0;
                            if (count($tarefas) > 0):
                                foreach ($tarefas as $tarefa): 
                                    $soma_total += $tarefa['custo'];
                                    $classe_linha = ($tarefa['custo'] >= 1000) ? 'custo-alto' : '';
                            ?>
                                <tr class="draggable-item <?= $classe_linha ?>" data-id="<?= $tarefa['id'] ?>" id="linha-<?= $tarefa['id'] ?>">
                                    <td class="text-center"><i class="bi bi-grip-vertical text-muted fs-5"></i></td>
                                    <td>
                                        <span class="d-block view-mode"><?= htmlspecialchars($tarefa['nome']) ?></span>
                                        <input type="text" class="form-control form-control-sm edit-mode d-none" value="<?= htmlspecialchars($tarefa['nome']) ?>">
                                    </td>
                                    <td>
                                        <span class="d-block view-mode">R$ <?= number_format($tarefa['custo'], 2, ',', '.') ?></span>
                                        <input type="number" step="0.01" class="form-control form-control-sm edit-mode d-none" value="<?= $tarefa['custo'] ?>">
                                    </td>
                                    <td>
                                        <span class="d-block view-mode"><?= date('d/m/Y', strtotime($tarefa['data_limite'])) ?></span>
                                        <input type="date" class="form-control form-control-sm edit-mode d-none" value="<?= $tarefa['data_limite'] ?>">
                                    </td>
                                    <td class="text-end pe-3">
                                        <button class="btn btn-sm btn-outline-secondary view-mode border-0" onclick="toggleEdicao(<?= $tarefa['id'] ?>)" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-energy edit-mode d-none" onclick="salvarEdicao(<?= $tarefa['id'] ?>)">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary edit-mode d-none" onclick="toggleEdicao(<?= $tarefa['id'] ?>)">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                        <a href="acoes.php?acao=excluir&id=<?= $tarefa['id'] ?>" class="btn btn-sm btn-outline-danger view-mode border-0 ms-1" onclick="return confirm('Excluir esta tarefa?')" title="Excluir">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="6" class="text-center py-4 text-muted">Nenhuma tarefa cadastrada.</td></tr>
                        <?php endif; ?>
                    </tbody>
                    </table>
            </div>
        </div>
    </div>

    <div class="fixed-bottom footer-fixo py-3">
        <div class="container d-flex justify-content-between align-items-center">
            
            <div class="d-flex flex-column">
                <span class="text-muted small">Total Acumulado</span>
                <span class="h4 fw-bold text-success mb-0">
                    R$ <?= number_format($soma_total, 2, ',', '.') ?>
                </span>
            </div>

            <button type="button" class="btn btn-energy shadow-sm px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalIncluir">
                <i class="bi bi-plus-lg me-2"></i> Nova Tarefa
            </button>
            
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>

    <script>
        var el = document.getElementById('lista-tarefas');
        var sortable = Sortable.create(el, {
            handle: '.bi-grip-vertical',
            animation: 150,
            ghostClass: 'bg-dark',
            // Removi o filtro .static pois o botão não está mais na tabela
            onEnd: function () {
                var ordem = sortable.toArray();
                fetch('acoes.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'acao=reordenar&ordem=' + ordem.join(',')
                });
            }
        });

        function toggleEdicao(id) {
            const linha = document.getElementById('linha-' + id);
            linha.querySelectorAll('.view-mode, .edit-mode').forEach(el => el.classList.toggle('d-none'));
        }

        function salvarEdicao(id) {
            const linha = document.getElementById('linha-' + id);
            const inputs = linha.querySelectorAll('input');
            const dados = new URLSearchParams({
                acao: 'editar', id: id, nome: inputs[0].value, custo: inputs[1].value, data_limite: inputs[2].value
            });
            if (!inputs[0].value || !inputs[1].value || !inputs[2].value){
                alert("Informe todos os dados!");
            } else {
                fetch('acoes.php', { method: 'POST', body: dados })
                .then(res => res.json())
                .then(data => {
                if(data.sucesso) window.location.reload();
                else alert(data.mensagem);
                });
            }
        }
    </script>
</body>
</html>