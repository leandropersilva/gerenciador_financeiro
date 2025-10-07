export default class AjaxService {
    constructor(defaults = {}) {
        this.defaults = {
            url: '',
            type: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            timeout: 15000,
            cache: false,
            ...defaults
        };
    }

    request(config) {
        const settings = $.extend({}, this.defaults, config);
        return $.ajax(settings)
            .then(
                (data) => {
                    if (data && data.reply) {
                        return data.reply;
                    } else if (data && data.error) {
                        throw new Error(data.error);
                    } else {
                        return data;
                    }
                },
                (jqXHR) => {
                    throw this._handleError(jqXHR);
                }
            );
    }

    post(url, data, customConfig = {}) {
        return this.request({
            url,
            type: 'POST',
            data: JSON.stringify(data),
            ...customConfig
        });
    }

    get(url, customConfig = {}) {
        return this.request({
            url,
            type: 'GET',
            ...customConfig
        });
    }

    _handleError(jqXHR) {
        if (jqXHR.statusText === 'timeout') {
            return new Error('Tempo esgotado ao contatar o servidor.');
        }

        switch (jqXHR.status) {
            case 0:
                return new Error('Falha de conexão. Verifique sua internet.');
            case 404:
                return new Error('Serviço não encontrado.');
            case 500:
                return new Error('Erro interno do servidor.');
            default:
                const msg = jqXHR.responseJSON?.error || 'Erro ao processar requisição.';
                return new Error(msg);
        }
    }


    async enviarPrompt(promptText) {
        try {
            const resposta = await this.post('/ApiGemini/handler', {
                prompt: promptText
            });
            return resposta;
        } catch (error) {
            console.error("Falha na requisição:", error);
            return error.message;
        }
    }

}
