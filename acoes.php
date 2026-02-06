<?php
require 'config.php';

$acao = $_REQUEST['acao'] ?? '';

// --- INCLUIR ---
if ($acao == 'incluir') {
    $nome = $_POST['nome'];
    $custo = $_POST['custo'];
    $data = $_POST['data_limite'];

    // 1. Verifica duplicidade
    $stmt = $pdo->prepare(query: "SELECT COUNT(*) FROM Tarefas WHERE nome = ?");
    $stmt->execute(params: [$nome]);
    if ($stmt->fetchColumn() > 0) {
        die("Erro: Já existe uma tarefa com esse nome. <a href='index.php'>Voltar</a>");
    }

    // 2. Pega a última ordem
    $stmt = $pdo->query("SELECT MAX(ordem_apresentacao) FROM Tarefas");
    $proxima_ordem = $stmt->fetchColumn() + 1;

    // 3. Insere
    $sql = "INSERT INTO Tarefas (nome, custo, data_limite, ordem_apresentacao) VALUES (?, ?, ?, ?)";
    $pdo->prepare($sql)->execute(params: [$nome, $custo, $data, $proxima_ordem]);

    header('Location: index.php');
    exit;
}

// --- EXCLUIR ---
if ($acao == 'excluir') {
    $id = $_GET['id'];
    $pdo->prepare("DELETE FROM Tarefas WHERE id = ?")->execute([$id]);
    header('Location: index.php');
    exit;
}

// --- EDITAR (AJAX) ---
if ($acao == 'editar') {
    header('Content-Type: application/json');
    
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $custo = $_POST['custo'];
    $data = $_POST['data_limite'];

    // Verifica duplicidade (exceto se for o próprio ID)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Tarefas WHERE nome = ? AND id != ?");
    $stmt->execute([$nome, $id]);
    
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Nome já existe!']);
    } else {
        $sql = "UPDATE Tarefas SET nome = ?, custo = ?, data_limite = ? WHERE id = ?";
        $pdo->prepare($sql)->execute([$nome, $custo, $data, $id]);
        echo json_encode(['sucesso' => true]);
    }
    exit;
}

// --- REORDENAR (AJAX) ---
if ($acao == 'reordenar') {
    $ids = explode(',', $_POST['ordem']); // Recebe "1,5,2,3"
    
    // Atualiza um por um
    $sql = "UPDATE Tarefas SET ordem_apresentacao = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    
    foreach ($ids as $posicao => $id_tarefa) {
        $stmt->execute(params: [$posicao + 1, $id_tarefa]);
    }
    exit;
}
?>