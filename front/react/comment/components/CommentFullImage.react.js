/**
 * @jsx React.DOM
 */

var React = require('react');


var CommentFullImage = React.createClass({
	deleteView: function() {
		React.unmountComponentAtNode(document.getElementById('comment-image-view'));
	},
	render: function() {
		var image = this.props.image.replace('/thumb/', '/orig/');

		return (
			<div className="comment-full-image-view">
				<div className="comment-full-image-delete">
					<i className="fa fa-paper-plane" onClick={this.deleteView}></i>
				</div>
				<div className="absolute-center is-image">
					<img src={image} />
				</div>
			</div>	
		);
	}
});


module.exports = CommentFullImage;