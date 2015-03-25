"use strict";

export default class MemberSelector {
    constructor() {
        this.timer = 0;
    }

    fireTimeoutSelector(user_id, callback) {
        if (!/\d+/.test(user_id)) {
            return;
        }

        clearTimeout(this.timer);
        this.timer = setTimeout(
            () => this.fireSelector(user_id, callback)
        , 2000);
    }

    fireSelector(user_id, callback) {
        $.getJSON(
            "/user/api/search/" + user_id,
            (feedback) => this.onSelectSuccess(feedback, user_id, callback)
        );
    }

    onSelectSuccess(feedback, user_id, callback) {
        var user = feedback.ok === "ok" ? feedback : { user_id: "" };

        callback(user);

        if (feedback.ok !== "ok") {
            Notifier.showTimedMessage("No member with id [ " + user_id + " ]", "warning", 3);
        }
    }
}