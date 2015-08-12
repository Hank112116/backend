/**
  * @jsx React.DOM
  */

var React = require('react');
var SolutionGallery = React.createFactory(require('./solution-gallery/components/SolutionGallery.react'));

var gallery_wrapper = document.getElementById('solution-gallery');
if(gallery_wrapper !== null) {
	var galleries = gallery_wrapper.dataset.solutionGallery ?
		JSON.parse(gallery_wrapper.dataset.solutionGallery) : [];

	React.render(
		<SolutionGallery
			mode={ gallery_wrapper.dataset.mode }
			cover={ gallery_wrapper.dataset.solutionCover }
			galleries={ galleries }
			/>,
		gallery_wrapper
	);
}