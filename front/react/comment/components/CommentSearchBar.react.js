/**
 * @jsx React.DOM
 */

var React = require('react');
var CommentActions =  React.createFactory(require('../actions/CommentActions'));
var CommentSearchInput =  React.createFactory(require('./CommentSearchInput.react'));

var _ = require('lodash');

var CommentSearchBar = React.createClass({
	genSearchInputs: function() {
        switch(this.props.type) {
            case 'project':
                return (
					<div className="comment-search-container">
						<CommentSearchInput where="topic_creator" placeholder="Topic Creator" />
					    <CommentSearchInput where="owner" placeholder="Project Owner" />
					    <CommentSearchInput where="title" placeholder="Project Title" />
					</div>
        		);

            case 'solution':
                return (
					<div className="comment-search-container">
						<CommentSearchInput where="topic_creator" placeholder="Topic Creator" />
					    <CommentSearchInput where="owner" placeholder="Solution Owner" />
					    <CommentSearchInput where="title" placeholder="Solution Title" />
					</div>
        		);

            case 'user':
                return (
					<div className="comment-search-container">
						<CommentSearchInput where="topic_creator" placeholder="Topic Creator" />
					    <CommentSearchInput where="profession_name" placeholder="Member Name" />
					</div>
        		);
            default: 
                return null;
        }
    },

    render: function() {

        return (
			<div>
				{this.genSearchInputs()}
			</div>
        );
    }

});


module.exports = CommentSearchBar;