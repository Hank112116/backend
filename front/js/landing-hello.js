'use strict';

import superagent from 'superagent';

var frontUrl = document.querySelector('[data-front-url]').dataset.frontUrl,
    destination = document.querySelector('[name=destination]');

document.querySelector('#test').addEventListener('click', () => {
    var url = 'https://'+frontUrl+destination.value;
    window.open(url, '_blank');
});
document.querySelector('#save').addEventListener('click', () => {
    superagent.post('/landing/update-hello-redirect')
        .send({ destination: destination.value })
        .set('Accept', 'application/json')
        .end(() => Notifier.showTimedMessage('Update success', 'success', 5));
});