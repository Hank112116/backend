var EventEmitter = require('events').EventEmitter;

var AppDispatcher = require('../dispatchers/AppDispatcher');
var InboxConstants = require('../constants/InboxConstants');
var InboxApiUtils = require('../utils/InboxApiUtils');

var CHANGE_EVENT = 'change';

var _data = {

	topics: [],
    searched_topics: [],

	meta: {
		total: null,
		pages: null,
		at_page: null,
        use_search: false
	}
}, loaded_page = [];

var mergeTopics = function(new_topics, page) {
	_.each(new_topics, function(topic) {
        topic.page = page;
        topic.threads = _.sortBy(topic.threads, 'message_id');

		_data.topics.push(topic);
	});
}

var resetSearchTopics = function(new_topics) {
    _data.searched_topics = [];

    _.each(new_topics, function(topic) {
        topic.threads = _.sortBy(topic.threads, 'message_id');

        _data.searched_topics.push(topic);
    });
}

var InboxStore = _.extend(EventEmitter.prototype, {
    data: function() {
       return _data;
    },
    emitChange: function() {
        this.emit(CHANGE_EVENT);
    },
    addChangeListener: function(callback) {
        this.on(CHANGE_EVENT, callback);
    }
});

var InboxModifier = {
    initTopics: function(topics) {
        mergeTopics(topics.data, topics.current_page);

        _data.meta = {
            total: topics.total,
            pages: topics.last_page,
            at_page: topics.current_page
        };

        InboxStore.emitChange();
    },

    attachTopics: function(topics) {
        mergeTopics(topics.data, topics.current_page);

        InboxStore.emitChange();
    },

    resetSearchTopics: function(topics) {
        resetSearchTopics(topics.data);

        InboxStore.emitChange();
    },

    deleteTopic: function(topic_id) {
        _.remove(this.fetchTopics(), function(topic) {
            return topic.message_id == topic_id;
        });

        this.say('Topic Deleted');
        InboxStore.emitChange();
    },

    deleteThread: function(thread_id) {
        _.each(this.fetchTopics(), function(topic) {
            _.remove(topic.threads, function(thread) {
                return thread.message_id == thread_id;
            });
        });

        this.say('Thread Deleted');
        InboxStore.emitChange();
    },

    say: function(msg) {
        Notifier.showTimedMessage(msg, 'success', 5);
    },

    fetchTopics: function() {
        return _data.meta.use_search? _data.searched_topics : _data.topics;
    }

};

AppDispatcher.register(function(payload) {
   var action = payload.action;

    switch(action.actionType) {
        case InboxConstants.BOOT:

            InboxApiUtils.fetchTopics(
                action.page,
                InboxModifier.initTopics
            );

            break;

        case InboxConstants.FETCH_BY_SEARCH:

            var where = action.where,
                search = action.search;

            _data.meta.at_page = null;
            _data.meta.use_search = true;

            InboxApiUtils.searchTopics(
                where, search,
                InboxModifier.resetSearchTopics
            );

            break;

        case InboxConstants.FETCH_BY_PAGE:
            var page = action.page;

            _data.meta.at_page = page;
            _data.meta.use_search = false;

            if(loaded_page[page]) {
                InboxStore.emitChange();
                return;
            }

            loaded_page[page] = true;

            InboxApiUtils.fetchTopics(
                page,
                InboxModifier.attachTopics
            );

            break;

        case InboxConstants.DELETE_TOPIC:
            var topic_id = action.topic_id;

            InboxApiUtils.delete(topic_id, function() {
                InboxModifier.deleteTopic(topic_id);
            });
            break;

        case InboxConstants.DELETE_THREAD:
            var thread_id = action.thread_id;

            InboxApiUtils.delete(thread_id, function() {
                InboxModifier.deleteThread(thread_id);
            });
            break;
    }

    return true;
});

module.exports = InboxStore;
