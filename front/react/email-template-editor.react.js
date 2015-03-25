/**
 * @jsx React.DOM
 */

var React = require('react');
var Main = React.createFactory(require('./email-template-editor/components/Main.react'));
var element = document.getElementById('email-template-editor');

React.render(
    <Main message={element.innerHTML}  />,
    element
);