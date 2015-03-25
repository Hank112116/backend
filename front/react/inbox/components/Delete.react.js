/**
 * @jsx React.DOM
 */

var React = require('react');
var SweetAlert = require('../../../js/libs/SweetAlert');

var Delete = React.createClass({

	alertDelete: function() {
        var _self = this;

        SweetAlert.alert({
            title: "Are you sure?",
            desc: "This will delete this topic / thread!",
            confirmButton: "Yes, delete it!",
            handleOnConfirm:  _self.props.handleDelete.bind(null, _self.props.message_id)
        });
    },

    render: function() {
        return null;

    	return (
            <button className="inbox-delete" onClick={this.alertDelete}>
                DELETE
            </button>
    	)
    }
});

module.exports = Delete;