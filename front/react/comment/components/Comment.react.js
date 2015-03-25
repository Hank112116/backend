/**
 * @jsx React.DOM
 */

var React = require('react');
var CommentSearchBar = React.createFactory(require('./CommentSearchBar.react'));
var CommentPaginater = React.createFactory(require('./CommentPaginater.react'));
var CommentList = React.createFactory(require('./CommentList.react'));


var CommentStore = require('../stores/CommentStore');
var CommentActions = require('../actions/CommentActions');
var CommentConstants = require('../constants/CommentConstants');

var Comment = React.createClass({
	getInitialState: function() {
		return CommentStore.data();
	},

	componentDidMount: function() {
	    CommentStore.addChangeListener(this._onChange);
	},

	_onChange: function() {
		this.setState(CommentStore.data());
	},

    render: function() {
    	var at_page = this.state.meta.at_page,
    		comments = this.state.meta.use_search?
    			this.state.searched_comments : 
    			_.filter(this.state.comments, function(comment) {
					return (+comment.page) === (+at_page)
    			});

        return (
            <div>
                <CommentSearchBar type={this.props.type} />

			    <CommentList 
			    	type={this.props.type}
			    	comments={comments} 
			    />

                <CommentPaginater 
                    pages={this.state.meta.pages} 
                    at_page={this.state.meta.at_page} 
                />                
            </div>
        );
    }
});

module.exports = Comment;