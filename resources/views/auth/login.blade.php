<!DOCTYPE html>
<html dir="" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title>Administration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <link href="{{ asset('stylesheet/bootstrap.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('javascript/font-awesome/css/font-awesome.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('javascript/summernote/summernote.css') }}" rel="stylesheet" />
    <link href="{{ asset('javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css') }}" type="text/css" rel="stylesheet" media="screen" />
    <link type="text/css" href="{{ asset('stylesheet/stylesheet.css') }}" rel="stylesheet" media="screen" />
    <link rel="stylesheet" href="{{ asset('stylesheet/jquery-ui.css') }}">


    <script type="text/javascript" src="{{ asset('javascript/jquery/jquery-2.1.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('javascript/bootstrap/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('javascript/summernote/summernote.js') }}"></script>
    <!--<script src="{{ asset('javascript/jquery/datetimepicker/moment.js') }}" type="text/javascript"></script>
    <script src="{{ asset('javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>-->
    <script src="{{ asset('javascript/common.js') }}" type='text/javascript'></script>
    <script src="{{ asset('javascript/jquery/jquery-ui.js') }}"></script>

<style>

.dropbtn {
    background-color: #f05a28;
    color: white;
    padding: 13px;
    font-size: 14px;
    border: none;
    cursor: pointer;
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    overflow-y: auto;
    height: 150px;
}

.dropdown-content span {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-content span:hover {background-color: #f05a28;cursor: pointer;color:#fff;}

.dropdown:hover .dropdown-content {
    display: block;
}

.dropdown:hover .dropbtn {
    background-color: #f05a28;
}
#header{
    background-color:#03A9F4;
}
</style>

</head>
<body style="background-color: #fff">
    <div id="divLoading" class="show"></div>
    <header id="header" class="navbar navbar-static-top">
        <div class="navbar-header">
            @guest
            @else
            <a type="button" id="button-menu" id="menu-toggle" class="pull-left"><i class="fa fa-indent fa-lg"></i></a>
            @endguest
            <a href="" class="navbar-brand" style="color: #fff; font-size: 20px; font-weight: bold;">{{ config('app.name') }}</a>
        </div>

            @guest
            @else
            <ul class="nav pull-right">
                <li><a href="" title="Store"  class="di_store_name" style="border-left:none;"><i class="fa fa-chevron-down"></i></a></li>
                <li><a href="" title="Reports" class="di_reports"><i class="fa fa-bar-chart"></i></a></li>
                <li><a href="" title="Settings"><i class="fa fa-cog"></i></a></li>
                <li><a href="" title="Quick Links"><i class="fa fa-external-link"></i></a></li>
                <li><a href="{{ url('/logout') }}" title="Logout"><i class="fa fa-sign-out fa-lg"></i></a></li>
            </ul>
            @endguest
        </header>

        <div id="content" style="margin-top: 34px ">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-offset-4 col-sm-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        <h1 class="panel-title"><i class="fa fa-lock"></i> Please enter your login details.</h1>
                        </div>
                        <div class="panel-body">
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
                        <form  method="POST"  action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                            <label for="input-username">Username</label>
                            <div class="input-group"><span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="text" name="vemail" value="" placeholder="" id="input-username" class="form-control" />
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="input-password">Password</label>
                            <div class="input-group"><span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                <input type="password" name="password" value="" placeholder="" id="input-password" class="form-control" />
                            </div>


                            <span class="help-block" id="forgotten_link"><a href="">Forgot Password</a></span>

                            </div>
                            <div class="text-right">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-key"></i> Login</button>
                            </div>

                        </form>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>

        <footer id="footer">
            &copy; 2020 All Rights Reserved.
        </footer>

        <script>
            $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
            });
            
            $(document).on('click', '#forgotten_link', function(event) {
                event.preventDefault();
                $('#forgottenModal').modal('show');
            });
        </script>
        <script type="text/javascript">
            $(window).load(function() {
              $("div#divLoading").removeClass('show');
            });

            $(window).on('beforeunload', function(){
              $("div#divLoading").addClass('show');
            });
        </script>
        
        <div id="forgottenModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-12 text-center">
                      <p style="font-size: 15px"><b>To Reset Your Password Call On :</b><a href="tel:+18885026650" style="font-size: 15px">&nbsp;&nbsp;1-888-502-6650</a></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
</body>
</html>
