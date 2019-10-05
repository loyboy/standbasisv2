
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
    <script>
    
    </script>
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