/**
 * @jsx React.DOM
 */

var React = require('react');
var Editor = React.createFactory(require('./Editor.react'));
var Preview = React.createFactory(require('./Preview.react'));

var Main = React.createClass({
    componentDidMount: function() {
        var _self = this;

        $.get('/mail/template', function(template) {
            _self.setState({
                show: true,
                template: template
            });
        });
    },

    getInitialState: function() {
        return {
            show: false,
            render: this.props.message
        }
    },

    switchShowPreview: function() {
        this.setState({
            show: !this.state.show
        });
    },

    onChangeEdition: function(message) {
        this.setState({
            render: message
        });
    },

    render: function() {
        var show_preview_classes = "show-preview " + (this.state.show? 'active' : '');

        return (
            <div>
                <div className={show_preview_classes} onClick={this.switchShowPreview}>
                    <i className="fa fa-eye"></i>
                </div>

                <Editor
                    message={this.props.message}
                    onChangeEdition={this.onChangeEdition}
                />

                <Preview show={this.state.show} render={this.state.render} template={this.state.template} />
            </div>
        );
    }
});

module.exports = Main;