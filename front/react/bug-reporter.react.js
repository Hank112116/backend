/**
 * @jsx React.DOM
 */

var React = require('react');
var BugReporter = React.createFactory(require('./bug-reporter/components/BugReporter.react'));

React.render(
    <BugReporter />,
    document.getElementById('bug-reporter')
);