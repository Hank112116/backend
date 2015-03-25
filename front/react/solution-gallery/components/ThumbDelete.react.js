/**
  * @jsx React.DOM
  */

var React = require('react');

var ThumbDelete = React.createClass({
	handleClick: function(e) {
		this.props.handleSwitchDeleteImage(this.props.thumb.index);
	},

	render: function() {
		var thumb_delete_name = 'thumb_delete_' + this.props.thumb.index,
			switchClass = 'solution-thumb-delete' + (this.props.thumb.is_deleted? ' active':''),
			value = this.props.thumb.is_deleted? '1' : '0';

		return (		
			<div className={switchClass} onClick={this.handleClick}>
				<i className="fa fa-times"></i> 
				<input type='hidden' name={thumb_delete_name} value={value} />
			</div>
		);
	}
});


module.exports = ThumbDelete;