$(document).ready(function () {


    /**
     * for currecy selection drop down in availability page
     */
    $("#ls-currency li a").click(function () {
        var selText = $(this).text();
        $("#hdn_currency").val(selText);
        $(this).parents('.btn-group').find('.dropdown-toggle').html(selText + '<span class="caret"></span>');
    });

    /**
     *for adult drop down in availability page
     */
    $("#ls-adult-count li a").click(function () {
        var selText = $(this).text();
        $("#adults").val(selText);
        $(this).parents('.btn-group').find('.dropdown-toggle').html(selText + '<span class="caret"></span>');
    });

    /**
     *for child drop down in availability page
     */
    $("#ls-child-count li a").click(function () {
        var selText = $(this).text();
        $("#children").val(selText);
        $(this).parents('.btn-group').find('.dropdown-toggle').html(selText + '<span class="caret"></span>');
    });

    /**
     *for card type selection drop down in your details page.
     */
    $("#ls-card-type li a").click(function () {
        var selText = $(this).text();
        $("#card-type").val(selText);
        $(this).parents('.btn-group').find('.dropdown-toggle').html(selText + '<span class="caret"></span>');
    });

    /**
     *for card expire year selection drop down in your details page.
     */
    $("#ls-expire-year li a").click(function () {
        var selText = $(this).text();
        $("#expire-year").val(selText);
        $(this).parents('.btn-group').find('.dropdown-toggle').html(selText + '<span class="caret"></span>');
    });

    /**
     *for card expire month selection drop down in your details page.
     */
    $("#ls-expire-month li a").click(function () {
        var selText = $(this).text();
        $("#expire-month").val(selText);
        $(this).parents('.btn-group').find('.dropdown-toggle').html(selText + '<span class="caret"></span>');
    });

    /**
     *for country selection drop down in your details page.
     */
    $("#ls-country li a").click(function () {
        var selText = $(this).text();
        $("#sel-country").val(selText);
        $(this).parents('.btn-group').find('.dropdown-toggle').html(selText + '<span class="caret"></span>');
    });

    /**
     *for title selection drop down in your details page.
     */
    $("#ls-title li a").click(function () {
        var selText = $(this).text();
        $("#sel-title").val(selText);
        $(this).parents('.btn-group').find('.dropdown-toggle').html(selText + '<span class="caret"></span>');
    });


    /**
     * enable submit button
     */
    $("#termCondition").click(function(){
        if($(this).is(':checked')){
            $('[type=submit]').removeAttr('disabled');
        }else{
            $('[type=submit]').attr('disabled', 'disabled');
        }
    });


    /**
     *
     */
    $('#arraival-date').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
    });


    /**
     *
     */
    $('#departure-date').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
    });

    /**
     *
     */
    $(function () {
        $('.btn-radio').click(function(e) {
            $('.btn-radio').not(this).removeClass('active')
                .siblings('input').prop('checked',false)
                .siblings('.img-radio').css('opacity','0.5');
            $(this).addClass('active')
                .siblings('input').prop('checked',true)
                .siblings('.img-radio').css('opacity','1');
        });
    });


    /**
     *
     */
    $('#reviewReservation').click(function() {
        if (!$("input[name='cardType']:checked").val()) {
            $("#messageModel").modal('show');
            return false;
        }
        else {
            return true;
        }
    });


});