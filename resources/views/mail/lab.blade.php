<!DOCTYPE html>
<html>
    
    <style>
         .lab-form .header .d-flex{
            display: flex !important;
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
            display: inline-flex;
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
        display: flex;
        }
        .lab-form .body .content-box {
            width: 50%;
            display: inline-flex;
        }
        .lab-form .body .content-box .top-info {
            width: 100%;
            padding: 0.25rem !important;
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
            width: 13px;
            height: 13px;
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
                    <p> Lorem ipsum dolor sit amet, consecteturb hic facilis itaque voluptatum laboriosam deleniti nulla debitis.</p>
                    <p> ( Lorem ipsum dolor sit amet, consecteturb hic facilis itaque. )</p>
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

                <div class="d-flex" style="margin-top: 0px; padding: 0px;">
                    <div class="patient-box" style="width: 140px;  margin-right: 0px !important; margin-left: 0px !important;">
                        <h6>Date of Birth: </h6>
                        <p>{{ $patient->pBirthdate }}</p>
                    </div>

                    <div class="patient-box" style="width: 100px;  margin-right: 0px !important; margin-left: 0px !important;">
                        <h6>Gender: </h6>
                        <p>{{ $patient->pGender }}</p>
                    </div>

                    <div class="patient-box" style="width: 312px;  margin-right: 0px !important; margin-left: 0px !important;"></div>

                    <div class="patient-box" style="max-width: 140px;">
                        <h6>Today's Date: </h6>
                        <p>{{ $now }}</p>
                    </div>
                </div>
            </div>
            
            <div class="body d-flex">
                <div class="content-box" style="max-width: 300px;">

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
                    </div>
                        

                    <div class="top-info">
                        <h6> CHEMISTRY</h6>
                    </div>
                    
                    <div class="info-content">
                        @foreach ($chemOptions as $data)
                        
                            <div class="d-flex">
                                
                                @if (in_array( $data->value, $labform))
                                    <div class="checked box"></div>
                                @else
                                    <div class="box"></div>
                                @endif
                                <p>{{ $data->text }}</p>
                            </div>
                        
                        @endforeach
                    </div>
                    
                    <div class="top-info">
                        <h6> GLUCOSE TOLERANT TEST</h6>
                    </div>

                    <div class="info-content">
                        @foreach ($glucoseOptions as $data)
                        
                            <div class="d-flex">
                                
                                @if (in_array( $data->value, $labform))
                                    <div class="checked box"></div>
                                @else
                                    <div class="box"></div>
                                @endif
                                <p>{{ $data->text }}</p>
                            </div>
                        
                        @endforeach
                    </div>
                </div>

                <div class="content-box"  style="max-width: 300px;">
                    
                    <div class="top-info">
                        <h6> BODY FLUIDS</h6>
                    </div>
                    
                    <div class="info-content">
                        @foreach ($bodyFluidsOptions as $data)
                        
                            <div class="d-flex">
                                
                                @if (in_array( $data->value, $labform))
                                    <div class="checked box "></div>
                                @else
                                    <div class="box"></div>
                                @endif
                                <p>{{ $data->text }}</p>
                            </div>
                        
                        @endforeach
                    </div>
                    
                    <div class="top-info">
                        <h6> HEMATOLOGY/COAGULATION</h6>
                    </div>
                    
                    <div class="info-content">
                        @foreach ($hemaOptions as $data)
                        
                            <div class="d-flex">
                                
                                @if (in_array( $data->value, $labform))
                                    <div class="checked box "></div>
                                @else
                                    <div class="box"></div>
                                @endif
                                <p>{{ $data->text }}</p>
                            </div>
                        
                        @endforeach
                    </div>
                    
                    <div class="top-info">
                        <h6> URINALYSIS</h6>
                    </div>

                    <div class="info-content">
                        @foreach ($urineOptions as $data)
                        
                            <div class="d-flex">
                                
                                @if (in_array( $data->value, $labform))
                                    <div class="checked box "></div>
                                @else
                                    <div class="box"></div>
                                @endif
                                <p>{{ $data->text }}</p>
                            </div>
                        
                        @endforeach
                    </div>
                    
                </div>
            </div>

        </div>

    </body>
</html>