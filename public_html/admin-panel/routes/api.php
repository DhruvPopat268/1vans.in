<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EngineerController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\ApiController;
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
Route::post('login', [ApiController::class, 'login']);
Route::post('engineer/login', [\App\Http\Controllers\Api\LoginController::class, 'engineerlogin']);
Route::get('/material/stock/pdf/{id}', [\App\Http\Controllers\MaterialAnalysisController::class, 'downloadMaterialPdfApplication']);
Route::get('/material/purchase-order/pdf/{id}', [\App\Http\Controllers\MaterialAnalysisController::class, 'downloadMaterialPurchaseOrderPdfApplication']);
                Route::get('/equipment/form/pdf/{id}', [\App\Http\Controllers\EquipmentController::class, 'downloadPdfApplication']);
                Route::get('/site-report/pdf/{id}', [\App\Http\Controllers\SiteReportController::class, 'downloadSiteReportPdf']);
Route::get('daily-report/pdf/{id}', [\App\Http\Controllers\DailyReportController::class, 'downloaReportdPdf']);
Route::get('work-issue/pdf/{id}', [\App\Http\Controllers\WorkIssueController::class, 'downloaWorkIssuedPdfApplication']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    


    Route::post('logout', [ApiController::class, 'logout']);
    Route::get('get-projects', [EngineerController::class, 'getUserProjects']);
    Route::get('get-user-access', [EngineerController::class, 'getUserAccess']);
    Route::post('/project-equipments', [EngineerController::class, 'getEquipmentsByProject']);
        Route::post('/equipment-form/basic', [EngineerController::class, 'storeFormBasic']);
    Route::post('/equipment-form/details', [EngineerController::class, 'storeFormDetails']);
    Route::post('/equipment-form/report', [EngineerController::class, 'fetchFormDataByProject']);
        Route::post('/get-material-category', [EngineerController::class, 'getMaterialCategoryByProject']);
    Route::post('/get-material-sub-category', [EngineerController::class, 'getMaterialSubCategoryByCategory']);
        Route::post('/material-form/basic', [EngineerController::class, 'storeMaterialFormBasic']);
    Route::post('/material-form/stock', [EngineerController::class, 'storeMaterialStock']);
    Route::post('/material-incoming/report', [EngineerController::class, 'fetchMaterialIncomingByProject']);
        Route::post('/store/work-issue', [EngineerController::class, 'storeworkIssue']);
    Route::post('/get/work-issue', [EngineerController::class, 'getWorkIssueByProject']);
        Route::get('/get/user/profile', [EngineerController::class, 'getUserProfile']);
        Route::post('/get/project-document/category', [EngineerController::class, 'projectdocumentGetCategory']);
    Route::post('/project-document/attachment', [EngineerController::class, 'projectdocumentuploadAttachments']);
    Route::post('/get/project-document/attachment', [EngineerController::class, 'getprojectdocumentAttachments']);
        Route::post('/material/purchase-order/basic', [EngineerController::class, 'storeMaterialPurchaseOrderBasic']);
    Route::post('/material/purchase-order/stock', [EngineerController::class, 'storeMaterialPurchaseOrderStock']);
        Route::post('/get-material-purchase-order', [EngineerController::class, 'fetchMaterialPurchaseOrderByProject']);
                Route::post('/get/project-testing-report/category', [EngineerController::class, 'projectTestingReportGetCategory']);
    Route::post('/project-testing-report/attachment', [EngineerController::class, 'projectTestingReportuploadAttachments']);
    Route::post('/get/project-testing-report/attachment', [EngineerController::class, 'getprojectTestingReportAttachments']);
                Route::post('/get/working-drawing/category', [EngineerController::class, 'workingDrawingsGetCategory']);
    Route::post('/working-drawing/attachment', [EngineerController::class, 'workingDrawingsuploadAttachments']);
    Route::post('/get/working-drawing/attachment', [EngineerController::class, 'getworkingDrawingsAttachments']);
            Route::post('/bill-of-quantity/attachment', [EngineerController::class, 'billOfQuantityuploadAttachments']);
    Route::post('/get/bill-of-quantity/attachment', [EngineerController::class, 'billOfQuantityAttachments']);
    
            Route::post('/get/main-category-list', [EngineerController::class, 'getMainCategoryList']);

    
        Route::post('/get/name-of-work', [EngineerController::class, 'getNameOfWorkList']);
    Route::post('/get/unit/sub-category', [EngineerController::class, 'getUnitSubCategoryList']);
    Route::post('/get/man-power', [EngineerController::class, 'getManPowerkList']);
    Route::post('/get/mesurement-attribute', [EngineerController::class, 'getMesurementAttributeList']);
     Route::post('/store/daily-report/form/basic', [EngineerController::class, 'dailyReportformBasic']);
    Route::post('/store/daily-report/man-power', [EngineerController::class, 'storedailyReportManPower']);
    Route::post('/store/daily-report/material-used-stock', [EngineerController::class, 'storedailyReportMaterialStock']);
    Route::post('/store/daily-report/mesurement', [EngineerController::class, 'storedailyReportMesurement']);
    Route::post('/store/daily-report/equipments', [EngineerController::class, 'storedailyReportEquipments']);
    Route::post('/get/project-master-data', [EngineerController::class, 'getProjectMasterData']);

         Route::post('/get/wings', [EngineerController::class, 'getwingsList']);
      Route::post('/get/flours', [EngineerController::class, 'getflourList']);
      Route::post('/get/site/gallery', [EngineerController::class, 'getsitegalleryimages']);
          Route::post('/upload/profile/logo/image', [EngineerController::class, 'uploadLogoimage']);


Route::post('/get/daily-report-data', [EngineerController::class, 'getDailyReportByProject']);
          Route::post('/get/todo/task/category', [EngineerController::class, 'getToDoListByProject']);
          Route::post('/todo/list/task/category/create', [EngineerController::class, 'ToDoListFolderstore']);
          Route::post('/todo/list/task/create', [EngineerController::class, 'ToDoTaskstore']);
          Route::post('/get/todo/task', [EngineerController::class, 'getToDoTaskByEngineer']);
          Route::post('/update/todo/task', [EngineerController::class, 'updateToDoTask']);
                    Route::post('/update/todo/task/status', [EngineerController::class, 'updateToDoTaskStatus']);
                    
                     Route::get('/get/notification', [EngineerController::class, 'getEngineerNotification']);
                     Route::post('/notification/mark-as-read', [EngineerController::class, 'EngineerNotificationmarkAsRead']);
Route::post('/engineer-notification/delete', [EngineerController::class, 'deleteNotification']);
Route::post('/get/auto-dex/folder', [EngineerController::class, 'getAutoDexByProject']);
Route::post('/auto-dex/folder/create', [EngineerController::class, 'AutoDeskFolderstore']);
Route::post('/get/auto-dex/attachment', [EngineerController::class, 'getAutoDeskAttachment']);
Route::post('/auto-dex/attachment/upload', [EngineerController::class, 'AutoDexAttachmentUpload']);

Route::post('/engineer/attendance/mark', [EngineerController::class, 'engineerAttendancestore']);
Route::post('/engineer/attendance/latest', [EngineerController::class, 'getLatestEngineerAttendance']);

Route::post('/site-report/store', [EngineerController::class, 'storeSiteReport']);
Route::post('/get/site-report', [EngineerController::class, 'getSiteReportByProject']);


    Route::post('add-tracker', [ApiController::class, 'addTracker']);
    Route::post('stop-tracker', [ApiController::class, 'stopTracker']);
    Route::post('upload-photos', [ApiController::class, 'uploadImage']);
});
