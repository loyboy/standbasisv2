<?php

function getTeacherName($tea_id){
    $tea_name = "";
    $name = DB::select("SELECT CONCAT(_LNAME,' ',_FNAME) as myname FROM teacher WHERE tea_id = :id " , [ "id" => $tea_id ] ); //get each teacher's name 
    foreach($name as $n){
      $tea_name = $n->myname;  
    }   
    return $tea_name;
}


////////////save
function getgeneralscore($lsn,$pupil,$type){
    $sub_name = "";
    $name = DB::select("SELECT actual FROM scores WHERE enrol_id = :pup AND ass_id IN (SELECT id FROM assessments WHERE _type = :typ AND lsn_id = :lsn ) " , [ "lsn" => $lsn, "pup" => $pupil, "typ" => $type] ); //get assignment value
    foreach($name as $n){
      $sub_name = $n->actual;  
    }   
    return $sub_name;
}
////////////save
function getmaxscore($lsn,$type){
    $sub_name = "";
    $name = DB::select("SELECT actual FROM scores WHERE ass_id IN (SELECT id FROM assessments WHERE _type = :typ AND lsn_id = :lsn ) LIMIT 1" , [ "lsn" => $lsn, "typ" => $type] ); //get maximum value
    foreach($name as $n){
      $sub_name = $n->actual;  
    }   
    return $sub_name;
}

/////////save
function checkforExamGeneral($lsn_id, $type){
    $sub_name = "";
    $name = DB::select("SELECT id FROM assessments WHERE _type = :typ AND lsn_id = :lsn " , [ "lsn" => $lsn_id,"typ" => $type  ] );
    if (count($name) <= 0){
      
      $subexam = DB::select(" SELECT sub_id FROM lessonnotes WHERE id = :lsn  " , [ "lsn" => $lsn_id ] );
      $id = DB::table('assessments')->insertGetId(
        [
            'lsn_id' =>  $lsn_id,
            'sub_id' =>  $subexam[0]->sub_id,
            'source' => 'nil',
            'title' => " Test",
            '_date' => date('Y-m-d'),
            '_type' => $type,
        ]
      );

        return $id;
    }
    else{
      foreach($name as $n){
        $sub_name = $n->id;  
      }   
        return $sub_name;
    }
}
///////////save
function getLNName($lsn_id){
    $lsn_title = "";
    $name = DB::select("SELECT title FROM lessonnotes WHERE id = :id " , [ "id" => $lsn_id ] ); //get each teacher's name 
    foreach($name as $n){
        $lsn_title = $n->title;  
    }   
    return $lsn_title;
}

