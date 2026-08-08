
                            @foreach ($in as $i=>$in)
                            <tr>
                            <td>{{ $i+1 }}</td>

                            <td>{{ $in->dutyDate }}</td>
                            <td>
                            @foreach ($shifts as $dept)
                                 @if ($dept->id == $in->shiftID)
                                     {{ $dept->shiftName }}
                                 @endif
                             @endforeach
                            </td>
                            <td>{{ $in->inTime }}</td>
                            <td>
                            @if($out->count() > 0)
                            @foreach ($out as $data)
                                 @if ($data->empID == $in->empID && $data->dutyDate == $in->dutyDate)
                                    {{ $data->outTime }}
                                 @endif
                             @endforeach
                             @endif
                            </td>
                            <td id="workingTime">
                                @if($out->count() > 0)
                                @php
                                    $inTime = $in->inTime;
                                    $outTime = $data->outTime;
                                    $inTime = strtotime($inTime);
                                    $outTime = strtotime($outTime);
                                    $totalTime = $outTime - $inTime;
                                    $totalTime = date("H:i:s", $totalTime);
                                    echo $totalTime;
                                @endphp

                            </td>
                            <td>
                                @php
                                    $fixedTime = strtotime("09:00:00");
                                    $totalTime = strtotime($totalTime);
                                    $late = $totalTime - $fixedTime;
                                    if($late >= 0){
                                        echo "00:00:00";
                                    }
                                    else{
                                        $late = gmdate("H:i:s", $late);
                                        echo $late;
                                    }
                                @endphp
                            </td>
                            <td>
                                @php
                                    $over = $totalTime - $fixedTime;
                                    if($over > 0){
                                        $over = gmdate("H:i:s", $over);
                                        echo $over;
                                    }
                                    else{
                                        echo "00:00:00";
                                    }
                                @endphp
                            </td>
                            <td>
                                @php
                                    $status = $totalTime - $fixedTime;
                                    $over = $totalTime - $fixedTime;

                                    if ($status > "00:00:00" || $status == "00:00:00") {
                                        echo "late";
                                    }elseif( $status > 0)
                                    {
                                        echo "over";
                                    }
                                    else{
                                        echo "OK";
                                    }
                                @endphp
                                @endif
                            </td>
                            <td>
                                <ul class="actionBar">
                                    <!-- <li>
                                        <a href="" class="btn btn-sm btn-success">
                                            <i class="fa-duotone fa-edit"></i>
                                        </a>
                                    </li>
                                     -->
                                    <li>
                                        <a href="{{ url('deleteAttendance') }}/{{ $in->id }}" class="btn btn-danger bg-white">
                                             <i class="feather icon-trash"></i>
                                        </a>
                                    </li>
                                </ul>
                            </td>
                        </tr>
 @endforeach
