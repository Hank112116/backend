var ImageParser = {
	LIMIT: 1024 * 1024 * 3,
	TYPES: ['image/png', 'image/jpeg', 'image/jpg'],

	check: function(file) {
		if(this.TYPES.indexOf(file.type) === -1) {
			Notifier.showTimedMessage('Only accept png | jpg image', 'warn', 5);
			return false;
		}

		if(file.size > this.LIMIT) {
			Notifier.showTimedMessage('Size too large', 'warn', 5);
			return false;
		}

		return true;
	},

	readFile: function(file, callback) {
		var reader = new FileReader();	        
        reader.onload = callback;
        reader.readAsDataURL(file);
	}
}

module.exports = ImageParser;