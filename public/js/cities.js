jQuery(document).ready(function ($) {
    'use strict';

    const stateIdUrl = $('[id$=form_stateId]').data('autocomplete-url');

    $('[id$=form_stateId]').select2({
        theme: 'bootstrap4',
        minimumInputLength: 3,
        allowClear: true,
        placeholder: '',
        ajax: {
            url: stateIdUrl,
            method: 'POST',
            dataType: 'json',
            delay: 300,
            cache: true,
            headers: {
                'X-XSRF-Token': getCookie('XSRF-Token')
            },
            data: function (params) {
                return {
                    term: params.term,
                    page: params.page
                };
            }
        },
    });
});
