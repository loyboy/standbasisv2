<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use App\School;
use App\Timetable;
use App\Teacher;
use App\Term;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->call(function () {
          
            //POPULATE ATTENDANCE BY DEFAULT....................................................
            
            //get all the schools that are ready and SRI certified
            $allschools = School::where('sri', 1)->get();
           
            foreach($allschools as $sch){
                //get the resumption year & term
                 //$school_time = DB::table('school_time')->select("_resumedate","_term")->where('sch_id', $sch->sch_id)->where('_status', 1)->where('_attend', 1)->first();
                 $school_time = Term::where('school_id', $sch->id)->where('_cron', 1)->first();
                 
                 echo "School time set...";
                 
                 if ($school_time !== null){
                 
                 // $theyear = date("Y",strtotime($school_time->_resumedate));
                 //,strtotime('+1 hours')
                 $thedayofweek = date("N");
                 $thetimeofday = date("H:i");
                 $splittime = explode(":",$thetimeofday);
                 $theyear = date("Y",strtotime($school_time->resumedate));
                 $todaydate = date("Y-m-d");
                 
                 //teachers in the school
                  $teachers = Teacher::where('school_id', $sch->id)->where('_status', 1)->where('_type', 0)->get();

                  $time = DB::select('SELECT id , term,  resumedate , ( week(curdate()) - week(resumedate) + 1 ) AS weeksout FROM terms WHERE _status = 1 AND school_id = :sch;', [ "sch" => $sch->id ]);
                    
                    foreach ($time as $t){
                          $weeksout = $t->weeksout;
                          $term = $t->id;
                          $termval = $t->term;
                    }

                    $period2 = array('1'=>"1ST TERM",'2'=>"2ND TERM",'3'=>"3RD TERM");
                    $thedesc = "WEEK ".$weeksout." ".$period2[$termval];
                    
                      $morning = strtotime("08:15");
                      $afternoon = strtotime("11:45");
                      $close = strtotime("13:15");
                      $timenow = strtotime(date('H:i'));
                      $period = "";                    
                     
                       if ($timenow <= $morning){ $period = "M"; }
                       if ($timenow > $morning && $timenow <= $afternoon){ $period = "B"; }
                       if ($timenow > $afternoon){ $period = "C"; }
                            
                foreach ($teachers as $t){
                
                //categorize the school_timetable data by subject -- also check for Waived teachers
                // If timetables.waiver === 1, then the whole school has been waived
                // It timetable.affected has any teacher id inside, then that teacher alone is waived, 
                // i DID a uNION HERE TO INCLUDE THE DELEGATED CLASSES GIVEn TO THE tEACHER
                $resultsteach = DB::select(
                "SELECT s.ID, te.ID as TEA_ID, t.CLASS_ID, st.ID, t.ID as subclass, st.time_id , t.DELEGATED, t1.waiver as waived, t1.affected as affected
                FROM subjects s 
                INNER JOIN subjectclasses t ON t.SUB_ID = s.ID 
                INNER JOIN timetable_sches st ON t.ID = st.SUB_CLASS
                INNER JOIN timetables t1 ON t1.ID = st.TIME_ID 
                INNER JOIN teachers te ON te.ID = t.TEA_ID
                WHERE t.TEA_ID = :tea 
                AND st.TIME_ID 
                IN (SELECT ID FROM timetables st1 WHERE st1._TIME LIKE :tym AND st1._TIME LIKE :tym2 AND st1._DAY = :dayz)
               
                " , 
                [ "tea" =>  $t->id, "tym" => '%'.$splittime[0].'%' ,"tym2" => '%'.$splittime[1].'%' , "dayz" => $thedayofweek ] );
                
                echo "Teacher: ". $t->id."\r\n"; echo "Year: ". $theyear."\r\n"; echo "Term: ". $school_time->term."\r\n"; echo "Time of the day: ".$thetimeofday."\r\n"; echo "Dayofweek: ".$thedayofweek."\r\n";
                    
                foreach ($resultsteach as $rt)  {
                      
                      echo "Subject now teaching is...".$rt->TEA_ID."\r\n";
                      $done = 0; $delegated = null;
                      $checkifattexists = DB::select("SELECT * FROM attendances WHERE sub_class_id = :id AND time_id = :timex AND term = :term AND _date LIKE :dat " , ['id' => $rt->subclass, 'term' => $school_time->id , "dat" => '%'.$todaydate.'%', "timex" => $rt->time_id  ] ); 
  
                          if (empty($checkifattexists)){ 
                            $td = date("Y-m-d H:i");
                            $search = $rt->TEA_ID.",";
                              if(!is_null($rt->DELEGATED)){
                                 $delegated = $rt->DELEGATED;
                              }
                              if (stripos($rt->affected, $search) !== false) {
                                  $done = -1;// Let this mean the class was waived
                              }
                              else if ($rt->waived === 1){
                                  $done = -1;
                              }
                              else{
                                  $done = 0;
                              }
                            // $td = date("Y-m-d H:i:s",strtotime('+1 hours'));
                           
                            DB::table('attendances')->insert([ 'time_id' => $rt->time_id, 'sub_class_id' => $rt->subclass , '_desc' => $thedesc , "_date" => $td , "period" => $period, "term" => $term, "_done" => $done, "_delegated" => $delegated ]);                             
                          }                      
                      }  
                     
                } 
                     
                     
              }
            
                
            }
            //END ATTENDANCE....................................................
            
          })->everyMinute()->appendOutputTo(public_path("cron.log"));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
