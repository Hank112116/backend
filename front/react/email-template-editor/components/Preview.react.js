/**
 * @jsx React.DOM
 */

var React = require('react');

var Preview = React.createClass({
    render: function() {
        if(!this.props.show) {
            return null;
        }

        var render = this.props.render
            .replace(/\{break\}/g, '<br/>')
            .replace(/(\{.+\})/g, '<i class="preview-tag">$1</i>' );

        var content = this.props.template.replace('{content}', render);

        return (
            <div className="preview-message"
                dangerouslySetInnerHTML={{__html: content}}>

            </div>
        )
    }
});


module.exports = Preview;