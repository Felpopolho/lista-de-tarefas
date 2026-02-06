<div class="modal fade" id="modalIncluir" tabindex="-1" data-bs-theme="dark">
    <div class="modal-dialog modal-fullscreen-sm-down modal-dialog-centered">
        <div class="modal-content" style="background-color: #202024; border: 1px solid #323238; color: #E1E1E6;">
            <form action="acoes.php" method="POST">
                <input type="hidden" name="acao" value="incluir">
                
                <div class="modal-header" style="border-bottom: 1px solid #323238;">
                    <h5 class="modal-title fw-bold text-success">
                        <i class="bi bi-lightning-fill me-2"></i>Nova Tarefa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label text-secondary small text-uppercase fw-bold">O que precisa ser feito?</label>
                        <input type="text" name="nome" class="form-control form-control-lg bg-dark text-light border-secondary" required placeholder="Ex: Criar campanha...">
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label text-secondary small text-uppercase fw-bold">Custo</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-secondary">R$</span>
                                <input type="number" name="custo" step="0.01" min="0" class="form-control bg-dark text-light border-secondary" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary small text-uppercase fw-bold">Prazo</label>
                            <input type="date" name="data_limite" class="form-control bg-dark text-light border-secondary" required>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer" style="border-top: 1px solid #323238;">
                    <button type="button" class="btn btn-outline-secondary w-50" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn w-50" style="background-color: #00B37E; color: white; font-weight: bold;">Salvar Tarefa</button>
                </div>
            </form>
        </div>
    </div>
</div>