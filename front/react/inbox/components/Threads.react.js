/**
 * @jsx React.DOM
 */

var React = require('react');
var Thread = React.createFactory(require('./Thread.react'));

import Moment from '../../../js/libs/Moment';

var Threads = React.createClass({
	getInitialState: function() {
		return {
			is_expand : false
		}
	},

	toggleThreads: function() {
		this.setState({
			is_expand: !this.state.is_expand
		});
	},

	genThreads: function() {
		var _self = this,
			threads = [];

		if(!this.state.is_expand) {
			return null;
		}

		_self.props.threads.forEach(function(thread) {
			threads.push(
				<Thread
					key={thread.message_id}
					thread={thread}
				/>
			);
		});

		return threads;
	},

    getLatestUpdated: function() {
        var thread = _.max(this.props.threads, 'message_id');
        return Moment.ago(thread.date_added);
    },

	genThreadsInfo: function() {

		if(this.props.threads.length == 0) {
			return null;
		}

		return (
            <div className="inbox-threads-info" onClick={this.toggleThreads}>
                 <div className="inbox-threads-info-count">
                     <span className="inbox-threads-info-value">
                     	{ this.props.threads.length }
                     </span> threads below
                 </div>

                <div className="inbox-threads-info-latest">
                    Last updated at 
                    <span className="inbox-threads-info-value">
                        {this.getLatestUpdated()}
                    </span>
                </div>

                <div className="inbox-threads-info-expand">
                    <i className="fa fa-bars"></i>
                </div>
            </div>   
		);		

	},

	render: function() {

		return (
            <div className="inbox-threads">

				{this.genThreadsInfo()}
            	{this.genThreads()}

            </div>			
		);
	}
});

module.exports = Threads;