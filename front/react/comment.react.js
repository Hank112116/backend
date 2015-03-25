/**
 * @jsx React.DOM
 */

var React = require('react');

var Comment = React.createFactory(require('./comment/components/Comment.react'));
var CommentServerActions = require('./comment/actions/CommentServerActions');

var element = document.getElementById('comments');

CommentServerActions.initComments(1, element.dataset.type);

React.render(
    <Comment type={element.dataset.type} />,
    element
);