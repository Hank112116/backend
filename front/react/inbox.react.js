/**
 * @jsx React.DOM
 */

var React = require('react');
var Inbox = React.createFactory(require('./inbox/components/Inbox.react'));
var InboxServerActions = require('./inbox/actions/InboxServerActions');

InboxServerActions.boot();

React.render(<Inbox />, document.getElementById('inbox'));