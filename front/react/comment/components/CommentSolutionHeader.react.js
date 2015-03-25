/**
 * @jsx React.DOM
 */

var React = require('react');

var CommentSolutionHeader = React.createClass({
	render: function () {
		return (
            <div className="comment-profession">
                <div className="comment-profession-name">
                    # {this.props.solution.solution_id} : { this.props.solution.solution_title }
                </div>
            </div>
		);
	}
});

module.exports = CommentSolutionHeader;