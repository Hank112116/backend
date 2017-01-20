/**
  * @jsx React.DOM
  */

var React = require('react');

var Thumb = React.createFactory(require('./Thumb.react'));
var Cover = React.createFactory(require('./Cover.react'));

var ImageParser = require('../utils/ImageParser');

var SolutionGallery = React.createClass({
	handleSwitchDeleteImage: function(index) {
		var thumbs = this.state.thumbs;

		if(this.state.is_display || !thumbs[index].image_url) {
			return;
		}

		thumbs[index].is_deleted = !thumbs[index].is_deleted;

		this.setState({
			thumbs:thumbs
		});
	},

	handleChooseCover: function(index) {
		var thumbs = this.state.thumbs;

		if(this.state.is_display || !thumbs[index].image_url) {
			return;
		}

		thumbs = this.state.thumbs.map((thumb) => {
			thumb.is_cover = false;
			return thumb;
		});

		thumbs[index].is_cover = true;

		this.setState({
			thumbs:thumbs
		});
	},

	handleHoverImage: function(image_url) {
	    this.setState({
			cover: image_url
        });
	},

	handleChangeImage: function(file, index) {

		ImageParser.readFile(file, (e) => {

        	var image_url = e.target.result,
        		thumbs = this.state.thumbs;

        	thumbs[index].image_url = image_url;

            this.setState({
				cover: image_url,
				thumbs: thumbs
            });

		});
	},

	getInitialState: function() {
		var thumbs = [], cover = null;

		_.times(5, (num) => {

			var image = this.props.galleries[num],
				is_cover = false;

			if(image && image.fileUrl == this.props.cover) {
				cover = image.fileUrl;
				is_cover = true;
			}

			thumbs.push({
				index : num,
				key : 'thumb_' + num,
				image_url : image? image.fileUrl : null,
				description : image? image.description : null,
				is_cover : is_cover,
				is_deleted : false
			});

		});

		return {
			is_display: (this.props.mode == 'display'),
			cover: cover,
			thumbs: thumbs
		};
	},

	render: function() {

		var thumbs = this.state.thumbs.map((thumb) => {
			return (
				<Thumb
					key = { thumb.key }
					thumb = {thumb}
					is_display = {this.state.is_display}
					handleChangeImage = { this.handleChangeImage }
					handleSwitchDeleteImage = { this.handleSwitchDeleteImage }
					handleHoverImage = { this.handleHoverImage }
					handleChooseCover = { this.handleChooseCover }
				/>
			);
		});

		return (
			<div>
				<Cover cover={this.state.cover} show_preview={this.state.show_preview} />
				{ thumbs }
			</div>
		);
	}
});

module.exports = SolutionGallery;
