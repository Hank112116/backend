/* jshint quotmark: false */
$(function () {
    var $propose_dialog          = $("#propose-solution-dialog");
    var $recommend_dialog        = $("#recommend-expert-dialog");
    var $match_statistics_dialog = $("#project-match-statistics-dialog");
    var $dstart           = $("#statistic-start-date").val();
    var $dend             = $("#statistic-end-date").val();
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
                propose_type: propose_type,
                dstart: $dstart,
                dend: $dend
            },
            dataType: "JSON",
            success: function success(feeback) {
                var propose_list = '';
                $.each(feeback, function(index, value) {
                    propose_list += "<div style='border-bottom: 1px solid #ddd; padding: 5px'>" +
                        "#" + value.solution_id + ". " + "<a href='" + value.solution_url + "' target='_blank' style='color: #428bca'> " + value.solution_title +" </a>" +
                        " By " + "<a href='" + value.user_url + "' target='_blank' style='color: #428bca'> " + value.user_name + " </a>" +
                        " At " + value.at_time +
                        "</div>";
                });
                if (propose_list === "") {
                    propose_list = "N/A";
                }
                $propose_dialog.html(propose_list);
            }
        });
        $propose_dialog.dialog({
            title: title,
            height: 400,
            width: 700
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
                recommend_type: recommend_type,
                dstart: $dstart,
                dend: $dend
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

                        if (value.referral_user_name) {
                            email_out_recommend_list += " By " + value.referral_user_name;
                        }
                        email_out_recommend_list += " At " + value.at_time;
                        email_out_recommend_list += "</div>";
                    }

                    if (value.type == 'applicant') {
                        applicant_recommend_list += "<div style='border-bottom: 1px solid #ddd; padding: 5px'>";
                        if (value.company_name) {
                            applicant_recommend_list += "#" + value.user_id + ". " + "<a href='" + value.profile_url + "' target='_blank' style='color: #428bca'>" + value.user_name + " </a> from " + value.company_name;
                        } else {
                            applicant_recommend_list += "#" + value.user_id + ". " + "<a href='" + value.profile_url + "' target='_blank' style='color: #428bca'>" + value.user_name + " </a>";
                        }

                        if (value.referral_user_name) {
                            applicant_recommend_list += " By " + "<a href='" + value.referral_user_url + "' target='_blank' style='color: #428bca'> " + value.referral_user_name + " </a>";
                        }
                        applicant_recommend_list += " At " + value.at_time;
                        applicant_recommend_list += "</div>";
                    }
                });
                if (email_out_recommend_list === "") {
                    email_out_recommend_list = "N/A";
                }
                if (applicant_recommend_list === "") {
                    applicant_recommend_list = "N/A";
                }
                $recommend_email_out.html(email_out_recommend_list);
                $recommend_applicant.html(applicant_recommend_list);
            }
        });

        if (recommend_type == "internal") {
            $("#email-out-tr").show();
        } else if (recommend_type == "external") {
            $("#email-out-tr").hide();
        }

        $recommend_dialog.dialog({
            title: title,
            height: 400,
            width: 1100
        });
    });
    $( ".match-statistics-btn" ).click(function () {
        var statistics = JSON.parse($("#match-statistics").val());
        var statistics_list = "";
        $.each(statistics,function(index, value){
            console.log('My array has at position ' + index + ', this value: ' + value.total_count);
            statistics_list += "<tr><td class='col-md-3'>" + index + "</td>" +
                    "<td>Proposed:" + value.propose_count + " Referrals: " + value.recommend_count + " Total: " +  value.total_count + "</td>" +
                    "<td>Project: " + value.project_count + " </td>" +
                "</tr>";
        });
        $("#project-match-statistics-table").html(statistics_list);
        $match_statistics_dialog.dialog({
            height: 400,
            width: 700
        });
    });
});
