<!DOCTYPE html>
<html dir="" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="htmlcss bootstrap menu, navbar, hover nav menu CSS examples" />
    <meta name="description" content="Bootstrap navbar hover examples for any type of project, Bootstrap 4" />


    <title>Administration | Login</title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />


    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    {{-- <link href="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table.min.css" rel="stylesheet"
        type="text/css" /> --}}
    {{-- <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet"> --}}

    <link rel="stylesheet" href="{{ asset('asset/css/login.css') }}">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js" type="text/javascript">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/js/bootstrap.bundle.min.js"
        type="text/javascript"></script>

    <script src="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table.min.js"></script>

    <style>
        .loginCard {
            display: none;
        }

        .verifyCard {
            display: none;
        }

        .login-img {}

    </style>
</head>

<body>

    <div class="container-fluid bg-light" id="login-container">
        <div class="row">
            <div id="divLoading" class="show"></div>
            <div class="col-7">
                <div class="login-content text-capitalize">
                    <h3 class="text-white text-center">check out all our other products & services</h3>
                </div>
                <div class="icons-content-text text-center text-white login-img">
                    <img src="{{ asset('image/outline_credit_card_white_24dp.png') }}" />
                    <p class="text-capitalize login-texts font-weight-bold">Card Processing</p>
                    <p class="text-capitalize login-conts">we will meet or beat your current card processing rates!
                    </p>
                </div>
                <div class="icons-content-text text-center text-white login-img">
                    <img src="{{ asset('image/outline_restaurant_white_24dp.png') }}" />
                    <p class="text-capitalize login-texts font-weight-bold">food ordering kiosk</p>
                    <p class="text-capitalize login-conts">running your business, not your wallet</p>
                </div>
                <div class="icons-content-text text-center text-white login-img">
                    <img src="{{ asset('image/outline_local_atm_white_24dp.png') }}" />
                    <p class="text-capitalize login-texts font-weight-bold">atm</p>
                    <p class="text-capitalize login-conts">easy, safe, secure</p>
                </div>
                <div class="icons-content-text text-center text-white">
                    <img src="{{ asset('image/outline_shopping_cart_white_24dp.png') }}" />
                    <p class="text-capitalize login-texts font-weight-bold">Express Checkout</p>
                    <p class="text-capitalize login-conts">Start busting lines!</p>
                </div>
                <div class="d-flex justify-content-center mb-5">
                    <a type="button" href="https://www.albertapayments.com"
                        class="login-btn text-capitalize bg-white text-primary text-center font-weight-bold">click
                        here for more information</a>
                </div>
            </div>
            <div class="col-5">
                <div class="card" id="card">
                    <img class="card-img-top" src="{{ asset('asset/img/alberta-logo.png') }}" alt="Alberta">
                    <div class="card-body text-center">
                        <p class="card-title text-capitalize m-auto text-center">
                            <span class="text-uppercase text-primary font-weight-bold">login</span>
                            to your account to manage your back office
                        </p>
                        @error('vemail')
                            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{ $message }}
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        @enderror
                        @error('password')
                            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{ $message }}
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        @enderror
                        <form method="POST" action="{{ route('login') }}" class="login-form">
                            @csrf
                            <div class="form-group mt-5">
                                <input type="email" name="vemail" value="" placeholder="Email ID" id="input_email"
                                    class="form-control" />
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" value="" placeholder="Password"
                                    id="input-password" class="form-control" />
                            </div>

                            <button type="submit"
                                class="btn btn-primary btn-block login-btns text-white font-weight-bold text-uppercase">Login</button>
                        </form>
                        {{-- <img class="card-img-top" src="{{ asset('asset/img/alberta-logo.png') }}" alt="Alberta"> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>




    <script>
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });

        $(document).on('click', '#forgotten_link', function(event) {
            event.preventDefault();
            $('#forgottenModal').modal('show');
        });

        $(document).ready(function() {
            $('#versionCard').removeClass('verifyCard');

            $("#proceedBtn").click(function() {
                var vemail = $("#vemail").val();
                if (vemail) {
                    $.ajax({
                        url: '<?php echo url('/checkVersion'); ?>',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        type: 'POST',
                        data: {
                            vemail: vemail
                        }, // access in body
                        success: function(data) {
                            if (data >= 320) {
                                $('#loginFormCard').removeClass('loginCard');
                                $('#versionCard').addClass('verifyCard');
                                $("#input_email").val(vemail);
                            } else {
                                window.location.replace(
                                    "https://tempcustomer.albertapayments.com/?vemail=" +
                                    vemail)
                            }

                        },
                        error: function(request, error) {
                            // alert("Request: "+JSON.stringify(request));
                            return false;
                        }
                    });
                } else {
                    alert("Please enter email to proceed");
                    return false;
                }
            })
        });
    </script>
    <script type="text/javascript">
        $(window).load(function() {
            $("div#divLoading").removeClass('show');
        });

        $(window).on('beforeunload', function() {
            $("div#divLoading").addClass('show');
        });
    </script>


</body>

</html>
