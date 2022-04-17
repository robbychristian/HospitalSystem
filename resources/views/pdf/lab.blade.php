<!DOCTYPE html>
<html>
    
    <style>
        .page-break {
            page-break-after: always;
        }

         .lab-form .header .d-flex{
            display: flex !important;
            display: inline-block;
            justify-content: center;
            margin-bottom: 0px
        }
        .lab-form {
            padding: 0.5rem;
            width: 100%;
        }
        .lab-form .top-info {
            background-color: #C0504D;
            color: white;
            text-align: center;
            width: 100%;
            padding: 0.25rem;
            border: 1px black solid;
            max-width: 692px;
        }
        .lab-form .top-info p {
            margin-bottom: 0px;
        }
        .lab-form .header .patient-box {
            display: flex;
            flex-direction: column;
            border: 1px black solid;
            padding: 0.35rem;
            display: inline-block;
            flex: 1;
            height: 80px;
        }
        .lab-form .header .patient-box p {
        margin-bottom: 0px;
        }
        .lab-form .header .patient-box h6, .lab-form .header .patient-box .h6 {
        margin-bottom: 0.25rem;
        }
        .lab-form .body {
            display: flex !important;
        }
        .lab-form .body .content-box {
            width: 100%;
            display: inline-flex !important;

        }
        .lab-form .body .content-box .top-info {
            text-transform: uppercase;
            width: 100%;
            /* padding: 0.25rem !important; */
        }
        .lab-form .body .content-box .top-info h6, .lab-form .body .content-box .top-info .h6 {
            margin-bottom: 0px !important;
        }
        .lab-form .body .content-box .info-content {
            width: 100%;
            border: 1px black solid;
            padding: 0.25rem;
        }
        .lab-form .body .content-box .info-content .box {
            width: 10px;
            height: 10px;
            background-color: white;
            margin-right: 0.25rem;
            border: #000 1px solid;
            border-radius: 2px;
            
            display: inline-flex;
        }
        .lab-form .body .content-box .info-content .checked {
        background-color: #000 !important;
        }
        .lab-form .body .content-box .info-content p {
            margin-bottom: 0px;
            display: inline-flex;
        }

    </style>

    <head>
        <title>Lab Request Form</title>
    </head>

    <body>
        
        <div class="lab-form">
            
            <div class="header">
                <div class="top-info">
                    <h6> LABORATORY TEST REQUEST </h6>
                    <p>  Purpose: To be used when requesting testing of biological and potentially pathogenic agents at the Laboratory Services. Please present this form at the Laboratory Testing Center. </p>
                    <p> ( Requested by Dr.{{$patient->drfName}} {{$patient->drlName}}  )</p>
                </div>
                
                <div class="d-flex" style="margin-top: 18px; padding: 0px;">
                    <div class="patient-box" style="width: 600px;  margin-right: 0px !important; margin-left: 0px !important;">
                        <h6>Patient's Name: </h6>
                        <p>{{ $patient->name }}</p>
                    </div>

                    <div class="patient-box" style="width: 72px; margin-left: 0px !important; margin-right: 0px !important;">
                        <h6>PID: </h6>
                        <p>{{ $patient->pno }}</p>
                    </div>
                </div>
                
                <div class="d-flex">
                    <div class="patient-box" style="width: 550px;  margin-right: 0px !important; margin-left: 0px !important;">
                        <h6>Patient's Address: </h6>
                        <p>{{ $patient->pAddress }}</p>
                    </div>

                    <div class="patient-box" style="width: 122px; margin-left: 0px !important; margin-right: 0px !important;">
                        <h6>Phone: </h6>
                        <p>{{ $patient->pPhone }}</p>
                    </div>
                </div>

                <div class="d-flex" style="margin-top: 0px; padding: 0px; margin-bottom: 0px">
                    <div class="patient-box" style="width: 140px;  margin-right: 0px !important; margin-left: 0px !important;">
                        <h6>Date of Birth: </h6>
                        <p>{{ $patient->pBirthdate }}</p>
                    </div>

                    <div class="patient-box" style="width: 100px;  margin-right: 0px !important; margin-left: 0px !important;">
                        <h6>Gender: </h6>
                        <p>{{ $patient->pGender }}</p>
                    </div>

                    <div class="patient-box" style="width: 325px;  margin-right: 0px !important; margin-left: 0px !important;"></div>

                    <div class="patient-box" style="max-width: 200px;">
                        <h6>Today's Date: </h6>
                        <p>{{ $now }}</p>
                    </div>
                </div>
            </div>
            
            <div class="body" style="position: relative; width: 100%;">
                
                @php
                    $names =[
                        'Blood Chemistry',
                        'Urinalysis and Fecalysis',
                        'HEMATOLOGY, IMMUNOLOGY, and TUMOR MARKERS',
                        'Microbiology',
                        'Cardiovascular Examintation',
                        'stress protocol',
                        'Ultrasound',
                        'Pulmonary Examintation',
                        'x-ray',
                    ];
                @endphp

                @foreach ($checkedLab as $information)

                @if (  count($information) > 0)

                    <div class="content-box" style="width: 692px; margin-bottom: 5px; padding: 0px; margin-right: 0px; margin-top: 0px">
                        <div class="top-info">
                            <h6 style="margin-top: 0px"> {{$names[$loop->index]}}</h6>
                        </div>

                        <div class="info-content" style="display: inline-flex;">
                            @foreach ($information as $text)

                                @if (  $loop->index % 2 == 0)
                                    <div class="d-flex" style="display: inline-block; margin: 0px">
                                @endif

                                    <div class="d-flex" style="width: 325px; display: inline-block;">
                                        <div class="checked box "></div>
                                        <p style="font-size: 12px">{{ $text }}</p>
                                    </div>

                                @if (  ($loop->index + 1) % 2 == 0 && $loop->index > 0 || $loop->last)
                                    </div>
                                @endif
                            
                            @endforeach
                        </div>
                    </div>

                @endif
                    
                @endforeach
                {{-- <div class="content-box">

                    <div class="top-info">
                        <h6> CHEMISTRY PROFILE</h6>
                    </div>

                    <div class="info-content">
                        @foreach ($chemProfOptions as $data)
                        
                            <div class="d-flex">
                                
                                @if (in_array( $data->value, $labform))
                                    <div class="checked box "></div>
                                @else
                                    <div class="box"></div>
                                @endif
                                <p>{{ $data->text }}</p>
                            </div>
                        
                        @endforeach
                    </div> --}}
                        

                </div>

            </div>

            <div style=" padding: 5px;">

                <div >

                    <div style="display: inline-block; float: left">
                        <h6> LIC: &nbsp; <u>{{$lic}}</u></h6>
                        <h6> PTR: &nbsp; <u>{{$ptr}}</u></h6>
                        <h6> S2: &nbsp; <u> {{$s2}}</u></h6>
                    </div>

                    <div style="display: inline-block; float: right">
                        <img src="{{public_path( '/pdf' . $signature )}}" alt="" width="200" height="100">

                        <h6 style="margin-bottom: 0px"> Signature: &nbsp; <u> {{$patient->drfName}} {{$patient->drlName}} MD. </u></h6>
                    </div>

                </div>
            </div>
        </div>

    </body>
</html>