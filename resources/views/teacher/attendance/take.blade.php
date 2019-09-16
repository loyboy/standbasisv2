@extends('layouts.dashboard')

@section('content')
<div class="gx-wrapper">

    <div class="animated slideInUpTiny animation-duration-3">

        <div class="page-heading">
            <h2 class="title">Attendance</h2>
        </div> 

        <div class="gx-entry-header"><h3 class="entry-heading"> Take An Attendance </h3></div>

        <div class="row">
                    <div class="col-lg-12">
                        <div class="gx-card ">
                            <div class="gx-card-header">
                                <h3 class="card-title">Select Your Class based on Time</h3>
                            </div>
                            <div class="gx-card-body">
                                <form class="form-horizontal">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-lg-3 control-label">Pick a Class Subject Time Combination</label>
                                        <div class="col-sm-6 col-lg-5">
                                            <select class="select2 form-control form-control-lg select2-hidden-accessible" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                                <option value="1" data-select2-id="3">Option 1</option>
                                                <option value="2">Option 2</option>
                                                <option value="3">Option 3</option>
                                                <option value="4">Option 4</option>
                                                <option value="5">Option 5</option>
                                            </select>

                                           <!-- <span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="2" style="width: 436.753px;">
                                                <span class="selection">
                                                    <span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-xmsl-container">
                                                        <span class="select2-selection__rendered" id="select2-xmsl-container" role="textbox" aria-readonly="true" title="Option 1">Option 1</span>
                                                        <span class="select2-selection__arrow" role="presentation"><b role="presentation"></b> </span> 
                                                    </span> 
                                                </span> 
                                                <span class="dropdown-wrapper" aria-hidden="true"> </span>
                                            </span> -->
                                        </div>
                                    </div>
                                    <div class="line-dashed"></div>

                                    <a href="javascript:void(0)" class="gx-btn gx-btn-primary text-uppercase btn-block"> Proceed </a>
                                   
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

    </div>

</div>
@endsection