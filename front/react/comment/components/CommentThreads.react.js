/**
 * @jsx React.DOM
 */

var React = require('react');
var CommentThread = React.createFactory(require('./CommentThread.react'));

import Moment from '../../../js/libs/Moment';

var CommentThreads = React.createClass({
    getInitialState: function () {
        return {
            is_expand: false
        }
    },

    toggleThreads: function () {
        this.setState({
            is_expand: !this.state.is_expand
        });
    },

    getLatestUpdated: function () {
        var thread = _.max(this.props.threads, function (thread) {
            return +thread.comment_id;
        });
        return Moment.ago(thread.date_added);
    },

    genThreads: function () {
        var _self = this,
            threads = [];

        if (!this.state.is_expand) {
            return null;
        }

        _self.props.threads.forEach(function (thread) {
            threads.push(
                <CommentThread
                    key={thread.comment_id}
                    thread={thread}
                    />
            );
        });

        return threads;
    },

    genThreadsInfo: function () {

        if (this.props.threads.length == 0) {
            return null;
        }

        return (
            <div className="comment-threads-info" onClick={this.toggleThreads}>
                <div className="comment-threads-info-count">
                     <span className="comment-threads-info-value">
                     	{ this.props.threads.length }
                     </span> threads below
                </div>

                <div className="comment-threads-info-latest">
                    Last updated at 
                    <span className="comment-threads-info-value">
                    	{this.getLatestUpdated()}
                    </span>
                </div>

                <div className="comment-threads-info-expand">
                    <i className="fa fa-bars"></i>
                </div>
            </div>
        );

    },

    render: function () {

        return (
            <div className="comment-threads">
                {this.genThreadsInfo()}
                {this.genThreads()}
            </div>
        );
    }
});

module.exports = CommentThreads;