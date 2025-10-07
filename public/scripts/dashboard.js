$('#ai-form').on('submit', function (event) {
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

    const ajaxService = new AjaxService();

    ajaxService.enviarPrompt(promptText)
        .then(resposta => {
            $responseDiv.text(resposta);
        })
        .finally(() => {
            $submitButton.prop('disabled', false);
        });
});