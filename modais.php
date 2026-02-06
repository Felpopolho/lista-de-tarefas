<div class="modal fade" id="modalIncluir" tabindex="-1" data-bs-theme="dark">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: #202024; border: 1px solid #323238; color: #E1E1E6;">
            <form action="acoes.php" method="POST">
                <input type="hidden" name="acao" value="incluir">
                
                <div class="modal-header" style="border-bottom: 1px solid #323238;">
                    <h5 class="modal-title fw-bold text-success">
                        <i class="bi bi-plus-circle me-2"></i>Nova Ideia/Tarefa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-secondary">Nome da Tarefa</label>
                        <input type="text" name="nome" class="form-control bg-dark text-light border-secondary" required placeholder="Ex: Comprar material...">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-secondary">Custo (R$)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-secondary">R$</span>
                                <input type="number" name="custo" step="0.01" min="0" class="form-control bg-dark text-light border-secondary" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-secondary">Data Limite</label>
                            <input type="date" name="data_limite" class="form-control bg-dark text-light border-secondary" required>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer" style="border-top: 1px solid #323238;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn" style="background-color: #00B37E; color: white; font-weight: bold;">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>