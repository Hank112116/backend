<?php
/**
 * Send Email
 * @author Hank
 **/

class EmailSend
{
    //email template
    const WELCOME_EMAIL_CREATOR              = 1;
    const WELCOME_EMAIL_EXPERT               = 2;
    const FORGOT_PASSWORD                    = 3;
    const SUBMIT_PRODUCT_OWNER               = 8;
    const SUBMIT_PRODUCT_ADMIN               = 19;
    const INCOMING_FUNDING_ADMIN             = 15;
    const INCOMING_FUNDING_OWNER             = 16;
    const INCOMING_FUNDING_BACKER            = 17;
    const CHANGE_PASSWORD                    = 23;
    const CANCEL_DONATION_OWNER              = 24;
    const CANCEL_DONATION_BACKER             = 25;
    const NEW_PRODUCT_UPDATE                 = 32;
    const INCOMING_MESSAGE_ADMIN             = 36;
    const INCOMING_INBOX                     = 37;
    const NEW_COMMENT_TOPIC_IN_PROJECT_PAGE  = 40;
    const NEW_COMMENT_TOPIC_IN_EXPERT_PAGE   = 41;
    const NEW_COMMENT_TOPIC_IN_SOLUTION_PAGE = 56;
    const NEW_COMMENT_REPLY_IN_PROJECT_PAGE  = 42;
    const NEW_COMMENT_REPLY_IN_EXPERT_PAGE   = 43;
    const NEW_COMMENT_REPLY_IN_SOLUTION_PAGE = 57;
    const CHARGE_BACKER_NOTIFICATION         = 44;
    const CHARGE_ADMIN_NOTIFICATION          = 45;
    const SUBMIT_SOLUTION_OWNER              = 46;
    const SUBMIT_SOLUTION_ADMIN              = 47;
    const CREATE_PROJECT_OWNER               = 48;
    const CREATE_PROJECT_ADMIN               = 49;
    const NEW_SOLUTION_ADMIN                 = 51;
    const PUBLISH_PROJECT_OWNER              = 52;
    const PUBLISH_PROJECT_ADMIN              = 53;
    const NEW_FOLLOWER_EXPERT                = 54;
    const NEW_FOLLOWER_SOLUTION              = 55;
    const NEW_SOLUTION_UPDATE                = 58;
    const INBOX_REPLY_ADMIN                  = 59;
    const INBOX_REPLY_USER                   = 60;
    const SUBMIT_EDITING_PRODUCT_ADMIN       = 68;
    const SUBMIT_EDITING_SOLUTION_ADMIN      = 69;
    const SUBMIT_QUESTIONNAIRE_ADMIN         = 70;
    const HUB_FAQ_CONTACT                    = 71;
    const VERIFICATION_MAIL                  = 72;
    const CONTACT_US                         = 73;
    const INCOMING_OVERVIEW_COMMENT_ADMIN    = 74;
    const NEW_COMMENT_TOPIC_IN_OVERVIEW_PAGE = 75;
    const NEW_COMMENT_REPLY_IN_OVERVIEW_PAGE = 76;
    const TEAM_MEMBER_INVITATION_REGISTER    = 77;
    const TEAM_MEMBER_INVITATION_GUEST       = 79;
    const REQUEST_TO_ACCESS                  = 81;
    const REFFERRAL_TEAM_MEMBER_OWNER        = 82;
    const REFFERRAL_TEAM_MEMBER_INVITEE      = 83;
    const BROADCAST_TO_CREATORS_W_PROJECT    = 86;
    const BROADCAST_TO_CREATORS_WO_PROJECT   = 87;
    const BROADCAST_TO_EXPERTS               = 88;
    const HUB_SCHEDULE_RELEASE               = 89;

    private $mailer;
    private $mailSetting;
    public function send($data)
    {
        $env = env('APP_ENV');
        $this->mailSetting = config("email.{$env}");
        $this->mailer      = new PHPMailer();

        $this->mailer->isSMTP();
        $this->mailer->isHTML(true);

        $this->mailer->CharSet    = $this->mailSetting["charset"];
        $this->mailer->Host       = $this->mailSetting["host"];
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = $this->mailSetting["user_name"];
        $this->mailer->Password   = $this->mailSetting["password"];
        $this->mailer->SMTPSecure = $this->mailSetting["smtp_secure"];
        $this->mailer->Port       = $this->mailSetting["port"];
        $this->mailer->From       = $this->mailSetting["from"];
        $this->mailer->FromName   = $this->mailSetting["from_name"];

        $this->mailer->addReplyTo($this->mailSetting["from"], $this->mailSetting["from_name"]);
        $this->mailer->addAddress($data["address"]);
        if (isset($data["cc"])) {
            $this->mailer->addCC($data["cc"]);
        }
        if (isset($data["bcc"])) {
            foreach ($data["bcc"] as $row) {
                $this->mailer->addBCC($row);
            }
        }
        $this->mailer->Subject = $data["title"];
        $this->mailer->Body    = $data["body"];
        return $this->mailer->send();
    }
    /**
     * Returns content html
     * Replace content via $replace_arr,
     * Will add '{' and '}' to key and replace with value
     *
     * @param string content
     * @param string replace array
     * @return string
     */
    public function content_replace($content, $replace_arr)
    {
        $replace_arr['break'] = '<br>';
        foreach ($replace_arr as $key => $value) {
            $content = str_replace('{' . $key . '}', $value, $content);
        }

        return $content;
    }
    /**
     * Convert raw data to plaintext brief with char limit
     * This will remove all html tags and attributes in $raw_data
     * And slice its length <= $char_limit
     * Add '...' at the end if needed
     *
     * @param string $raw_data String of raw data which may contains html tag
     * @param int $char_limit (Optional) Default 200
     * @return string XSS cleaned
     */
    public function convert_to_brief($raw_data, $char_limit = 200)
    {
        // Remove html tags and attributes from $raw_data
        $sf_data = htmlspecialchars_decode(
            htmlentities(
                html_entity_decode(
                    strip_tags($raw_data)
                ),
                ENT_NOQUOTES
            )
        );

        // Extract first limit chars
        $brief = mb_substr(
            $sf_data,
            0,
            $char_limit
        );

        // Add '...' in the end of string if strlen($sf_data) > $char_limit
        if (mb_strlen($sf_data) > $char_limit) {
            $brief .= '...';
        }

        return $brief;
    }
}
