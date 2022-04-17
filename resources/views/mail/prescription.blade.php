<h5> 
    Hi 
    @if( $data['patient']->pGender == 'Female' ) Mrs. @else Mr. @endif
     &nbsp; {{$data['patient']->pfName}} &nbsp; {{$data['patient']->plName}}
</h5>

<p> 
    Dr.
    &nbsp; {{$data['patient']->drfName}} &nbsp; {{$data['patient']->drlName}}
    Has sent you your Prescription form that you will use for buying your medicines! <br>
    please do fill up the Patient information above if there's a blank.!
</p>

<a href="{{env('APP_URL')}}/api/inquiry/download/{{$pdf}}"> Click here to Download! </a>
