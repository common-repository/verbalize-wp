"use strict";

jQuery(document).ready(function ($) {

    $('.pause-aud').hide();
    $('.speak-aud').hide();
    $('.mute-aud').hide();
    $('.pause-text').hide();
    $('.mute-text').hide();
    $('.speak-text').hide();

    /* Audio Play */
    $('.play-aud').click(function () {

        $('audio').each(function () {
            this.pause(); // Stop playing
            this.currentTime = 0; // Reset time
        });

        $(".speak-aud-default").show();
        $(".speak-text-default").show();
        $('.play-aud').show();
        $('.play-text').show();

        $('.pause-aud').hide();
        $('.speak-aud').hide();
        $('.mute-aud').hide();
        $('.pause-text').hide();
        $('.mute-text').hide();
        $('.speak-text').hide();


        var audiofile = $(this).data("src");
        var audio_id = $(this).data("id");
        var audioElement = document.createElement('audio');

        audioElement.setAttribute('src', audiofile);
        audioElement.setAttribute('class', "audio-short");
        audioElement.setAttribute('id', "audio-" + audio_id);

        audioElement.addEventListener('ended', function () {
            audioElement.pause();
            $('#pause-' + audio_id).hide();
            $('#play-' + audio_id).show();
        }, false);

        $("#audio-set-" + audio_id).html(audioElement);
        $('#play-' + audio_id).hide();
        audioElement.play();
        $('#pause-' + audio_id).show();
    });

    /* Audio Pause */
    $('.pause-aud').click(function () {
        var audio_id = $(this).data("id");
        var audioElement = document.getElementById("audio-" + audio_id);
        audioElement.pause();

        $("#audio-set-" + audio_id).html('');
        $('#pause-' + audio_id).hide();
        $('#play-' + audio_id).show();
    });

    /* Text Audio Play */
    $('.play-text').click(function () {

        $('audio').each(function () {
            this.pause(); // Stop playing
            this.currentTime = 0; // Reset time
        });

        $(".speak-aud-default").show();
        $(".speak-text-default").show();
        $('.play-aud').show();
        $('.play-text').show();

        $('.pause-aud').hide();
        $('.speak-aud').hide();
        $('.mute-aud').hide();
        $('.pause-text').hide();
        $('.mute-text').hide();
        $('.speak-text').hide();

        var audiofile = $(this).data("src");
        var audio_id = $(this).data("id");
        var audioElement = document.getElementById('text-audio-' + audio_id);

        audioElement.addEventListener('ended', function () {
            audioElement.pause();
            $('#pause-text-' + audio_id).hide();
            $('#play-text-' + audio_id).show();
        }, false);

        $('#play-text-' + audio_id).hide();
        audioElement.play();
        $('#pause-text-' + audio_id).show();
    });

    /* Text Audio Pause */
    $('.pause-text').click(function () {
        var audio_id = $(this).data("id");
        var audioElement = document.getElementById("text-audio-" + audio_id);
        audioElement.pause();
        $('#pause-text-' + audio_id).hide();
        $('#play-text-' + audio_id).show();
    });

    $(".text-convert-modal .close").click(function () {
        $('audio').each(function () {
            this.pause(); // Stop playing
            this.currentTime = 0; // Reset time
        });

        $(".speak-aud-default").show();
        $(".speak-text-default").show();
        $('.play-aud').show();
        $('.play-text').show();

        $('.pause-aud').hide();
        $('.speak-aud').hide();
        $('.mute-aud').hide();
        $('.pause-text').hide();
        $('.mute-text').hide();
        $('.speak-text').hide();
    });

    /* Speak Audio default*/
    $('.speak-aud-default').click(function () {

        $('audio').each(function () {
            this.pause(); // Stop playing
            this.currentTime = 0; // Reset time
        });

        var audio_id = $(this).data("id");

        $(".speak-aud-default").show();
        $(".speak-text-default").show();
        $('.play-aud').show();
        $('.play-text').show();

        $('.pause-aud').hide();
        $('.speak-aud').hide();
        $('.mute-aud').hide();
        $('.pause-text').hide();
        $('.mute-text').hide();
        $('.speak-text').hide();

        var audiofile = $(this).data("src");
        var audioElement = document.createElement('audio');

        audioElement.setAttribute('src', audiofile);
        audioElement.setAttribute('class', "audio-short");
        audioElement.setAttribute('id', "audio-" + audio_id);

        audioElement.addEventListener('ended', function () {
            audioElement.pause();
            $('#mute-' + audio_id).hide();
            $('#speak-' + audio_id).hide();
            $('#speak-default' + audio_id).show();
        }, false);

        $("#audio-set-" + audio_id).html(audioElement);
        $('#speak-default' + audio_id).hide();
        audioElement.play();
        $('#speak-' + audio_id).show();
    });

    /* Speak Audio default*/
    $('.speak-aud').click(function () {

        var audio_id = $(this).data("id");
        var audioElement = document.getElementById("audio-" + audio_id);

        audioElement.addEventListener('ended', function () {
            audioElement.pause();
            $('#mute-' + audio_id).hide();
            $('#speak-' + audio_id).hide();
            $('#speak-default' + audio_id).show();
        }, false);

        audioElement.muted = true;
        $('#mute-' + audio_id).show();
        $('#speak-' + audio_id).hide();
    });

    /* Audio Pause */
    $('.mute-aud').click(function () {
        var audio_id = $(this).data("id");
        var audioElement = document.getElementById("audio-" + audio_id);
        audioElement.muted = false;
        audioElement.addEventListener('ended', function () {
            audioElement.pause();
            $('#mute-' + audio_id).hide();
            $('#speak-' + audio_id).hide();
            $('#speak-default' + audio_id).show();
        }, false);

        $('#mute-' + audio_id).hide();
        $('#speak-' + audio_id).show();
    });

    /* Text Audio Play */
    $('.speak-text-default').click(function () {

        var audio_id = $(this).data("id");

        $(".speak-aud-default").show();
        $(".speak-text-default").show();
        $('.play-aud').show();
        $('.play-text').show();

        $('.pause-aud').hide();
        $('.speak-aud').hide();
        $('.mute-aud').hide();
        $('.pause-text').hide();
        $('.mute-text').hide();
        $('.speak-text').hide();

        $('audio').each(function () {
            this.pause(); // Stop playing
            this.currentTime = 0; // Reset time
        });

        var audiofile = $(this).data("src");
        var audioElement = document.getElementById('text-audio-' + audio_id);

        audioElement.addEventListener('ended', function () {
            audioElement.pause();
            $('#mute-text-' + audio_id).hide();
            $('#speak-text-' + audio_id).hide();
            $('#speak-text-default-' + audio_id).show();
        }, false);

        $('#speak-text-default-' + audio_id).hide();
        audioElement.play();
        $('#speak-text-' + audio_id).show();
        $("#text-short" + audio_id).modal('show');
    });

    /* Speak text*/
    $('.speak-text').click(function () {

        var audio_id = $(this).data("id");
        var audioElement = document.getElementById("text-audio-" + audio_id);

        audioElement.addEventListener('ended', function () {
            audioElement.pause();
            $('#mute-text-' + audio_id).hide();
            $('#speak-text-' + audio_id).hide();
            $('#speak-text-default-' + audio_id).show();
        }, false);

        audioElement.muted = true;
        $('#mute-text-' + audio_id).show();
        $('#speak-text-' + audio_id).hide();
        $('#speak-text-default-' + audio_id).hide();
    });

    /* Text Audio Pause */
    $('.mute-text').click(function () {

        var audio_id = $(this).data("id");
        var audioElement = document.getElementById("text-audio-" + audio_id);
        audioElement.pause();
        $('#mute-text-' + audio_id).hide();
        $('#speak-text-' + audio_id).show();
    });
});