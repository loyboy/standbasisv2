
<?php
function getSubTime($date, $period, $classid, $sch){    
  
    $value = DB::select("SELECT a._done, s2.name FROM attendances a INNER JOIN subjectclasses s ON a.sub_class_id = s.id INNER JOIN timetables t ON a.time_id = t.id INNER JOIN subjects s2 ON s2.id = s.sub_id INNER JOIN class_streams c ON c.id = s.class_id WHERE a._date LIKE :dat AND t._time = :per AND t.school_id = :sch AND c.id = :cls" , [ "dat" => "%".$date."%", "per" => $period, "sch" => $sch, "cls" => $classid ] ); //get maximum value
   if ($value[0] !== null){
        $done = intval($value[0]->_done) === 0 ? "<b style='color: red;'> NO </b>" : "<b style='color: green;'> YES </b>";
        $subject = $value[0]->name;
        $html = "<span> $done </span> <br> <span style='font-size: 9px; font-style: italic;'> $subject </span> ";
        return $html;
   }
   else{
        $html = "<span>  <b> Error </b> </span> <br>  ";
        return $html;
   }
}

?>