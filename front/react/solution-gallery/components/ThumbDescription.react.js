/**
  * @jsx React.DOM
  */

var React = require('react');

var ThumbDescription = React.createClass({

	handleKeyDown: function(e) {

		if(e.keyCode == 13) {
			e.stopPropagation();
		} 
	},

	handleChangeDescription: function() {
		var textarea = this.refs.desc.getDOMNode();

		this.setState({
			description:textarea.value.substr(0, 100)
		});
	},

	getInitialState: function() {
		return {description:this.props.thumb.description};
	}, 

	render: function() {		
		var thumb_desc_name = 'thumb_desc_' + this.props.thumb.index;	

		if( this.props.is_display) {
            return (
	            <div className = "solution-thumb-content readonly">
	            	<div className = "content-readonly">
	            		{this.state.description}
	            	</div>	
	            </div> 
	        );
		}

		return (
            <div className = "solution-thumb-content">
            	<textarea 
            		ref  = "desc"
            		name = {thumb_desc_name} 
            		value= {this.state.description} 
            		onChange = {this.handleChangeDescription} 
            		onKeyDown = {this.handleKeyDown} 
            	/>
            </div> 
		);
	}
});

module.exports = ThumbDescription;