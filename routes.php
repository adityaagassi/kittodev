<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'OperatorController@openOperatorPage');
Route::get('/upload', 'BatchOutputController@resumeHistories');
Route::get('/download', 'ErrorReportController@importErrorReports');

Route::get('/login', function() {
	return Redirect::route('signin');
});
Route::get('/signin', array('as' => 'signin', 'uses' => 'UserController@openSigninPage'));
Route::post('/signin', 'UserController@userSignin');
Route::get('/logout', 'UserController@userSignout');
Route::get('/signout', 'UserController@userSignout');
	

Route::get('/completions', array('as' => 'completions', 'uses' => 'CompletionController@openCompletionPage'));
Route::post('/completions', array('as' => 'scanCompletions', 'uses' => 'CompletionController@completionItem'));

Route::get('/transfers', array('as' => 'transfers', 'uses' => 'TransferController@openTransferPage'));
Route::post('/transfers', array('as' => 'scanTransfers', 'uses' => 'TransferController@transferItem'));



Route::group(array('before' => 'auth'), function() {

	// Dashboard
	Route::get('/dashboard', array('as' => 'dashboard', 'uses' => 'DashboardController@openDashboardPage'));

	/**
	 * Error Reports
	 */
	
	Route::get('/errorreport/import', array('as' => 'importerrorreport', 'uses' => 'ErrorReportController@importErrorReports'));
	Route::get('/errorreport', array('as' => 'errorreport', 'uses' => 'ErrorReportController@openListPage'));
	Route::get('/errorreport/{filename}/{id}/edit', 'ErrorReportController@openEditPage');
	Route::post('/errorreport/{filename}/{id}/edit', 'ErrorReportController@updateErrorReport');
	Route::get('/errorreport/{filename}/{id}/delete', 'ErrorReportController@deleteErrorReport');
	Route::get('/errorreport/{filename}', array('as' => 'detailerrorreport', 'uses' => 'ErrorReportController@openDetailPage'));

	/**
	 * Materials
	 */
	
	Route::get('/materials/export', 'MaterialController@exportToExcel');
	Route::post('/materials/import', 'MaterialController@importFromExcel');
	Route::get('/materials', array('as' => 'materials', 'uses' => 'MaterialController@openListPage'));
	Route::get('/materials/add', array('as' => 'addMaterials', 'uses' => 'MaterialController@openAddPage'));
	Route::post('/materials/add', 'MaterialController@createMaterial');
	Route::get('/materials/{id}', array('as' => 'detailMaterial', 'uses' => 'MaterialController@openDetailPage'));
	Route::get('/materials/{id}/edit', array('as' => 'editMaterial', 'uses' => 'MaterialController@openEditPage'));
	Route::post('/materials/{id}/edit','MaterialController@updateMaterial');
	Route::get('/materials/{id}/delete', 'MaterialController@deleteMaterial');

	/**
	 * Inventories
	 */
	Route::get('/inventories/list', array('as' => 'inventories', 'uses' => 'InventoryController@openListPage'));
	Route::get('/inventories/export', 'InventoryController@exportToExcel');

	/**
	 * Completions
	 */
	
	Route::get('/completions/export', 'CompletionController@exportToExcel');
	Route::post('/completions/import', 'CompletionController@importFromExcel');
	Route::get('/completions/adjustment', array('as' => 'adjustmentCompletions', 'uses' => 'CompletionController@openAdjustmentPage'));
	Route::get('/completions/adjustment/excel', 'CompletionController@openAdjustmentExcelPage');
	Route::post('/completions/adjustment/excel', 'CompletionController@completionAdjustmentFromExcel');
	Route::get('/completions/adjustment/manual', 'CompletionController@openAdjustmentManualPage');
	Route::post('/completions/adjustment/manual', 'CompletionController@completionAdjustmentManual');
	Route::get('/completions/cancel', array('as' => 'cancelCompletions', 'uses' => 'CompletionController@openCancelPage'));
	Route::post('/completions/cancel', 'CompletionController@cancelCompletionItem');
	Route::get('/completions/filter', 'CompletionController@openHistoryPage');
	Route::get('/completions/history', 'CompletionController@filterCompletionHistory');
	Route::get('/completions/history/export', 'CompletionController@exportHistoryToExcel');
	Route::get('/completions/temporary', array('as' => 'temporaryCompletions', 'uses' => 'CompletionController@openTemporaryPage'));
	Route::get('/completions/temporary/{id}/delete', 'CompletionController@deleteCompletionTemporary');

	Route::get('/completions/list', array('as' => 'listcompletions', 'uses' => 'CompletionController@openListPage'));
	Route::get('/completions/add', array('as' => 'addCompletions', 'uses' => 'CompletionController@openAddPage'));
	Route::post('/completions/add', 'CompletionController@createCompletion');
	Route::get('/completions/{id}', array('as' => 'detailCompletion', 'uses' => 'CompletionController@openDetailPage'));
	Route::get('/completions/{id}/edit', array('as' => 'editCompletion', 'uses' => 'CompletionController@openEditPage'));
	Route::post('/completions/{id}/edit','CompletionController@updateCompletion');
	Route::get('/completions/{id}/delete', 'CompletionController@deleteCompletion');



	/**
	 * Transfers
	 */
	
	Route::get('/transfers/export', 'TransferController@exportToExcel');
	Route::post('/transfers/import', 'TransferController@importFromExcel');
	Route::get('/transfers/adjustment', array('as' => 'adjustmentTransfers', 'uses' => 'TransferController@openAdjustmentPage'));
	Route::get('/transfers/adjustment/excel', 'TransferController@openAdjustmentExcelPage');
	Route::post('/transfers/adjustment/excel', 'TransferController@transferAdjustmentFromExcel');
	Route::get('/transfers/adjustment/manual', 'TransferController@openAdjustmentManualPage');
	Route::post('/transfers/adjustment/manual', 'TransferController@transferAdjustmentManual');
	Route::get('/transfers/cancel', array('as' => 'cancelTransfers', 'uses' => 'TransferController@openCancelPage'));
	Route::post('/transfers/cancel', 'TransferController@cancelTransferItem');
	Route::get('/transfers/filter', 'TransferController@openHistoryPage');
	Route::get('/transfers/history', 'TransferController@filterTransferHistory');
	Route::get('/transfers/history/export', 'TransferController@exportHistoryToExcel');
	Route::get('/transfers/temporary', array('as' => 'temporaryTransfers', 'uses' => 'TransferController@openTemporaryPage'));
	Route::get('/transfers/temporary/{id}/delete', 'TransferController@deleteTransferTemporary');
	
	Route::get('/transfers/list', array('as' => 'listtransfers', 'uses' => 'TransferController@openListPage'));
	Route::get('/transfers/add', array('as' => 'addTransfers', 'uses' => 'TransferController@openAddPage'));
	Route::get('/transfers/add/{completion_id}', array('as' => 'addTransfersByCompletion', 'uses' => 'TransferController@openAddPageWithCompletion'));
	Route::post('/transfers/add', 'TransferController@createTransfer');
	Route::get('/transfers/{id}', array('as' => 'detailTransfer', 'uses' => 'TransferController@openDetailPage'));
	Route::get('/transfers/{id}/edit', array('as' => 'editTransfer', 'uses' => 'TransferController@openEditPage'));
	Route::post('/transfers/{id}/edit','TransferController@updateTransfer');
	Route::get('/transfers/{id}/delete', 'TransferController@deleteTransfer');

	/**
	 * Return
	 */

	Route::get('/returns/add', array('as' => 'addReturns', 'uses' => 'ReturnController@openCreatePage'));
	Route::post('/returns/add', 'ReturnController@createReturn');

	/**
	 * Repair
	 */

	Route::get('/repairs/add', array('as' => 'addRepairs', 'uses' => 'RepairController@openCreatePage'));
	Route::post('/repairs/add', 'RepairController@createRepair');

	/**
	 * Batch Output
	 */

	// Route::get('/cron','BatchOutputController@testCron');
	Route::get('/batchoutputs/history', 'BatchOutputController@openListPage');
	Route::get('/batchoutputs/completion', 'BatchOutputController@resumeCompletion');
	Route::get('/batchoutputs/transfer', 'BatchOutputController@resumeTransfer');
	Route::get('/uploadftp', 'BatchOutputController@uploadFTP');
	Route::get('/batchoutputs', array('as' => 'listbatchoutputs', 'uses' => 'BatchOutputController@openListPage'));
	Route::get('/batchoutputs/{id}', array('as' => 'detailBatchoutputs', 'uses' => 'BatchOutputController@openDetailPage'));

	/**
	 * Users
	 */
	
	Route::get('/users', array('as' => 'users', 'uses' => 'UserController@openListPage'));
	Route::get('/users/add', array('as' => 'addUsers', 'uses' => 'UserController@openAddPage'));
	Route::post('/users/add', 'UserController@createUser');
	Route::get('/users/{id}', array('as' => 'detailUser', 'uses' => 'UserController@openDetailPage'));
	Route::get('/users/{id}/edit', array('as' => 'editUser', 'uses' => 'UserController@openEditPage'));
	Route::post('/users/{id}/edit','UserController@updateUser');
	Route::get('/users/{id}/delete', 'UserController@deleteUser');

	/**
	 * Sessions
	 */
	
	Route::get('/sessions', array('as' => 'sessions', 'uses' => 'SessionController@openListPage'));
	Route::get('/sessions/{id}/delete', 'SessionController@deleteSession');

	/**
	 * Sessions
	 */
	
	Route::get('/settings', array('as' => 'settings', 'uses' => 'SettingController@openEditPage'));
	Route::post('/settings/{id}', 'SettingController@updateSetting');

});

/*
 * AJAX Request
 */

Route::group(array(
	// 'before' => 'auth', 
	'before' => 'auth',
	'prefix' => 'api/v1',
	'after' => 'allowOrigin'
), function() {

	Route::get('/materials', 'AjaxController@materialJSON');
	Route::get('/completions', 'AjaxController@completionJSON');
	Route::get('/transfers', 'AjaxController@transferJSON');

});