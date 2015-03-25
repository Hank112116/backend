var AppDispatcher = require('../dispatchers/AppDispatcher');
var InboxConstants = require('../constants/InboxConstants');

var InboxAction = {

    fetchTopics: function(page) {
        AppDispatcher.handleViewAction({
            actionType: InboxConstants.FETCH_BY_PAGE,
            page: page
        });
    },

    searchTopics: function(where, search) {
        AppDispatcher.handleViewAction({
            actionType: InboxConstants.FETCH_BY_SEARCH,
            where: where,
            search: search
        });
    },

    deleteTopic: function(topic_id) {
        AppDispatcher.handleViewAction({
            actionType: InboxConstants.DELETE_TOPIC,
            topic_id: topic_id
        });
    },

    deleteThread: function(thread_id) {
        AppDispatcher.handleViewAction({
            actionType: InboxConstants.DELETE_THREAD,
            thread_id: thread_id
        });
    }
};

module.exports = InboxAction;


