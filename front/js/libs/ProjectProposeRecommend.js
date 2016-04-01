/* jshint quotmark: false */
$(function () {
    var $propose_dialog   = $("#propose-solution-dialog");
    var $recommend_dialog = $("#recommend-expert-dialog");
    $( ".project_propose" ).click(function () {
        $propose_dialog.html('');
        var $this        = $(this);
        var title        = $this.attr("title");
        var project_id   = $this.attr("rel");
        var propose_type = $this.attr("propose");
        $.ajax({
            type: "POST",
            url: "/project/propose-solution",
            data: {
                project_id: project_id,
                propose_type: propose_type
            },
            dataType: "JSON",
            success: function success(feeback) {
                var propose_list = '';
                $.each(feeback, function(index, value) {
                    propose_list += "<div style='border-bottom: 1px solid #ddd; padding: 5px'>" +
                        "#" + value.solution_id + ". " + "<a href='" + value.solution_url + "' target='_blank' style='color: #428bca'> " + value.solution_title +" </a>" +
                        "</div>";
                });
                $propose_dialog.html(propose_list);
            }
        });
        $propose_dialog.dialog({
            title: title,
            height: 400,
            width: 600
        });
    });

    var $recommend_email_out = $("#email-out-recommend");
    var $recommend_applicant = $("#applicant-recommend");
    $( ".project_recommend" ).click(function () {
        $recommend_email_out.html('');
        $recommend_applicant.html('');
        var $this          = $(this);
        var title          = $this.attr("title");
        var project_id     = $this.attr("rel");
        var recommend_type = $this.attr("recommend");
        $.ajax({
            type: "POST",
            url: "/project/recommend-expert",
            data: {
                project_id: project_id,
                recommend_type: recommend_type
            },
            dataType: "JSON",
            success: function success(feeback) {
                var applicant_recommend_list = '';
                var email_out_recommend_list = '';
                $.each(feeback, function(index, value) {

                    if (value.type == 'email-out') {
                        email_out_recommend_list += "<div style='border-bottom: 1px solid #ddd; padding: 5px'>";
                        if (value.company_name) {
                            email_out_recommend_list += "#" + value.user_id + ". " + "<a href='" + value.profile_url + "' target='_blank' style='color: #428bca'>" + value.user_name + " </a> from " + value.company_name;
                        } else {
                            email_out_recommend_list += "#" + value.user_id + ". " + "<a href='" + value.profile_url + "' target='_blank' style='color: #428bca'>" + value.user_name + " </a>";
                        }
                        email_out_recommend_list += "</div>";
                    }

                    if (value.type == 'applicant') {
                        applicant_recommend_list += "<div style='border-bottom: 1px solid #ddd; padding: 5px'>";
                        if (value.company_name) {
                            applicant_recommend_list += "#" + value.user_id + ". " + "<a href='" + value.profile_url + "' target='_blank' style='color: #428bca'>" + value.user_name + " </a> from " + value.company_name;
                        } else {
                            applicant_recommend_list += "#" + value.user_id + ". " + "<a href='" + value.profile_url + "' target='_blank' style='color: #428bca'>" + value.user_name + " </a>";
                        }
                        applicant_recommend_list += "</div>";
                    }
                });
                $recommend_email_out.html(email_out_recommend_list);
                $recommend_applicant.html(applicant_recommend_list);
            }
        });
        $recommend_dialog.dialog({
            title: title,
            height: 400,
            width: 700
        });
    });
});
