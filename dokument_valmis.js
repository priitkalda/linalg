// kohustuslikud tegevused

$(document).ready(function() {
     
    $("#laiuse_soovitus").click(function() {
        $('#laiuse_soovitus').hide();
    });

    $("#teatekeskus").click(function() {
        teata();
    });

    // tööriistariba nuppude kuulajad
    $("#nupp_lvs_esitamine").click(function() {
        $('#rippmenüü_2').toggle();
    });


    $("#nupp_lvs_0").click(function() {
        lvs_lah_puuduvad();
        $('#rippmenüü_2').hide();

    });

    $("#nupp_lvs_1").click(function() {
        lvs_lahendite_kontrollimine(1);
        $('#rippmenüü_2').hide();

    });
    $("#nupp_lvs_1+").click(function() {
        lvs_lahendite_kontrollimine(0);
        $('#rippmenüü_2').hide();
    });




    $("#nupp_pöörd").click(function() {
        $('#rippmenüü').toggle();
    });




    $("#nupp_pöörd_valmis").click(function() {
        pöördmaatriksi_kontrollimine(true);
        $('#rippmenüü').hide();

    });

    $("#nupp_pöörd_pole").click(function() {
        pöördmaatriksi_kontrollimine(false);
        $('#rippmenüü').hide();
    });



    $("#raadio_veerud").click(function() {
        if ($('#raadio_veerud:checked').val()) {
            kasVeerud = true;
        }
    });

    $("#raadio_read").click(function() {
        if ($('#raadio_read:checked').val()) {
            kasVeerud = false;
        }
    });

    //v
    $("#linnuke_vahetus").click(function() {
        if ($('#linnuke_vahetus:checked').val()) {
            $('#linnuke_liitkor').prop("checked", false);
            kasVahetada = true;
        } else {
            $('#linnuke_liitkor').prop("checked", true);
            kasVahetada = false;
        }
        kasOotan = false;
        kasArenda = false;
        kasDet = false;
        kasLvs = false;
        kasAstmed = false;
        kasPöörd = false;

        $('#linnuke_arendamine').prop("checked", false);
        $('#linnuke_determinandi_esitamine').prop("checked", false);
        $('#linnuke_eemalda_nullidest_rida').prop("checked", false);
        $('#linnuke_astaku_esitamine').prop("checked", false);
        $('#linnuke_pöördmaatriksi_esitamine').prop("checked", false);


    });


    $("#tagasi").click(function() {
        võta_samm_tagasi();
    });


    //a
    $("#linnuke_arendamine").click(function() {
        if ($('#linnuke_arendamine:checked').val()) {

            $('#linnuke_liitkor').prop("checked", false);
            kasArenda = true;
        } else {

            $('#linnuke_liitkor').prop("checked", true);
            kasArenda = false;
        }
        kasOotan = false;
        kasVahetada = false;
        kasDet = false;
        kasLvs = false;
        kasAstmed = false;
        kasPöörd = false;

        $('#linnuke_vahetus').prop("checked", false);
        $('#linnuke_determinandi_esitamine').prop("checked", false);
        $('#linnuke_eemalda_nullidest_rida').prop("checked", false);
        $('#linnuke_astaku_esitamine').prop("checked", false);
        $('#linnuke_pöördmaatriksi_esitamine').prop("checked", false);
    });

    //det
    $("#linnuke_determinandi_esitamine").click(function() {

        if ($('#linnuke_determinandi_esitamine:checked').val()) {

            $('#linnuke_liitkor').prop("checked", false);
            kasDet = true;
        } else {

            $('#linnuke_liitkor').prop("checked", true);
            kasDet = false;
        }
        kasOotan = false;
        kasArenda = false;
        kasVahetada = false;
        kasLvs = false;
        kasAstmed = false;
        kasPöörd = false;

        $('#linnuke_vahetus').prop("checked", false);
        $('#linnuke_arendamine').prop("checked", false);
        $('#linnuke_eemalda_nullidest_rida').prop("checked", false);
        $('#linnuke_astaku_esitamine').prop("checked", false);
        $('#linnuke_pöördmaatriksi_esitamine').prop("checked", false);


    });

    //nul
    $("#linnuke_eemalda_nullidest_rida").click(function() {
        if ($('#linnuke_eemalda_nullidest_rida:checked').val()) {

            $('#linnuke_liitkor').prop("checked", false);
            kasLvs = true;
        } else {

            $('#linnuke_liitkor').prop("checked", true);
            kasLvs = false;
        }
        kasVeerud = false;

        if ($('#raadio_veerud:checked').val()) {

            $('#raadio_veerud').prop("checked", false);
            $('#raadio_read').prop("checked", true);
        }

        kasOotan = false;
        kasArenda = false;
        kasVahetada = false;
        kasDet = false;
        kasAstmed = false;
        kasPöörd = false;

        $('#linnuke_vahetus').prop("checked", false);
        $('#linnuke_arendamine').prop("checked", false);
        $('#linnuke_determinandi_esitamine').prop("checked", false);
        $('#linnuke_astaku_esitamine').prop("checked", false);
        $('#linnuke_pöördmaatriksi_esitamine').prop("checked", false);
    });

    //as
    $("#linnuke_astaku_esitamine").click(function() {

        if ($('#linnuke_astaku_esitamine:checked').val()) {

            $('#linnuke_liitkor').prop("checked", false);
            kasAstmed = true;
        } else {

            $('#linnuke_liitkor').prop("checked", true);
            kasAstmed = false;
        }
        kasOotan = false;
        kasArenda = false;
        kasVahetada = false;
        kasDet = false;
        kasLvs = false;
        kasPöörd = false;

        $('#linnuke_vahetus').prop("checked", false);
        $('#linnuke_arendamine').prop("checked", false);
        $('#linnuke_determinandi_esitamine').prop("checked", false);
        $('#linnuke_eemalda_nullidest_rida').prop("checked", false);
        $('#linnuke_pöördmaatriksi_esitamine').prop("checked", false);
    });

    //pn
    $("#linnuke_pöördmaatriksi_esitamine").click(function() {
        if ($('#linnuke_pöördmaatriksi_esitamine:checked').val()) {

            $('#linnuke_liitkor').prop("checked", false);
            kasPöörd = true;
        } else {

            $('#linnuke_liitkor').prop("checked", true);
            kasPöörd = false;
        }
        kasOotan = false;
        kasArenda = false;
        kasVahetada = false;
        kasDet = false;
        kasLvs = false;
        kasAstmed = false;

        $('#linnuke_vahetus').prop("checked", false);
        $('#linnuke_arendamine').prop("checked", false);
        $('#linnuke_determinandi_esitamine').prop("checked", false);
        $('#linnuke_eemalda_nullidest_rida').prop("checked", false);
        $('#linnuke_astaku_esitamine').prop("checked", false);
    });


    $("#linnuke_liitkor").click(function() {
        if ($('#linnuke_liitkor:checked').val()) {

            $('#linnuke_liitkor').prop("checked", true);
        } else {

            $('#linnuke_liitkor').prop("checked", true);

        }
        kasOotan = false;
        kasArenda = false;
        kasVahetada = false;
        kasDet = false;
        kasLvs = false;
        kasAstmed = false;
        kasPöörd = false;

        $('#linnuke_vahetus').prop("checked", false);
        $('#linnuke_arendamine').prop("checked", false);
        $('#linnuke_determinandi_esitamine').prop("checked", false);
        $('#linnuke_eemalda_nullidest_rida').prop("checked", false);
        $('#linnuke_astaku_esitamine').prop("checked", false);
        $('#linnuke_pöördmaatriksi_esitamine').prop("checked", false);
    });



    // tühista viimatised pooleli tegevusd, kui klõpsata tühjale ekraani alale
    $('#keha').click(
        function(e) {
            //console.log("e.target: "+e.target.className+", this: "+this.className);
            if (e.target == this) {
                kasOotan = false;
            }

            $('#rippmenüü').hide();
            $('#rippmenüü_2').hide();
            //$('#abi_1').hide();

        }
    );

    $('#rippmenüü').hide();
    $('#rippmenüü_2').hide();
    renderda_maatriks();
    värskenda_sammuloendurit();
    $('#raadio_read').prop("checked", true);
    $('#linnuke_liitkor').prop("checked", true);

    $.ajaxSetup({
        type: 'POST',
        cache: true,
        timeout: 4000,
        error: function(xhr) {
            alert("Viga serverist vastuse kontrollimisel.");
        }
    });

});