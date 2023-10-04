
const csrfToken = window.csrfToken;

function openSpinner() {
    $("#overlay").fadeIn(300);　
}

function offSpinner() {
    $("#overlay").fadeOut(300);
}

$(document).ready(function() {
    // detectar evento submit changePasswordAuth
    const passwordInput = $('#passwordProfile');
    const confirmPasswordInput = $('#passwordConfirmProfile');

    console.log(userId);


    $("#changePasswordAuth").submit(function(event) {


        // validate input password not empty
        if ($("#passwordProfile").val() == "") {
            toastr.error("La contraseña no puede estar vacía");
            passwordInput.css('border-color', 'red');
            return false;
        } else {
            passwordInput.css('border-color', 'green');
        }

        // validate input confirm password not empty
        if ($("#passwordConfirmProfile").val() == "") {
            toastr.error("La confirmación de contraseña no puede estar vacía");
            confirmPasswordInput.css('border-color', 'red');
            return false;
        } else {
            confirmPasswordInput.css('border-color', 'green');
        }

        // validate input password and confirm password
        if ($("#passwordProfile").val() != $("#passwordConfirmProfile").val()) {
            toastr.error("Las contraseñas no coinciden");
            passwordInput.css('border-color', 'red');
            confirmPasswordInput.css('border-color', 'red');
            return false;
        } else {
            passwordInput.css('border-color', 'green');
            confirmPasswordInput.css('border-color', 'green');
        }

        // prevent default submit
        event.preventDefault();

        // open spinner
        openSpinner();

        // send ajax
        $.ajax({
            type: 'POST',
            url: '/users/change-password/' + userId,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: {
                password: passwordInput.val(),
                password_confirmation: confirmPasswordInput.val(),
            },
            success: function(response) {
                // hide modal

                // close spinner
                offSpinner();
                if ( response.status == 200 ) {
                    toastr.success(response.message);
                    setTimeout(function () {
                        window.location.href = '/';
                    }, 1000);
                } else {
                    toastr.error(response.message);
                }
            }, error: function(e) {
                // close spinner
                offSpinner();
                if ( e?.responseJSON?.message ) {
                    toastr.error(e.responseJSON.message);
                } else if ( e?.message ) {
                    toastr.error(e.message);
                } else {
                    toastr.error('Ha ocurrido un error al guardar el usuario.')
                }
            }
        });


    });
});

