/**
 * @jsx React.DOM
 */

var React = require('react');
var CommentImages  = React.createFactory(require('./CommentImages.react'));
var CommentDelete  = React.createFactory(require('./CommentDelete.react'));

var CommentActions = require('../actions/CommentActions');

import Moment from '../../../js/libs/Moment';

var CommentThread = React.createClass({
    
	render: function() {

		var thread = this.props.thread,
			publisher = thread.publisher,
			publisher_image_style = {
		  		backgroundImage: 'url(' + publisher.image_url + ')'
			};

		return (
            <div className="comment-thread">
                <CommentDelete
                    handleDelete={CommentActions.deleteThread} 
                    comment_id={thread.comment_id}
                />   

                <div className="comment-thread-publisher">
                     <div className="comment-thread-publisher-image"
                     	  style={publisher_image_style} >
                     </div>
                     <div className="comment-thread-publisher-name">
                         { publisher.full_name }
                     </div>
                </div>

                <div className="comment-thread-meta">
                     { Moment.time(thread.date_added) }
                </div>

                <CommentImages images={thread.image_urls} />

                <div className="comment-thread-content"
                     dangerouslySetInnerHTML={{__html: thread.comments}}>
                </div>
            </div>
		);
	}
});

module.exports = CommentThread;