/**
 * @jsx React.DOM
 */

var React = require('react');

var Delete  = React.createFactory(require('./Delete.react'));
var Threads = React.createFactory(require('./Threads.react'));

var Sender = React.createFactory(require('./Sender.react'));
var Receiver = React.createFactory(require('./Receiver.react'));

var InboxActions = require('../actions/InboxActions');

import Moment from '../../../js/libs/Moment';

var Topics = React.createClass({

	genTopic: function(topic) {
		return (
            <div className="inbox" key={topic.message_id} data-id={topic.message_id} >
                <Delete
                    handleDelete={InboxActions.deleteTopic}
                    message_id={topic.message_id}
                />   

                <Sender sender={topic.sender} />
                <Receiver receiver={topic.receiver} />

                <div className="inbox-header">
                    <div className="inbox-title">
                        { topic.message_subject }
                    </div>
                     <div className="inbox-meta">
                        { Moment.time(topic.date_added) }
                     </div>
                </div>

                <div className="inbox-content"
                     dangerouslySetInnerHTML={{__html:topic.message_content}}>
                </div>

                <Threads
                    threads={topic.threads}
                    handleDelete={this.props.handleDeleteThread}
                />

            </div>
		);
	},

    render: function() {
        var comments = _.map(this.props.topics, this.genTopic);

        return (
            <div>
                {comments}
            </div>
        );
    }
});


module.exports = Topics;