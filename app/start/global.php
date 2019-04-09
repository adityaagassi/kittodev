<?php

use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/database/seeds',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path().'/logs/laravel.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function(Exception $exception, $code)
{
	Log::error($exception);
});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function()
{
	return Response::make("Be right back!", 503);
});

// App::error(function($exception, $code) {
//     return View::make('404');
// });

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require app_path().'/filters.php';


/*
|--------------------------------------------------------------------------
| Cron Job
|--------------------------------------------------------------------------
*/

// Reference Cron
// https://github.com/liebig/cron
// Laravel 4
// Set Time
// https://crontab.guru/

Event::listen('cron.collectJobs', function() {
	Cron::add('1', '57 19 * * *', function() {

        // Do some crazy things successfully every minute
        // Resume and Upload Completion

        $batchOutputController = new BatchOutputController();
        $resumeCompletionResponse = $batchOutputController->resumeCompletion();
        $resumeTransferResponse = $batchOutputController->resumeTransfer();

        // BatchOutputController::resumeCompletion();
        // Resume and Upload Transfer
        // BatchOutputController::resumeTransfer();
        // Download Import Error Report
        // ErrorReportController::importErrorReports();
        return 'Run resume completion and transfer';
    });

	Cron::setEnablePreventOverlapping();
	$report = Cron::run();
	var_dump($report);
	/*
	Cron::add('example2', '* * * * *', function() {
        // Do some crazy things successfully every minute
        BatchOutputController::resumeTransfer();
        return true;
    });
	*/

	// Disable Cron by ID
	// Code
	// Cron::setDisableJob(cronID);
	// Example
	// Cron::setDisableJob('1');

	// Enable Cron by ID
	// Code
	// Cron::setEnableJob(cronID);
	// Example
	// Cron::setEnableJob('1');

	// Run Cron
	// Cron::run();
});


Event::listen('cron.jobSuccess', function($name, $runtime, $rundate) {
	Log::error('Job success with the name ' . $name . ' success ' . $runtime . ' ' .$rundate);
});

Event::listen('cron.afterRun', function($rundate, $inTime, $runtime, $errors) {
	// Log::error('Job with the name ' . $name . ' success ' . $runtime . ' ' .$rundate);
	Log::error('Job after run. ' . $rundate . " " . $inTime . " " . $runtime . " " . $errors);
});

Event::listen('cron.jobError', function($name, $return, $runtime, $rundate){
	Log::error('Job error with the name ' . $name . ' returned an error.' . $return . ' ' . $runtime . ' '. $rundate);
});