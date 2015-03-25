/**
  * @jsx React.DOM
  */

var React = require('react');

var ThumbChoose = React.createFactory(require('./ThumbChoose.react'));
var ThumbDelete = React.createFactory(require('./ThumbDelete.react'));
var ThumbDescription = React.createFactory(require('./ThumbDescription.react'));

var ImageParser = require('../utils/ImageParser');

var Thumb = React.createClass({
	genDeleteThumb: function() {
		if(this.props.is_display) {
			return null;
		}
		
		return (
            <ThumbDelete
            	thumb = {this.props.thumb}
            	handleSwitchDeleteImage= {this.props.handleSwitchDeleteImage}
            />
		)
	},

	genThumbInput: function() {
		if(this.props.is_display) {
			return null;
		}

		return (
        	<input  type = "file" 
        			ref  = "uploader" 
        			name = {this.props.thumb.key}
        			className= "solution-image-upload" 
        			onChange = {this.handleChangeImage} 
        	/>
		)
	},

	genCoverInput: function() {
		if(!this.props.thumb.is_cover || this.props.is_display) {
			return null;
		}

		return (
			<input type='hidden' name='cover' value={this.props.thumb.key} readOnly />
		);
	},

	handleHoverImage: function() {
		if(!this.props.thumb.image_url) {
			return;
		}

	    this.props.handleHoverImage(this.props.thumb.image_url);    
	},

	handleChangeImage: function() {
		var input = this.refs.uploader.getDOMNode();
		
		if (!input.files || 
			!input.files[0] || 
			!ImageParser.check(input.files[0])
		) {
			
			input.value = '';
			input.files = null;

	    	return;
    	}

		this.props.handleChangeImage(input.files[0], this.props.thumb.index);  
	},

	render: function() {		
		var bg = this.props.thumb.image_url ?
				{ backgroundImage : 'url(' + this.props.thumb.image_url + ')' } : {};

		if( this.props.is_display && 
			!this.props.thumb.image_url
		) {
			return null;
		}

		return (
			<div className = "solution-thumb-wrapper" >	        	            			    
	            <ThumbChoose 
	            	thumb = {this.props.thumb}
	            	handleChooseCover= {this.props.handleChooseCover}
	            />

	            <div className = "solution-thumb" 
	            	 style = { bg } 
	            	 onMouseOver = {this.handleHoverImage}>
	            	{this.genThumbInput()}
	            </div>

	            <ThumbDescription
	            	is_display = {this.props.is_display}
	            	thumb = {this.props.thumb}
	            />

	            {this.genDeleteThumb()}
	            {this.genCoverInput()}
	        </div>    
		);
	}
});

module.exports = Thumb;