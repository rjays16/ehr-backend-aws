@extends('layouts.app')


@section('content')
    <div class="row">
        <section style="margin-left: 15px" class="content-header">
            <h1>
                Generate Authenticate code
            </h1>
        </section>
        {{-- <div class="col-md-4">
            <section class="content">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <center style="margin-bottom: 30px">
                        <h1 id="authCode"></h1>
                        <small id="authMessage" style="color: gray">Click to generate authentication code</small>
                        </center>
                        <button onclick="generateAuth()" type="button" class="btn btn-block btn-primary btn-lg">Generate</button>
                    </div>
                </div>
            </section>
        </div> --}}
        <div class="col-md-12">
            <section class="content">
                <div class="box box-info">
                    <div class="box-header with-border">
                          <!-- /.box-header -->
                          <div class="box-body">
                              <div class="row">
                                <div class="col-md-7">

                                </div>
                                <div class="col-md-5">
                                    <div class="row">
                                        <div class="col-lg-4" style="text-align: right; font-size: 17px; margin-top: 3px;">
                                            <span> <strong> Search </strong> </span>
                                        </div>
                                            <div class="col-md-8"><input id="authcode" style="border-radius: 20px; height: 38px" class="form-control input-sm" type="search" placeholder="Search authentication code"/>
                                        </div>
                                    </div>
                                </div>
                              </div>
                            <table id="dataTableCodes" class="table table-bordered table-hover display">
                              <thead>
                              <tr>
                                <th>Personnel Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Action</th>
                              </tr>
                              </thead>
                              <tbody>
                              </tbody>
                            </table>
                          </div>
                          <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection


<script>
    // function generateAuth(){
    //     base_url = "{{url('/generateAuth')}}";
    //     event.preventDefault();
    //     $.ajax({
    //         url:base_url,
    //         type: 'GET',
    //         success:function(data) {

    //            $("#authCode").html(data.message);
    //            $("#authMessage").html("New code generated. <br> Please scan/input this on your EHRv2 mobile application");
    //            $('#dataTableCodes').DataTable().ajax.reload();
    //         },
    //         error:function(e){
    //             console.log(e);
    //         }
    //     });
    // }

    function searchAuth(){

    }

</script>
