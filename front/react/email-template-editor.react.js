/**
 * @jsx React.DOM
 */

import superagent from "superagent"

var React = require('react');
var Main = React.createFactory(require('./email-template-editor/components/Main.react'));
var element = document.getElementById('email-template-editor');

window.superagent = superagent;

React.render(
    <Main message={element.innerHTML}  />,
    element
);