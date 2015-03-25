/**
 * @jsx React.DOM
 */

var React = require('react');
var CommentFullImage = React.createFactory(require('./CommentFullImage.react'));


var renderFullImage = function(image) {
	React.renderComponent(
		<CommentFullImage image={image} />, 
		document.getElementById('comment-image-view')
	);
};

var CommentImages = React.createClass({
	fullImage: function(image) {
		renderFullImage(image);
	},

	genImages: function() {
		var _self = this;

		return _self.props.images.map(function(image) { 
			var bindFullImage = _self.fullImage.bind(_self, image);

			return (
				<div className="comment-image" 
					onClick={bindFullImage} 
					key={image} >
					<img src={image} />
				</div>	
			);
		});
	},

	render: function () {
		if(!this.props.images) {
			return null;
		}

		return (
			<div className="comment-images">
				{this.genImages()}
			</div>
		);
	}
});

module.exports = CommentImages;