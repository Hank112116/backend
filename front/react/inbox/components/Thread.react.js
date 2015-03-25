/**
 * @jsx React.DOM
 */

var React = require('react');
var Delete  = React.createFactory(require('./Delete.react'));
var Sender  = React.createFactory(require('./Sender.react'));
var Receiver  = React.createFactory(require('./Receiver.react'));

var InboxActions = require('../actions/InboxActions');

import Moment from '../../../js/libs/Moment';

var Thread = React.createClass({
	render: function() {
        var thread = this.props.thread;

		return (
            <div className="inbox-thread">
                <Delete
                    handleDelete={InboxActions.deleteThread}
                    message_id={thread.message_id}
                />   

                <Sender sender={thread.sender} />
                <Receiver receiver={thread.receiver} />

                <div className="inbox-thread-meta">
                     { Moment.time(thread.date_added) }
                </div>

                <div className="inbox-thread-content"
                     dangerouslySetInnerHTML={{__html:thread.message_content}}>
                </div>
            </div>
		);
	}
});

module.exports = Thread;