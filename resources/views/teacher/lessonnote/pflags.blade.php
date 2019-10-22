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
                                <label class="col-md-4 col-sm-3 control-label">Search Date</label>
                                    <div class="col-md-4 col-sm-6">
                                        <input type="date" value="" class="form-control" onchange="getLessonnoteOnChange()" id="attdate"/>
                                    </div>
                            </div>

                      
                    </div>

                    <div class="table-responsive" style=" height: 400px; overflow-y: auto; overflow-x: hidden; ">
                       Your Search Date: <label class="col-md-4 col-sm-3 control-label" > <strong id="mylabel"> 2019-10-10 </strong>  </label>
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

        let xhr = new XMLHttpRequest();
        xhr.open('POST', '/lessonnotes_getFlags/'+teacher);
        xhr.responseType = 'json';
        let formData = new FormData();
        formData.append("api_token", token);
        formData.append("_date", datex);
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
                mylabel.innerHTML = datarow._date;
               //
        } 
</script>
@endsection



@section('myscript')
<script>
    $(document).ready(function(){
   
    });

    function getLessonnoteOnChange(){
        let datebox = $('#attdate').val();
        //let subclassbox = $('#subclass').val();
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '/lessonnotes_getFlags/'+teacher);
        xhr.responseType = 'json';
        let formData = new FormData();
        formData.append("api_token", token);
        formData.append("_date", datebox);
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
                mylabel.innerHTML = datarow._date;
               
                document.getElementById('loading').style.display = 'none';
        } 
    }
</script>
@endsection

@include('teacher.modals.modal')