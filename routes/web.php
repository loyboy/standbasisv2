<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('teacher.general.welcome');
})->middleware('auth');

Route::get('/home', function () {
    return view('teacher.general.welcome');
})->middleware('auth');

Route::get('/twelcome', function () {
    return view('teacher.general.welcome');
})->middleware('auth');

Route::get('/tatttake', function () {
    return view('teacher.attendance.take');
})->middleware('auth');

Route::get('/tatttakepupil/{val}', function ($val) {
    return view('teacher.attendance.takereal', compact('val')) ;
})->middleware('auth');

Route::get('/tattviews', function () {
    return view('teacher.attendance.view');
})->middleware('auth');

Route::get('/pattviews', function () {
    return view('teacher.attendance.pview');
})->middleware('auth');

Route::get('/pattflags', function () {
    return view('teacher.attendance.pflags');
})->middleware('auth');

Route::get('/tlsnsubmit', function () {
    return view('teacher.lessonnote.submit');
})->middleware('auth');

Route::get('/tlsnview', function () {
    return view('teacher.lessonnote.view');
})->middleware('auth');

Route::get('/plsnflags', function () {
    return view('teacher.lessonnote.pflags');
})->middleware('auth');

Route::get('/plsnview', function () {
    return view('teacher.lessonnote.pview');
})->middleware('auth');

Route::get('/tlsnscores', function () {
    return view('teacher.lessonnote.addscores');
})->middleware('auth');

Route::get('/tmneview', function () {
    return view('teacher.mne.teacher');
})->middleware('auth');

//Parents
Route::get('/ptwards', function () {
    return view('teacher.attendance.ptward');
})->middleware('auth');


Route::get('/logoutuser', function () {
   Auth::logout();
   return redirect('/login');
});

Auth::routes();


Route::Resource('schools', 'SchoolController');

Route::Resource('attendances', 'AttendanceController');
//// Custom methods for Attendance  
Route::post('/attendances_getTimeofAtt/{token}/class/{classid}', 'AttendanceController@getTimeOfAttendance');
Route::post('/attendances_getSubClass/{teaid}', 'AttendanceController@getSubjectClass');
Route::post('/attendances_getSubClassWithTime/{teaid}', 'AttendanceController@getSubjectClassWithTime');
Route::post('/attendances_submitAtt', 'AttendanceController@submitAttendance');
Route::post('/attendances_viewAtt/{teaid}', 'AttendanceController@viewAttendance');
Route::post('/attendances_viewAttAll/{teaid}', 'AttendanceController@viewAttendanceAll');
//Route::post('/attendances_viewAtt/{teaid}', 'AttendanceController@viewAttendanceSubject');
Route::post('/attendances_viewAttLog/{teaid}', 'AttendanceController@viewAttendanceLog');
Route::post('/attendances_attendAtt/{teaid}', 'AttendanceController@attendTo'); //
Route::post('/attendances_attendAttComment/{attid}', 'AttendanceController@attendViewComment');
Route::post('/attendances_getFlags/{teaid}', 'AttendanceController@viewAttendanceFlags');
Route::post('/attendances_viewWards/{teaid}', 'AttendanceController@viewWardsAtt');

Route::Resource('lessonnotes', 'LessonnoteController');
/////Custom functions for lessonnote
Route::post('/lessonnotes_getSubClass/{teaid}', 'LessonnoteController@getSubjectClass');
Route::post('/lessonnotes_submitLsn', 'LessonnoteController@submitLessonnote');
Route::post('/lessonnotes_viewLsn/{teaid}', 'LessonnoteController@viewLessonnoteTeacher');
Route::post('/lessonnotes_viewLsnAll/{teaid}', 'LessonnoteController@viewLessonnoteTeacherAll');
Route::post('/lessonnotes_statusLsn/{lsnid}/id/{idx}', 'LessonnoteController@changeStatusLessonnote');
Route::post('/lessonnotes_getFlags/{teaid}', 'LessonnoteController@viewLessonnoteFlags');

Route::Resource('lessonnote_managements', 'LessonmgtController');

Route::Resource('rowcalls', 'RowcallController');

Route::Resource('teachers', 'TeacherController');

Route::Resource('class_streams', 'ClassStreamController');

Route::Resource('subjects', 'SubjectController');

Route::Resource('subjectclasses', 'SubjectClassController');
Route::post('/subjectclasses_findTeaSub/{id}/type/{type}', 'SubjectClassController@findTeaSubjects');

Route::Resource('pupils', 'PupilController');
Route::post('/pupils_getPupilForTeacher/{teaid}', 'PupilController@getPupilForTeacher');
Route::post('/pupils_getClassForTeacher/{teaid}', 'PupilController@getClassForTeacher');

Route::Resource('terms', 'TermController');
Route::post('/terms_getDate/{schid}', 'TermController@getTermDate');

Route::Resource('enrollments', 'EnrolmentController');

Route::Resource('timetables', 'TimetableController');

Route::Resource('timetable_sches', 'TimetableSchController');

Route::Resource('timetable_sches', 'TimetableSchController');

Route::Resource('assessments', 'AssessmentController');

Route::Resource('scores', 'ScoreController');

Route::Resource('users', 'UserController');
//// Custom methods for USers
Route::post('/users_login', 'UserController@login');

Route::Resource('lsn_activities', 'LsnActivityController');

Route::Resource('att_activities', 'AttActivityController');

Route::Resource('att_performances', 'AttPerfController');

Route::Resource('lsn_performances', 'LsnPerfController');

Route::Resource('school_policies', 'SchoolpolicyController');