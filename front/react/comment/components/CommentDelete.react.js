/**
 * @jsx React.DOM
 */

var React = require('react');
var SweetAlert = require('../../../js/libs/SweetAlert');

var CommentDelete = React.createClass({

	alertDelete: function() {
        var _self = this;

        SweetAlert.alert({
            title: "Are you sure?",
            desc: "This will delete this topic / thread!",
            confirmButton: "Yes, delete it!",
            handleOnConfirm: function() {
                _self.props.handleDelete(_self.props.comment_id);
            }
        });
    },

    render: function() {

        return null; // Disable the delete ability now

    	return (
            <button className="comment-delete" onClick={this.alertDelete}>
                DELETE
            </button>
    	)
    }
});

module.exports = CommentDelete;
