/**
 * @jsx React.DOM
 */

var React = require('react');

var CommentProjectHeader = React.createClass({
	render: function () {

		return (
            <div className="comment-profession">
                <div className="comment-profession-name">
                    # {this.props.project.project_id} : { this.props.project.project_title }
                </div>
            </div>
		);
	}
});

module.exports = CommentProjectHeader;