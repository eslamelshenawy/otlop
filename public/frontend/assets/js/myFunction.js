$(document).ready(function () {

    $("#formLock").submit(function (e) {

        $("#btnLogin").attr("disabled", true);
        $("#btnRegister").attr("disabled", true);
        $("#btnRestPassword").attr("disabled", true);
        $("#btnReset").attr("disabled", true);
        $("#Update").attr("disabled", true);
        return true;

    });
});