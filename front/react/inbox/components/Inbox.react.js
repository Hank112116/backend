/**
 * @jsx React.DOM
 */

var React = require('react');

var SearchBar = React.createFactory(require('./SearchBar.react'));
var Topics = React.createFactory(require('./Topics.react'));
var Paginater = React.createFactory(require('./Paginater.react'));

var InboxStore = require('../stores/InboxStore');

var Inbox = React.createClass({
	getInitialState: function() {
		return InboxStore.data();
	},

	componentDidMount: function() {
	    InboxStore.addChangeListener(this._onChange);
	},

	_onChange: function() {
		this.setState(InboxStore.data());
	},

    getTopics: function() {
        var at_page = this.state.meta.at_page;

        if(this.state.meta.use_search) {
            return this.state.searched_topics;
        }

        return _.filter(this.state.topics, function(topic) {
                return topic.page === at_page
            });
    },

    render: function() {
    	var meta = this.state.meta,
            topics = this.getTopics();

        return (
            <div>
                <SearchBar />

			    <Topics topics={topics} />
                <Paginater pages={meta.pages} at_page={meta.at_page} />
            </div>
        );
    }
});

module.exports = Inbox;