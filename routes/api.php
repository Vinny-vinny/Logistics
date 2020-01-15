<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('job-card','JobcardController');
Route::post('close-jobcard/{id}','JobcardController@closeJobcard');
Route::post('generate-job','JobcardController@generateJobcard');
Route::apiResource('machines','MachineController');
Route::get('import-machines','MachineController@importMachines');
Route::apiResource('users','UserController');
Route::apiResource('track-by','TrackByController');
Route::apiResource('service-types','ServiceTypeController');
Route::apiResource('fuel-balance','FuelBalanceController');
Route::apiResource('expense','ExpensesController');
Route::apiResource('fuel','FuelController');
Route::apiResource('fuel-types','FuelTypeController');
Route::apiResource('fuel-supplier','FuelSupplierController');
Route::apiResource('checklists','ChecklistsController');
Route::post('add-checklist-file','ChecklistsController@addFile');
Route::post('update-checklist-file/{id}','ChecklistsController@updateFile');
Route::post('remove-checklist-file','ChecklistsController@removeFile');
Route::apiResource('expiry-types','ExpiryDatesController');
Route::apiResource('assign-checklist','AssignChecklistController');
Route::apiResource('external-services','ExternalServiceController');
Route::apiResource('parts','PartsController');
Route::apiResource('categories','CategoriesController');
Route::apiResource('warehouse','WarehouseController');
Route::get('import-parts','PartsController@importParts');
Route::apiResource('projects','ProjectController');
Route::apiResource('customers','CustomerController');
Route::apiResource('customer-types','CustomerTypeController');
Route::apiResource('jobcard-category','JobcardCategoryController');
Route::apiResource('mechanics','MechanicsController');
Route::apiResource('requisitions','RequisitionController');
Route::apiResource('checklist-tool','ChecklistToolController');
Route::post('generate-requisition','RequisitionController@autoGenerate');
Route::get('item-requisition','RequisitionController@itemRequisitions');
Route::apiResource('stk-groups','StkCategoryController');
Route::apiResource('job-types','JobTypesController');
Route::apiResource('transactions','TransactionController');
Route::apiResource('asset-category','AssetCategoryController');
Route::get('import-projects','ProjectController@importProjects');
Route::get('import-customers','CustomerController@importCustomers');
Route::post('job-report','ReportsController@JobReport');
Route::post('fuel-report','ReportsController@FuelReport');
Route::post('km-per-hr','ReportsController@KmPerHr');
Route::post('diesel-analysis','ReportsController@DieselAnalysis');

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});
