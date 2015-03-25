/**
  * @jsx React.DOM
  */

var React = require('react');

var Switcher = React.createClass({
	render: function() {
		var switchClass = 'onoffswitch-label' + (this.props.on? ' active':'');

		return (		
			<div className="onoffswitch">
			    <label className={switchClass} onClick={this.props.onSwitch}>
			        <span className="onoffswitch-inner"></span>
			        <span className="onoffswitch-switch"></span>
			    </label>
			</div>
		);
	}
});


module.exports = Switcher;