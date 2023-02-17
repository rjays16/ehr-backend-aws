<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>iHomp | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->


  <link rel="stylesheet" href="<?php echo asset('bower_components/bootstrap/dist/css/bootstrap.min.css')?>" type="text/css">
  <link rel="stylesheet" href="<?php echo asset('bower_components/font-awesome/css/font-awesome.min.css')?>" type="text/css">
  <link rel="stylesheet" href="<?php echo asset('bower_components/Ionicons/css/ionicons.min.css')?>" type="text/css">
  <link rel="stylesheet" href="<?php echo asset('dist/css/AdminLTE.min.css')?>" type="text/css">
  <link rel="stylesheet" href="<?php echo asset('css/sweetalert.min.css')?>" type="text/css">
  <link rel="stylesheet" href="<?php echo asset('dist/css/skins/_all-skins.min.css')?>" type="text/css">
  <link rel="stylesheet" href="<?php echo asset('bower_components/morris.js/morris.css')?>" type="text/css">
  <link rel="stylesheet" href="<?php echo asset('bower_components/jvectormap/jquery-jvectormap.css')?>" type="text/css">
  <link rel="stylesheet" href="<?php echo asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')?>" type="text/css">
  <link rel="stylesheet" href="<?php echo asset('bower_components/bootstrap-daterangepicker/daterangepicker.css')?>" type="text/css">
  <link rel="stylesheet" href="<?php echo asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')?>" type="text/css">
  <link rel="stylesheet" href="<?php echo asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')?>" type="text/css">

    <!-- jQuery 3 -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="bower_components/jquery-ui/jquery-ui.min.js"></script>
  <style>
      .swal2-popup {
        font-size: 1.6rem !important;
        }
  </style>
  @yield('css')

</head>

  <!-- Google Font -->
  <link rel="stylesheet" href="<?php echo asset('css/css-font.css')?>">

  <body class="skin-blue sidebar-mini">

    @include('sweet::alert')
        <div class="wrapper">
            <!-- Main Header -->
            <header class="main-header">

                <!-- Logo -->
                <a href="#" class="logo">
                    <b>iHomp</b>
                </a>

                <!-- Header Navbar -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    <!-- Navbar Right Menu -->
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <!-- User Account Menu -->
                            <li class="dropdown user user-menu">
                                <!-- Menu Toggle Button -->
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <!-- The user image in the navbar-->
                                    <img src="<?php echo asset('images/avatar2.png')?>"
                                         class="user-image" alt="User Image"/>
                                    <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                <span class="hidden-xs">{{ Auth::user()->username }}</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- The user image in the menu -->
                                    <li class="user-header">
                                        <img src="<?php echo asset('images/avatar2.png')?>"
                                             class="img-circle" alt="User Image"/>
                                        <p>
                                            {{ Auth::user()->personnel->p->name_first. ' ' . Auth::user()->personnel->p->name_last }}
                                            {{-- <small>Member since {!! Auth::user()->created_at->format('M. Y') !!}</small> --}}
                                        </p>
                                    </li>
                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-right">
                                            <a href="{!! url('/logout') !!}" class="btn btn-default btn-flat"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                Sign out
                                            </a>
                                            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>

            <!-- Left side column. contains the logo and sidebar -->
            @include('layouts.sidebar')
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                @yield('content')
            </div>

            <!-- Main Footer -->
            <footer class="main-footer" style="max-height: 100px;text-align: center">
                <strong>Copyright © 2016 <a href="#">Segworks</a>.</strong> All rights reserved.
            </footer>

        </div>


<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>


    <!-- Bootstrap 3.3.7 -->
    <script src="<?php echo asset('bower_components/bootstrap/dist/js/bootstrap.min.js')?>"></script>
    <script src="<?php echo asset('bower_components/bootstrap-daterangepicker/daterangepicker.js')?>"></script>
    <!-- datepicker -->
    <script src="<?php echo asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')?>"></script>
    <!-- AdminLTE App -->
    <!-- DataTables -->
    <script src="<?php echo asset('bower_components/datatables.net/js/jquery.dataTables.min.js')?>"></script>
    <script src="<?php echo asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')?>"></script>
    <script src="<?php echo asset('bower_components/jquery-slimscroll/jquery.slimscroll.min.js')?>"></script>
    <script src="<?php echo asset('bower_components/fastclick/lib/fastclick.js')?>"></script>

    <script src="<?php echo asset('dist/js/adminlte.min.js')?>"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="<?php echo asset('dist/js/pages/dashboard.js')?>"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo asset('dist/js/demo.js')?>"></script>
    <script src="<?php echo asset('css/promise-polyfill.js')?>"></script>
    <script src="<?php echo asset('css/sweetalert2@9.js')?>"></script>
    <script>


    function authTable(authCode = null){
        return $('#dataTableCodes').DataTable({
            'paging'      : true,
            'lengthChange': false,
            'searching'   : false,
            'ordering'    : true,
            'info'        : true,
            'processing'  : true,
            'serverSide'  : false,
            'ajax'        : {
                    'url': 'ajaxdata/getdata',
                    'type': 'POST',
                    'data' : {
                        'authCode'    :   authCode,
                        "_token": "{{ csrf_token() }}"
                    },
                },
            'columns'     : [
                {'data'    : 'auth_personnel_name'},
                {'data'    : 'auth_email'},
                {
                    'render'    :   function ( data, type, full, meta ) {
                        var isActive = 'success';
                        var label = 'used';
                        if(full.auth_status == 0){
                            isActive = 'warning';
                            label = 'On Process';
                        }else if(full.auth_status == 2){
                            isActive = 'info';
                            label = 'verifying';
                        }else if(full.auth_status == 3){
                            isActive = 'danger';
                            label = 'rejected';
                        }
                        return "<center><span class='label label-"+isActive+"'>"+ label +"</span></center>"
                    }

                },
                {

                    'data'    : null,
                    "targets": -1,
                    'render'    :   function ( data, type, full, meta ) {
                        var isActive = '';
                        if(full.auth_status == 2){
                            return "<center><button onClick='verifyRequestedEmail("+full.auth_code+")' class='btn btn-info' "+ isActive+"><i class='fa fa-refresh'></i></button> </center>";
                        }
                        if(full.auth_status != 0){
                            isActive = 'disabled';
                        }
                        return "<center><button onClick='verifyRequestedEmail("+'"'+full.auth_code+'"'+")' style='margin-right: 10px' class='btn btn-success' "+ isActive+"><i class='fa fa-check'></i></button>"
                            +"<button onClick='verifyRequestedEmail("+'"'+full.auth_code+'"'+", 1)' class='btn btn-danger' "+ isActive+"><i class='fa fa-times'></i></button> </center>";
                    }

                },
            ]
            });
    }

  $(function () {
    var table = authTable();


    var timer, delay = 500;
    var searchBar = $.trim($("#test").val());
    $('#authcode').bind('keydown blur change', function(e) {
        var _this = $(this);
        clearTimeout(timer);
        timer = setTimeout(function() {
            table.clear().draw();
            if($('#authcode').val() == ''){
                $('#dataTableCodes').DataTable().destroy();
                authTable(null);
                $('#dataTableCodes').DataTable().ajax.reload();
            }else if(isValidEmailAddress($('#authcode').val())){
                $('#dataTableCodes').DataTable().destroy();
                authTable($('#authcode').val());
                $('#dataTableCodes').DataTable().ajax.reload();
            }
        }, delay );
    });
  })

    function isValidEmailAddress(emailAddress) {
        var pattern = new RegExp(/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][\d]\.|1[\d]{2}\.|[\d]{1,2}\.))((25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\.){2}(25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\]?$)/i);
        return pattern.test(emailAddress);
    };

    function verifyRequestedEmail(auth_code, status=0){

        base_url = "{{url('/verifyEmail')}}";
        event.preventDefault();
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        });
        Swal.fire({
            title: status ? 'Delete email request?' : 'Confirm this email?',
            text: status ? 'This transaction cannot be undone.' : 'Authentication code will be send to doctor\'s email',
            icon: 'warning',
            confirmButtonText: status ? 'Yes, delete it!' : 'Yes, send it!',
            cancelButtonText: 'No, cancel!',
            showCancelButton: true,
            reverseButtons: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            }).then((result) => {
            if (result.value) {
                Swal.fire('Please wait ...');
                Swal.showLoading();
                $.post( base_url, {
                _token: "{{ csrf_token() }}",
                auth_code: (auth_code),
                status: status,
                })
                .then( data => {
                    Swal.hideLoading();
                    swalWithBootstrapButtons.fire(
                        status ? 'Email rejected' : 'Sent to Email!',
                        status ? 'Email request has been rejected' : 'Authentication successfully sent',
                        'success'
                    );
                    $('#dataTableCodes').DataTable().ajax.reload();

                })
                .fail(function(e) {
                    Swal.hideLoading();
                    swalWithBootstrapButtons.fire(
                        'Failed to send!!',
                        'Email not sent',
                        'error'
                    )
                });

            }
            })

    }

    </script>

        @yield('scripts')
    </body>

    </html>
