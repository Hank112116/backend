/**
 * @jsx React.DOM
 */

var React = require('react');
var CommentImages  = React.createFactory(require('./CommentImages.react'));

var CommentDelete  = React.createFactory(require('./CommentDelete.react')); 
var CommentThreads = React.createFactory(require('./CommentThreads.react'));

var CommentActions = require('../actions/CommentActions');
import Moment from '../../../js/libs/Moment';

var CommentList = React.createClass({

    isCommentBelongingExist: function(comment) {
        switch(this.props.type) {
            case 'user':
                return !!comment.expert;

            case 'project':
                return !!comment.project;

            case 'solution':
                
                return !!comment.solution;
            default: 
                return false;
        }
    },

    genCommentBelongingHeader: function(comment) {

        switch(this.props.type) {
            case 'user':
                var CommentExpertHeader = React.createFactory(require('./CommentExpertHeader.react'));
                return <CommentExpertHeader expert={comment.expert}/>

            case 'project':
                var CommentProjectHeader = React.createFactory(require('./CommentProjectHeader.react'));
                return <CommentProjectHeader project={comment.project}/>

            case 'solution':
                var CommentSolutionHeader = React.createFactory(require('./CommentSolutionHeader.react'));
                return <CommentSolutionHeader solution={comment.solution}/>

            default: 
                return null;
        }
    },

    genCommentLock: function(comment) {
 
        var is_private = !!(+comment.private_comment),
            class_lock = is_private ? 
                'comment-private-lock' : 'comment-private-unlock',
            icon_lock = is_private ? 
                'fa fa-lock' : 'fa fa-unlock-alt';

        var handleClick = CommentActions.togglePrivate.bind(null, comment.comment_id);        

        return (
            <div className={class_lock} onClick={handleClick}>
                <i className={icon_lock}></i>
            </div>
        );
        
    },

	genComment: function(comment) {
        if(!this.isCommentBelongingExist(comment)) {
            return null;
        }

		var publisher = comment.publisher,
			publisher_image_style = {
		  		backgroundImage: 'url(' + publisher.image_url + ')'
			};

		return (
            <div className="comment" key={comment.comment_id} data-id={comment.comment_id} >
                <CommentDelete
                    handleDelete={CommentActions.deleteTopic} 
                    comment_id={comment.comment_id}
                />   

                {this.genCommentBelongingHeader(comment)}

                <div className="comment-publisher">
                     <div className="comment-publisher-image" 
                     	  style={publisher_image_style} >
                     </div>

                     <div className="comment-publisher-name">
                         { publisher.full_name }
                     </div>

                     {this.genCommentLock(comment)}                
                </div>

                <div className="comment-header">
                    <div className="comment-title">
                        { comment.title }
                    </div>
                     <div className="comment-meta">
                        { Moment.time(comment.date_added) }
                     </div>
                </div>

                <CommentImages images={comment.image_urls} />

                <div className="comment-content"
                     dangerouslySetInnerHTML={{__html: comment.comments}}>
                </div>

                <CommentThreads 
                    threads={comment.threads} 
                    handleDelete={this.props.handleDeleteThread}
                />

            </div>
		);
	},

    render: function() {
        var comments = _.map(this.props.comments, this.genComment);

        return (
            <div>
                {comments}
            </div>
        );
    }
});


module.exports = CommentList;