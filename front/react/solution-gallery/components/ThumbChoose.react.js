/**
  * @jsx React.DOM
  */

var React = require('react');

var ThumbChoose = React.createClass({
	handleClick: function(e) {
		this.props.handleChooseCover(this.props.thumb.index);
	},

	render: function() {
		var switchClass = 'onoffswitch-label' + (this.props.thumb.is_cover? ' active':'');

		return (		
			<div className = "solution-thumb-choose">
				<div className="onoffswitch">
				    <label className={switchClass} onClick={this.handleClick}>
				        <span className="onoffswitch-inner"></span>
				        <span className="onoffswitch-switch"></span>
				    </label>
				</div>
			</div>
		);
	}
});


module.exports = ThumbChoose;