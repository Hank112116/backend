/**
 * @jsx React.DOM
 */

var React = require('react');

var Editor = React.createClass({
    handleChange: function(event) {
        this.setState({
            message: event.target.value
        });

        this.props.onChangeEdition(event.target.value);
    },

    getInitialState:function() {
        return {message: this.props.message}
    },

    render: function() {
        return (
            <textarea id="message" name="message" ref="editor"
                      className="form-control" rows="20"
                      value={this.state.message}
                      onChange={this.handleChange} >
            </textarea>
        )
    }
});


module.exports = Editor;