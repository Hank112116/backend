/**
 * @jsx React.DOM
 */

var React = require('react');
var Cropper = React.createFactory(require('./cropper/components/Cropper.react'));
var element = document.getElementById('cropper');

React.render(
    <Cropper image={element.dataset.image} />,
    element
);