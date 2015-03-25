/**
  * @jsx React.DOM
  */

var React = require('react');
var ResizeSpot = React.createFactory(require('./ResizeSpot.react'));

var DataUrlGenerator = require('../utils/DataUrlGenerator');
var ResetImageData = require('../utils/ResetImageData');

var Cropper = React.createClass({
	handleSwitchResizeSpot : function(point) {
		if(point === this.state.point) {
			return;
		}

		this.setState({
			point: point
		});
  	},

  	handleCrop: function() {
	    //Find the part of the image that is inside the crop box
	    
	    var containerNode = this.refs.container.getDOMNode(),
	    	overlayNode = this.refs.overlay.getDOMNode();
		
		var container = {
	    		top 	: containerNode.offsetTop,
	    		left 	: containerNode.offsetLeft
	    	},

	    	image_opt = {
	    		x 	: 0,
		    	y 	: 0,	
	    		width 	: overlayNode.offsetWidth,
	    		height 	: overlayNode.offsetHeight   
	    	}, 
	    	clip_opt = {
	    		x 	: overlayNode.offsetLeft - container.left,
		    	y 	: overlayNode.offsetTop  - container.top,
	    		width 	: overlayNode.offsetWidth,
	    		height 	: overlayNode.offsetHeight    
	    	};

	    var callback = function(data_url) {
			window.open(data_url);
	    };

    	DataUrlGenerator.imageUrlToDataUrl(
    		this.state.image,
    		image_opt, clip_opt, callback
    	);
  	},

  	handleStartResize: function(e) {
  		e.preventDefault();
   	 	e.stopPropagation();

  		var container = this.refs.container.getDOMNode();

	    this.setState({
	    	is_resizing: true,
	    	mouse: this.getMousePosition(e),
	    	container: {
	    		width 	: container.offsetWidth,
	    		height 	: container.offsetHeight,
	    		top 	: container.offsetTop,
	    		left 	: container.offsetLeft,
	    		ratio   : {
	    			width 	: this.state.image_obj.width,
	    			height 	: this.state.image_obj.height
	    		}
	    	}
	    });
  	},

  	handleEndResize : function(e) {
  		e.preventDefault();

	    this.setState({
	    	is_resizing: false,
	    	point: null
	    });
  	},

  	handleMove: function(e) {
  		var mouse = this.getMousePosition(e);

  		this.setState({
			container_top: mouse.y - ( this.state.mouse.y - this.state.container.top ),
			container_left: mouse.x - ( this.state.mouse.x - this.state.container.left )
	    });
  	},

	handleResize: function(e) {
		if(!this.state.is_resizing) {
			return;
		}

		if(!this.state.point) {
			this.handleMove(e);
			return;
		}

		var spot = ResetImageData.gen(
				this.state.container, 
				this.getMousePosition(e), 
				this.state.point
			);

	    this.resizeImage(spot);
	},

	getMousePosition: function(e) {
		var component = this.refs.component.getDOMNode(),
			position = { //mouse relate-to-component position 
	    		x: (e.clientX || e.pageX) - component.offsetLeft,
	    		y: (e.clientY || e.pageY) - component.offsetTop
	    	};

	    	return {
		    	x : position.x + window.pageXOffset,
		    	y : position.y + window.pageYOffset
	    	};
	},

	resizeImage : function(spot) {		
		this.setState({
			image: DataUrlGenerator.imageObjToDataUrl(this.state.image_obj, spot),
			container_top: spot.top,
			container_left: spot.left,
	    });
  	},

	getInitialState: function() {
		var img = new Image();
		img.src = this.props.image;

		return {
			image: this.props.image,
			image_obj: img
		};
	},

	render: function() {
		var position = {
				top:  this.state.container_top,
				left: this.state.container_left
			}, 
			resize_container_classes = 
				"resize-container " + (this.state.is_resizing? 'active' : '');

		return (
			<div className="component" 
				 ref="component" 
				 onMouseDown = {this.handleStartResize} 
				 onMouseMove = {this.handleResize}
				 onMouseUp	 = {this.handleEndResize}
				 onDoubleClick={this.handleCrop} >

				<div className="overlay" ref="overlay" >
					<div className="overlay-inner">
					</div>
				</div>

				<div className={resize_container_classes}
					 ref="container" 
					 style={position} >

					<ResizeSpot point="nw" onSwitchResizeSpot={this.handleSwitchResizeSpot} />

					<ResizeSpot point="ne" onSwitchResizeSpot={this.handleSwitchResizeSpot} />

					<img className="resize-image" src={this.state.image} />

					<ResizeSpot point="sw" onSwitchResizeSpot={this.handleSwitchResizeSpot} />

					<ResizeSpot point="se" onSwitchResizeSpot={this.handleSwitchResizeSpot} />
				</div>

			</div>	
		)
	}
});

module.exports = Cropper;