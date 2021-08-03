@extends('layouts.layout')

@section('title')
    Dashboard Layout
@stop


{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> --}}
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css"> --}}
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css"> --}}
{{-- <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css"> --}}
{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" rel="stylesheet"
    type="text/css" /> --}}

@section('main-content')
    {{-- {{ dd($users) }} --}}
    <div id="content">
        <div class="container-fluid">
            <div class="panel panel-default">

                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            {{-- <h2>CUSTOMIZABLE MENU</h2> --}}
                            <br>
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#home">FOOTER LINKS</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#menu1">DASHBOARD SORTING</a>
                                </li>
                            </ul>

                            @if (session()->has('message'))
                                <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i>
                                    {{ session()->get('message') }}
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                </div>
                            @endif

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div id="home" class="container tab-pane active"><br>
                                    <form name="myForm" action="{{ route('dashboardlayout') }}" method="POST"
                                        enctype="multipart/form-data" id="form-user" class="form-horizontal" onsubmit="return checkout(this)">
                                        @csrf
                                        {{-- <div class="row">
                                                <div class="col-md-12 bg-light text-right">
                                                    <button type="button" class="btn btn-primary">Cancel</button>
                                                    <button type="button" class="btn btn-warning ml-2">Save</button>
                                                </div>
                                            </div> --}}




                                        <div class="form-group control_system pull-right col-md-1">
                                            <input type="submit" id="Submit" value="Save" class="btn btn-primary">
                                        </div>
                                        <?php echo "<h1 class='title align-center'
                                            style='text-align: center;'>MENU LIST</h1>"; ?>

                                        <div class="clearfix"></div>

                                        <?php
                                        $max_per_row = 5;
                                        $item_count = 0;

                                        echo '<table class="table table-bordered">';

                                            echo '<tr>';
                                                foreach ($data as $key => $user_data) {
                                                if ($item_count == $max_per_row) {
                                                echo '<th>Menu List</th>';
                                                // echo '</tr>';
                                            }
                                            }
                                            // echo '<tr>';
                                                foreach ($data as $key => $user_data) {
                                                if ($item_count == $max_per_row) {
                                                echo "</tr>
                                            <tr>";
                                                $item_count = 0;
                                                }
                                                if (isset($user_data->iuserid)) {
                                                echo "<td>
                                                    <div class='custom-control custom-checkbox'>
                                                        <input type='checkbox' class='custom-control-input'
                                                            id='$user_data->menu_des' name='menu[]'
                                                            value='$user_data->menu_des' checked>
                                                        <label class='custom-control-label' for='$user_data->menu_des'>
                                                            $user_data->menu_name</label>
                                                    </div>
                                                </td>";
                                                } else {
                                                echo "<td>
                                                    <div class='custom-control custom-checkbox'>
                                                        <input type='checkbox' class='custom-control-input'
                                                            id='$user_data->menu_des' name='menu[]'
                                                            value='$user_data->menu_des'>
                                                        <label class='custom-control-label' for='$user_data->menu_des'>
                                                            $user_data->menu_name</label>
                                                    </div>
                                                </td>";
                                                }

                                                $item_count++;
                                                }
                                                echo '</tr>';
                                            echo "
                                        </table>";
                                        ?>
                                    </form>
                                </div>
                                <div id="menu1" class="container tab-pane fade"><br>
                                    <h3>Dashboard Sorting</h3>
                                    <p>This is dashboard sorting</p>
                                </div>
                            </div>



                        </div>
                    </div>

                </div>


            </div>
        </div>
    </div>
@endsection

@section('page-script')
    {{-- <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script> --}}
    {{-- <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script> --}}
    {{-- <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script> --}}
    <script>
        $(document).ready(function() {
            $('.mydatatable').DataTable();
            // alert("Testing now");
        });

        function checkout() {
            var checkBoxes = document.getElementsByClassName('custom-control-input');
            // console.log(checkBoxes);

            var nbChecked = 0;
            for (var i = 0; i < checkBoxes.length; i++) {
                if (checkBoxes[i].checked) {
                    nbChecked++;
                };
            };
            if (nbChecked > 12) {
                alert('You cannot select more than 12 Footer links');
                return false;
            } else if (nbChecked == 0) {
                alert('Please, check at least one checkbox!');
                return false;
            } else {
                //Do what you need for form submission, if needed...
            }

            // const checkBoxes = document.querySelectorAll('.custom-control-input:checked');

            // if (checkBoxes.length > 3) {
            //     alert('You cannot select more than 3 books');
            //     return false;
            // }
        }

    </script>
    <script type="text/javascript">
        $(window).load(function() {
            $("div#divLoading").removeClass('show');
        });

    </script>
    <script>
        function myFunction() {
            $("form-users").sumbit();
        }


        $(document).on('keyup', '#automplete-product', function(event) {
            event.preventDefault();

            $('#users tbody tr').hide();
            var txt = $(this).val().toUpperCase();
            var td1, td2, td3, td4, td5;

            if (txt != '') {
                $('#users tbody tr').each(function() {

                    td1 = $(this).find("td")[1];
                    td2 = $(this).find("td")[2];
                    td3 = $(this).find("td")[3];
                    td4 = $(this).find("td")[4];
                    td5 = $(this).find("td")[5];

                    if (td1 || td2 || td3 || td4 || td5) {
                        if (td1.innerHTML.toUpperCase().indexOf(txt) > -1 || td2.innerHTML.toUpperCase()
                            .indexOf(txt) > -1 || td3.innerHTML.toUpperCase().indexOf(txt) > -1 || td4
                            .innerHTML.toUpperCase().indexOf(txt) > -1 || td5.innerHTML.toUpperCase()
                            .indexOf(txt) > -1) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    }
                });
            } else {
                $('#users tbody tr').show();
            }
        });


        $(function() {
            $('input[name="automplete-product"]').focus();
        });

    </script>


    <div class="modal fade" id="successModal" role="dialog">
        <div class="modal-dialog modal-sm">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="border-bottom:none;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success text-center">
                        <p id="success_msg"></p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="modal fade" id="errorModal" role="dialog" style="z-index: 9999;">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="border-bottom:none;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger text-center">
                        <p id="error_msg"></p>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none;">
                    <button type="button" class="btn btn-info" data-dismiss="modal">OK</button>
                </div>
            </div>

        </div>
    </div>
    <style>
        .disabled {
            pointer-events: none; //This makes it not clickable

        }

    </style>

@endsection
