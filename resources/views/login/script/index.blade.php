<script>
    let app = new Vue({
        el: "#login",
        data: {
            email: "",
            password: "",
        },
        methods: {
            submitLogin: function() {
                const form_data = new FormData();
                const self = this;
                form_data.append("email", self.email);
                form_data.append("password", self.password);
                $.ajax({
                    url: "{{ route('loging-in') }}",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    type: "POST",
                    data: form_data,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        showLoading();
                        $("#alert-message-error").fadeOut(300);
                        $("#alert-message-success").fadeOut(200);
                    },
                    success: function(response) {
                        $("#alert-message-success").find(".alert-body").html(response
                            .message);
                        $("#alert-message-success").fadeIn(200);
                        $("#form-login")[0].reset();
                        const redirect_url = response.data.redirect_url;
                        window.location.href(redirect_url);
                    },
                    error: function(xhr, status, error) {
                        const responseJson = xhr.responseJSON;
                        $("#alert-message-error").find(".alert-body").html(responseJson
                            .message);
                        $("#alert-message-error").fadeIn(200)
                        switch (xhr.status) {
                            case 422:
                                const errors = Object.entries(responseJson.errors);
                                errors.forEach(([field, message]) => {
                                    field = field.replace('.', '_');
                                    $(`div.invalid-feedback[for="${field}"]`).html(
                                        message);
                                    $(`#${field}`).addClass('is-invalid');
                                });
                                setTimeout(
                                    function() {
                                        $("#alert-message-error").fadeOut(300)
                                    }, 2000);
                                break;
                        }

                    },
                    complete: function() {
                        hideLoading();
                        document.body.scrollTop = 0;
                        document.documentElement.scrollTop = 0;
                    }
                })
            },
            remove_invalid_error: function(target) {
                $(target).removeClass('is-invalid');
            },
        },
    })
</script>
