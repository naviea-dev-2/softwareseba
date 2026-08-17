<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\DeviceMapping;  
use App\Models\BusinessDevice;
use App\Models\AttendanceSetting;
use App\Models\Hr\Employee;
use App\Models\Hr\Attendance;
use Carbon\Carbon;
class ZKDeviceController extends Controller
{
    function cDataResponse(Request $request){
        // Log::info("test r: " . json_encode($request->all()));
        // Log::info("test c: " . json_encode($request->getContent()));
        // Log::info("t".$request->table == "ATTLOG");
        //  return response("OK");
        try{
            if($request->table == "ATTLOG"){
                // Log::info("tss".$request->table == "ATTLOG");
                $raw = $request->getContent();
                $lines = preg_split('/\s+/', trim($raw));
                Log::info("attenace log : " . json_encode($lines));
                // return response("OK");
                $business_device = BusinessDevice::where('sn',$request->SN)->first();
                // Log::info("business_device: " . json_encode($business_device));
                $employee = Employee::where("employee_id",$lines[0])->where("business_id",$business_device->business_id)->first();
                
                
                if($employee){
                    // Log::info("employee: " . json_encode($employee));
                    // return response("OK");
                    $shift = $employee->shift;
                    if($shift){
                        $a_date = \Carbon\Carbon::parse($lines[1])->format('Y-m-d');
                        $attendance = Attendance::where("empID", $employee->id)
                            ->where("shiftID", $shift->id)
                            ->where("dutyDate", $lines[1])
                            ->where("business_id",$business_device->business_id)->first();
                        if($attendance){
                            $startTime = Carbon::parse($attendance->inTime);
                            $endTime = Carbon::parse($lines[1].' '.$lines[2]);
                            $hour=$startTime->diff($endTime)->format('%H');
                            $min=$startTime->diff($endTime)->format('%I');
                            $totalWorkingMinnute=($hour*60)+$min;  
    
                            $shift_endTime = Carbon::parse($lines[1].' '.$shift->endTime.':59');
                            $shift_startTime = Carbon::parse($lines[1].' '.$shift->startTime.':59');
                            $eheck_over_time_diff = $endTime->diff($shift_endTime);
                            $overtimeMiniute = 0;
                            $out_status = 1;
                            if($eheck_over_time_diff->invert > 0){
                                $hour=$endTime->diff($shift_endTime)->format('%H');
                                $min=$endTime->diff($shift_endTime)->format('%I');
                                $overtimeMiniute=($hour*60)+$min;
                            }else{
                                $eheck_early_time_diff = $shift_endTime->diff($endTime);
                                if($eheck_early_time_diff->invert > 0){
                                    $out_status=2;
                                }
                            }
    
                            if($attendance->lateMiniute > 0 && $overtimeMiniute > 0){
                                $overtimeMiniute = $overtimeMiniute-$attendance->lateMiniute;
                                if($overtimeMiniute < 0){
                                    $overtimeMiniute = 0;
                                }
                            }
                            $shift_hour=$shift_startTime->diff($shift_endTime)->format('%H');
                            $shift_min=$shift_startTime->diff($shift_endTime)->format('%I');
                            $totalShitWorkingMinute = ($shift_hour*60)+$shift_min;
    
                            $attendance->outTime =  Carbon::parse($lines[1].' '.$lines[2]);
                            $attendance->workingMiniute = $totalWorkingMinnute;
                            $attendance->overtimeMiniute = $overtimeMiniute;
                            $attendance->out_status = $out_status;
                            if($totalShitWorkingMinute > $totalWorkingMinnute){
                                if($attendance->lateMiniute > 0){
                                }else{
                                    $attendance->status="Partial"; 
                                }
                            }
                            $attendance->totalShitWorkingMinute = $totalShitWorkingMinute;
                            $attendance->save();
                        }else{
                           
                            $attendance_setting = AttendanceSetting::where("business_id",$business_device->business_id)->first();
                            $check_in_time_pre = Carbon::parse($lines[1].' '.$shift->startTime.':59');
                            $check_in_time_pre1 = Carbon::parse($lines[1].' '.$shift->startTime.':59');
                            $emp_in_time = Carbon::parse($lines[1].' '.$lines[2]);
                            $is_void_attendance = 0;
                            if($attendance_setting){
                                $check_in_time_pre = Carbon::parse($lines[1].' '.$attendance_setting->delay_time.':59');
                                $last_in_time_pre = Carbon::parse($lines[1].' '.$attendance_setting->last_entry_time.':59');
                                $last_time_diff = $emp_in_time->diff($last_in_time_pre);
                                if($last_time_diff->invert > 0){
                                    $is_void_attendance = 1;
                                }
                            }
                            
                            if($is_void_attendance == 0){
                                $eheck_time_diff = $emp_in_time->diff($check_in_time_pre);
                                $in_status = 1;
                                $lateMiniute = 0;
                                $status = 'Ok';
                                if($eheck_time_diff->invert > 0){
                                    $in_status=2;
                                    $hour=$emp_in_time->diff($check_in_time_pre1)->format('%H');
                                    $min=$emp_in_time->diff($check_in_time_pre1)->format('%I');
                                    $lateMiniute=($hour*60)+$min;
                                    $status="Late";
                                    // $attendance->status = "Late";
                                }
                                $attendance = new Attendance;
                                $attendance->empID=$employee->id;
                                $attendance->shiftID=$shift->id;
                                $attendance->dutyDate=$lines[1];
                                $attendance->business_id=$business_device->business_id;
                                $attendance->lateMiniute = $lateMiniute;
                                $attendance->in_status=$in_status;
                                $attendance->status = $status;
                                $attendance->inTime = Carbon::parse($lines[1].' '.$lines[2]);
                                $attendance->save();
                            }
                            
                        }
                    }
                }
            }else{
                
                $raw = $request->getContent();
                $raw = trim($raw);
                if (str_starts_with($raw, 'FP ')) {
                    preg_match(
                        '/FP\s+PIN=([^\t]+)\s+FID=(\d+)\s+Size=(\d+)\s+Valid=(\d+)\s+TMP=(.+)$/s',
                        $raw,
                        $matches
                    );
                    if ($matches) {
                        $pin = trim($matches[1]);
                        $fingerId = (int) $matches[2];
                        $size = (int) $matches[3];
                        $valid = (int) $matches[4];
                        $template = trim($matches[5]);

                        $device_mapping =DeviceMapping::leftJoin('employees',"employees.id","device_mappings.emp_id")
                        ->where("device_mappings.device_sn",$sn)
                        ->where('employees.employee_id',$pin)
                        ->first(); 
                        $device_mapping->finger_id = $fingerId;
                        $device_mapping->size = $size;
                        $device_mapping->valid = $valid;
                        $device_mapping->template = $template;
                        $device_mapping->save();
                    }
                }
            }
        }catch(\Exception $e){
            Log::info($e->getMessage());
        }
        return response("OK");
    }
    
    function cDataRequest(Request $request){
        // Log::info("r: " . json_encode($request->all()));
        // return response("OK");
        $sn = $request->input('SN','unknown');
        $device_mapping =DeviceMapping::leftJoin('employees',"employees.id","device_mappings.emp_id")
        ->where("device_mappings.device_sn",$sn)
        ->where('device_mappings.is_done',0)
        ->select('employees.employee_id as userId',"employees.employee_name as name","device_mappings.id","device_mappings.command_type","device_mappings.finger_id","device_mappings.template")  
        ->first(); 
        if($device_mapping){
            if($device_mapping->command_type == "add"){
                $device_mapping->is_done = 1;
                $device_mapping->save();
                $cmdStr = "C:{$device_mapping->userId}:DATA USER PIN={$device_mapping->userId}\tName={$device_mapping->name}\tPrivilege=0\tPassword=";
                return response($cmdStr);
            }else if($device_mapping->command_type == "delete"){
                $device_mapping->is_done = 1;
                $device_mapping->save();
                $cmdStr = "C:{$device_mapping->userId}:DATA DELETE USERINFO PIN={$device_mapping->userId}";
                return response($cmdStr);
            }
            else if($device_mapping->command_type == "upload fr"){
                $device_mapping->is_done = 1;
                $device_mapping->save();
                $cmdStr = "C:{$device_mapping->userId}:DATA FP " . "PIN={$device_mapping->userId}" ."\tFID={$device_mapping->finger_id}" ."\tTMP={$device_mapping->template}";
                return response($cmdStr);
            } 

        }
        return response("OK");
    }
    function cDataCmd(Request $request){
        $raw = $request->getContent();
        parse_str($raw, $data);
        $empId = $data['ID'] ?? '';
        $return = $data['Return'] ?? '';
        if($return == 0){
            $sn = $request->input('SN','unknown');
            $device_mapping =DeviceMapping::leftJoin('employees',"employees.id","device_mappings.emp_id")
            ->where("device_mappings.device_sn",$sn)
            ->where('employees.employee_id',$empId)
            ->where('device_mappings.is_done',1)
            ->select('device_mappings.*')
            ->first();
            if($device_mapping){
                if($device_mapping->command_type == "add" || $device_mapping->command_type == "upload fr"){
                    $device_mapping->is_done = 2;
                    $device_mapping->save();
                }else if($device_mapping->command_type == "delete"){
                    $device_mapping->delete();
                }
            }  
        }
        return response("OK");
    }
}