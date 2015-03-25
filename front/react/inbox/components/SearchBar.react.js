/**
 * @jsx React.DOM
 */

var React = require('react');
var SearchInput =  React.createFactory(require('./SearchInput.react'));

var SearchBar = React.createClass({

    render: function() {

        return (
			<div>
                <div className="inbox-search-container">
                    <SearchInput where="sender" placeholder="Sender Name" />
                    <SearchInput where="receiver" placeholder="Receiver Name" />
                </div>

                <div className="inbox-search-container">
                    <SearchInput where="sender_id" placeholder="Sender Id" />
                    <SearchInput where="receiver_id" placeholder="Receiver Id" />
                </div>
			</div>
        );
    }

});


module.exports = SearchBar;