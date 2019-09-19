$(function () {

    //inline
    console.log('tttt2121');

    $('a.editable.editable-invoice').editable({
        type: 'text',
        name: 'invoice',
        url: '/admin/order/editable',
        title: 'Введите номер накладной',
        pk: window.location.pathname.substr(window.location.pathname.lastIndexOf('/') + 1),
        mode: 'inline',
        success: function (response, newValue) {
            if (response.status == 'error') return response.msg; //msg will be shown in editable form
        }
    });

    $('a.editable.editable-status').editable({
        type: 'select',
        name: 'status',
        url: '/admin/order/editable',
        prepend: 'Выберите статус',
        pk: window.location.pathname.substr(window.location.pathname.lastIndexOf('/') + 1),
        mode: 'inline',
        source: [
            {
                value: 1,
                text: 'Новый'
            },
            {
                value: 2,
                text: 'Обработан'
            },
            {
                value: 3,
                text: 'Передан в службу доставки'
            },
            {
                value: 4,
                text: 'Выполнен'
            },
            {
                value: 5,
                text: 'Отменен'
            },
        ],
        success: function (response, newValue) {
            if (response.status == 'error') return response.msg; //msg will be shown in editable form
        }
    });

    $('a.editable.editable-phone_status').editable({
        type: 'select',
        name: 'phone_status',
        url: '/admin/order/editable',
        prepend: 'Выберите телефонный статус',
        pk: window.location.pathname.substr(window.location.pathname.lastIndexOf('/') + 1),
        mode: 'inline',
        source: [
            {
                value: 1,
                text: 'Ждет звонка'
            },
            {
                value: 2,
                text: 'Не звонить'
            },
            {
                value: 3,
                text: 'Не взял'
            },
            {
                value: 4,
                text: 'Отбился'
            },
            {
                value: 5,
                text: 'Нужно перезвонить'
            },
        ],
        success: function (response, newValue) {
            if (response.status == 'error') return response.msg; //msg will be shown in editable form
        }
    });

});