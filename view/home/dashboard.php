<div class="container">
    <form id="ai-form" class="mt-5">
        <div class="form-group d-flex gap-3">
            <textarea id="prompt" name="prompt" class="form-control" placeholder="Digite sua pergunta aqui..."></textarea>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </div>
    </form>

    <div id="response-container" class="mt-5" style="display:none;">
        <h2 class="text-center">Resposta:</h2>
        <div id="response" class="alert alert-secondary" role="alert"></div>
    </div>
</div>