"use strict";

var moment = require("moment-timezone");

export default class Moment {
	static ago(time) {
		return moment.tz(time, "America/Los_Angeles").fromNow();
	}

	static time(date) {
		return moment(date).format("YYYY, MMM D, HH:mm");
	}

	static timeTW(date) {
		return moment.tz(date, "America/Los_Angeles").tz("Asia/Taipei").format("YYYY, MMM D, HH:mm");
	}
}
