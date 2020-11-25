
           <!-- Get the attendnace View for this Attendance-->                     
                                <div class="modal fade" id="getattendance" tabindex="-1" role="dialog" aria-labelledby="backupAccount" style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">

                                        <div class="modal-content">
                                            <div class="modal-header bg-info border-0">
                                                <h4 class="modal-title" id="mytitle"> </h4>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>    
                                                </button>
                                            </div>

                                            <div class="modal-body"> <!-- -->                                             
                                                
                                              

                                                <div class="row">
                                                    <div class="col-md-12"> 
                                                        <img id="attimg" width="480" height="300" style="margin: 0 auto;"/> <br/>
                                                        <div class="gx-card">                             
                                                            <div class="gx-card-body">
                                                                <div class="d-flex flex-column">
                                                                    <div class="list-line-item">
                                                                        <div class="list-line-badge bg-primary"></div>
                                                                        <div class="list-line-content">
                                                                            <h3 class="mb-1 text-primary">Principal's Comment</h3>
                                                                            <p class="text-muted" id="p_comment"></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>  
                                                        </div>

                                                        <div style = "padding: 5px; margin-top: 50px; overflow-y: scroll; max-height:300px;" id="myatt_table" > </div>
                                                    </div>                                                   
                                                </div>
                                                            
                                            </div>

                                            <div class="modal-footer">
                                                    <button type="button" class="btn btn-info btn-block" data-dismiss="modal">Close</button>                    
                                            </div>
                                        
                                        </div>                                        
                                    </div>
                                </div>
    <!-- Approve Attendance by Head Modal -->
    <div class="modal fade" id="headapproveatt" tabindex="2" role="dialog">
        <div class="modal-dialog modal-lg">
           
            <div class="modal-content">
                <div class="modal-header bg-success white">
                  
                     <h4 class="modal-title" id="head_modal_header"> Your Approval is needed </h4>
                     <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>    
                    </button>
                </div>
                <div class="modal-body">
                     <div class="form-group col-md-12">
                          <input type="hidden" name='att_id' id="head_modal_att_id"> 
                          <input type="hidden" name='decision_id' id="head_modal_att_decision"> 
                          <input type="hidden" name='att_rowid' id="head_modal_att_rowid"> 
                       <p> <b> <h3>Approve of this Attendance Activity by Teacher? </h3> </b> </p>
                     </div>                        
                </div>
                <div class="modal-footer">                       
                    <button class="btn btn-info btn-block" onclick="attendAtt()"> Approve Attendance </button> 
                </div>
               
            </div>
           
        </div>
    </div>
    

  <!-- Disapprove attendance Head Modal -->  
<div class="modal fade" id="headapproveatt2" tabindex="2" role="dialog">
        <div class="modal-dialog modal-lg">
           <!--  <form role="form" id="headdisapproveatt_form" method="post" accept-charset="UTF-8" action=<?php // echo action('SchoolheadController@attdisapprove'); ?> > -->
            <div class="modal-content">
                <div class="modal-header bg-danger white">
                  
                     <h4 class="modal-title" id="head_modal_header2"> Your Disapproval is needed </h4>
                     <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>    
                    </button>
                </div>
                <div class="modal-body">
                 
               
                     <div class="form-group col-md-12">
                         
                        <input type="hidden" name='att_id' id="head_modal_att_id2"> 
                          <input type="hidden" name='att_rowid' id="head_modal_att_rowid2">      
                        <label class="bolden"> <h3>Type your comment </h3></label>   
                        <textarea rows="4" cols="40" class="form-control" name="comment" id="comment_box"> 
                         
                        </textarea>
                     </div>  
              
                        
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-block" onclick="attendAttDis()"> Submit Comment</button> 
                </div>
               
            </div>
             
        </div>
    </div> 

    <!--- Lessonnote -->
      <!-- Approve Lessonnote by Head Modal -->
      <div class="modal fade" id="headapprovelsn" tabindex="2" role="dialog">
        <div class="modal-dialog modal-lg">
           
            <div class="modal-content">
                <div class="modal-header bg-success white">                  
                     <h4 class="modal-title" id="head_modal_header3"> Your Approval is needed </h4>
                     <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>    
                    </button>
                </div>

                <div class="modal-body">
                     <div class="form-group col-md-12">
                          <input type="hidden" name='lsn_id' id="head_modal_lsn_id"> 
                          <input type="hidden" name='decision_id' id="head_modal_lsn_decision"> 
                          <input type="hidden" name='lsn_rowid' id="head_modal_lsn_rowid"> 
                       <p> <b> <h3>Approve of this Lessonnote Activity by Teacher? </h3> </b> </p>
                     </div>                        
                </div>

                <div class="modal-footer">                       
                    <button class="btn btn-info btn-block" onclick="attendLsn()"> Approve Lessonnote </button> 
                </div>
               
            </div>
           
        </div>
    </div>
    
  <!-- Disapprove attendance Head Modal -->  
<div class="modal fade" id="headapprovelsn2" tabindex="2" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-danger white">
                  
                     <h4 class="modal-title" id="head_modal_header4"> Your Disapproval is needed </h4>
                     <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>    
                    </button>
                </div>
                <div class="modal-body">                 
               
                     <div class="form-group col-md-12">
                         
                        <input type="hidden" name='lsn_id' id="head_modal_lsn_id2"> 
                          <input type="hidden" name='lsn_rowid' id="head_modal_lsn_rowid2">      
                        <label class="bolden"> <h3>Type your comment </h3></label>   
                        <textarea rows="4" cols="40" class="form-control" name="comment" id="comment_box"> 
                         
                        </textarea>
                     </div>  
              
                        
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-block" onclick="attendLsnDis()"> Submit Comment</button> 
                </div>
               
            </div>
             
        </div>
    </div> 

    <!---Add scores -->
    <div class="modal fade" id="teacheraddscores" tabindex="2" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header bg-info white">                  
                        <h4 class="modal-title" id="head_modal_addscoreheader"> Add Scores </h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>    
                        </button>
                </div>

                <div class="modal-body">                 
               
                    <div class="form-group col-md-12">
                     
                        <div class="gx-card p-0 overflow-hidden">
                            <div class="gx-card-body">
                                <div class="table-responsive" style="height: 400px; overflow-y: auto; overflow-x: hidden; ">
                                    <table class="table table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th class="text-uppercase" scope="col">S/N</th>
                                                    <th class="text-uppercase" scope="col">Student Name</th>
                                                    <th class="text-uppercase" scope="col">Add Score</th>
                                                </tr>
                                            </thead>

                                            <tbody id="scoretbody">
                                                <tr id="scoremax">
                                                    <th scope="row">*</th>
                                                    <td>Max. Score</td>
                                                    <td><input type="number" required="required" name="max" id="scoremaximum" value="" class=" form-control-sm"/> </td>
                                                
                                                </tr>                                            
                                            </tbody>
                                            
                                        </table>
                                    </div>
                                </div>
                            </div>
                     
                     </div>  
              
                        
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-block" onclick="submitScores()"> Submit Scores</button> 
                </div>
               
            </div>
             
        </div>
    </div> 

    <!-- View MNE Student page -->
    <div class="modal fade" id="getmnestudent" tabindex="2" role="dialog" style="font-family: 'Trebuchet MS'; ">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title" id=""> MNE Student Result Page <i class="fa"></i></h4>
                     <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>    
                    </button>
                </div>
                
                 <div class="modal-body"> <!-- -->
                        <div class="container">
                            <div class="row col-12">
                                <div class="col-4"> 
                                 <table class="table table-xs">
                                     <tbody> 
                                     <tr> <td class="mnetabletd">Attendance: </td> <td class="mnetabletd2" id='tab1mne1'> </td> </tr>
                                     <tr> <td  class="mnetabletd">Class work: </td> <td class="mnetabletd2" id='tab1mne2'> </td> </tr>
                                     <tr> <td  class="mnetabletd">Home work: </td> <td class="mnetabletd2" id='tab1mne3'> </td> </tr>
                                     <tr> <td  class="mnetabletd">Tests: </td> <td class="mnetabletd2" id='tab1mne4'> </td> </tr>
                                     <tr> <td  class="mnetabletd">Mid-Term: </td> <td class="mnetabletd2" id='tab1mne5'> </td> </tr>
                                     <tr> <td  class="mnetabletd">Terminal: </td> <td class="mnetabletd2" id='tab1mne6'> </td> </tr>
                                     <tr> <td id="tabedit7">Attendance by Subject: </td> <td id='tab1mne7'> <b> See Table Below </b></td> </tr>
                                     </tbody>
                                </table>         
                                </div>
                                <div class="col-4"> 
                                <table class="table table-xs">
                                     <tbody> 
                                     <tr> <td class="mnetabletd">Student Name/ID: </td> <td class="mnetabletd2" id='tab2mne1'> </td> </tr>
                                     <tr> <td class="mnetabletd"> Class : </td> <td  class="mnetabletd2" id='tab2mne2'> </td> </tr>
                                     <tr> <td class="mnetabletd"> Date: </td> <td class="mnetabletd2"  id='tab2mne3'> </td> </tr>
                                    
                                     </tbody>
                                </table>
                                </div>
                                
                                <div class="col-4">
                                 <table class="table table-xs">
                                     <tbody> 
                                     <tr> <td class="mnetabletd">Requester Name: </td> <td  class="mnetabletd2" id='tab3mne1'> </td> </tr>
                                     <tr> <td class="mnetabletd">Date/Time : </td> <td  class="mnetabletd2" id='tab3mne2'> </td> </tr>
                                     <tr> <td class="mnetabletd">Request Count: </td> <td  class="mnetabletd2" id='tab3mne3'> </td> </tr>
                                    <tr> <td class="mnetabletd">Copy/Print Authority: </td> <td  class="mnetabletd2" id='tab3mne4'> </td> </tr>
                                     </tbody>
                                </table>    
                                </div>
                            </div>
                            <div class="row col-12">
                               <div class="card">
                                <table class="table table-xs">
                                     <tbody id="tabbody"> 
                                    
                                     </tbody>
                                </table>  
                               </div>
                            </div>
                        </div>          
                 </div>
                 
                 <div class="modal-footer"><button type="button" class="btn btn-default btn-block" data-dismiss="modal">Close</button> </div>
               
            </div>
                
        </div>
         
    </div>

    <!-- View MNE TEacher page -->
    <div class="modal fade" id="getmneteacher" tabindex="2" role="dialog" style="font-family: 'Trebuchet MS'; ">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h4 class="modal-title" id=""> MNE Teacher Result Page <i class="fa"></i></h4>
                     <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>    
                    </button>
                </div>
                
                 <div class="modal-body"> <!-- -->
                        <div class="container">
                            <div class="row col-12">
                                <div class="col-4"> 
                                 <table class="table table-xs">
                                     <tbody> 
                                     <tr> <td class="mnetabletd">Attendance: </td> <td class="mnetabletd2" id='Ttab1mne1'> </td> </tr>
                                     <tr> <td  class="mnetabletd">Class work: </td> <td class="mnetabletd2" id='Ttab1mne2'> </td> </tr>
                                     <tr> <td  class="mnetabletd">Home work: </td> <td class="mnetabletd2" id='Ttab1mne3'> </td> </tr>
                                     <tr> <td  class="mnetabletd">Tests: </td> <td class="mnetabletd2" id='Ttab1mne4'> </td> </tr>
                                     <tr> <td  class="mnetabletd">Mid-Term: </td> <td class="mnetabletd2" id='Ttab1mne5'> </td> </tr>
                                     <tr> <td  class="mnetabletd">Terminal: </td> <td class="mnetabletd2" id='Ttab1mne6'> </td> </tr>
                                      <tr> <td  class="mnetabletd">Lessonnote Management: </td> <td class="mnetabletd2" id='Ttab1mne7'> </td> </tr>
                                      <tr> <td  class="mnetabletd">Attendance Management: </td> <td class="mnetabletd2" id='Ttab1mne8'> </td> </tr>                                   
                                     </tbody>
                                </table>         
                                </div>
                                <div class="col-4"> 
                                <table class="table table-xs">
                                     <tbody> 
                                     <tr> <td class="mnetabletd">Teacher Name/ID: </td> <td class="mnetabletd2" id='Ttab2mne1'> </td> </tr>
                                    
                                     <tr> <td class="mnetabletd"> Date: </td> <td class="mnetabletd2"  id='Ttab2mne2'> </td> </tr>
                                    
                                     </tbody>
                                </table>
                                </div>
                              <!--  
                                <div class="col-4">
                                 <table class="table table-xs">
                                     <tbody> 
                                     <tr> <td class="mnetabletd">Requester Name: </td> <td  class="mnetabletd2" id='Ttab3mne1'> </td> </tr>
                                     <tr> <td class="mnetabletd">Date/Time : </td> <td  class="mnetabletd2" id='Ttab3mne2'> </td> </tr>
                                     <tr> <td class="mnetabletd">Request Count: </td> <td  class="mnetabletd2" id='Ttab3mne3'> </td> </tr>
                                    <tr> <td class="mnetabletd">Copy/Print Authority: </td> <td  class="mnetabletd2" id='Ttab3mne3'> </td> </tr>
                                     </tbody>
                                </table>    
                                </div>
                                -->
                            </div>
                            <div class="row col-12" style="width: auto; overflow-x: scroll; white-space: nowrap;">
                              
                                <table class="table table-xs">
                                 
                                     <tbody id="tabbody2" > 
                                    
                                     </tbody>
                                </table>  
                            
                            </div>
                        </div>          
                 </div>
                 
                 <div class="modal-footer"><button type="button" class="btn btn-default btn-block" data-dismiss="modal">Close</button> </div>
               
            </div>
                
        </div>
         
    </div>