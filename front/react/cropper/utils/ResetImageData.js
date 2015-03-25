var ResetImageData = {
	gen: function(image_data, mouse_data, point) {

		var spot;

		switch(point) {
			case 'nw':
	    		spot = this.getNorthWestSpot(image_data, mouse_data);		
	    		break;

	    	case 'ne':
	    		spot = this.getNorthEestSpot(image_data, mouse_data);
	    		break;
	    	
	    	case 'se':
	    		spot = this.getSouthEastSpot(image_data, mouse_data);
	    		break;
	    	
	    	case 'sw':
	    		spot = this.getSouthWestSpot(image_data, mouse_data);
	    		break;
	    }

		spot.x = 0;
		spot.y = 0;
	    spot.height = spot.width * image_data.ratio.height / image_data.ratio.width;

	    return spot;
	},

	getNorthWestSpot: function(container, mouse) {
		var width = container.width - (mouse.x - container.left),
	      	height = container.height - (mouse.y - container.top),
	      	height_rationed = width / container.ratio.width * container.ratio.height;

		return {
	      width : width,
	      left : mouse.x,
	      top : mouse.y - (height_rationed - height),
		};
	},
	
	getNorthEestSpot: function(container, mouse) {
		var width  = mouse.x - container.left,
	      	height = container.height - (mouse.y - container.top),
	      	height_rationed = width / container.ratio.width * container.ratio.height;

		return {
			width : width,
	      	left  : container.left,
	      	top   : mouse.y - (height_rationed - height)
		};
	},

	getSouthWestSpot: function(container, mouse) {
		return {
	      width : container.width - (mouse.x - container.left),
	      left 	: mouse.x,
	      top 	: container.top
		};
	},

	getSouthEastSpot: function(container, mouse) {
		return {
	      width : mouse.x - container.left,
	      left 	: container.left,
	      top 	: container.top,			
		};
	}
};

module.exports = ResetImageData;