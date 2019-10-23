@extends('layouts.dashboard')

@section('principal')
  <!--gx-wrapper-->
  <div class="gx-wrapper">

<div class="animated slideInUpTiny animation-duration-3">

    <div class="page-heading">
        <h2 class="title">View Lessonnote Flags Data</h2>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="gx-card">
                <div class="gx-card-header">
                    <h3 class="card-heading">All Lessonnote flags triggered within system</h3>
                </div>
               
                <div class="gx-card-body">
                
                    <div class=""> 

                            <div class="form-group row">
                                <label class="col-md-4 col-sm-3 control-label">Search Week</label>
                                    <div class="col-md-4 col-sm-6">
                                      
                                        <select class="form-control" onchange="getLessonnoteOnChange()" id="lsnweek" > 
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

                      
                    </div>

                    <div class="table-responsive" style=" height: 400px; overflow-y: auto; overflow-x: hidden; ">
                       Your Search Week: <label class="col-md-4 col-sm-3 control-label" > <strong id="mylabel"> Week 1  </strong>  </label>
                        <table id="mydatatable" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                               
                                <th>Flag Type</th>
                                <th>Flag count</th>
                                <th>Head Teacher remarks</th>
                              
                               
                            </tr>
                            </thead>

                            <tbody id="tbody1" style="overflow: scroll; ">
                                <tr> <td colspan="3" style="text-align: center;"> Lessonnotes Flags data will display here...  </td> </tr>
                            </tbody>
                           
                        </table>

                    </div>
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
    let datex = '{{ date("Y-m-d") }}';
    let cycle = 1;

        let xhr = new XMLHttpRequest();
        xhr.open('POST', '/lessonnotes_getFlags/'+teacher);
        xhr.responseType = 'json';
        let formData = new FormData();
        formData.append("api_token", token);
        formData.append("_date", datex);
        formData.append("cycle", cycle);
        xhr.send(formData);

        xhr.onload = function() {
            let responseObj = xhr.response;
            if (responseObj.status === "Failed"){
                alert(responseObj.message);
                return;
            }
            
            let datarow = responseObj.data;
            var i = 0;
            
                var tablex = document.getElementById('tbody1').insertRow(0);
                var cell10 = tablex.insertCell(0);
                var cell11 = tablex.insertCell(1);
                var cell12 = tablex.insertCell(2);
               
              
                cell10.className = "_toolbar widthplus";

                cell10.innerHTML = "<strong>"+ "Late Submission" + "</strong>";
                cell11.innerHTML = "<strong>"+ datarow.LSubmit + "</strong>";
             //   cell12.innerHTML = "<strong>"+ datarow.SAbsent + "</strong>";

                var tablex2 = document.getElementById('tbody1').insertRow(1);
                var cell11 = tablex2.insertCell(0);
                var cell13 = tablex2.insertCell(1);
                var cell14 = tablex2.insertCell(2);
               
              
                cell11.className = "_toolbar";

                cell11.innerHTML = "<strong>"+ "Late Re-Submission" + "</strong>";
                cell13.innerHTML = "<strong>"+ datarow.LRSubmit +  "</strong>";

                var tablex3 = document.getElementById('tbody1').insertRow(1);
                var cell15 = tablex3.insertCell(0);
                var cell16 = tablex3.insertCell(1);
                var cell17 = tablex3.insertCell(2);
               
              
                cell15.className = "_toolbar";

                cell15.innerHTML = "<strong>"+ "Poor Quality" + "</strong>";
                cell16.innerHTML = "<strong>"+ datarow.Quality + "</strong>";

                var tablex4 = document.getElementById('tbody1').insertRow(1);
                var cell18 = tablex4.insertCell(0);
                var cell19 = tablex4.insertCell(1);
                var cell20 = tablex4.insertCell(2);
               
              
                cell18.className = "_toolbar";

                cell18.innerHTML = "<strong>"+ "Poor Performance" + "</strong>";
                cell19.innerHTML = "<strong>"+ datarow.Perf + "</strong>";

                
                var tablex5 = document.getElementById('tbody1').insertRow(1);
                var cell21 = tablex5.insertCell(0);
                var cell22 = tablex5.insertCell(1);
                var cell23 = tablex5.insertCell(2);
               
              
                cell21.className = "_toolbar";

                cell21.innerHTML = "<strong>"+ "Delayed Response" + "</strong>";
                cell22.innerHTML = "<strong>"+ datarow.Delay + "</strong>";
                
                let mylabel = document.querySelector('#mylabel');
                mylabel.innerHTML = "Week "+datarow.Week;
               //
        } 
</script>
@endsection



@section('myscript')
<script>
    $(document).ready(function(){
   
    });

    function getLessonnoteOnChange(){
        let cycle = $('#lsnweek').val();
        //let subclassbox = $('#subclass').val();
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '/lessonnotes_getFlags/'+teacher);
        xhr.responseType = 'json';
        let formData = new FormData();
        formData.append("api_token", token);
        formData.append("cycle", cycle);

        document.getElementById('loading').style.display = 'block';
        xhr.send(formData);

        xhr.onload = function() {
            let responseObj = xhr.response;
            if (responseObj.status === "Failed"){
                document.getElementById('loading').style.display = 'none';
                alert(responseObj.message);
                return;
            }
            $('#tbody1').empty();
            let datarow = responseObj.data;
            var i = 0;
            
            var tablex = document.getElementById('tbody1').insertRow(0);
                var cell10 = tablex.insertCell(0);
                var cell11 = tablex.insertCell(1);
                var cell12 = tablex.insertCell(2);
               
              
                cell10.className = "_toolbar widthplus";

                cell10.innerHTML = "<strong>"+ "Late Submission" + "</strong>";
                cell11.innerHTML = "<strong>"+ datarow.LSubmit + "</strong>";
             //   cell12.innerHTML = "<strong>"+ datarow.SAbsent + "</strong>";

                var tablex2 = document.getElementById('tbody1').insertRow(1);
                var cell11 = tablex2.insertCell(0);
                var cell13 = tablex2.insertCell(1);
                var cell14 = tablex2.insertCell(2);
               
              
                cell11.className = "_toolbar";

                cell11.innerHTML = "<strong>"+ "Late Re-Submission" + "</strong>";
                cell13.innerHTML = "<strong>"+ datarow.LRSubmit +  "</strong>";

                var tablex3 = document.getElementById('tbody1').insertRow(1);
                var cell15 = tablex3.insertCell(0);
                var cell16 = tablex3.insertCell(1);
                var cell17 = tablex3.insertCell(2);
               
              
                cell15.className = "_toolbar";

                cell15.innerHTML = "<strong>"+ "Poor Quality" + "</strong>";
                cell16.innerHTML = "<strong>"+ datarow.Quality + "</strong>";

                var tablex4 = document.getElementById('tbody1').insertRow(1);
                var cell18 = tablex4.insertCell(0);
                var cell19 = tablex4.insertCell(1);
                var cell20 = tablex4.insertCell(2);
               
              
                cell18.className = "_toolbar";

                cell18.innerHTML = "<strong>"+ "Poor Performance" + "</strong>";
                cell19.innerHTML = "<strong>"+ datarow.Perf + "</strong>";

                
                var tablex5 = document.getElementById('tbody1').insertRow(1);
                var cell21 = tablex5.insertCell(0);
                var cell22 = tablex5.insertCell(1);
                var cell23 = tablex5.insertCell(2);
               
              
                cell21.className = "_toolbar";

                cell21.innerHTML = "<strong>"+ "Delayed Response" + "</strong>";
                cell22.innerHTML = "<strong>"+ datarow.Delay + "</strong>";
                
                let mylabel = document.querySelector('#mylabel');
                mylabel.innerHTML = "Week "+datarow.Week;
               
                document.getElementById('loading').style.display = 'none';
        } 
    }
</script>
@endsection

@include('teacher.modals.modal')