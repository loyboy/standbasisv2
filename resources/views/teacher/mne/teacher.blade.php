@extends('layouts.dashboard')

@section('teacher')
  <!--gx-wrapper-->
  <div class="gx-wrapper">

<div class="animated slideInUpTiny animation-duration-3">

    <div class="page-heading">
        <h2 class="title">View MNE data for Teacher</h2>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="gx-card">
                <div class="gx-card-header">
                    <h3 class="card-heading">MNE data For Term</h3>
                </div>
                <div class="gx-card-body">
                   <!-- content -->
                    <div class="row"> 
                        <div class="container"> 
                        <div class="row col-12" style="margin-left: 4px; ">
                                <div class="form-group col-4">
                                    <label> Start Date: </label>
                                    <input type="date" name="mydate" class="form-control" id="thedate" value="">
                                </div>
                                
                                
                                <div class="form-group col-4">
                                    <label> End Date: </label>
                                    <input type="date" name="mydate2" class="form-control" id="thedate2" value="<?php echo date('Y-m-d'); ?>">
                                </div>  
                                
                            
                                <div class="form-group col-4">
                                    <label> Term: </label>
                                    <select class="form-control"  name="head_year" id="theterm">
                                    <!--    <option value="">Select.. </option>
                                        <option value="1"> 1st Term </option>
                                        <option value="2"> 2nd Term </option>
                                        <option value="3"> 3rd Term </option>-->
                                    </select>
                                </div>  
                        </div>
                    </div>
                </div>
                
                <div class="row">
            
                    <div class="container"> 
                      <table class="table table-bordered table-hover st_table">                         
                       <thead> 
                                <tr>                                   
                                    <td class='_toolbar'><a>Student</a><br/>
                                        <select name="state" class="mymneselectstudent" style="width: 200px;">
                                    
                                        </select>
                                    <br/>
                                    <button class="btn btn-info" id="vtstudent_but" onClick="myFunctionStu()"> View Report </button>
                                    </td>

                                    <td class='_toolbar'><a>Teacher</a> <br/> <button class="btn btn-info" id="vtteacher_but" onClick="myFunctionTea()"> View Report </button> </td>
                                   
                                    <td class='_toolbar'><a>Class</a> 
                                     <br/>
                                     <select class="mymneselectclass" name="state" style="padding: 15px;">
                                    
                                     </select>
                                     <br/> <button class="btn btn-info" id="vtclass_but" onClick="myFunctionClass()"> View Report </button>
                                    </td>
                                    
                                  <!--  <td class='_toolbar'><a>Flags</a></td> -->
                                </tr>
                       </thead>
                       
                       <tbody> 
                        <tr> <td class='textc'> <a href="#" id='mnest1' > Attendance </a> </td> <td class='textc'> <a href="#" > Attendance </a> </td>  <td class='textc myhide'> <a>  Attendance </a> </td> <td class='textc'> <a> Attendance </a> </td> </tr>
                           
                        <tr> <td class='textc'> <a href="#" id='mnest2' > Class work Average </a>  </td> <td class='textc'> <a href="#"> Class work Performance </a> </td> <td class='textc myhide'> <a> Class work </a> </td> <td class='textc'> <a> Class work </a> </td> </tr>
                        <tr> <td class='textc'> <a href="#" id='mnest3' > Class work by subject </a>  </td> <td class='textc'> <a href="#"> Assignment Performance </a></td>  <td class='textc myhide'> <a> Homework </a> </td> <td class='textc'> <a> Homework </a> </td> </tr>
                        <tr> <td class='textc'> <a href="#" id='mnest4' > Homework average </a>  </td> <td class='textc'>  <a href="#"> Test Performance </a> </td> <td class='textc myhide'> <a> Test </a> </td> <td class='textc'>  <a> Test </a> </td> </tr>
                        <tr> <td class='textc'> <a href="#" id='mnest5' > Homework by subject </a>  </td> <td class='textc'>  <a href="#"> MidTerm Performance </a> </td>  <td class='textc myhide'> <a> Mid-tem </a> </td> <td class='textc'>  <a> Mid-tem </a> </td>  </tr>
                        <tr> <td class='textc'> <a href="#" id='mnest6' > Test average </a>  </td> <td class='textc'>  <a href="#"> Terminal examination Performance </a> </td> <td class='textc myhide'> <a> Terminal Exams </a> </td> <td class='textc'> <a> Terminal Exams </a> </td> </tr>
                        <tr> <td class='textc'> <a href="#" id='mnest7' > Test by Subject </a>  </td> <td class='textc'> <a href="#"> Attendance management</a> </td> <td class='textc myhide'> <a> Class work by subject </a> </td> <td class='textc'>  <a> Class work by subject </a> </td>  </tr>
                        <tr> <td class='textc'> <a href="#" id='mnest8' > Midterm </a>  </td> <td class='textc'> <a href="#"> Lessonnote management</a> </td>  <td class='textc myhide'> <a> Homework by subject </a> </td> <td class='textc'>  <a> Homework by subject </a> </td>  </tr>
                        <tr> <td class='textc'> <a href="#" id='mnest9' > Midterm by Subject </a>  </td> <td class='textc'>  <a href="#"> Lessonnote performance </a> </td> <td class='textc myhide'> <a> Test by subject </a> </td> <td class='textc'> <a> Test by subject </a>  </td>  </tr>
                        <tr> <td class='textc'> <a href="#" id='mnest10' > Terminal examination </a>  </td> <td class='textc'>  </td> <td class='textc'> Mid-tem by subject  </td> <td class='textc myhide'>  <a> Terminal exams by subject </a> </td>  </tr>
                    
                     <!--    <tr> <td class='textc'> <a href="#"> </a>  </td> <td class='textc'>  </td> <td class='textc myhide'> <a> Terminal exams by subject </a> </td> <td class='textc'>   </td> </tr>
                             <tr> <td class='textc'> <a href="#"> </a>  </td> <td class='textc'> <a> </a> </td> <td class='textc'> </td> <td class='textc'> </td> <td class='textc'> </td> </tr>
                    -->
                       </tbody>
                      </table>
                        
                    </div>
                 
                </div>
                   <!-- end content -->
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
        xhr.open('POST', '/terms_getDate/'+teacher);
        xhr.responseType = 'json';
        let formData = new FormData();
        formData.append("api_token", token);
        xhr.send(formData);

        let xhr2 = new XMLHttpRequest();
        xhr2.open('POST', '/pupils_getPupilForTeacher/'+teacher);
        xhr2.responseType = 'json';
        let formData2 = new FormData();
        formData2.append("api_token", token);
        xhr2.send(formData2);

       let xhr3 = new XMLHttpRequest();
        xhr3.open('POST', '/pupils_getClassForTeacher/'+teacher);
        xhr3.responseType = 'json';
        let formData3 = new FormData();
        formData3.append("api_token", token);
        xhr3.send(formData3);

        document.getElementById('loading').style.display = 'block';

        xhr.onload = function() {
                let responseObj = xhr.response;
                if (responseObj.status === "Failed"){
                    alert(responseObj.message);
                    document.getElementById('loading').style.display = 'none';
                    return;
                }
                console.log("First comment Data: " + responseObj.data);
                document.getElementById('loading').style.display = 'none';
                
               /* document.getElementById('thedate').value = responseObj.data.date;
                var headyear = document.querySelector('#theterm');
                console.log("The head year: " + headyear);
                headyear.value = responseObj.data.term;*/
                var selectinput3 = document.querySelector('#theterm');
                var termval =  { '1' : "1ST TERM" , '2' : "2ND TERM", '3' : "3RD TERM" };
               for ( let datarow of responseObj.data ){ 
                    selectinput3.options[selectinput3.options.length] = new Option (  termval[ datarow.Term ] , datarow.Termid + ";" + Number(datarow.Term) );         
               }
        }

        xhr2.onload = function() {
                let responseObj = xhr2.response;
                if (responseObj.status === "Failed"){
                    alert(responseObj.message);
                    document.getElementById('loading').style.display = 'none';
                    return;
                }
                var selectinput = document.querySelector('.mymneselectstudent');
               
                for ( let datarow of responseObj.data ){ 
                    selectinput.options[selectinput.options.length] = new Option( datarow.PupilName+ " >> " + datarow.ClassName , datarow.Enrolid + ';' + datarow.PupilId );         
                }

        }

        xhr3.onload = function() {
                let responseObj = xhr3.response;
                if (responseObj.status === "Failed"){
                    alert(responseObj.message);
                    document.getElementById('loading').style.display = 'none';
                    return;
                }
                var selectinput2 = document.querySelector('.mymneselectclass');
               
                for ( let datarow of responseObj.data ){ 
                    selectinput2.options[selectinput2.options.length] = new Option( datarow.ClassName, datarow.ClassId );         
                }

        } 

        
    
</script>
@endsection

@include  ('modals/student')
@include  ('modals/teacher')


@section('myscript')
    <script>
        ////////////STUDENT
            //general view
            function myFunctionStu() {
               
               var idofevent = event.target.id;
               var thedate = document.getElementById("thedate").value;
               var enddate = document.getElementById("thedate2").value;
               var theterm = document.getElementById("theterm").value;
               var student =  $('.mymneselectstudent').val();
               let tea = {{ Auth::user()->teacher_id }};
               
               console.log("Console ID"+ idofevent);
               
               if (thedate == "" || enddate == "" || theterm == ""){
                   alert("Please select all the parameters to complete your query...");
                   return;
               }
               
               if ( idofevent == "vtstudent_but" && ( student == "" ) ){
                   alert("Please select a Student ID from the Drop Box");
                   return;
               }
               
                $.ajax({
                                  type: "POST",
                                  beforeSend: function (xhr) {
                                              var token = $('meta[name="_token"]').attr('content');
                                              if (token) {
                                                  return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                                              }
                                              return xhr;
                                          },
                                  url: "mneapistudentgen",//laravel controller method inside web.php
                                  dataType: "json", //expect html to be returned  
                                  data: {
                                      _type:'teacher' , sdate: thedate, edate: enddate, term: theterm, stu: student, tea: Number(tea)
                                  },
                                  success: function(data) { 
                                  //console.log(data);
                                   var clsname = JSON.parse(JSON.stringify(data));
                                  $('#tab1mne1').html(""); $('#tab1mne2').html(""); $('#tab1mne3').html("");$('#tab1mne4').html("");$('#tab1mne5').html("");$('#tab1mne6').html(""); 
                                   
                                   var tbody = $('#tabbody');
                                   if (tbody.children().length > 0) {
                                        tbody.empty();
                                   }
                                   
                                   console.log("My big data "+ clsname);
                                   
                                   $('#tab1mne1').html(round(clsname._att1._perf,0) + ' %');
                                   $('#tab1mne2').html(round(clsname._att2._perf,0) + ' %');
                                   $('#tab1mne3').html(round(clsname._att4._perf,0) + ' %');
                                   $('#tab1mne4').html(round(clsname._att6._perf,0) + ' %');
                                   $('#tab1mne5').html(round(clsname._att8._perf,0) + ' %');
                                   $('#tab1mne6').html(round(clsname._att10._perf,0) + ' %');
                                   
                                   $('#tab2mne1').html(clsname._att1._name);
                                   $('#tab2mne2').html(clsname._att1._class);
                                   $('#tab2mne3').html(clsname._att1._datenow);
                                   
                                   
                                  var tr2 = document.createElement('tr');
                                  var td2 = document.createElement('td'); 
                                  var td3 = document.createElement('td'); var td4 = document.createElement('td'); var td5 = document.createElement('td'); var td6 = document.createElement('td'); var td7 = document.createElement('td'); var td8 = document.createElement('td');
                                        
                                  td2.append("Subject(s)"); 
                                  td8.append("Attendance (%)"); td3.append("Classwork (%)"); td4.append("Homework (%)"); td5.append("Test (%)"); td6.append("Mid-Term (%)"); td7.append("Terminal (%)");
                                        
                                  tr2.append(td2); 
                                  tr2.append(td8);
                                  tr2.append(td3);  tr2.append(td4);  tr2.append(td5);  tr2.append(td6);   tr2.append(td7); 
                                      
                                  tbody.append(tr2);
                                 
                                   for ( var i = 0; i < clsname._att3._subid.length; i++ ) {
                                       
                                        var tr1 = document.createElement('tr');
                                        var td11 = document.createElement('td');  var td12 = document.createElement('td'); 
                                        var td21 = document.createElement('td'); var td31 = document.createElement('td'); var td41 = document.createElement('td'); var td51 = document.createElement('td'); var td61 = document.createElement('td'); var td71 = document.createElement('td');
                                        
                                        var id = clsname._att3._subid[i];
                                        
                                        td11.append(clsname._att3._subjects[id] ); 
                                        td12.append(round(clsname._att11._arrayperf[id],0) + ' %');
                                        td21.append(round(clsname._att3._arrayperf[id][0].perf,0) + ' %');//Classwork
                                        td31.append(round(clsname._att5._arrayperf[id][0].perf,0) + ' %');//Homework
                                        td41.append(round(clsname._att7._arrayperf[id][0].perf,0) + ' %');//Test
                                        td51.append(round(clsname._att9._arrayperf[id][0].perf,0) + ' %');//MidTerm Exam
                                        //td61.append(clsname._att11._arrayperf[id][i].perf);//Terminal Exam
                                        
                                        tr1.append(td11); 
                                        tr1.append(td12); 
                                        tr1.append(td21);
                                        tr1.append(td31); 
                                        tr1.append(td41);
                                        tr1.append(td51);
                                       // tr1.append(td61);
                                     
                                        tbody.append(tr1);
                                   }
                                   
                                 
                                
                                   $('#getmnestudent').modal('show');
                                   
                                  
                                   
                                  },
                                   error: function(xmlHttpRequest, textStatus, errorThrown)
                                  { 
                                  
                                        alert( textStatus + " " + errorThrown );
                                  
                                  }
                          });  
         }
          
        
          
  ////////////TEACHER 
           //general view
          function myFunctionTea() {
             
               var idofevent = event.target.id;
               var thedate = document.getElementById("thedate").value;
               var enddate = document.getElementById("thedate2").value;
               var theterm = document.getElementById("theterm").value;
               var student = document.getElementById("idtea").value;
              
               
               console.log("Console ID"+ idofevent);
               
               if (thedate == "" || enddate == "" || theterm == ""){
                   alert("Please select all the parameters to complete your query...");
                   return;
               }
               
             
               
                $.ajax({
                                  type: "POST",
                                  beforeSend: function (xhr) {
                                              var token = $('meta[name="_token"]').attr('content');
                                              if (token) {
                                                  return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                                              }
                                              return xhr;
                                          },
                                  url: "mneapiteachergen",//laravel controller method inside web.php
                                  dataType: "json", //expect html to be returned  
                                  data: {
                                      sdate: thedate, edate: enddate, term: theterm
                                  },
                                  success: function(data) { 
                                   var clsname = JSON.parse(JSON.stringify(data));
                                    console.log(clsname._att1b);
                                   $('#Ttab1mne1').html(""); $('#Ttab1mne2').html(""); $('#Ttab1mne3').html("");$('#Ttab1mne4').html("");$('#Ttab1mne5').html("");$('#Ttab1mne6').html(""); 
                                   $('#Ttab1mne7').html("");  $('#Ttab1mne8').html("");
                                   
                                   var tbody = $('#tabbody2');
                                   if (tbody.children().length > 0) {
                                        tbody.empty();
                                   }
                                   
                                  // console.log("My big data "+ clsname);
                                   
                                   $('#Ttab1mne1').html(clsname._att1._perf + ' %');
                                   $('#Ttab1mne2').html(clsname._att2._perf + ' %');
                                   $('#Ttab1mne3').html(clsname._att3._perf + ' %');
                                   $('#Ttab1mne4').html(clsname._att4._perf + ' %');
                                   $('#Ttab1mne5').html(clsname._att5._perf + ' %');
                                   $('#Ttab1mne6').html(clsname._att6._perf + ' %');
                                   $('#Ttab1mne7').html(clsname._att8 + ' %');
                                   $('#Ttab1mne8').html(clsname._att9 + ' %');
                                   
                                   $('#Ttab2mne1').html(clsname._att1._name);
                                   $('#Ttab2mne2').html(clsname._att1._datenow);
                                   
                                   
                                  var tr2 = document.createElement('tr');
                                  var td2 = document.createElement('td');  var td2d = document.createElement('td'); var td2x = document.createElement('td'); 
                                  var td3 = document.createElement('td'); 
                                  var td4 = document.createElement('td'); 
                                  var td5 = document.createElement('td'); 
                                  var td6 = document.createElement('td'); 
                                  var td7 = document.createElement('td'); 
                                  var td8 = document.createElement('td');  var td9 = document.createElement('td');  var td10 = document.createElement('td'); var td11 = document.createElement('td');
                                        
                                  td2.append("Subject(s)");  td2d.append("Class(s)"); 
                                  td2x.append("Attendance (%)"); td3.append("Classwork (%)"); td4.append("Homework (%)"); td5.append("Test (%)"); td6.append("Mid-Term (%)"); td7.append("Terminal (%)"); td8.append("Lesson Note P (%)");  td9.append("Lesson Note M (%)"); td10.append("Attendance M (%)");
                                        
                                  tr2.append(td2);  tr2.append(td2d);  tr2.append(td2x); 
                                  tr2.append(td3);  tr2.append(td4);  
                                  tr2.append(td5);  tr2.append(td6);   
                                  tr2.append(td7);  tr2.append(td8);  
                                  tr2.append(td9);  tr2.append(td10);  
                                      
                                  tbody.append(tr2);
                                  
                                  var i = 0;
                                 
                                   for ( var j = 0; j < clsname._att10._subid.length ; j++ ) {
                                       for ( var sub in clsname._att10._subid[j] ) {
                                        
                                        console.log ( "Digi:" + JSON.stringify(sub) );
                                        
                                        var tr1 = document.createElement('tr');
                                        var td11 = document.createElement('td'); var td11x = document.createElement('td');
                                        var td12 = document.createElement('td'); 
                                        var td21 = document.createElement('td'); var td31 = document.createElement('td'); 
                                        var td41 = document.createElement('td'); var td51 = document.createElement('td'); 
                                        var td61 = document.createElement('td'); var td71 = document.createElement('td');
                                        var td81 = document.createElement('td'); var td91 = document.createElement('td');
                                        var td101 = document.createElement('td');
                                        
                                        var cls = clsname._att10._subid[j][sub];  var count = clsname._att10._count;
                                        
                                        td11.append(clsname._att10._subjects[j][sub] ); 
                                        td11x.append(cls);
                                      
                                        td12.append(round(clsname._att1b._arrayperf[j][sub],0) + ' %');//Attendance
                                        td21.append(round(clsname._att10._arrayperf[j][sub][0].perf,0) + ' %');//Classwork
                                        td31.append(round(clsname._att11._arrayperf[j][sub][0].perf,0) + ' %');//Homework
                                        td41.append(round(clsname._att12._arrayperf[j][sub][0].perf,0) + ' %');//Test
                                        td51.append(round(clsname._att13._arrayperf[j][sub][0].perf,0) + ' %');//MidTerm Exam
                                        td61.append(round(clsname._att14._arrayperf[j][sub][0].perf,0) + ' %');//Terminal Exam
                                        
                                        var lsnavg = ( Number(clsname._att10._arrayperf[j][sub][0].perf) + Number(clsname._att11._arrayperf[j][sub][0].perf) + Number(clsname._att12._arrayperf[j][sub][0].perf) ) / 3;
                                        
                                        td71.append(round(lsnavg,0) + ' %');//LSN perf
                                        td81.append(round(clsname._att15._arrayperf[j][sub][0].myperf,0) + ' %');//LSN management
                                        td91.append(round(clsname._att16._arrayperf[j][sub][0].myperf,0) + ' %');//ATT management
                                       
                                        tr1.append(td11);  
                                        tr1.append(td11x); 
                                        tr1.append(td12); 
                                        tr1.append(td21);
                                        tr1.append(td31); 
                                        tr1.append(td41);
                                        tr1.append(td51);
                                        tr1.append(td61);
                                        
                                        tr1.append(td71); tr1.append(td81); tr1.append(td91);
                                     
                                        tbody.append(tr1);
                                        
                                     
                                   } }
                                
                                   $('#getmneteacher').modal('show');
                                   
                                  
                                   
                                  },
                                   error: function(xmlHttpRequest, textStatus, errorThrown)
                                  { 
                                  
                                  alert("Sorry, but check your network connection....");
                                  
                                  }
                          });  
         }
         
  ///////////CLASS
          //general view
          function myFunctionClass() {
                     
                       var idofevent = event.target.id;
                       var thedate = document.getElementById("thedate").value;
                       var enddate = document.getElementById("thedate2").value;
                       var theterm = document.getElementById("theterm").value;
                       var classx  =  $('.mymneselectclass').val();
                      
                       
                       console.log("Console ID"+ idofevent);
                       
                       if (thedate == "" || enddate == "" || theterm == ""){
                           alert("Please select all the parameters to complete your query...");
                           return;
                       }
                       
                        if ( idofevent == "vtclass_but" && ( classx == "" ) ){
                           alert("Please select a Class ID from the Drop Box");
                           return;
                        }
                     
                       
                        $.ajax({
                                          type: "POST",
                                          beforeSend: function (xhr) {
                                                      var token = $('meta[name="_token"]').attr('content');
                                                      if (token) {
                                                          return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                                                      }
                                                      return xhr;
                                                  },
                                          url: "mneapiclassgen",//laravel controller method inside web.php
                                          dataType: "json", //expect html to be returned  
                                          data: {
                                              sdate: thedate, edate: enddate, term: theterm , cla: classx
                                          },
                                          success: function(data) { 
                                           
                                          
                                           
                                           var clsname = JSON.parse(JSON.stringify(data));
                                            console.log(clsname._att1b);
                                           
                                           $('#Ctab1mne1').html(""); $('#Ctab1mne2').html(""); $('#Ctab1mne3').html("");$('#Ctab1mne4').html("");$('#Ctab1mne5').html("");$('#Ctab1mne6').html(""); 
                                           $('#Ctab1mne7').html(""); $('#Ctab1mne8').html("");
                                           
                                           var tbody = $('#tabbody3');
                                           if (tbody.children().length > 0) {
                                                tbody.empty();
                                           }
                                           
                                           console.log("My big data "+ clsname);
                                           
                                           $('#Ctab1mne1').html(clsname._att1._perf + ' %');
                                           $('#Ctab1mne2').html(clsname._att2._perf + ' %');
                                           $('#Ctab1mne3').html(clsname._att3._perf + ' %');
                                           $('#Ctab1mne4').html(clsname._att4._perf + ' %');
                                           $('#Ctab1mne5').html(clsname._att5._perf + ' %');
                                           $('#Ctab1mne6').html(clsname._att6._perf + ' %');
                                         
                                           
                                           $('#Ctab2mne1').html(clsname._att1._class);
                                           $('#Ctab2mne2').html(clsname._att1._datenow);
                                           
                                           
                                          var tr2 = document.createElement('tr');
                                          var td2 = document.createElement('td');  var td2x = document.createElement('td'); 
                                          var td3 = document.createElement('td'); 
                                          var td4 = document.createElement('td'); 
                                          var td5 = document.createElement('td'); 
                                          var td6 = document.createElement('td'); 
                                          var td7 = document.createElement('td'); 
                                                
                                          td2.append("Subject(s)"); td2x.append("Attendance (%)"); 
                                          td3.append("Classwork (%)"); td4.append("Homework (%)"); 
                                          td5.append("Test (%)"); td6.append("Mid-Term (%)");
                                          td7.append("Terminal (%)"); 
                                                
                                          tr2.append(td2);  tr2.append(td2x); 
                                          tr2.append(td3);  tr2.append(td4);  
                                          tr2.append(td5);  tr2.append(td6);   
                                          tr2.append(td7); 
                                              
                                          tbody.append(tr2);
                                         
                                           for (  var i = 0; i < clsname._att7._subid.length; i++ ) {
                                               
                                                var tr1 = document.createElement('tr');
                                                var td11 = document.createElement('td'); var td12 = document.createElement('td'); 
                                                var td21 = document.createElement('td'); var td31 = document.createElement('td'); 
                                                var td41 = document.createElement('td'); var td51 = document.createElement('td'); 
                                                var td61 = document.createElement('td'); var td71 = document.createElement('td');
                                                var td81 = document.createElement('td'); var td91 = document.createElement('td');
                                                var td101 = document.createElement('td');
                                                
                                                 var id = clsname._att7._subid[i];
                                                
                                                td11.append(clsname._att7._subjects[id] ); 
                                                td12.append(round(clsname._att1b._arrayperf[id],0) + ' %');
                                            
                                                td21.append(round(clsname._att7._arrayperf[id][0].perf,0) + ' %');//Classwork
                                                td31.append(round(clsname._att8._arrayperf[id][0].perf,0) + ' %');//Homework
                                                td41.append(round(clsname._att9._arrayperf[id][0].perf,0) + ' %');//Test
                                                td51.append(round(clsname._att10._arrayperf[id][0].perf,0) + ' %');//MidTerm Exam
                                                td61.append(round(clsname._att11._arrayperf[id][0].perf,0) + ' %');//Terminal Exam
                                            
                                              
                                                tr1.append(td11); 
                                                tr1.append(td12); 
                                                tr1.append(td21);
                                                tr1.append(td31); 
                                                tr1.append(td41);
                                                tr1.append(td51);
                                                tr1.append(td61);
                                                
                                               
                                             
                                                tbody.append(tr1);
                                           }
                                        
                                           $('#getmneclass').modal('show');
                                           
                                           
                                           
                                          },
                                           error: function(xmlHttpRequest, textStatus, errorThrown)
                                          { 
                                          
                                          alert("Sorry, but check your network connection....");
                                          
                                          }
                                  });  
                 }
          
              function round(value, decimals) {
               return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
              }
    </script>
@endsection
