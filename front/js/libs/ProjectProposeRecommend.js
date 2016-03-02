/* jshint quotmark: false */
$(function () {
    var $propose_dialog   = $("#propose-solution-dialog");
    var $recommend_dialog = $("#recommend-expert-dialog");
    $( ".project_propose" ).click(function () {
        var $this   = $(this);
        var title   = $this.attr("title");
        var propose = $this.attr("propose");
        propose = JSON.parse(propose);
        var propose_list = '';
        $.each(propose, function(index, value) {
            propose_list += "<div style='border-bottom: 1px solid #ddd; padding: 5px'>" +
                "#" + value.solution_id + ". " + "<a href='" + value.solution_url + "' target='_blank' style='color: #428bca'> " + value.solution_title +" </a>" +
                "</div>";
        });
        $propose_dialog.html(propose_list);
        $propose_dialog.dialog({
            title: title,
            height: 400,
            width: 600
        });
    });

    $( ".project_recommend" ).click(function () {
        var $this     = $(this);
        var title     = $this.attr("title");
        var recommend = $this.attr("recommend");
        recommend     = JSON.parse(recommend);
        var applicant_recommend_list = '';
        var email_out_recommend_list = '';
        $.each(recommend, function(index, value) {

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
        $("#email-out-recommend").html(email_out_recommend_list);
        $("#applicant-recommend").html(applicant_recommend_list);
        $recommend_dialog.dialog({
            title: title,
            height: 400,
            width: 700
        });
    });
});
