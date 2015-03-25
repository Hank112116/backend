var AppDispatcher = require('../dispatchers/AppDispatcher');
var InboxConstants = require('../constants/InboxConstants');

var InboxServerAction = {
    boot: function() {
        AppDispatcher.handleServerAction({
            actionType: InboxConstants.BOOT,
            page: 1
        });
    }
}

module.exports = InboxServerAction;