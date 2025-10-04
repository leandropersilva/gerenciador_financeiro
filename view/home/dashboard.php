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
    $(document).ready(function() {
        $('#ai-form').on('submit', function(event) {
            event.preventDefault();

            const promptText = $('#prompt').val();
            const $responseContainer = $('#response-container');
            const $responseDiv = $('#response');
            const $submitButton = $(this).find('button');

            if (!promptText.trim()) {
                alert('Por favor, digite uma pergunta.');
                return;
            }

            $responseDiv.text('Pensando...');
            $responseContainer.show();
            $submitButton.prop('disabled', true);

            $.ajax({
                    url: '/ApiGemini/handler',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        prompt: promptText
                    }),
                    dataType: 'json',
                    timeout: 15000,
                    cache: false
                })
                .done(function(data) {
                    if (data && data.reply) {
                        $responseDiv.text(data.reply);
                    } else if (data && data.error) {
                        $responseDiv.text('Erro: ' + data.error);
                    } else {
                        $responseDiv.text('Resposta inesperada do servidor.');
                    }
                })
                .fail(function(jqXHR, textStatus) {
                    if (textStatus === 'timeout') {
                        $responseDiv.text('Tempo esgotado ao contatar o servidor.');
                        return;
                    }

                    switch (jqXHR.status) {
                        case 0:
                            $responseDiv.text('Falha de conexão. Verifique sua internet.');
                            break;
                        case 404:
                            $responseDiv.text('Serviço não encontrado.');
                            break;
                        case 500:
                            $responseDiv.text('Erro interno do servidor.');
                            break;
                        default:
                            const msg = jqXHR.responseJSON?.error || 'Erro ao processar requisição.';
                            $responseDiv.text(msg);
                    }
                })
                .always(function() {
                    $submitButton.prop('disabled', false);
                });
        });
    });
</script>
