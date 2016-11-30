"use strict";

$(() => {
    $('#oauth-form').hide();

    $('#login-form-link').click(function(e) {
        $("#login-form").delay(100).fadeIn(100);
        $("#oauth-form").fadeOut(100);
        $('#oauth-login-form-link').removeClass('active');
        $(this).addClass('active');
        e.preventDefault();
    });
    $('#oauth-login-form-link').click(function(e) {
        $("#oauth-form").delay(100).fadeIn(100);
        $("#login-form").fadeOut(100);
        $('#login-form-link').removeClass('active');
        $(this).addClass('active');
        e.preventDefault();
    });
});