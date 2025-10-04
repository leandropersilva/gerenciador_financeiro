class AjaxService {
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
        // O $.ajax já retorna uma Promise (jqXHR object)
        return $.ajax(settings);
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
}
