/**
  * @jsx React.DOM
  */
  
var React = require('react');
var OwnerSelector = React.createFactory(require('./owner-select/components/OwnerSelector.react'));

var user = $('#owner-selector').data('user');

React.render(
    <OwnerSelector user={user} />,
    document.getElementById('owner-selector')
);

