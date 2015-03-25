/**
  * @jsx React.DOM
  */

var React = require('react');

var ResizeSpot = React.createClass({

	render: function() {
		var classes = "resize-handle resize-handle-" + this.props.point;

		return (
			<span className	 ={classes} 
				  onMouseOver={this.props.onSwitchResizeSpot.bind(null, this.props.point)}>
			</span>
		)
	}
});

module.exports = ResizeSpot;