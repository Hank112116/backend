/**
 * @jsx React.DOM
 */

var React = require('react');

var Receiver = React.createClass({

    render: function() {
        if(!this.props.receiver) {
            return null;
        }

        var receiver = this.props.receiver,
            receiver_image_style = {
                backgroundImage: 'url(' + receiver.image_url + ')'
            };

    	return (
            <div className="inbox-receiver">
                <div className="inbox-receiver-image"
                    style={receiver_image_style} >
                </div>

                <div className="inbox-receiver-name">
                    <span className="inbox-receiver-id">{ receiver.user_id }</span> { receiver.full_name }
                </div>

                <div className="inbox-receiver-icon">
                    <i className="fa fa-inbox"></i>
                </div>
            </div>
    	)
    }
});

module.exports = Receiver;