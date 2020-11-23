@extends('layouts.dashboard')

@section('teacher')
  <!--gx-wrapper-->
  <div class="gx-wrapper">

<div class="animated slideInUpTiny animation-duration-3">

    <div class="page-heading">
        <h2 class="title">Submit Lessonnote</h2>
    </div>

    <div class="row">
                    <div class="col-xl-8 col-sm-6 col-12 order-xl-3">
                      
                    <form id="formsubmit" action="javascript:void(0)" enctype="multipart/form-data">
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-lg-3 control-label">Select Subject & Class</label>
                                            <div class="col-sm-6 col-lg-5">
                                                <select class="select2 form-control form-control-lg select2-hidden-accessible" data-select2-id="1" tabindex="-1" aria-hidden="true" id ="input1">
                                                  
                                                </select>
                                            </div>                                            
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-lg-3 control-label">Select Week</label>
                                            <div class="col-sm-6 col-lg-5">
                                                <select class="select2 form-control form-control-lg select2-hidden-accessible" data-select2-id="1" tabindex="-1" aria-hidden="true" id ="input2" name="week" required>
                                                    <option value="">Select...</option>
                                                    <option value="1" >Week 1</option>
                                                    <option value="2">Week 2</option>
                                                    <option value="3">Week 3</option>
                                                    <option value="4">Week 4</option>
                                                    <option value="5">Week 5</option>
                                                    <option value="6">Week 6</option>
                                                    <option value="7">Week 7</option>
                                                    <option value="8">Week 8</option>
                                                    <option value="9">Week 9</option>
                                                    <option value="10">Week 10</option>
                                                    <option value="11">Week 11</option>
                                                    <option value="12">Week 12</option>
                                                </select>
                                            </div>                                            
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-lg-3 control-label">Select Lessonnote File</label>
                                            <div class="col-sm-6 col-lg-5">
                                               <input type="file" id="input3" name="lsn" class="form-control" accept="application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document" required />
                                            </div>                                            
                                        </div>

                                        <div class="line-dashed"></div>

                                        <div class="form-group row">                                           
                                            <div class="col-sm-6 col-lg-5" style="padding-left: 30%;">
                                               <input type="submit" class="btn btn-default btn-dark" value="Submit Lessonnote"  />
                                            </div>                                            
                                        </div>                                   
                                     
                                </div>
                    </form> 
                        <div class="col-xl-4 col-sm-6 col-12 order-xl-3">
                                    <div class="card text-right">
                                        <div class="card-header bg-primary text-white">Don't have a Template Yet?</div>
                                        <div class="card-body">
                                            <h3 class="card-title">Download It  <a href="{{ asset('storage/lsntemplate.docx') }}" class="btn btn-light">Here</a></h3>
                                            <h1 class="card-subtitle"> <b>  OR  </b> </h1>
                                            <p class="card-text">Complete the form on your Left Hand side</p>
                                           
                                        </div>
                                      
                        </div>
                    </div>
              
    </div>
</div>

</div>
<!--/gx-wrapper-->
<script> 
    let teacher = {{ Auth::user()->teacher_id }};
    let token = '{{ Auth::user()->api_token }}';
    let xhr = new XMLHttpRequest();
    xhr.open('POST', '/lessonnotes_getSubClass/'+teacher);
    xhr.responseType = 'json';
    let formData = new FormData();
    formData.append("api_token", token);
    xhr.send(formData);
        // var file = $('#extra-file');
        //console.log(file[0].files);
    xhr.onload = function() {
        let responseObj = xhr.response;
        var selectinput = document.querySelector('#input1');
      
        if (responseObj.message){
            alert(responseObj.message);
            return;
        }
       
        for ( let datarow of responseObj.data ){ 
           selectinput.options[selectinput.options.length] = new Option( datarow.ClassName + " " + datarow.Subject, datarow.SubjectId + ";" + datarow.ClassCat );         
        }
      
    };

</script>
@endsection

@section('myscript')
    <script>
     $(document).ready(function (e) {
            
            $('#formsubmit').on('submit',(function(e) {  
                 e.preventDefault();    
                let formData = new FormData(this);
                let subclass = $("#input1").val(); 
                let mysub = subclass.split(";");
                let week = $("#input2").val();
                //let lsn = $("#input3")[0].files;
                //console.log("Files: "+ lsn);
            
                formData.append("subject", mysub[0]);
                formData.append("classcat", mysub[1]);
                formData.append("teacher", teacher);
                
                $.ajax({
                    url: '/lessonnotes_submitLsn', 
                    type: "POST", 
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData 
                    }).done(function(e){
                        alert(e.message);
                }).fail(function(e){
                    // Report that there is a problem!
                        alert(e.responseText);
                })
             })); });
               
         
       
    
    </script>
@endsection