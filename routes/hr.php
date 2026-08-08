<?php
use Illuminate\Support\Facades\Route;

Route::prefix('business')->middleware(['auth',"is_active",'permission'])->group(function () {
    Route::get('device-mapping/index', [App\Http\Controllers\Hr\DeviceIDMappingController::class,"index"])->name('hr.device_mapping');
    Route::post('device-mappin/ajax', [App\Http\Controllers\Hr\DeviceIDMappingController::class,"ajaxDeviceMapping"])->name('hr.device_mapping.ajax');
    Route::post('device-mapping/store', [App\Http\Controllers\Hr\DeviceIDMappingController::class,"store"])->name('hr.device_mapping.store');
    Route::get('device-mapping/edit/{id}', [App\Http\Controllers\Hr\DeviceIDMappingController::class,"edit"])->name('hr.device_mapping.edit');
    Route::post('device-mapping/delete', [App\Http\Controllers\Hr\DeviceIDMappingController::class,"delete"])->name('hr.device_mapping.delete');
    //attendence
    Route::get('/manageAttendance', 'App\Http\Controllers\Hr\EmployeeController@manageAttendance')->name('manageAttendance');
    Route::post('/attendance/ajax', 'App\Http\Controllers\Hr\EmployeeController@ajaxAttendance')->name('attendance.ajax');
    Route::get('/manageAttendanceSorting', 'App\Http\Controllers\Hr\EmployeeController@manageAttendanceSorting')->name('manageAttendanceSorting');

    Route::post('/attendanceStoreIn', 'App\Http\Controllers\Hr\EmployeeController@attendanceStoreIn')->name('attendanceStoreIn');
    Route::post('/attendanceStoreOut', 'App\Http\Controllers\Hr\EmployeeController@attendanceStoreOut')->name('attendanceStoreOut');
    Route::get('/deleteAttendance/{id}', 'App\Http\Controllers\Hr\EmployeeController@deleteAttendance')->name('deleteAttendance');

    //end attendance

    //notice
    Route::get('/viewNotice', 'App\Http\Controllers\Hr\noticeController@viewNotice')->name('viewNotice');

    Route::post('/notice/ajax_data_list', 'App\Http\Controllers\Hr\noticeController@ajaxDataList')->name('notice.ajax_data_list');

    Route::get('/addNotice', 'App\Http\Controllers\Hr\noticeController@addNotice')->name('addNotice');

    Route::post('/storeNotice', 'App\Http\Controllers\Hr\noticeController@storeNotice')->name('storeNotice');

    Route::get('/editNotice/{id}', 'App\Http\Controllers\Hr\noticeController@editNotice')->name('editNotice');

    Route::post('/updateNotice/{id}', 'App\Http\Controllers\Hr\noticeController@updateNotice')->name('updateNotice');

    Route::get('/deleteNotice/{id}', 'App\Http\Controllers\Hr\noticeController@deleteNotice')->name('deleteNotice');

    Route::get('/updateNoticeStatus/{id}', 'App\Http\Controllers\Hr\noticeController@updateNoticeStatus')->name('updateNoticeStatus');
    //end notice


    //salary
    Route::get('/manageSalary', 'App\Http\Controllers\Hr\HrController@manageSalary')->name('manageSalary');
    Route::post('/salary/ajax', 'App\Http\Controllers\Hr\HrController@ajaxSalary')->name('salary.ajax');

    Route::get('/SalarySheet', 'App\Http\Controllers\Hr\HrController@SalarySheet')->name('SalarySheet');

    Route::get('/addSalary', 'App\Http\Controllers\Hr\HrController@addSalary')->name('addSalary');

    Route::post('/storeSalary', 'App\Http\Controllers\Hr\HrController@storeSalary')->name('storeSalary');

    Route::get('/editSalary/{id}', 'App\Http\Controllers\Hr\HrController@editSalary')->name('editSalary');

    Route::post('salarySheet/update', 'App\Http\Controllers\Hr\HrController@updateSalary')->name('salarySheet.update');

    Route::get('/deleteSalary/{id}', 'App\Http\Controllers\Hr\HrController@deleteSalary')->name('deleteSalary');
    Route::get('/salary/slip/fetch', 'App\Http\Controllers\Hr\HrController@salarySlip')->name('salary.slip.fetch');
    Route::get('/emp_bank_accountbybankId', 'App\Http\Controllers\Hr\HrController@empBankAccountByBankId')->name('empbankaccount.callByBankID');
    Route::get('/com_bank_accountbybankId', 'App\Http\Controllers\Hr\HrController@comBankAccountByBankId')->name('combankaccount.callByBankID');

    //end salary

    //employee
    Route::get('/allEmployee', 'App\Http\Controllers\Hr\HrController@allEmployee')->name('allEmployee');
    Route::post('/employee/ajax', 'App\Http\Controllers\Hr\HrController@ajaxEmployee')->name('employee.ajax');
    Route::get('/addEmployee', 'App\Http\Controllers\Hr\HrController@addEmployee')->name('addEmployee');
    Route::post('/storeEmployee', 'App\Http\Controllers\Hr\HrController@storeEmployee')->name('storeEmployee');
    Route::get('/editEmployee/{id}', 'App\Http\Controllers\Hr\HrController@editEmployee')->name('editEmployee');
    Route::post('/updateEmployee/{id}', 'App\Http\Controllers\Hr\HrController@updateEmployee')->name('updateEmployee');
    Route::get('/deleteEmployee/{id}', 'App\Http\Controllers\Hr\HrController@deleteEmployee')->name('deleteEmployee');
    Route::get('select2-employee','App\Http\Controllers\Hr\HrController@select2Employee')->name('select2.employee');
    Route::get('select2-shift-employee','App\Http\Controllers\Hr\HrController@select2ShiftEmployee')->name('select2.employee_s');
    Route::get('select2-driver_employee','App\Http\Controllers\Hr\HrController@select2DriverEmployee')->name('select2.driver_employee');
    Route::get('select2-asr_employee','App\Http\Controllers\Hr\HrController@select2AsrEmployee')->name('select2.asr_employee');
    Route::get('select2-dsr_employee','App\Http\Controllers\Hr\HrController@select2DsrEmployee')->name('select2.dsr_employee');
    //end employee

    //shiftManage
    Route::get('/shiftManage-view','App\Http\Controllers\Hr\shiftManageController@view')->name('shiftManage.view');
    Route::post('/shiftManage-store','App\Http\Controllers\Hr\shiftManageController@store')->name('shiftManage.store');
    Route::get('/shiftManage-edit','App\Http\Controllers\Hr\shiftManageController@edit')->name('shiftManage.edit');
    Route::get('/shiftManage-delete/{id}','App\Http\Controllers\Hr\shiftManageController@delete')->name('shiftManage.delete');
    Route::get('/select2-shift', [App\Http\Controllers\Hr\shiftManageController::class, 'select2Shift'])->name('select2.shift');
    //end shift

    //leaveType
    Route::get('/leaveType-view','App\Http\Controllers\Hr\leaveTypeController@view')->name('leaveType.view');
    Route::post('/leaveType-store','App\Http\Controllers\Hr\leaveTypeController@store')->name('leaveType.store');
    Route::get('/leaveType-edit','App\Http\Controllers\Hr\leaveTypeController@edit')->name('leaveType.edit');
    Route::get('/leaveType-delete/{id}','App\Http\Controllers\Hr\leaveTypeController@delete')->name('leaveType.delete');

    //leavePart
    Route::get('/leavePart-view','App\Http\Controllers\Hr\leavePartController@view')->name('leavePart.view');
    Route::post('/leavePart-store','App\Http\Controllers\Hr\leavePartController@store')->name('leavePart.store');
    Route::get('/leavePart-edit','App\Http\Controllers\Hr\leavePartController@edit')->name('leavePart.edit');
    Route::get('/leavePart-delete/{id}','App\Http\Controllers\Hr\leavePartController@delete')->name('leavePart.delete');
    // leave Application
    Route::get('/leaveApplication-view','App\Http\Controllers\Hr\leaveApplicationController@view')->name('leaveApplication.view');
    Route::post('/leaveApplication-ajax','App\Http\Controllers\Hr\leaveApplicationController@ajaxLeave')->name('leaveApplication.ajax');
    Route::post('/leaveApplication-store','App\Http\Controllers\Hr\leaveApplicationController@store')->name('leaveApplication.store');
    Route::get('/leave-application-pending','App\Http\Controllers\Hr\leaveApplicationController@pending')->name('leave.application.pending');
    Route::get('/leave-application-approve','App\Http\Controllers\Hr\leaveApplicationController@approve')->name('leave.application.approve');
    Route::get('/leave-application-reject','App\Http\Controllers\Hr\leaveApplicationController@reject')->name('leave.application.reject');
    Route::post('/leave-application-update','App\Http\Controllers\Hr\leaveApplicationController@update')->name('leave.application.update');
    Route::get('/leave-application-delete/{id}','App\Http\Controllers\Hr\leaveApplicationController@delete')->name('leaveApplication.delete');
    Route::get('/leave-application-single-view','App\Http\Controllers\Hr\leaveApplicationController@singleView')->name('leave.application.single.view');
    Route::post('/leaveApplication-search','App\Http\Controllers\Hr\leaveApplicationController@search')->name('leaveApplication.search');
    Route::get('/leavePartID-callByLeaveTYpe','App\Http\Controllers\Hr\leaveApplicationController@leavePartID_callByLeaveTYpe')->name('leavePartID.callByLeaveTYpe');
    //leaveTagline
    Route::get('/leaveTagline-view','App\Http\Controllers\Hr\leaveTaglineController@view')->name('leaveTagline.view');
    Route::post('/leaveTagline-store','App\Http\Controllers\Hr\leaveTaglineController@store')->name('leaveTagline.store');
    Route::get('/leaveTagline-edit','App\Http\Controllers\Hr\leaveTaglineController@edit')->name('leaveTagline.edit');
    Route::get('/leaveTagline-delete/{id}','App\Http\Controllers\Hr\leaveTaglineController@delete')->name('leaveTagline.delete');

    //department
    Route::get('/viewDepartment', 'App\Http\Controllers\Hr\HrController@viewDepartment')->name('viewDepartment');

    Route::get('/addDepartment', 'App\Http\Controllers\Hr\HrController@addDepartment')->name('addDepartment');

    Route::post('/storeDeptData', 'App\Http\Controllers\Hr\HrController@storeDeptData')->name('storeDeptData');

    Route::get('/editDepartment/{id}', 'App\Http\Controllers\Hr\HrController@editDepartment')->name('editDepartment');

    Route::post('/updateDepartment/{id}', 'App\Http\Controllers\Hr\HrController@updateDepartment')->name('updateDepartment');

    Route::get('/deleteDept/{id}', 'App\Http\Controllers\Hr\HrController@deleteDept')->name('deleteDept');
    Route::get('select2-department','App\Http\Controllers\Hr\HrController@select2Department')->name('select2.department');
    //end department


    //designation
    Route::get('/viewDesignation', 'App\Http\Controllers\Hr\HrController@viewDesignation')->name('viewDesignation');

    Route::get('/addDesignation', 'App\Http\Controllers\Hr\HrController@addDesignation')->name('addDesignation');

    Route::post('/storeDesgData', 'App\Http\Controllers\Hr\HrController@storeDesgData')->name('storeDesgtData');

    Route::get('/editDesignation/{id}', 'App\Http\Controllers\Hr\HrController@editDesignation')->name('editDesignation');

    Route::post('/updateDesignation/{id}', 'App\Http\Controllers\Hr\HrController@updateDesignation')->name('updateDesignation');

    Route::get('/deleteDesg/{id}', 'App\Http\Controllers\Hr\HrController@deleteDesg')->name('deleteDesg');

    Route::get('select2-designation','App\Http\Controllers\Hr\HrController@select2Designation')->name('select2.designation');

    Route::get('/getEmpDesig', 'App\Http\Controllers\Hr\HrController@getEmpDesig')->name('getEmpDesig');

    Route::post('/getDesigName', 'App\Http\Controllers\Hr\EmployeeController@getDesigName')->name('/getDesigName');
    Route::post('/getEmployeeId', 'App\Http\Controllers\Hr\EmployeeController@getEmployeeId')->name('/getEmployeeId');

    Route::post('/getDesigName1', 'App\Http\Controllers\Hr\EmployeeController@getDesigName')->name('/getDesigName1');
    Route::post('/getEmployeeId1', 'App\Http\Controllers\Hr\EmployeeController@getEmployeeId')->name('/getEmployeeId1');

    Route::post('/getDesigName2', 'App\Http\Controllers\Hr\EmployeeController@getDesigName')->name('/getDesigName2');
    Route::post('/getEmployeeId2', 'App\Http\Controllers\Hr\EmployeeController@getEmployeeId')->name('/getEmployeeId2');

    Route::post('/getDesigName3', 'App\Http\Controllers\Hr\HRController@getDesigName')->name('/getDesigName3');

    Route::post('/getDesigName4', 'App\Http\Controllers\Hr\EmployeeController@getDesigName')->name('/getDesigName4');
    Route::post('/getEmployeeId4', 'App\Http\Controllers\Hr\EmployeeController@getEmployeeId')->name('/getEmployeeId4');
    // Route::get('designations-by-dept/{id}','App\Http\Controllers\Hr\GlobalController@getDesignationByDepartment')->name('designations-by-dept');
    // Route::get('branch-by-bank/{id}','App\Http\Controllers\Hr\GlobalController@getBranchByBankj')->name('branch-by-bank');
    //end designation

    //payroll
    Route::get('/managePayroll', 'App\Http\Controllers\Hr\HrController@managePayroll')->name('managePayroll');

    Route::get('/addPayroll', 'App\Http\Controllers\Hr\HrController@addPayroll')->name('addPayroll');

    Route::post('/storePayroll', 'App\Http\Controllers\Hr\HrController@storePayroll')->name('storePayroll');

    Route::get('/editPayroll/{id}', 'App\Http\Controllers\Hr\HrController@editPayroll')->name('editPayroll');

    Route::post('/updatePayroll/{id}', 'App\Http\Controllers\Hr\HrController@updatePayroll')->name('updatePayroll');

    Route::get('/deletePayroll/{id}', 'App\Http\Controllers\Hr\HrController@deletePayroll')->name('deletePayroll');
    //end payroll

    //absent
    Route::get('/manageAbsent', 'App\Http\Controllers\Hr\HrController@manageAbsent')->name('manageAbsent');

    Route::get('/addAbsent', 'App\Http\Controllers\Hr\HrController@addAbsent')->name('addAbsent');

    Route::post('/storeAbsent', 'App\Http\Controllers\Hr\HrController@storeAbsent')->name('storeAbsent');

    Route::get('/editAbsent/{id}', 'App\Http\Controllers\Hr\HrController@editAbsent')->name('editAbsent');

    Route::post('/updateAbsent/{id}', 'App\Http\Controllers\Hr\HrController@updateAbsent')->name('updateAbsent');

    Route::get('/deleteAbsent/{id}', 'App\Http\Controllers\Hr\HrController@deleteAbsent')->name('deleteAbsent');
    //end absent

    //attendance_setting
    Route::get('/attendance_setting', 'App\Http\Controllers\Hr\HrController@attendanceSetting')->name('attendance_setting');   
    Route::get('/attendance_setting/add', 'App\Http\Controllers\Hr\HrController@attendanceSettingAdd')->name('attendance_setting.add');   
    Route::post('/attendance_setting/store', 'App\Http\Controllers\Hr\HrController@attendanceSettingStore')->name('attendance_setting.store');   
    Route::get('/attendance_setting/edit/{id}', 'App\Http\Controllers\Hr\HrController@attendanceSettingEdit')->name('attendance_setting.edit');   
    Route::post('/attendance_setting/update/{id}', 'App\Http\Controllers\Hr\HrController@attendanceSettingUpdate')->name('attendance_setting.update');   
    //end attendance_setting

    //late roll
    Route::get('/manageLateRoll', 'App\Http\Controllers\Hr\HrController@manageLateRoll')->name('manageLateRoll');

    Route::get('/addLateRoll', 'App\Http\Controllers\Hr\HrController@addLateRoll')->name('addLateRoll');

    Route::post('/storeLateRoll', 'App\Http\Controllers\Hr\HrController@storeLateRoll')->name('storeLateRoll');

    Route::get('/editLateRoll/{id}', 'App\Http\Controllers\Hr\HrController@editLateRoll')->name('editLateRoll');

    Route::post('/updateLateRoll/{id}', 'App\Http\Controllers\Hr\HrController@updateLateRoll')->name('updateLateRoll');

    Route::get('/deleteLateRoll/{id}', 'App\Http\Controllers\Hr\HrController@deleteLateRoll')->name('deleteLateRoll');
    //end roll


    //overtime
    Route::get('/manageOvertime', 'App\Http\Controllers\Hr\HrController@manageOvertime')->name('manageOvertime');

    Route::get('/addOvertime', 'App\Http\Controllers\Hr\HrController@addOvertime')->name('addOvertime');

    Route::post('/storeOvertime', 'App\Http\Controllers\Hr\HrController@storeOvertime')->name('storeOvertime');

    Route::get('/editOvertime/{id}', 'App\Http\Controllers\Hr\HrController@editOvertime')->name('editOvertime');

    Route::post('/updateOvertime/{id}', 'App\Http\Controllers\Hr\HrController@updateOvertime')->name('updateOvertime');

    Route::get('/deleteOvertime/{id}', 'App\Http\Controllers\Hr\HrController@deleteOvertime')->name('deleteOvertime');
    //end overtime

    //payment range
    Route::get('/managePaymentRange', 'App\Http\Controllers\Hr\HrController@managePaymentRange')->name('managePaymentRange');
    Route::post('/storePaymentRange', 'App\Http\Controllers\Hr\HrController@storePaymentRange')->name('storePaymentRange');
    Route::get('/deletePaymentRange/{id}', 'App\Http\Controllers\Hr\HrController@deletePaymentRange')->name('deletePaymentRange');
    Route::post('/updatePaymentRange/{id}', 'App\Http\Controllers\Hr\HrController@updatePaymentRange')->name('updatePaymentRange');
    //end payroll range

    //monthManage
    Route::get('/monthManage-view','App\Http\Controllers\Hr\monthManageController@view')->name('monthManage.view');
    Route::post('/monthManage-store','App\Http\Controllers\Hr\monthManageController@store')->name('monthManage.store');
    Route::get('/monthManage-edit','App\Http\Controllers\Hr\monthManageController@edit')->name('monthManage.edit');
    Route::get('/monthManage-delete/{id}','App\Http\Controllers\Hr\monthManageController@delete')->name('monthManage.delete');

    //holiday
    Route::get('/holiday-view','App\Http\Controllers\Hr\holidayController@view')->name('holiday.view');
    Route::post('/holiday-store','App\Http\Controllers\Hr\holidayController@store')->name('holiday.store');
    Route::get('/holiday-edit','App\Http\Controllers\Hr\holidayController@edit')->name('holiday.edit');
    Route::get('/holiday-delete/{id}','App\Http\Controllers\Hr\holidayController@delete')->name('holiday.delete');
    //emploan
    Route::get('/emploan-view','App\Http\Controllers\Hr\emploanController@view')->name('emploan.view');
    Route::post('/emploan-ajax','App\Http\Controllers\Hr\emploanController@ajaxLoan')->name('emploan.ajax');
    Route::post('/emploan-store','App\Http\Controllers\Hr\emploanController@store')->name('emploan.store');
    Route::get('/emploan-edit','App\Http\Controllers\Hr\emploanController@edit')->name('emploan.edit');
    Route::get('/emploan-delete/{id}','App\Http\Controllers\Hr\emploanController@delete')->name('emploan.delete');
    // Route::get('/emp-bank-account','App\Http\Controllers\Hr\emploanController@empbankaccount')->name('empbankaccount.callByBankID');
    // Route::get('/com-bank-account','App\Http\Controllers\Hr\emploanController@combankaccount')->name('combankaccount.callByBankID');
    Route::get('/emploan-loan-legder','App\Http\Controllers\Hr\emploanController@loanLegder')->name('emploan.loanLegder');

    //bonuspay
    Route::get('/bonus-calculation','App\Http\Controllers\Hr\bonuspayController@calculation')->name('bonuspay.calculation');
    Route::get('/bonuspay-view','App\Http\Controllers\Hr\bonuspayController@view')->name('bonuspay.view');
    Route::post('/bonuspay-ajax','App\Http\Controllers\Hr\bonuspayController@ajaxBonus')->name('bonuspay.ajax');
    Route::post('/bonuspay-store','App\Http\Controllers\Hr\bonuspayController@store')->name('bonuspay.store');
    Route::get('/bonuspay-edit','App\Http\Controllers\Hr\bonuspayController@edit')->name('bonuspay.edit');
    Route::post('/bonuspay-search','App\Http\Controllers\Hr\bonuspayController@search')->name('bonuspay.search');
    Route::get('/bonuspay-delete/{id}','App\Http\Controllers\Hr\bonuspayController@delete')->name('bonuspay.delete');
    //empbankaccount
    Route::get('/empbankaccount-view','App\Http\Controllers\Hr\empbankaccountController@view')->name('empbankaccount.view');
    Route::post('/empbankaccount-store','App\Http\Controllers\Hr\empbankaccountController@store')->name('empbankaccount.store');
    Route::get('/empbankaccount-edit','App\Http\Controllers\Hr\empbankaccountController@edit')->name('empbankaccount.edit');
    Route::get('/empbankaccount-delete/{id}','App\Http\Controllers\Hr\empbankaccountController@delete')->name('empbankaccount.delete');

    //bank
    // Route::get('/bank-view','App\Http\Controllers\accounts\bankController@view')->name('bank.view');
    // Route::post('/bank-store','App\Http\Controllers\accounts\bankController@store')->name('bank.store');
    // Route::get('/bank-edit','App\Http\Controllers\accounts\bankController@edit')->name('bank.edit');
    // Route::post('/bank-delete','App\Http\Controllers\accounts\bankController@delete')->name('bank.delete');

    //bankaccount
    Route::get('/bank-account-view','App\Http\Controllers\accounts\bankaccountController@view')->name('bankaccount.view');
    Route::post('/bank-account-store','App\Http\Controllers\accounts\bankaccountController@store')->name('bankaccount.store');
    Route::get('/bank-account-edit','App\Http\Controllers\accounts\bankaccountController@edit')->name('bankaccount.edit');
    Route::post('/bank-account-delete','App\Http\Controllers\accounts\bankaccountController@delete')->name('bankaccount.delete');

    //account head
});
