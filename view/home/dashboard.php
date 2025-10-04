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

            const apiService = new AjaxService();

            async function enviarPrompt(promptText) {
                try {
                    const data = await apiService.post('/ApiGemini/handler', {
                        prompt: promptText
                    });

                    console.log(data);

                    if (data && data.reply) {
                        return data.reply;
                    } else if (data && data.error) {
                        return 'Erro: ' + data.error;
                    } else {
                        return 'Resposta inesperada do servidor.';
                    }

                } catch (jqXHR) {
                    console.error("Falha na requisição:", jqXHR);

                    let errorMessage;
                    if (jqXHR.statusText === 'timeout') {
                        errorMessage = 'Tempo esgotado ao contatar o servidor.';
                    } else {
                        switch (jqXHR.status) {
                            case 0:
                                errorMessage = 'Falha de conexão. Verifique sua internet.';
                                break;
                            case 404:
                                errorMessage = 'Serviço não encontrado.';
                                break;
                            case 500:
                                errorMessage = 'Erro interno do servidor.';
                                break;
                            default:
                                const msg = jqXHR.responseJSON?.error || 'Erro ao processar requisição.';
                                errorMessage = msg;
                        }
                    }
                    return errorMessage; 
                }
            }

            enviarPrompt("Qual a cor do céu?")
                .then(resposta => {
                    console.log("Resultado final:", resposta);
                    $responseDiv.text(resposta); 
                })
                .finally(() => {
                    $submitButton.prop('disabled', false);
                });

        });
    });
</script>