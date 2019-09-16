@extends('layouts.dashboard')

@section('content')
  <!--gx-wrapper-->
  <div class="gx-wrapper">

<div class="animated slideInUpTiny animation-duration-3">

    <div class="page-heading">
        <h2 class="title">Submit Lessonnote</h2>
    </div>

    <div class="row">
                    <div class="col-xl-8 col-sm-6 col-12 order-xl-3">
                        <form class="form-horizontal" action="" method="POST" enctype='multipart/form-data' >
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-lg-3 control-label">Select Subject & Class</label>
                                            <div class="col-sm-6 col-lg-5">
                                                <select class="select2 form-control form-control-lg select2-hidden-accessible" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                                    <option value="1" data-select2-id="3">Option 1</option>
                                                    <option value="2">Option 2</option>
                                                    <option value="3">Option 3</option>
                                                    <option value="4">Option 4</option>
                                                    <option value="5">Option 5</option>
                                                </select>
                                            </div>                                            
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-lg-3 control-label">Select Week</label>
                                            <div class="col-sm-6 col-lg-5">
                                                <select class="select2 form-control form-control-lg select2-hidden-accessible" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                                    <option value="1" data-select2-id="3">Option 1</option>
                                                    <option value="2">Option 2</option>
                                                    <option value="3">Option 3</option>
                                                    <option value="4">Option 4</option>
                                                    <option value="5">Option 5</option>
                                                </select>
                                            </div>                                            
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-lg-3 control-label">Select Lessnnote File</label>
                                            <div class="col-sm-6 col-lg-5">
                                               <input type="file" class="form-control" accept="application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document"  />
                                            </div>                                            
                                        </div>

                                        <div class="line-dashed"></div>

                                        <div class="form-group row">
                                           
                                            <div class="col-sm-6 col-lg-5" style="padding-left: 30%;">
                                               <input type="submit" class="btn btn-default btn-dark" />
                                            </div>                                            
                                        </div>
                                    
                                    </form>
                            </div>
                        <div class="col-xl-4 col-sm-6 col-12 order-xl-3">
                                    <div class="card text-right">
                                        <div class="card-header bg-primary text-white">Don't have a Template Yet?</div>
                                        <div class="card-body">
                                            <h3 class="card-title">Download It  <a href="javascript:void(0)" class="btn btn-light">Here</a></h3>
                                            <h1 class="card-subtitle"> <b>  OR  </b> </h1>
                                            <p class="card-text">Complete the form on your Left Hand side</p>
                                           
                                        </div>
                                      
                        </div>
                    </div>
              
    </div>
</div>

</div>
<!--/gx-wrapper-->
@endsection