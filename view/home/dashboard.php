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

<script>
    // Certifique-se de que o jQuery completo está carregado antes deste script

    $(document).ready(function() {
        $('#ai-form').on('submit', function(event) {
            event.preventDefault(); // Impede o envio padrão do formulário

            const promptText = $('#prompt').val();
            const $responseContainer = $('#response-container');
            const $responseDiv = $('#response');
            const $submitButton = $(this).find('button');

            if (!promptText.trim()) {
                alert('Por favor, digite uma pergunta.');
                return;
            }

            // Mostra um feedback de carregamento
            $responseDiv.text('Pensando...');
            $responseContainer.show();
            $submitButton.prop('disabled', true);

            // Requisição AJAX com jQuery
            $.ajax({
                    url: 'api_handler.php', // Verifique o caminho
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        prompt: promptText
                    }),
                    dataType: 'json'
                })
                .done(function(data) {
                    // Sucesso na requisição (equivale ao .then() para sucesso)
                    $responseDiv.text(data.reply);
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    // Falha na requisição (equivale ao .catch())
                    console.error('Erro na requisição:', textStatus, errorThrown);
                    const errorMsg = jqXHR.responseJSON?.error || 'Ocorreu um erro ao obter a resposta.';
                    $responseDiv.text(errorMsg);
                })
                .always(function() {
                    // Sempre executa, com sucesso ou falha (equivale ao .finally())
                    $submitButton.prop('disabled', false);
                });
        });
    });
</script>