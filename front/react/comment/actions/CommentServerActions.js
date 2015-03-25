var AppDispatcher = require('../dispatchers/AppDispatcher');
var CommentConstants = require('../constants/CommentConstants');

var CommentServerAction = {

    initComments: function(page, type) {
        AppDispatcher.handleServerAction({
            actionType: CommentConstants.INIT_COMMENTS,
            page: page,
            type: type
        });
    }
    
}

module.exports = CommentServerAction;