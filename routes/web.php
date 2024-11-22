<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HumanResourceDocumentController;
use App\Http\Controllers\Frontend\ClientController;
use App\Http\Controllers\Frontend\homeController as FrontendHomeController;
use App\Http\Controllers\LeaveController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\manageEmployeeController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\IncomingDocumentController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\viewEmployeeController;
use App\Http\Controllers\RequestDocumentController;
use App\Http\Middleware\IsEmployee;

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

// Website or Frontend
// Route::get('/', [FrontendHomeController::class, 'home'])->name('home');
Route::get('employees/archived', [manageEmployeeController::class, 'archivedEmployees'])->name('employees.archived');
        Route::post('employee/restore/{id}', [manageEmployeeController::class, 'restoreEmployee'])->name('employee.restore');
        Route::post('/employee/archive/{id}', [manageEmployeeController::class, 'archiveEmployee'])->name('employee.archive');

Route::get('/', [UserController::class, 'login'])->name('admin.login');
Route::get('/login/user/{email}', [UserController::class, 'updatePassword'])->name('user.updateLogin');
Route::post('/login-form', [UserController::class, 'loginPost'])->name('admin.login.post');

//register config admin
Route::get('/register', [UserController::class, 'register'])->name('admin.register');
Route::post('/register-admin', [UserController::class, 'registerPost'])->name('admin.register.post');
Route::get('/register-admin-verify/{id}', [UserController::class, 'registerVerify'])->name('register.confirm');

Route::group(['middleware' => 'auth'], function () {

    // Admin Routes (Accessible only by admin users)
    Route::group(['middleware' => ['IsAdmin']], function () {

        // // Employee Management
        // Route::get('/Employee/addEmployee', [manageEmployeeController::class, 'addEmployee'])->name('manageEmployee.addEmployee');
        // Route::post('/manageEmployee/addEmployee/store', [manageEmployeeController::class, 'store'])->name('manageEmployee.addEmployee.store');
        // Route::get('/Employee/viewEmployee', [viewEmployeeController::class, 'viewEmployee'])->name('manageEmployee.ViewEmployee');
        // Route::get('/Employee/delete/{id}', [viewEmployeeController::class, 'delete'])->name('Employee.delete');
        // Route::get('Employee/edit/{id}', [viewEmployeeController::class, 'edit'])->name('Employee.edit');
        // Route::put('/Employee/update/{id}', [viewEmployeeController::class, 'update'])->name('Employee.update');
        // Route::get('/Employee/profile/{id}', [viewEmployeeController::class, 'profile'])->name('Employee.profile');
        // Route::get('/search-employee', [viewEmployeeController::class, 'search'])->name('employee.search');


        // attendance
        Route::get('/Attendance/viewAttendance', [AttendanceController::class, 'attendanceList'])->name('attendance.viewAttendance');
        Route::get('/Attendance/AttendanceReport', [AttendanceController::class, 'attendanceReport'])->name('attendanceReport');
        Route::get('/Attendance/searchAttendanceReport', [AttendanceController::class, 'searchAttendanceReport'])->name('searchAttendanceReport');
        Route::get('/Attendance/delete/{id}', [AttendanceController::class, 'attendanceDelete'])->name('attendanceDelete');

        // department
        // Route::get('/Organization/department', [OrganizationController::class, 'department'])->name('organization.department');
        // Route::get('/Organization/department/archive', [OrganizationController::class, 'departmentArchive'])->name('organization.list.archive');
        // Route::post('/Organization/department/store', [OrganizationController::class, 'store'])->name('organization.department.store');
        // Route::get('/Organization/delete/{id}', [OrganizationController::class, 'delete'])->name('Organization.delete');
        // Route::get('/Organization/archive/{id}', [OrganizationController::class, 'archiveDepartment'])->name('Organization.archive');
        // Route::get('/Organization/edit/{id}', [OrganizationController::class, 'edit'])->name('Organization.edit');
        // Route::get('/Organization/restore/{id}', [OrganizationController::class, 'restoreDepartment'])->name('Organization.restore');
        // Route::put('/Organization/update/{id}', [OrganizationController::class, 'update'])->name('Organization.update');
        // Route::get('/Organization/Search/Department/Archive', [OrganizationController::class, 'searchArchiveDepartment'])->name('searchArchiveDepartment');
        // Route::get('/Organization/Search/{mode}', [OrganizationController::class, 'searchDepartment'])->name('searchDepartment');

        // designation
        Route::get('/Organization/designation', [DesignationController::class, 'designation'])->name('organization.designation');
        Route::post('/Organization/designation/store', [DesignationController::class, 'designationStore'])->name('organization.designation.store');
        Route::get('/Organization/designationList', [DesignationController::class, 'designationList'])->name('organization.designationList');
        Route::get('/designation/delete/{id}', [DesignationController::class, 'delete'])->name('designation.delete');
        Route::get('/designation/edit/{id}', [DesignationController::class, 'edit'])->name('designation.edit');
        Route::put('/designation/update/{id}', [DesignationController::class, 'update'])->name('designations.update');


        Route::get('/Designation/Search/Designation', [DesignationController::class, 'searchDesignation'])->name('searchDesignation');

        // Leave
        Route::get('/Leave/LeaveStatus', [LeaveController::class, 'leaveList'])->name('leave.leaveStatus');
        Route::delete('/leave/{leave}', [LeaveController::class, 'destroy'])->name('delete.leave');
        Route::get('/Leave/allLeaveReport', [LeaveController::class, 'allLeaveReport'])->name('allLeaveReport');

        // Approve,, Reject Leave
        Route::get('/leave/approve/{id}',  [LeaveController::class, 'approveLeave'])->name('leave.approve');
        Route::get('/leave/reject/{id}',  [LeaveController::class, 'rejectLeave'])->name('leave.reject');

        // Leave Type
        Route::get('/Leave/LeaveType', [LeaveController::class, 'leaveType'])->name('leave.leaveType');
        Route::post('/Leave/LeaveType/store', [LeaveController::class, 'leaveStore'])->name('leave.leaveType.store');
        Route::delete('/leave/leave-type/{id}', [LeaveController::class, 'LeaveDelete'])->name('leave.leaveType.destroy');
        Route::get('/LeaveType/edit/{id}', [LeaveController::class, 'leaveEdit'])->name('leave.leaveType.edit');
        Route::put('/leave/update/{id}', [LeaveController::class, 'LeaveUpdate'])->name('leave.leaveType.update');

        // Salary Structure
        Route::get('/SalaryStructure/createSalary', [SalaryController::class, 'createSalary'])->name('salary.create.form');
        Route::get('/SalaryStructure/viewSalary', [SalaryController::class, 'viewSalary'])->name('salary.view');
        Route::post('/Salary/store', [SalaryController::class, 'salaryStore'])->name('salary.store.data');
        Route::get('/Salary/delete/{id}', [SalaryController::class, 'salaryDelete'])->name('salaryDelete');
        Route::get('/Salary/edit/{id}', [SalaryController::class, 'salaryEdit'])->name('salaryEdit');
        Route::put('/Salary/update/{id}', [SalaryController::class, 'salaryUpdate'])->name('salaryUpdate');

        // Payroll
        Route::get('Payroll/createPayroll', [PayrollController::class, 'createPayroll'])->name('payroll.create');
        Route::get('/Payroll/PayrollList', [PayrollController::class, 'viewPayroll'])->name('payroll.view');
        Route::post('/Payroll/store', [PayrollController::class, 'payrollStore'])->name('payroll.store');
        Route::get('/Payroll/Single/{employee_id}/{month}', [PayrollController::class, 'singlePayroll'])->name('singlePayroll');
        Route::get('/Payroll/allPayrollList', [PayrollController::class, 'allPayroll'])->name('allPayrollList');
        Route::get('/Payroll/delete/{id}', [PayrollController::class, 'deletePayroll'])->name('payrollDelete');
        Route::get('/Payroll/edit/{id}', [PayrollController::class, 'payrollEdit'])->name('payrollEdit');
        Route::put('/Payroll/update/{id}', [PayrollController::class, 'payrollUpdate'])->name('payrollUpdate');
        Route::get('/search-AllPayroll', [PayrollController::class, 'searchAllPayroll'])->name('searchAllPayroll');


        // Task Management
        Route::get('/Task/createTask', [TaskController::class, 'createTask'])->name('createTask');
        Route::post('/Task/store', [TaskController::class, 'storeTask'])->name('storeTask');
        Route::get('/Task/TaskList', [TaskController::class, 'taskList'])->name('taskList');
        Route::get('/Task/delete/{id}', [TaskController::class, 'deleteTask'])->name('deleteTask');
        Route::get('/Task/edit/{id}', [TaskController::class, 'editTask'])->name('editTask');
        Route::put('/Task/update/{id}', [TaskController::class, 'updateTask'])->name('updateTask');
        Route::get('/Task/Search', [TaskController::class, 'searchTask'])->name('searchTask');


        // User updated
        // Route::get('/users', [UserController::class, 'list'])->name('users.list');
        // Route::get('/users/create', [UserController::class, 'createForm'])->name('users.create');
        // Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
        // Route::get('/users/{id}', [UserController::class, 'userProfile'])->name('users.profile.view');
        Route::get('/user/delete/{id}', [UserController::class, 'userDelete'])->name('delete');
        Route::get('/user/approve/{id}', [UserController::class, 'userApprove'])->name('approve.user');
        // Route::get('/user/edit/{id}', [UserController::class, 'userEdit'])->name('edit');
        // Route::put('/user/update/{id}', [UserController::class, 'userUpdate'])->name('update');
        // Route::get('/search-user', [UserController::class, 'searchUser'])->name('searchUser');


        // Services
        Route::get('/Service/create', [ServicesController::class, 'serviceForm'])->name('service.form');
        Route::post('/Service/store', [ServicesController::class, 'serviceStore'])->name('service.store');
        Route::get('/Service/serviceList', [ServicesController::class, 'serviceList'])->name('list.service');
        Route::get('/Service/serviceDelete/{id}', [ServicesController::class, 'serviceDelete'])->name('serviceDelete');
        Route::get('/Service/serviceEdit/{id}', [ServicesController::class, 'serviceEdit'])->name('serviceEdit');
        Route::put('/Service/serviceUpdate/{id}', [ServicesController::class, 'serviceUpdate'])->name('serviceUpdate');


        // Client List
        Route::get('/Client/create', [ClientController::class, 'clientForm'])->name('client.form');
        Route::post('/Client/store', [ClientController::class, 'clientStore'])->name('clientStore');
        Route::get('/Client/ClientList', [ClientController::class, 'viewClientList'])->name('viewClientList');
        Route::get('/Client/clientDelete/{id}', [ClientController::class, 'clientDelete'])->name('clientDelete');
        Route::get('/Client/clientEdit/{id}', [ClientController::class, 'clientEdit'])->name('clientEdit');
        Route::put('/Client/clientUpdate/{id}', [ClientController::class, 'clientUpdate'])->name('clientUpdate');





        // Notice Section

        // Route::get('/notice', [FrontendHomeController::class, 'notice'])->name('notice.create');
        // Route::post('/notice/store', [FrontendHomeController::class, 'noticeStore'])->name('notice.store');
        // Route::get('/notice', [FrontendHomeController::class, 'showNotice'])->name('show.notice');
    });

    Route::group(['middleware'=>['IsAdminOrSystemAdmin']],function(){
        // User updated
        Route::get('employees/archived', [manageEmployeeController::class, 'archivedEmployees'])->name('employees.archived');
        Route::post('employee/restore/{id}', [manageEmployeeController::class, 'restoreEmployee'])->name('employee.restore');
        Route::post('/employee/archive/{id}', [manageEmployeeController::class, 'archiveEmployee'])->name('employee.archive');


        
         Route::get('/users/create', [UserController::class, 'createForm'])->name('users.create');
         Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
         Route::get('/search-user', [UserController::class, 'searchUser'])->name('searchUser');
         Route::get('/Organization/department', [OrganizationController::class, 'department'])->name('organization.department');
         Route::get('/Organization/department/archive', [OrganizationController::class, 'departmentArchive'])->name('organization.list.archive');
         Route::post('/Organization/department/store', [OrganizationController::class, 'store'])->name('organization.department.store');
         Route::get('/Organization/delete/{id}', [OrganizationController::class, 'delete'])->name('Organization.delete');
         Route::get('/Organization/archive/{id}', [OrganizationController::class, 'archiveDepartment'])->name('Organization.archive');
         Route::get('/Organization/edit/{id}', [OrganizationController::class, 'edit'])->name('Organization.edit');
         Route::get('/Organization/restore/{id}', [OrganizationController::class, 'restoreDepartment'])->name('Organization.restore');
         Route::put('/Organization/update/{id}', [OrganizationController::class, 'update'])->name('Organization.update');
         Route::get('/Organization/Search/Department/Archive', [OrganizationController::class, 'searchArchiveDepartment'])->name('searchArchiveDepartment');
         Route::get('/Organization/Search/{mode}', [OrganizationController::class, 'searchDepartment'])->name('searchDepartment');
 
           // Leave
           Route::get('/Leave/LeaveStatus', [LeaveController::class, 'leaveList'])->name('leave.leaveStatus');
           Route::get('/Leave/allLeaveReport', [LeaveController::class, 'allLeaveReport'])->name('allLeaveReport');

           // Approve,, Reject Leave
           Route::get('/leave/approve/{id}',  [LeaveController::class, 'approveLeave'])->name('leave.approve');
           Route::get('/leave/reject/{id}',  [LeaveController::class, 'rejectLeave'])->name('leave.reject');

           // Leave Type
           Route::get('/Leave/LeaveType', [LeaveController::class, 'leaveType'])->name('leave.leaveType');
           Route::post('/Leave/LeaveType/store', [LeaveController::class, 'leaveStore'])->name('leave.leaveType.store');
           Route::get('/LeaveType/delete/{id}', [LeaveController::class, 'LeaveDelete'])->name('leave.leaveType.delete');
           Route::get('/LeaveType/edit/{id}', [LeaveController::class, 'leaveEdit'])->name('leave.leaveType.edit');
           Route::put('/leave/update/{id}', [LeaveController::class, 'LeaveUpdate'])->name('leave.leaveType.update');

           // HR Documents
            Route::resource('hrdocuments', HumanResourceDocumentController::class); 
            Route::get('hrdocuments', [HumanResourceDocumentController::class, 'index'])->name('admin.pages.hrdocuments.index');
            Route::post('/hrdocuments', [HumanResourceDocumentController::class, 'store'])->name('hrdocuments.store');

            Route::put('hrdocuments/{id}', [HumanResourceDocumentController::class, 'update'])->name('hrdocuments.update');
            Route::post('hrdocuments/{id}/restore', [HumanResourceDocumentController::class, 'restore'])->name('hrdocuments.restore');
            Route::get('archived-documents', [HumanResourceDocumentController::class, 'archivedDocuments'])->name('archived-documents');
            Route::delete('hrdocuments/permanent-delete/{id}', [HumanResourceDocumentController::class, 'permanentDelete'])->name('hrdocuments.permanentDelete');


           //Document Type
           Route::get('/Document/DocumentType', [DocumentController::class, 'documentType'])->name('document.documentType');
           Route::get('/Document/DocumentRequest', [DocumentController::class, 'documentRequest'])->name('document.documentStatus');
           Route::get('/Document/DocumentRequest/Archive', [DocumentController::class, 'documentArchive'])->name('document.document.archive');
           Route::get('/Document/DocumentList', [DocumentController::class, 'documentList'])->name('document.documentList');
           Route::post('/Document/DocumentType/store', [DocumentController::class, 'documentStore'])->name('document.documentType.store');
           Route::get('/DocumentType/delete/{id}', [DocumentController::class, 'documentDelete'])->name('document.documentType.delete');
           Route::get('/DocumentType/edit/{id}', [DocumentController::class, 'documentEdit'])->name('document.documentType.edit');
           Route::put('/DocumentType/update/{id}', [DocumentController::class, 'documentUpdate'])->name('document.documentType.update');


           Route::post('/Document/process/{id}', [DocumentController::class, 'processDocument'])->name('process.Document');
           Route::post('/Document/reject/{id}', [DocumentController::class, 'rejectDocument'])->name('reject.Document');

           Route::post('/Leave/process/{id}', [LeaveController::class, 'processDocument'])->name('process.Leave');
           Route::post('/Leave/reject/{id}', [LeaveController::class, 'rejectDocument'])->name('reject.Leave');

           //Status Type
           Route::get('/Status/StatusType', [StatusController::class, 'statusType'])->name('status.list');
           Route::get('/Status/edit/{id}', [StatusController::class, 'statusEdit'])->name('status.edit');
           Route::post('/Status/StatusType/store', [StatusController::class, 'statusStore'])->name('status.store');
           Route::put('Status/StatusType/update/{id}', [StatusController::class, 'statusUpdate'])->name('status.update');

           Route::get('/Status/delete/{id}', [StatusController::class, 'statusDelete'])->name('status.delete');
           Route::get('/Status/search', [StatusController::class, 'statusSearch'])->name('searchStatus');
           //Archive
            Route::get('/Status/archive', [StatusController::class, 'statusArchive'])->name('status.list.archive');
            Route::get('Status/archive', [StatusController::class, 'archived'])->name('status.archive');
            Route::get('/Status/restore/{id}', [StatusController::class, 'restoreStatus'])->name('status.restore');
            Route::get('/status/Search/Archive', [StatusController::class, 'searchArchiveStatus'])->name('searchArchiveStatus');


              // Employee Management
        Route::get('/Employee/addEmployee', [manageEmployeeController::class, 'addEmployee'])->name('manageEmployee.addEmployee');
        Route::post('/manageEmployee/addEmployee/store', [manageEmployeeController::class, 'store'])->name('manageEmployee.addEmployee.store');
        Route::get('/Employee/viewEmployee', [viewEmployeeController::class, 'viewEmployee'])->name('manageEmployee.ViewEmployee');
        Route::delete('/Employee/delete/{id}', [viewEmployeeController::class, 'delete'])->name('Employee.delete');
        Route::get('/Employee/profile/{id}', [viewEmployeeController::class, 'profile'])->name('Employee.profile');
        Route::get('/search-employee', [viewEmployeeController::class, 'search'])->name('employee.search');


   });


    Route::group(['middleware'=>['SharedInThreeRole']],function(){
         // User updated
        Route::get('Employee/edit/{id}', [viewEmployeeController::class, 'edit'])->name('Employee.edit');
        Route::put('/Employee/update/{id}', [viewEmployeeController::class, 'update'])->name('Employee.update');
         Route::get('/users', [UserController::class, 'list'])->name('users.list');
         Route::get('/users/{id}', [UserController::class, 'userProfile'])->name('users.profile.view');
         Route::get('/user/edit/{id}', [UserController::class, 'userEdit'])->name('edit');
         Route::put('/user/update/{id}', [UserController::class, 'userUpdate'])->name('update');
         Route::get('user/{id}/reset-password', [UserController::class, 'resetPassword'])->name('reset.confirm.password');

         Route::get('/Leave/LeaveType', [LeaveController::class, 'leaveType'])->name('leave.leaveType');
        Route::post('/Leave/LeaveType/store', [LeaveController::class, 'leaveStore'])->name('leave.leaveType.store');
        Route::post('/LeaveType/delete/{id}', [LeaveController::class, 'LeaveDelete'])->name('leave.leaveType.delete');
        Route::get('/LeaveType/edit/{id}', [LeaveController::class, 'leaveEdit'])->name('leave.leaveType.edit');
        Route::put('/leave/update/{id}', [LeaveController::class, 'LeaveUpdate'])->name('leave.leaveType.update');

        //Document Type
        Route::get('/Document/DocumentType', [DocumentController::class, 'documentType'])->name('document.documentType');
        Route::get('/Document/DocumentRequest', [DocumentController::class, 'documentRequest'])->name('document.documentStatus');
        Route::get('/Document/DocumentRequest/Archive', [DocumentController::class, 'documentArchive'])->name('document.document.archive');
        Route::get('/Document/DocumentList', [DocumentController::class, 'documentList'])->name('document.documentList');
        Route::post('/Document/DocumentType/store', [DocumentController::class, 'documentStore'])->name('document.documentType.store');
        Route::delete('/DocumentType/delete/{id}', [DocumentController::class, 'documentDelete'])->name('document.documentType.delete');
        Route::get('/DocumentType/edit/{id}', [DocumentController::class, 'documentEdit'])->name('document.documentType.edit');
        Route::put('/DocumentType/update/{id}', [DocumentController::class, 'documentUpdate'])->name('document.documentType.update');




         // Request form
         Route::get('/request/document/{IsPreview}', [RequestController::class, 'requestDocForm'])->name('request.documentForm');

         //Leave Form
         Route::get('/leave/document/{IsPreview}', [LeaveController::class, 'leaveDocForm'])->name('leave.documentForm');

         //Document
         Route::post('/request/document/store', [DocumentController::class, 'submitDocumentRequest'])->name('request.documentForm.submit');

         //Leave
         Route::post('/leave/document/store', [LeaveController::class, 'submitDocumentLeave'])->name('leave.documentForm.submit');

        //Search Leave / Document
        Route::get('/searchLeaveList', [LeaveController::class, 'searchLeaveList'])->name('searchLeaveList');

        Route::get('/searchLeaveList', [LeaveController::class, 'searchLeaveDashList'])->name('searchLeaveList.dash');

        Route::get('/searchIncomingList', [IncomingDocumentController::class, 'searchIncomingDashList'])->name('searchIncomingList.dash');

        Route::get('/searchDocumentList', [DocumentController::class, 'searchDocumentList'])->name('searchDocumentList');

        //Search form
        Route::get('/searchDocumentFormList', [DocumentController::class, 'searchFormDocumentList'])->name('searchFormDocumentList');

        Route::get('/searchFormDocumentTypeList', [DocumentController::class, 'documentTypeSearch'])->name('searchFormDocumentTypeList');

        Route::get('/searchFormLeaveTypeList', [LeaveController::class, 'leaveTypeSearch'])->name('leaveFormDocumentTypeList');

        Route::get('/searchLeaveFormList', [LeaveController::class, 'searchFormLeaveList'])->name('searchFormLeaveList');


            //Request Config
            Route::get('/request/requestform/config', [RequestController::class, 'requestDocConfig'])->name('request.config');

            Route::get('/request/requestForm/setVisibleOrInvisible/{fieldName}', [RequestController::class, 'setVisibleInvisible'])->name('request.setVisible');
    
            Route::get('/request/search/config', [RequestController::class, 'searchDocConfig'])->name('search.config');
    
            //Leave Config
            Route::get('/leave/leaveform/config', [LeaveController::class, 'leaveDocConfig'])->name('leave.config');
    
            Route::get('/leave/search/config', [LeaveController::class, 'searchDocConfig'])->name('search.leave.config');
    
            Route::get('/leave/leaveForm/setVisibleOrInvisible/{fieldName}', [LeaveController::class, 'setVisibleInvisible'])->name('leave.setVisible');
    

            //Incoming document copy summary-list
            Route::get('/incoming-docs/list', [IncomingDocumentController::class, 'incomingList'])->name('incoming.list');
            Route::patch('incoming/document/updateStatus/{id}', [IncomingDocumentController::class, 'updatestatus'])->name('incoming.document.updateStatus');
            Route::delete('incoming/document/delete/{id}', [IncomingDocumentController::class, 'delete'])->name('incoming.document.delete');
            Route::get('/incoming-docs/search', [IncomingDocumentController::class, 'incomingdocsSearch'])->name('incoming.list.search');

             //Incoming document copy config
             Route::get('/incoming-docs/form/config', [IncomingDocumentController::class, 'incomingConfig'])->name('incoming.config');
    
             Route::get('/incoming-docs/search/config', [IncomingDocumentController::class, 'searchDocConfig'])->name('search.incoming.config');
     
             Route::get('/incoming/incoming-docs/setVisibleOrInvisible/{fieldName}', [IncomingDocumentController::class, 'setVisibleInvisible'])->name('incoming.setVisible');
 
             //form incoming preview

             Route::get('/incoming/document/copy/{IsPreview}', [IncomingDocumentController::class, 'incomingDocForm'])->name('incoming.documentForm');

             //form incoming submit
             Route::post('/incoming/document/copy/submit', [IncomingDocumentController::class, 'sendDocumentCopy'])->name('incoming.documentForm.submit');

             Route::post('/incoming/document/copy/reject/{id}', [IncomingDocumentController::class, 'rejectDocumentCopy'])->name('reject.incoming');

             Route::post('/incoming/document/copy/process/{id}', [IncomingDocumentController::class, 'processDocument'])->name('process.incoming');

             

             //action list
            Route::get('/incoming-docs/actions/list', [IncomingDocumentController::class, 'actionList'])->name('incoming.action.list');

            Route::post('/incoming-docs/actions/list/store', [IncomingDocumentController::class, 'storeAction'])->name('action.actionType.store');

            Route::get('/incoming-docs/actions/list/edit/{id}', [IncomingDocumentController::class, 'actionEdit'])->name('action.actionType.edit');

            Route::put('/incoming-docs/actions/list/update/{id}', [IncomingDocumentController::class, 'actionUpdate'])->name('action.actionType.update');

            Route::delete('/incoming-docs/actions/list/delete/{id}', [IncomingDocumentController::class, 'actionDelete'])->name('action.actionType.delete');

 



    });

    // Employee route
    Route::group(['middleware' => [ 'IsEmployee']], function () {

        // Attendance Routes for Employee
        Route::get('/Attendance/giveAttendance', [AttendanceController::class, 'giveAttendance'])->name('attendance.giveAttendance');
        Route::get('/check-in', [AttendanceController::class, 'checkIn'])->name('check-in');
        Route::get('/check-out', [AttendanceController::class, 'checkOut'])->name('check-out');
        Route::get('/attendance/myAttendance', [AttendanceController::class, 'myAttendance'])->name('attendance.myAttendance');
        Route::get('/attendance/myAttendanceReport', [AttendanceController::class, 'myAttendanceReport'])->name('myAttendanceReport');
        Route::get('/attendance/searchMyAttendance', [AttendanceController::class, 'searchMyAttendance'])->name('searchMyAttendance');


        // Leave Routes for Employee
        Route::get('/Leave/LeaveForm', [LeaveController::class, 'leave'])->name('leave.leaveForm');
        Route::post('/Leave/store', [LeaveController::class, 'store'])->name('leave.store');
        Route::get('/Leave/myLeave', [LeaveController::class, 'myLeave'])->name('leave.myLeave');
        Route::get('/Leave/myLeaveBalance', [LeaveController::class, 'showLeaveBalance'])->name('leave.myLeaveBalance');
        Route::get('/Leave/myLeaveReport', [LeaveController::class, 'myLeaveReport'])->name('myLeaveReport');
        Route::get('/searchMyLeave', [LeaveController::class, 'searchMyLeave'])->name('searchMyLeave');


        // My Task
        Route::get('/Task/MyTask', [TaskController::class, 'myTask'])->name('myTask');
        // Task Complete
        Route::get('/Task/CompleteInTime/{id}',  [TaskController::class, 'completeTaskOnTime'])->name('taskComplete');
        Route::get('/Task/CompleteInLate/{id}',  [TaskController::class, 'completeTaskLate'])->name('taskCompleteLate');



        // user profile
        Route::get('/myProfile', [UserController::class, 'myProfile'])->name('profile');

        // payroll
        Route::get('/Payroll/MyPayrollList', [PayrollController::class, 'myPayroll'])->name('myPayroll');
        Route::get('/Payroll/mySingle/{employeeID}/{month}', [PayrollController::class, 'MySingle'])->name('mySinglePayroll');
        Route::get('/search-myPayroll', [PayrollController::class, 'searchMyPayroll'])->name('searchMyPayroll');


        // Notices for Employee
        // Route::get('/notice', [FrontendHomeController::class, 'showNotice'])->name('show.notice');
        // ... Additional Employee-specific routes
    });

    Route::get('/logout', [UserController::class, 'logout'])->name('admin.logout');
    Route::get('/dashboard', [HomeController::class, 'home'])->name('dashboard');
    Route::get('/notice', [FrontendHomeController::class, 'showNotice'])->name('show.notice');
    Route::get('/notice/create', [FrontendHomeController::class, 'notice'])->name('notice.create');
    Route::post('/notice/store', [FrontendHomeController::class, 'noticeStore'])->name('notice.store');
    Route::get('/notice/noticeList', [FrontendHomeController::class, 'noticeList'])->name('noticeList');
    Route::get('/notice/noticeDelete/{id}', [FrontendHomeController::class, 'noticeDelete'])->name('noticeDelete');
    Route::get('/notice/noticeEdit/{id}', [FrontendHomeController::class, 'noticeEdit'])->name('noticeEdit');
    Route::put('/notice/noticeUpdate/{id}', [FrontendHomeController::class, 'noticeUpdate'])->name('noticeUpdate');
 });


