<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/quienes-somos', function () {
    return view('about');
});

Auth::routes();

Route::group(['prefix' => 'student','middleware'=>['auth', 'role:student']], function () {

    Route::get('home','Student\HomeController@index');

    Route::get('activity/{id}/homework/{id2}','Student\ActivitiesController@show');
    Route::resource('activities','Student\ActivitiesController');
    Route::get('myteacher','Student\HomeController@myteacher');
    Route::resource('observations','Student\ObservationsController',['names'=>
      [
        'create' => 'student.observations.create',
        'update' => 'student.observations.update',
      ]
    ]);
    Route::get('information/{id}/edit','Student\HomeController@information');
    Route::patch('information/{id}',[
      'as'=>'edit.student',
      'uses'=> 'Student\HomeController@updateStudent',
    ]);
});



Route::group(['prefix' => 'teacher','middleware'=>['auth', 'role:tutor']], function () {


    Route::get('home','Teacher\HomeController@index');
    Route::get('group/{id}/statistics','Teacher\PdfsController@statisticsPdf');
    Route::get('group/{id}/pdf','Teacher\PdfsController@makepdf');
    Route::get('student/{id}/homeworks','Teacher\ActivitiesController@homeworks');//--------pendiente

    Route::get('group/{id_group}/activity/{id_activity}','Teacher\GroupsController@show_activity');

    Route::get('group/{id}/activities/{semester}','Teacher\GroupsController@show');
    Route::get('group/{id}/students','Teacher\GroupsController@showStudents');
    Route::get('groups','Teacher\GroupsController@index');
    Route::resource('statistics','Teacher\StatisticsController');
    Route::resource('activities','Teacher\ActivitiesController');
    Route::resource('homeworks','Teacher\HomeworksController');
    Route::resource('observations','Teacher\ObservationsController');
    Route::get('information/{id}/edit','Teacher\HomeController@informationTeacher');
    Route::patch('information/{id}',[
      'as'=>'edit.teacher',
      'uses'=> 'Teacher\HomeController@updateTeacher',
    ]);
    //Route::resourc('groups','Teacher\ActivitiesController');

});



Route::group(['prefix' => 'jefe-departamento','middleware' => ['auth', 'role:department_manager']], function () {
    Route::get('/',  'HeadOfDepartmentController@index');
    Route::group(['prefix' => 'alumnos'], function () {
      Route::get('/',  'HeadOfDepartmentController@students');
      Route::get('crear',  'HeadOfDepartmentController@createStudent');
      Route::get('editar',  'HeadOfDepartmentController@updateStudent');
    });
    Route::group(['prefix' => 'tutores'], function () {
      Route::get('/',  'HeadOfDepartmentController@tutors');
      Route::get('crear',  'HeadOfDepartmentController@createTutor');
    });
    Route::get('asignaciones',  'HeadOfDepartmentController@assignments');
    Route::get('perfil',  'HeadOfDepartmentController@profile');
    Route::get('nuevo-periodo',  'HeadOfDepartmentController@createPeriod');
    Route::post('guardar-periodo',  'HeadOfDepartmentController@storePeriod');
});

Route::group(['prefix' => 'coordinador','middleware' => ['auth', 'role:coordinator']], function () {
  Route::get('/',  'CoordinatorController@index');
  Route::group(['prefix' => 'alumnos'], function () {
      Route::get('/',  'CoordinatorController@students');
      Route::get('crear',  'CoordinatorController@createStudent');
      Route::get('editar',  'CoordinatorController@updateStudent');
  });
  Route::group(['prefix' => 'tutores'], function () {
    Route::get('/',  'CoordinatorController@tutors');
    Route::get('crear',  'CoordinatorController@createTutor');
  });
  Route::get('asignaciones',  'CoordinatorController@assignments');
  Route::get('perfil',  'CoordinatorController@profile');
  Route::get('grupos',  'CoordinatorController@groups');

});

Route::get('/student/list/{period}',  'StudentController@all');
Route::get('/student/list-for-period/{id}',  'StudentController@allInPeriod');
Route::get('/student/{id}/group',  'StudentController@groupForStudent');
Route::get('/group/list',  'GroupController@all');
Route::get('/group/{id}/students',  'GroupController@students');
Route::get('/group/{id}/tutor',  'GroupController@tutorForGroup');
Route::get('/group/list-for-period/{id}',  'GroupController@allInPeriod');
Route::get('/tutor/list',  'TutorController@all');
Route::get('/tutor/{id}/groups',  'TutorController@groupsForTutor');
Route::get('/period/list',  'PeriodController@all');

Route::resource('headOfDpt', 'HeadOfDepartmentController');
Route::resource('group', 'GroupController');
Route::resource('coordinator', 'CoordinatorController');
Route::resource('tutor', 'TutorController');
Route::resource('student', 'StudentController');


//Route::get('/home', 'HomeController@index');
