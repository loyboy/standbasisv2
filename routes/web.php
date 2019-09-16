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
    return view('welcome');
});

Route::get('/twelcome', function () {
    return view('teacher.general.welcome');
});

Route::get('/tatttake', function () {
    return view('teacher.attendance.take');
});

Route::get('/tatttakepupil', function () {
    return view('teacher.attendance.takereal');
});

Route::get('/tattview', function () {
    return view('teacher.attendance.view');
});

Route::get('/tlsnsubmit', function () {
    return view('teacher.lessonnote.submit');
});

Route::get('/tlsnview', function () {
    return view('teacher.lessonnote.view');
});

Route::get('/tlsnscores', function () {
    return view('teacher.lessonnote.addscores');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::Resource('schools', 'SchoolController');

Route::Resource('attendances', 'AttendanceController');
//// Custom methods for Attendance  
Route::post('/attendances_getTimeofAtt/{token}/class/{classid}', 'AttendanceController@getTimeOfAttendance');
Route::post('/attendances_getSubClass/{teaid}', 'AttendanceController@getSubjectClass');
Route::post('/attendances_getSubClassWithTime/{teaid}', 'AttendanceController@getSubjectClassWithTime');
Route::post('/attendances_submitAtt', 'AttendanceController@submitAttendance');

Route::Resource('lessonnotes', 'LessonnoteController');

Route::Resource('lessonnote_managements', 'LessonmgtController');

Route::Resource('rowcalls', 'RowcallController');

Route::Resource('teachers', 'TeacherController');

Route::Resource('class_streams', 'ClassStreamController');

Route::Resource('subjects', 'SubjectController');

Route::Resource('subjectclasses', 'SubjectClassController');
Route::post('/subjectclasses_findTeaSub/{id}/type/{type}', 'SubjectClassController@findTeaSubjects');

Route::Resource('pupils', 'PupilController');

Route::Resource('terms', 'TermController');

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