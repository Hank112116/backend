var keyMirror = require('react/lib/keyMirror');

var constants = keyMirror({
    BOOT: null,

    FETCH_BY_SEARCH: null,
    FETCH_BY_PAGE: null,
    
    ATTACH_INBOX: null,

    DELETE_TOPIC: null,
    DELETE_THREAD: null
});

constants.PER_PAGE = 10;

module.exports= constants;