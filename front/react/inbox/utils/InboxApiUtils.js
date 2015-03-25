var InboxConstants = require('../constants/InboxConstants');
var superagent = require('superagent');

window.superagent = superagent;

module.exports = {
    queryUri: '/inbox/topics',
    searchUri: '/inbox/search',
    deleteUri: '/inbox/delete/',

	fetchTopics: function(page, callback) {
		var url = this.queryUri + '?page=' + page + '&pp=' + InboxConstants.PER_PAGE;
		this.request(url, callback);
	},

	searchTopics: function(where, search, callback) {
		var url = this.searchUri + '?where=' + where + '&search=' + search;
		this.request(url, callback);
	},

	delete: function(message_id, callback) {
		var url = this.deleteUri + message_id;
		this.post(url, callback);
	},	

	request: function(url, callback) {
        callback = callback || function() {};

        superagent.get(url)
            .set('Accept', 'application/json')
            .end(function(error, res) {
                callback(res.body);
            });
	},

    post: function(url, callback) {
        callback = callback || function() {};
        superagent.post(url).end(callback);
    }
};