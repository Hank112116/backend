var DataUrlGenerator = {
	imageUrlToDataUrl : function(image_url, image_opt, clip_opt, callback) {

		var image = new Image();

		image.onload = function() {
			
			var data_url = this.imageObjToDataUrl(image, image_opt, clip_opt);
		    callback(data_url);

		}.bind(this);

		image.src = image_url;
	},

	imageObjToDataUrl : function(image_obj, image_opt, clip_opt) {
		var cv = document.createElement('canvas');
    	
    	cv.width = image_opt.width;
    	cv.height = image_opt.height;

    	if(!clip_opt) {
    		
    		cv.getContext('2d').drawImage(
    			image_obj, 
    			image_opt.x, image_opt.y, image_opt.width, image_opt.height
    		); 

    	} else {
    		
    		cv.getContext('2d').drawImage(
    			image_obj, 
    			clip_opt.x, clip_opt.y, clip_opt.width, clip_opt.height,
    			image_opt.x, image_opt.y, image_opt.width, image_opt.height
    		); 
    	
    	}

		return cv.toDataURL("image/png")
	}
};

module.exports = DataUrlGenerator;