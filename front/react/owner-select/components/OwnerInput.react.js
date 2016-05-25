/**
  * @jsx React.DOM
  */

var React = require('react');

import MemberSelector from '../../../js/libs/MemberSelector';

var OwnerInput = React.createClass({
	getInitialState: function() {
		return {
			user_id : this.props.user_id,
			origin_id: this.props.user_id
		}
	},

	componentDidUpdate: function() {
		if(!this.props.user_id) {
			this.refs.user.getDOMNode().value = this.state.origin_id;
		}
	},

	switchOwner: function() {
		var user_id = this.refs.user.getDOMNode().value;

		this.setState({ user_id : user_id });

		new MemberSelector().fireTimeoutSelector(user_id, this.props.switchOwner);
	},

	render: function() {
		return (
			<input type="number" id="member" name="user_id" ref="user"
				value={this.state.user_id}
				onChange={this.switchOwner}
			/>
		);
	}
});

module.exports = OwnerInput;
