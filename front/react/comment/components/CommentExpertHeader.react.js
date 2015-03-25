/**
 * @jsx React.DOM
 */

var React = require('react');

var CommentExpertHeader = React.createClass({
	render: function () {
		var expert_image_style = {
		  		backgroundImage: 'url(' + this.props.expert.image_url + ')'
			};

		return (
            <div className="comment-profession">
                <div className="comment-profession-name">
                    # {this.props.expert.user_id} : { this.props.expert.full_name }
                </div>
                 <div className="comment-profession-image" 
                 	  style={expert_image_style} >
                 </div>
            </div>
		);
	}
});

module.exports = CommentExpertHeader;