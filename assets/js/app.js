require('jquery');
global.$ = global.jQuery = $;

require('bootstrap');

import Choices from 'choices.js';
global.Choices = Choices;

import { saveAs } from 'file-saver';
var slug = require('slug');
slug.charmap['/'] = '-';
slug.charmap['?'] = '-';
slug.charmap['='] = '-';
global.slug = slug;

global.sleep = function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

global.createToast = function createToast(body) {
    var toast = `<div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body">
            ${body}
        </div>
    </div>`;
    $('#toast-container').prepend(toast);
    var toastObject = $('#toast-container .toast').first();
    toastObject.toast({'autohide': true, 'delay': 5000});
    toastObject.toast('show')
}

function messageToServiceWorker(content) {
    if('serviceWorker' in navigator && 'https:' == window.location.protocol) {
        navigator.serviceWorker.ready.then(function() {
            return new Promise(function(resolve, reject) {
                var messageChannel = new MessageChannel();
                messageChannel.port1.onmessage = function(event) {
                    if(event.data.error) {
                        reject(event.data.error);
                    } else {
                        resolve(event.data);
                    }
                };
                if(navigator.serviceWorker.controller) {
                    navigator.serviceWorker.controller.postMessage(content, [messageChannel.port2]);
                }
            });
        });
    }
}

if('serviceWorker' in navigator && 'https:' == window.location.protocol) {
    navigator.serviceWorker.register(app_base_url + 'serviceworker.js')
    .then(function(ServiceWorkerRegistration) {
        ServiceWorkerRegistration.addEventListener('updatefound', function() {
            messageToServiceWorker({'command': 'reload'});
        });
    });

    navigator.serviceWorker.addEventListener('message', function(MessageEvent) {
        switch(MessageEvent.data.type) {
            case 'reload':
                document.location.reload(true);
                break;
        }
    });
}

$(document).ready(function () {
    $('label.required').append(' <small class="badge bg-dark text-light ml-1">' + trans_required + '</small>');

    var selects = [].slice.call(document.querySelectorAll('select'));
    selects.map(function (select) {
        new Choices(select, {'searchResultLimit': 500, 'fuseOptions': {'threshold': 0.3}});
    });

    $(document).on('click', '.dashboard-table-expand', function(event) {
        event.preventDefault();
        $(this).remove();
        var table = $($(this).attr('href'));
        table.find('tr').removeClass('d-none');
    });
});
