var EventEmitter = require('events').EventEmitter;

var AppDispatcher = require('../dispatchers/AppDispatcher');
var CommentConstants = require('../constants/CommentConstants');
var CommentApiUtils = require('../utils/CommentApiUtils');

var CHANGE_EVENT = 'change';

var _data = {

    comments: [],
    searched_comments: [],

    meta: {
        total: null,
        pages: null,
        at_page: null,
        use_search: false
    }
}, loaded_page = [];

var mergeComments = function (new_comments, page) {
    _.each(new_comments, function (comment) {
        comment.page = page;
        comment.threads = _.sortBy(comment.threads, function (thread) {
            return +thread.comment_id;
        });
        _data.comments.push(comment);
    });
}

var resetSearchComments = function (new_comments) {
    _data.searched_comments = [];

    _.each(new_comments, function (comment) {
        comment.threads = _.sortBy(comment.threads, function (thread) {
            return +thread.comment_id;
        });
        _data.searched_comments.push(comment);
    });
}

var CommentStore = _.extend(EventEmitter.prototype, {
    data: function () {
        return _data;
    },
    emitChange: function () {
        this.emit(CHANGE_EVENT);
    },
    addChangeListener: function (callback) {
        this.on(CHANGE_EVENT, callback);
    }
});

var CommentModifier = {
    initComments: function (comments) {
        mergeComments(comments.data, comments.current_page);

        _data.meta = {
            total: comments.total,
            pages: comments.last_page,
            at_page: comments.current_page,
        };

        CommentStore.emitChange();
    },

    attachComments: function (comments) {
        mergeComments(comments.data, comments.current_page);

        CommentStore.emitChange();
    },

    resetSearchComments: function (comments) {
        resetSearchComments(comments.data);

        CommentStore.emitChange();
    },

    togglePrivate: function (topic_id) {

        _.each(this.fetchComments(), function (comment) {

                if (topic_id == comment.comment_id) {
                    var is_private = !!(+comment.private_comment);
                    comment.private_comment = !is_private;
                }

                return comment;
            }
        );

        CommentStore.emitChange();
    },

    deleteTopic: function (topic_id) {
        _.remove(this.fetchComments(), function (comment) {
            return comment.comment_id == topic_id;
        });

        this.say('Delete Topic Comment');
        CommentStore.emitChange();
    },

    deleteThread: function (thread_id) {
        _.each(this.fetchComments(), function (comment) {
            _.remove(comment.threads, function (thread) {
                return thread.comment_id == thread_id;
            });
        });

        this.say('Delete Thread Comment');

        CommentStore.emitChange();
    },

    say: function (msg) {
        Notifier.showTimedMessage(msg, 'success', 5);
    },

    fetchComments: function () {
        return _data.meta.use_search ?
            _data.searched_comments : _data.comments;
    }

};

AppDispatcher.register(function (payload) {
    var action = payload.action;

    switch (action.actionType) {
        case CommentConstants.INIT_COMMENTS:

            CommentApiUtils.initComments(
                action.page,
                action.type,
                CommentModifier.initComments
            );

            break;

        case CommentConstants.FETCH_BY_SEARCH:

            var where = action.where,
                search = action.search;

            _data.meta.at_page = null;
            _data.meta.use_search = true;

            CommentApiUtils.searchComments(
                where, search,
                CommentModifier.resetSearchComments
            );

            break;

        case CommentConstants.FETCH_BY_PAGE:
            var page = action.page;

            _data.meta.at_page = page;
            _data.meta.use_search = false;

            if (loaded_page[page]) {
                CommentStore.emitChange();
                return;
            }

            loaded_page[page] = true;

            CommentApiUtils.fetchComments(
                page,
                CommentModifier.attachComments
            );

            break;

        case CommentConstants.TOGGLE_PRIVATE:
            var topic_id = action.topic_id;

            CommentApiUtils.togglePrivate(topic_id);
            CommentModifier.togglePrivate(topic_id);

            break;

        case CommentConstants.DELETE_TOPIC:
            var topic_id = action.topic_id;

            CommentApiUtils.deleteComment(topic_id);
            CommentModifier.deleteTopic(topic_id);
            break;

        case CommentConstants.DELETE_THREAD:
            var thread_id = action.thread_id;

            CommentApiUtils.deleteComment(thread_id);
            CommentModifier.deleteThread(thread_id);
            break;
    }

    return true;
});

module.exports = CommentStore;
