/**
  * @jsx React.DOM
  */

var React = require('react');

var Cover = React.createClass({
	getUrlBackground: function() {
		return { backgroundImage : 'url(' + this.props.cover + ')' };
	},

	getPreviewBackground: function() {
		return { backgroundImage : this.props.cover };
	},

	render: function() {

		var bg = this.props.cover ?
			{ backgroundImage : 'url(' + this.props.cover.replace('/thumb/', '/orig/') + ')' } : {};

		return (
            <div className="solution-cover" style={ bg } >
            </div>
		);

	}
});

module.exports = Cover;