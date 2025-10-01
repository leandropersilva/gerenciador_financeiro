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
                    url: '/ApiGemini/handler',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        prompt: promptText
                    }),
                    dataType: 'json',
                    timeout: 15000, // evita pendurar
                    cache: false
                })
                .done(function(data, textStatus, jqXHR) {
                    // Sucesso HTTP (ex.: 200)
                    // Se seu backend padroniza { reply, error }, valide contrato:
                    if (data && data.reply) {
                        $responseDiv.text(data.reply);
                    } else if (data && data.error) {
                        $responseDiv.text('Erro da aplicação: ' + data.error);
                    } else {
                        $responseDiv.text('Resposta inesperada do servidor.');
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    // Falhas de rede/HTTP
                    // jqXHR.status: 0 (rede/CORS/timeout), 404, 401, 500, etc.
                    if (textStatus === 'timeout') {
                        $responseDiv.text('Tempo esgotado ao contatar o endpoint.');
                        return;
                    }

                    switch (jqXHR.status) {
                        case 0:
                            // Sem conexão, CORS bloqueado, DNS, SSL, request abortada
                            $responseDiv.text('Falha de conexão (rede/CORS). Verifique o endpoint e a rede.');
                            break;
                        case 404:
                            $responseDiv.text('Endpoint não encontrado (404). Verifique a rota/URL.');
                            break;
                        case 401:
                            $responseDiv.text('Não autorizado (401). Sessão expirada ou credenciais ausentes.');
                            break;
                        case 403:
                            $responseDiv.text('Acesso negado (403).');
                            break;
                        case 415:
                            $responseDiv.text('Tipo de conteúdo inválido (415). Use application/json.');
                            break;
                        case 429:
                            $responseDiv.text('Muitas requisições (429). Tente novamente mais tarde.');
                            break;
                        case 500:
                            $responseDiv.text('Erro interno no servidor (500).');
                            break;
                        case 502:
                        case 503:
                        case 504:
                            $responseDiv.text('Falha no serviço upstream ou indisponível (5xx).');
                            break;
                        default:
                            // Tente extrair mensagem do backend
                            const msg = jqXHR.responseJSON?.error || jqXHR.responseText || errorThrown || 'Erro desconhecido.';
                            $responseDiv.text('Falha na requisição: ' + msg);
                    }
                })

        });
    });
</script>