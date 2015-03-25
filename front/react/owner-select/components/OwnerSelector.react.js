/**
  * @jsx React.DOM
  */

var React = require('react');
var OwnerWrapper = React.createFactory(require('./OwnerWrapper.react'));
var OwnerInput = React.createFactory(require('./OwnerInput.react'));

var OwnerSelector = React.createClass({
	getInitialState: function() {
		if(!this.props.user) {
			return { user: {} }
		}

		var user = (typeof(this.props.user) === 'string') ? 
				JSON.parse(this.props.user) : this.props.user;

		return {
			user: user
		};
	},
	
	switchOwner: function(user) {
		this.setState({user : user});
	},

	render: function() {
		return (
			<div>
				<OwnerInput user_id={this.state.user.user_id} switchOwner={this.switchOwner} />
				<OwnerWrapper user={this.state.user} />
			</div>
		);
	}
});

module.exports = OwnerSelector;