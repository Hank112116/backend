var AppDispatcher = require('../dispatchers/AppDispatcher');
var CommentConstants = require('../constants/CommentConstants');

var CommentAction = {

    fetchComments: function(page) {
        AppDispatcher.handleViewAction({
            actionType: CommentConstants.FETCH_BY_PAGE,
            page: page
        });
    },

    searchComments: function(where, search) {
        AppDispatcher.handleViewAction({
            actionType: CommentConstants.FETCH_BY_SEARCH,
            where: where,
            search: search
        });
    },

    togglePrivate: function(topic_id) {
        AppDispatcher.handleViewAction({
            actionType: CommentConstants.TOGGLE_PRIVATE,
            topic_id: topic_id
        });
    },

    deleteTopic: function(topic_id) {
        AppDispatcher.handleViewAction({
            actionType: CommentConstants.DELETE_TOPIC,
            topic_id: topic_id
        });
    },

    deleteThread: function(thread_id) {
        AppDispatcher.handleViewAction({
            actionType: CommentConstants.DELETE_THREAD,
            thread_id: thread_id
        });
    }
};

module.exports = CommentAction;


