import superagent from "superagent"

var CommentActions = require('../actions/CommentActions');
var CommentConstants = require('../constants/CommentConstants');

var categories = {
	'user' : {
		type: 'user',
		query: '/user/comments/professions',
		search: '/user/comments/search'
	},

	'project' : {
		type: 'project',
		query: '/project/comments/projects',
		search: '/project/comments/search'
	},

	'solution' : {
		type: 'solution',
		query: '/solution/comments/solutions',
		search: '/solution/comments/search'
	}
}

module.exports = {

	setCommentType: function(type) {
		this.type = type;
		this.query = categories[type].query;
		this.search = categories[type].search;
	},

	initComments: function(page, type, callback) {
		this.setCommentType(type);
		var url = this.query + '?page=' + page + '&pp=' + CommentConstants.PER_PAGE;
		this.request(url, callback);
	},

	fetchComments: function(page, callback) {
		var url = this.query + '?page=' + page + '&pp=' + CommentConstants.PER_PAGE;
		this.request(url, callback);
	},

	searchComments: function(where, search, callback) {
		var url = this.search + '?where=' + where + '&search=' + search;
		this.request(url, callback);
	},

	togglePrivate: function(topic_id) {
		var url = this.query + '/private/' + topic_id;
		this.request(url);
	},

	deleteComment: function(comment_id) {
		var url = this.query + '/delete/' + comment_id;
		this.request(url);
	},

	request: function(url, callback) {
		callback = callback || function() {};

		superagent
		    .get(url)
		    .set('Content-Type', 'application/json')
		    .end((err, res) => callback(res.body));

		// callback = callback || function() {};
		//
		//
		// var request = $.ajax({
		// 	url: url,
		// 	type: 'GET',
		// 	dataType:'JSON'
		// });
		//
		// callback = callback || function() {};
		// request.done(callback);
	}
};
