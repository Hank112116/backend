/**
 * @jsx React.DOM
 */

var React = require('react');
var InboxActions = require('../actions/InboxActions');

var _ = require('lodash');

var Paginater = React.createClass({

	genPageList: function() {
		var _self = this;
		
		return _.times(this.props.pages, function(index) {
			var i = index + 1;

			if(i == _self.props.at_page) {
				return (
					<li key={i} className="active">
						<span>{i}</span>
					</li>
				)
			}

			var boundClick = InboxActions.fetchTopics.bind(null, i);
			
			return (
				<li key={i} >
					<a onClick={boundClick} className="pointer">{i}</a>
				</li>
			);
		});
	},

    render: function() {
    	if(this.props.pages == 1) {
    		return null;
    	}

        return (
			<div className="pagination-container">
			    <ul className="pagination">
					{this.genPageList()}
				</ul>
			</div>
        );
    }

});


module.exports = Paginater;