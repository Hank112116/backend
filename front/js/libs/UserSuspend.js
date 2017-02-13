/* jshint quotmark: false */
import * as SweetAlert from "../libs/SweetAlert";
$(function () {
    var $document = $(document);

    $document.on('click', '.js-disable-user', function (e) {
        e.preventDefault();
        var $this   = $(this);
        var user_id = $this.attr("rel");
        SweetAlert.alert({
            title: "Sure suspend this user?",
            confirmButton: "Yes!",
            handleOnConfirm: (is_confirm) => {
                if(is_confirm){
                    $this.html("<i class='fa fa-refresh fa-spin'></i> Suspend");
                    post_data(user_id , "/user/disable");
                }else{
                    return false;
                }
            }
        });
    });

    $document.on('click', '.js-enable-user', function (e) {
        e.preventDefault();
        var $this   = $(this);
        var user_id = $this.attr("rel");
        SweetAlert.alert({
            title: "Sure unsuspend this user?",
            confirmButton: "Yes!",
            handleOnConfirm: (is_confirm) => {
                if(is_confirm){
                    $this.html("<i class='fa fa-refresh fa-spin'></i> Unsuspend");
                    post_data(user_id , "/user/enable");
                }else{
                    return false;
                }
            }
        });
    });

    function post_data(user_id, url){
        $.ajax({
            type: "POST",
            url: url,
            data: {
                user_id: user_id
            },
            dataType: "JSON",
            statusCode: {
                201: function() {
                    Notifier.showTimedMessage("Update successful", "information", 2);
                    location.href = "/user/detail/" + user_id;
                },
                204: function () {
                    Notifier.showTimedMessage("Update successful", "information", 2);
                    location.href = "/user/detail/" + user_id;
                },
                400: function(feeback) {
                    var error_message = feeback.responseJSON.error.message;
                    Notifier.showTimedMessage(error_message, "warning", 2);
                },
                401: function(feeback) {
                    var error_message = feeback.responseJSON.error.message;
                    Notifier.showTimedMessage(error_message, "warning", 2);
                },
                404: function(feeback) {
                    var error_message = feeback.responseJSON.error.message;
                    Notifier.showTimedMessage(error_message, "warning", 2);
                },
                412: function() {
                    location.href = "/";
                },
                500: function(feeback) {
                    var error_message = feeback.responseJSON.error.message;
                    Notifier.showTimedMessage(error_message, "warning", 2);
                }
            }
        });
    }
});
