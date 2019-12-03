<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;


class LessonNoteController extends Controller
{

    /**
     * Allow this user to submit lessonnote with associated materials like Classwork and Assignment...
     *
     * @param  Request  $request
     * @return View
     */
     public function submitLN(Request $request) {
         
         date_default_timezone_set('Africa/Lagos');
        
         //let us try and add the tricks that make the Master_lsn_head table populated....
         $done = 1; ///if it has been done at all
         $policy = 0;///if it matches the policy of the school
         $fully = 0;///if it has come with the lessnnote+classwork+test+assignment
         $qua = 0;///if it has met policy + fully + cycle + closure of this lessonnote
         $flag = 0;///if the no. of students performance in assessment is ok
         $perf = 0;///the total of done + policY + fully + qua
         $cycle = 1;///the no of cycles the lessonnote made
         $closure = 0;///the total of done + policY + fully + qua
         
        $assessment = 1;//counter for lessonnote added materials
        
        $timestamptoday = strtotime(date('d-m-Y'));//
        $dayOfWeek = date("N", $timestamptoday);//get the day of the week from today's date
        
        if ($dayOfWeek <= session('general.policy_lsnsubmit') ) { //check if this lessonnote is submitted on Wednesday latest
            
            $policy = 1;
        } 
        
        $lessonnote_url = "LessonNote/".session('teacher.teacher_id')."/Template";
        $lessonnote_url2 = "LessonNote/".session('teacher.teacher_id')."/Classwork";
        $lessonnote_url3 = "LessonNote/".session('teacher.teacher_id')."/Assignment";
        $lessonnote_url4 = "LessonNote/".session('teacher.teacher_id')."/Test";
        $lessonnote_url5 = "LessonNote/".session('teacher.teacher_id')."/MidTerm";
        $lessonnote_url6 = "LessonNote/".session('teacher.teacher_id')."/TermExam";
       
        
        Storage::disk('public')->makeDirectory($lessonnote_url);
        Storage::disk('public')->makeDirectory($lessonnote_url2);
        Storage::disk('public')->makeDirectory($lessonnote_url3);
        Storage::disk('public')->makeDirectory($lessonnote_url4);
        Storage::disk('public')->makeDirectory($lessonnote_url5);
        Storage::disk('public')->makeDirectory($lessonnote_url6);
        
        
        $period =  session('ln_period'); 
            
         /**   if (null != $period){
                 session(['ln_period' => $period]);
            }  **/
       
             $validator = Validator::make($request->all(), [
            'ln_clw' =>  'max:5086',
            'ln_assign' => 'max:5086',
            'ln_test' => 'max:5086',
            'ln_midterm' => 'max:1024',
            'ln_termexam' => 'max:1024'
            ]);
            
            if ($validator->fails()) {
            $request->session()->flash('error', $validator->messages()->first() );
            return redirect()->back()->withInput();
            }
        
           //files from teacher

           $lessonnote_clw = $request->file('ln_clw');
           $lessonnote_assign = $request->file('ln_assign');
           $lessonnote_test = $request->file('ln_test');
           $lessonnote_midterm = $request->file('ln_midterm');
           $lessonnote_termexam = $request->file('ln_termexam');
           
           $lessonnote_file = $request->file('ln_gettemplate');
           
           $checktitle = strtoupper(session('ln_subject').'_'.'TERM '.session('ln_term').'_'.session('ln_class').'_'.'WEEK '.session('ln_period').'_'.date('Y'));
       
           $checkifweek = DB::select("SELECT * FROM lessonnote WHERE tea_id = :id AND school_sch_id = :sch AND _TITLE = :ti " , ["id" => session('teacher.teacher_id'),"sch" => session('general.school_id'), "ti"=>$checktitle ] ); 

           if (!empty($checkifweek)){
                  $request->session()->flash('ln_duplicate', session('ln_period'));
    
                  return redirect('/lntemplate_');
            }

            
            if(session()->has('teacher.teacher_id')) {
                 $ln_id = base_convert(hash('md5', mt_rand(10, 10000)),10,36);
           
                 //$lessonnote_url = "LessonNote/".session('ln_teacher_id')."/Template";
            }
            
            
            if (null != $lessonnote_file){
                //$lessonnote_file->move(storage_path("app/public/".$lessonnote_url),$ln_id.".doc");
                $lessonnote_file->storeAs(
                    "public/".$lessonnote_url, $ln_id.".doc"
                );
          
            }  
            //do the lessonnote file insert here
           
           $ln = new \App\LessonNote;
           $ln->_TITLE = strtoupper(session('ln_subject').'_'.'TERM '.session('ln_term').'_'.session('ln_class').'_'.'WEEK '.session('ln_period').'_'.date('Y'));
           $ln->_BODY = $lessonnote_url."/".$ln_id.".doc";
           $ln->_SCHLYR = date('Y');
           $ln->_TERM = session('ln_term');
           $ln->_PERIOD = $period;
           $ln->SCHOOL_SCH_ID = session('general.school_id');
           $ln->CLASS_ID = session('ln_class_id');          
           $ln->RES_ID = '';
           $ln->_CYCLE = 1;
           $ln->_SUBMISSION = date('Y-m-d H:i:s'); //changed from submission
           $ln->SUB_ID = session('ln_subject_id');
           $ln->TEA_ID = session('teacher.teacher_id');
           $ln->save();
           
           
           
         
           $latest_ln_id = DB::select("SELECT MAX(LSN_ID) As lsn FROM lessonnote WHERE TEA_ID = :id ",["id" => session('teacher.teacher_id')]);
            foreach($latest_ln_id as $at){
                $latest_ln_ = $at->lsn;
            }
            
            //Also save in the repo
           $ln2 = new \App\LessonNote_Repo;
           $ln2->_TITLE = strtoupper(session('ln_subject').'_'.'TERM '.session('ln_term').'_'.session('ln_class').'_'.'WEEK '.session('ln_period').'_'.date('Y'));
           $ln2->_BODY = $lessonnote_url."/".$ln_id.".doc";
           $ln2->_SCHLYR = date('Y');
           $ln2->_TERM = session('ln_term');
           $ln2->_PERIOD = $period;
           $ln2->SCHOOL_SCH_ID = session('general.school_id');
           $ln2->CLASS_ID = session('ln_class_id');          
           $ln2->RES_ID = '';
           $ln2->_CYCLE = 0;
           $ln2->_SUBMISSION = date('Y-m-d H:i:s'); //changed from submission
           $ln2->SUB_ID = session('ln_subject_id');
           $ln2->TEA_ID = session('teacher.teacher_id');
           $ln2->LSN_ID = $latest_ln_;
           $ln2->save();
           
            //update the school_check for lessonnote that helps you forsee who has submitted & who has not
           // DB::table('school_timetable_check2')->where('TEA_ID', session('teacher.teacher_id') )->where('SUB_ID', session('ln_subject_id') )->where('CLS_ID', session('ln_class_id') )->where('_YEAR', date('Y') )->where('_TERM', session('ln_term') )->where('_WEEK', $period )->update([ '_submit'=> date('Y-m-d') ]);
            
            if ( null !== $lessonnote_clw ){ //classwork
             $ex = new \App\Exam;
             $assessment++;
           $lessonnote_clw->storeAs(
                "public/".$lessonnote_url2, $ln_id.".pdf"
            ); 
          
           $ex->_TITLE = strtoupper('CLASSWORK'.'_'.session('ln_subject').'_'.'TERM '.session('ln_term').'_'.session('ln_class').'_'.'WEEK '.session('ln_period').'_'.date('Y'));
           $ex->_SOURCE = $lessonnote_url2."/".$ln_id.".pdf";
           $ex->_DATE = date('Y-m-d');
           $ex->_SUB_TITLE = session('ln_subject');
           $ex->_TYPE = 'CW';
           $ex->_EXT = 'pdf';
           $ex->LSN_ID = $latest_ln_;
           $ex->save();
           }
           
           if ( null !== $lessonnote_assign ){// assignment
             $ex = new \App\Exam;
            $assessment++;    
            $lessonnote_assign->storeAs(
                "public/".$lessonnote_url3, $ln_id.".pdf"
            ); 
          
          
           $ex->_TITLE = strtoupper('ASSIGNMENT'.'_'.session('ln_subject').'_'.'TERM '.session('ln_term').'_'.session('ln_class').'_'.'WEEK '.session('ln_period').'_'.date('Y'));
           $ex->_SOURCE = $lessonnote_url3."/".$ln_id.".pdf";
           $ex->_DATE = date('Y-m-d');
           $ex->_SUB_TITLE = session('ln_subject');
           $ex->_TYPE = 'AS';
           $ex->_EXT = 'pdf';
           $ex->LSN_ID = $latest_ln_;
           $ex->save();
           
           }
           
           if ( null !== $lessonnote_test ) {// test
            $ex = new \App\Exam;
            $assessment++;
            $lessonnote_test->storeAs(
                "public/".$lessonnote_url4, $ln_id.".pdf"
            ); 
          
           $ex->_TITLE = strtoupper('TEST'.'_'.session('ln_subject').'_'.'TERM '.session('ln_term').'_'.session('ln_class').'_'.'WEEK '.session('ln_period').'_'.date('Y'));
           $ex->_SOURCE = $lessonnote_url4."/".$ln_id.".pdf";
           $ex->_DATE = date('Y-m-d');
           $ex->_SUB_TITLE = session('ln_subject');
           $ex->_TYPE = 'TS';
           $ex->_EXT = 'pdf';
           $ex->LSN_ID = $latest_ln_;
           $ex->save();
           
           }
           
            if ( null !== $lessonnote_midterm ) {// MidTerm examination
            $ex = new \App\Exam;
            $assessment++;
            $lessonnote_midterm->storeAs(
                "public/".$lessonnote_url5, $ln_id.".pdf"
            ); 
          
           $ex->_TITLE = strtoupper('MIDTERM'.'_'.session('ln_subject').'_'.'TERM '.session('ln_term').'_'.session('ln_class').'_'.'WEEK '.session('ln_period').'_'.date('Y'));
           $ex->_SOURCE = $lessonnote_url5."/".$ln_id.".pdf";
           $ex->_DATE = date('Y-m-d');
           $ex->_SUB_TITLE = session('ln_subject');
           $ex->_TYPE = 'MT';
           $ex->_EXT = 'pdf';
           $ex->LSN_ID = $latest_ln_;
           $ex->save();
           
           }
           
           if ( null !== $lessonnote_termexam ) {// MidTerm examination
            $ex = new \App\Exam;
            $assessment++;
            $lessonnote_termexam->storeAs(
                "public/".$lessonnote_url6, $ln_id.".pdf"
            ); 
          
           $ex->_TITLE = strtoupper('TERMEXAM'.'_'.session('ln_subject').'_'.'TERM '.session('ln_term').'_'.session('ln_class').'_'.'WEEK '.session('ln_period').'_'.date('Y'));
           $ex->_SOURCE = $lessonnote_url6."/".$ln_id.".pdf";
           $ex->_DATE = date('Y-m-d');
           $ex->_SUB_TITLE = session('ln_subject');
           $ex->_TYPE = 'TE';
           $ex->_EXT = 'pdf';
           $ex->LSN_ID = $latest_ln_;
           $ex->save();
           
           }
           
           $request->session()->flash('ln_created', '1');
           $request->session()->forget('ln_step_2');
           $request->session()->forget('ln_step_1');
           
           //send a slip notice down to ln_activity table for teacher
           
           $schhead = DB::table('school_head')->select("schhead_id")->where( 'sch_id', session('general.school_id') )->first(); //first get the head's ID
         //  $expected = strtotime( "+18 hours", strtotime ( date('d-m-Y H:i:s') ) ); 
           $ht =  session('general.policy_lsnaction');
           $expected = strtotime( "+".$ht." hours", strtotime ( date('d-m-Y H:i') ) ); 
           $action = 0;
           
          
           
           /**if ($assessment >= 2){ //if all things are complete, it has been done fully
               $fully = 1;
           }**/
           if ( ($policy + $cycle) > 1 ){ //find out the quality of this lessonnote
            $qua = 1;
           }
           
           ///teacher performance calculation.......
          $perf = (($done + $policy + $fully + $qua) / 6 )*100;
          ///end teacher performance calculation
          
           //try and add to performance table:::: 
           DB::table('master_lsn_head')->insert(['lsn_id' => $latest_ln_, '_done' => $done , '_policy' => $policy , '_fully' => $fully, '_qua' => $qua, '_flag' =>$flag, '_perf'=> $perf,'_closure'=> $closure,'_cycle'=> $cycle, '_action' => '' ]);
         
           DB::table('ln_activity')->insert(['lsn_id' => $latest_ln_, '_owner' => $schhead->schhead_id , '_expected' =>  $expected , '_actual' => '', '_slip' => 0 , '_action' => $action  ]);
           
           //send slack message to notify everyone
             //
            
             $mytext = 'A Teacher by name: '. session('teacher.teacher_name').' Just Submitted a Fresh Lessonnote to the School Head with title: '.DB::table('lessonnote')->select("_TITLE")->where( 'lsn_id', $latest_ln_  )->first()->_TITLE;
             $slackdata = [ 'channel' => 'stb'.session('general.school_id'), 'text' => $mytext ];
             $this->performPostCurl('https://slack.com/api/chat.postMessage',$slackdata,$this->token_);
         
           return redirect('/lntemplate_');
        
           
    }
    /**
     * Allow this user to enter scores of attached files associated with a particular lessonnote.
     *
     * @param  Request  $request
     * @return View
     */
    public function enterscore(Request $request){

        $scores = $request->input('score');
        $max = $request->input('max');
        $examid = $request->input('examid');
        $classid = $request->input('clsid');
       
        $scorex = implode(";", $scores);//turn into a concatenated string
        $scorearray = explode(";", $scorex);

        $totalpupils = session('ln_pupil');
        $i = 0;
        
         $checkifexists = DB::select("SELECT _ACTUAL FROM score WHERE class_id = :id AND school_sch_id = :sch AND exam_id = :ex " , ["id" => $classid,"sch" => session('general.school_id')
              ,"ex"=>$examid ] ); 
         
         if ( count($checkifexists) <= 0 )   {  
        foreach ($totalpupils as $to) {
           
         $exam = new \App\Score;
         $exam->_ACTUAL = $scorearray[$i]; 
         $exam->_MAX = $max;
         $exam->_SCORE_DATE = date('Y-m-d');
         $exam->PUPIL_ID = $to->PUP_ID;
         $exam->CLASS_ID = $classid;
         $exam->SCHOOL_SCH_ID = session('general.school_id');
         $exam->EXAM_ID = $examid;
         $exam->_PERFORMANCE = 0;
         $exam->save(); 
         $i++;

                    }   } else {
             foreach ($totalpupils as $to) {
                 
           $exam = new \App\Score;             
           $exam::where('class_id', $classid)->where('school_sch_id', session('general.school_id'))->where('exam_id',$examid )->where('pupil_id', $to->PUP_ID)->update(['_PERFORMANCE' => 0,'_MAX' => $max,'_SCORE_DATE' => date('Y-m-d'), '_ACTUAL' => $scorearray[$i]]);
              $i++;
                 
             }
                    }
        
        $request->session()->flash('ln_enterscore_success',1);
         return redirect('/lnviewexam');
    }
        
       /**
     * Curl init method for use in Slack services
     *
     * @param  Request  $request
     * @return View
     */
     private function performPostCurl($url,$data,$token){ 
         
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => array(
                	// Set here requred headers
                    
                    "Authorization: Bearer $token",
                    "content-type: application/json"
                ),
            ));
            
            $response = curl_exec($curl);
            //$err = curl_error($curl);
            curl_close($curl);

           // return $response;
     }
     /**
     * Allow this user to logout from lessonnote module.///NOT in USE
     *
     * @param  Request  $request
     * @return View
     */
   public function logout(){
        
        return redirect('/');
    }
}

