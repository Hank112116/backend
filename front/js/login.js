"use strict";

$(() => {
    var $oauth_form = $("#oauth-form");
    var $login_form = $("#login-form");

    var $oauth_login_form_link = $("#oauth-login-form-link");
    var $login_form_link       = $("#login-form-link");

    $oauth_form.hide();

    $login_form_link.click(function(e) {
        $login_form.delay(100).fadeIn(100);
        $oauth_form.fadeOut(100);
        $oauth_login_form_link.removeClass("active");
        $(this).addClass("active");
        e.preventDefault();
    });
    $oauth_login_form_link.click(function(e) {
        $oauth_form.delay(100).fadeIn(100);
        $login_form.fadeOut(100);
        $login_form_link.removeClass("active");
        $(this).addClass("active");
        e.preventDefault();
    });
});
