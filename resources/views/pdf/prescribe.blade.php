<!DOCTYPE html>
<html>
    
    <style>
        .page-break {
            page-break-after: always;
        }
        
        .d-flex{
            display: inline-block;
            justify-content: center;
            margin-bottom: 0px
        }

        .fw-normal {
            font-weight: 400 !important;
        }

        .fw-bold {
            font-weight: 700 !important;
        }
        .prescribe-form {
            min-width: 600px;
        }
        .prescribe-form .header {
            text-align: center;
        }
        .prescribe-form .header img {
            margin-bottom: 0.75rem;
        }
        .prescribe-form .header .doctor-details {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .prescribe-form .header .doctor-details .img {
            margin-right: 1rem;
            margin-bottom: 0rem;
            width: 65px;
            height: 65px;
            border-radius: 100%;
            border: 2px solid #5C97C8;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .prescribe-form .divider {
            width: 100%;
            height: 4px !important;
            margin: 1rem 0 1rem 0 !important;
            border-radius: 0px !important;
            background-color: #568DB5 !important;
            border: none !important;
        }
        .prescribe-form .body {
            text-align: center;
        }
        .prescribe-form .body .patient-form-info {
            display: inline-block;
            width: 600px;
        }
        .prescribe-form .body .patient-form-info .form-info {
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }
        .prescribe-form .body .patient-form-info .form-info h6, .prescribe-form .body .patient-form-info .form-info .h6, .prescribe-form .body .patient-form-info .form-info p {
            margin: 0px 0.75rem 0rem 0rem;
        }
        .prescribe-form .body .patient-form-info .form-info p {
            text-align: start !important;
            min-width: 50px !important;
            border-bottom: 1px black solid;
            flex-grow: 1;
        }
        .prescribe-form .body .prescription {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .prescribe-form .body .prescription h5, .prescribe-form .body .prescription .h5 {
            margin-top: 1.5rem;
            color: #14679B;
            text-align: start !important;
            width: 815px;
        }
        .prescribe-form .body .prescription button {
            margin-top: 0.5rem;
        }
        .prescribe-form .body .prescription .prescription-box {
            width: 100%;
            background-color: #dbdbdb;
            border-radius: 5px;
            padding: 0.25rem;
            vertical-align: middle;
        }
        .prescribe-form .body .prescription .prescription-box .divider-vertical {
            height: 10px;
            width: 2px;
            background-color: #000;
            margin: 0.5rem 0.2rem 0rem 0.2rem;
        }
        .prescribe-form .body .prescription .prescription-box .prescription-input {
            display: flex;
            flex-wrap: wrap;
        }
        .prescribe-form .body .prescription .prescription-box .prescription-input input.error::-moz-placeholder {
            color: white;
        }
        .prescribe-form .body .prescription .prescription-box .prescription-input input.error:-ms-input-placeholder {
            color: white;
        }
        .prescribe-form .body .prescription .prescription-box .prescription-input input.error::placeholder, .prescribe-form .body .prescription .prescription-box .prescription-input .error {
            color: white;
        }
        .prescribe-form .body .prescription .prescription-box .prescription-input .error {
            background-color: rgba(255, 0, 0, 0.5) !important;
        }
        .prescribe-form .body .prescription .prescription-box .prescription-input input, .prescribe-form .body .prescription .prescription-box .prescription-input select {
            padding: 0.25rem;
            height: 35px;
            margin-top: 0.5rem;
        }
        .prescribe-form .body .prescription .prescription-box .prescription-input input[type=number] {
            max-width: 100px;
        }
        .prescribe-form .body .prescription .prescription-box .medicines {
            display: flex;
        }
        .prescribe-form .body .prescription .prescription-box .medicines h6, .prescribe-form .body .prescription .prescription-box .medicines .h6 {
            display: flex;
            align-items: center;
            margin-top: 0.5rem;
        }
        .prescribe-form .footer .payment {
            background-color: #D9D9D9;
        }
        .prescribe-form .footer .payment h5, .prescribe-form .footer .payment .h5 {
            margin-top: 1.5rem;
            color: #14679B;
            text-align: start !important;
            width: 815px;
        }
        .prescribe-form .footer .payment .signature {
            position: relative;
        }
        .prescribe-form .footer .payment .signature .img {
            margin-bottom: 0.5rem;
            width: 100px;
            height: 100px;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

    </style>

    <head>
        <title>Prescription</title>
    </head>

    <body>
        
        <div class="prescribe-form">

            <div class="header">
                <img src="{{ public_path('/img/logo1.png') }}" alt="Logo" width="90" height="115">
               
                <div class="doctor-details">
                    
                    <img class="img" src="{{  $patient['drPhotoUrl'] }}" alt="Logo" width="90" height="115">
                
                    <h4 class="mb-0 fw-bold" style="display: inline-block"> Dr. {{$patient['drfName']}} {{$patient['drlName']}}</h4>
                </div>

                <h6 class="fw-normal">{{$patient['drDegree']}}</h6>
                <h6 class="fw-normal">Phone: {{$patient['drPhone']}}</h6>
                <h6 class="fw-normal">Email: {{$patient['drEmail']}}</h6>

                <h6 class="fw-normal" style="margin-bottom: 0px; margin-right: 1rem;">LIC: &nbsp; {{$patient['lic']}} &nbsp; PTR: &nbsp; {{$patient['ptr']}} &nbsp; S2: &nbsp; {{$patient['s2']}}</h6>

            </div>

            <div class="divider"></div>
            
            <div class="body">

                <div class="patient-form-info">
                    
                    <div style="display: inline-block">
                        <div class="form-info" style="width: 200px !important; display: inline-block;">
                            <h6 style=" display: inline-block;">Date: </h6>  <p style=" display: inline-block;">{{now()}}</p>
                        </div>

                        <div class="form-info" style="width: 200px !important; display: inline-block;">
                            <h6 style=" display: inline-block;">P.No: </h6>  <p style=" display: inline-block;"> {{$patient['pno']}}</p>
                        </div>
                    </div>
                    
                    <div class="form-info">
                        <h6 style=" display: inline-block;">Patient Name: </h6>  <p style="width: 450px;display: inline-block;">{{$patient['name']}}</p>
                    </div>

                    <div class="form-info">
                        <h6 style=" display: inline-block;">Address: </h6>  <p style="width: 450px; display: inline-block;">{{$patient['pAddress']}}</p>
                    </div>
                    
                    <div style="display: inline-block">
                        <div class="form-info" style="width: 200px !important; display: inline-block;">
                            <h6 style=" display: inline-block;">Birthdate: </h6>  <p style=" display: inline-block;">{{$patient['pBirthdate']}}</p>
                        </div>

                        <div class="form-info" style="width: 200px !important; display: inline-block;">
                            <h6 style=" display: inline-block;">Gender: </h6>  <p style=" display: inline-block;"> {{$patient['pGender']}}</p>
                        </div>
                    </div>
                </div>

                <div class="prescription" style="text-align: left">
                    <h5>Prescribe Medicine</h5>
                    
                    <div class="prescription-box">
                        
                        <div class="medicines" style="width: 600px; margin-right: 50px !important;">
                            
                            @foreach ($medicine as $data)
                                <div style="height: 20px">
                                    <h6 style="display: inline-block;"> {{$data['medicine']}} |</h6>
                                    <h6 style="display: inline-block;"> {{$data['dosage']}} | </h6> 
                                    <h6 style="display: inline-block;"> {{$data['perDay']}} | </h6> 
                                    <h6 style="display: inline-block;"> {{$data['days']}} | </h6>
                                </div>
                            @endforeach
                        </div>

                    </div>

                    <h5>Patient Problem</h5>
                    
                    <div class="prescription-box">
                        
                        <div class="prescription-input">
                            <h6> {{$actualProblem}} </h6>
                        </div>
                    </div>
                    
                    <h5>Rx.</h5>
                    
                    @if ( $rx != '' && $rx != null)
                        <div class="prescription-box">
                            
                            <div class="prescription-input">
                                <h6> {{$rx}} </h6>
                            </div>
                        </div>
                    @endif
                    
                    <h5>Advice</h5>
                    
                    @if ( $advice != '' && $advice != null)
                        <div class="prescription-box">
                            
                            <div class="prescription-input">
                                <h6> {{$advice}} </h6>
                            </div>
                        </div>
                    @endif
                </div>
                
            </div>

            <div class="divider page-break"></div>

            <div class="footer" style="min-height: 0px; height: auto">
                <div class="payment">
                    <div style="height: 15px; margin: 5px">
                        <h5 style="height: 15px; margin: 5px; display: block; color: #14679B;" >Payment: </h5>
                    </div>
                    
                        
                    @foreach ($payments as $payment)
                            
                        @if( !$loop->last)
                            <div>
                                <div style="height: 15px; margin: 5px">
                                    <h6 style="height: 15px; margin: 5px; display: block; color: #000000;"> {{$payment}}</h6>
                                </div>
                            </div>
                        @else
                            <div>
                                <div style="height: 15px; margin: 5px">
                                    <h5 style="height: 15px; margin: 5px; display: block; color: #48898C;"> {{$payment}}</h5>
                                </div>
                            </div>
                        @endif


                    @endforeach
                </div>
                
            </div>

            <div class="signature" style="margin-top: 15px; float: right;" >
                <img src="{{public_path( '/pdf' . $signature )}}" alt="" width="200" height="100">

                <h6 style="margin-bottom: 0px"> Signature: &nbsp; <u> {{$patient['name']}}  MD.</u></h6>
            </div>

        </div>

    </body>
</html>