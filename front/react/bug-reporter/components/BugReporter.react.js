/**
  * @jsx React.DOM
  */

var React = require('react');
var Switcher = React.createFactory(require('./Switcher.react'));

var BugReporter = React.createClass({
	getInitialState: function() {
		return {
			on: true,
			bug: '',
			report: ''
		}
	},

	switch: function() {
		this.setState({
			on: !this.state.on
		});
	},

	genReport: function() {
		if(!this.state.report) {
			return null;
		}

		var report = this.state.report.replace(/\n/gi,  '<br/>');
		return (
			<div className='bug-report' dangerouslySetInnerHTML={{__html: report}} />
		)
	},

	handleChange: function(event) {	
		$.post(
			'/engineer/bug-decode', 
			{bug: event.target.value}, 
			(result) => this.setState({report: result.report})
		);

		this.setState({
			bug: event.target.value
		})
	},

	render: function() {
		return (		
			<div>
				<Switcher onSwitch={this.switch} on={this.state.on} />

				<textarea className='bug-content' 
					ref='bug' 
					value={this.state.bug}
					onChange={this.handleChange} />
				{this.genReport()}	
			</div>
		);
	}
});


module.exports = BugReporter;