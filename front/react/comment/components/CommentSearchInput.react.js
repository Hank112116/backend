/**
 * @jsx React.DOM
 */

var React = require('react');
var CommentActions = require('../actions/CommentActions');

var _ = require('lodash');

var CommentSearchInput = React.createClass({
	handleChange: function(e) {
		this.setState({search: e.target.value});
	},

	handleSubmit: function(e) {
		if (e.keyCode == 13 ) {
            CommentActions.searchComments(
            	this.props.where, 
            	this.state.search
            );
        }
	},

	getInitialState: function() {
		return {search: ''};
	},

    render: function() {
        return (
			<div className="comment-search-input">
			    <input 	type='text' 
			    		placeholder={this.props.placeholder} 
			    		value={this.state.search}
			    		onChange={this.handleChange}
			    		onKeyDown={this.handleSubmit}
			    />
			</div>
        );
    }

});


module.exports = CommentSearchInput;