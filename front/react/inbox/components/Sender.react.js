/**
 * @jsx React.DOM
 */

var React = require('react');

var Sender = React.createClass({

    render: function() {
        if(!this.props.sender) {
            return null;
        }

        var sender = this.props.sender,
            sender_image_style = {
                backgroundImage: 'url(' + sender.image_url + ')'
            };

    	return (
            <div className="inbox-sender">
                <div className="inbox-sender-image"
                    style={sender_image_style} >
                </div>

                <div className="inbox-sender-name">
                    <span className="inbox-sender-id">{ sender.user_id }</span> { sender.full_name }
                </div>

                <div className="inbox-sender-icon">
                    <i className="fa fa-mail-forward"></i>
                </div>
            </div>
    	)
    }
});

module.exports = Sender;