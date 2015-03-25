var keyMirror = require('react/lib/keyMirror');

var constants = {};

constants = keyMirror({
    INIT_COMMENTS: null,

    FETCH_BY_SEARCH: null,
    FETCH_BY_PAGE: null,
    
    ATTACH_COMMENTS: null,
    TOGGLE_PRIVATE: null,
    DELETE_TOPIC: null,
    DELETE_THREAD: null
});

constants.PER_PAGE = 10;

module.exports= constants;