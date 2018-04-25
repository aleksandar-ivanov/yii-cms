$(document).ready(function() {

    var $installSelectedBtn = $('.install-selected');

    $('input').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square',
        increaseArea: '20%' // optional
    });

    $installSelectedBtn.on('click', function (ev) {
        var keys = $('#w0').yiiGridView('getSelectedRows');

        if (!keys.length) {
            return;
        }

        $.ajax({
            url : '/module/installmany',
            data : {
                ids : keys
            }
        }).then(function (res) {
            window.location.reload();
        });
    });

    $('.check-module').on('ifToggled', function (ev) {

        var keys = $('#w0').yiiGridView('getSelectedRows');
        var installSelectedBtnState = keys.length < 1;

        $installSelectedBtn.prop('disabled', installSelectedBtnState );
    });

    $('.select-on-check-all').on('ifToggled', function (ev) {
        var $table = $(ev.target).closest('table');

        var command = $(ev.target).prop('checked') ? 'check' : 'uncheck';

        $table.find('.check-module').iCheck(command);

        var keys = $('#w0').yiiGridView('getSelectedRows');
        var installSelectedBtnState = keys.length < 1;

        $installSelectedBtn.prop('disabled', installSelectedBtnState );
    });

    $('.uninstall').on('click', function (ev) {
        ev.preventDefault();

        var moduleId = $(ev.target).data('module-id');

        $.ajax({
            url : '/module/checkmoduledependencies',
            data : {
                id : moduleId
            }
        }).then(function (res) {
            var confirmed = true;
            if (res.dependencies.length > 1) {
                confirmed = confirm("The module you try to uninstall has the followind dependencies : "
                    + res.dependencies.join(',') + '. Are you sure you want to uninstall?'
                );
            }

            if (res.dependencies.length < 1 || confirmed) {
                $.ajax({
                    url : $(ev.target).attr('href')
                }).then(function () {
                    window.reload();
                }).catch(function (err) {
                    console.log(err);
                });
            }

        }).catch(function (err) {
            console.log(err);
        });
    })

});