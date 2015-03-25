/**
  * @jsx React.DOM
  */

var React = require('react');

var OwnerWrapper = React.createClass({
	render: function() {
		var user = this.props.user;

		if(!this.props.user.user_id) {
			return null;
		}

		var bg = {
			backgroundImage: 'url(' + user.image + ')'
		};

		return (
	        <div className="owner-wrapper">
	            <div className="owner-image-wrapper" style={bg} >

	            </div>

	            <div className="owner-info-wrapper">
	            	<div>
				        <a href={user.link} target="_blank">
				            {user.full_name}
				        </a><br/>
						{user.is_expert? 'Expert' : 'Creator'}
				    </div>

			        <div className={user.is_expert? '' : 'hide'}>
			        	{user.position}  at  {user.company}
			        </div>	
	            </div>
	        </div>
		);
	}
});

module.exports = OwnerWrapper;