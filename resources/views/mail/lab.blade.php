<h5> 
    Hi 
    @if( $data['patient']->pGender == 'Female' ) Mrs. @else Mr. @endif
     &nbsp; {{$data['patient']->pfName}} &nbsp; {{$data['patient']->plName}}
</h5>

<p> 
    Dr. @if( $data['patient']->drGender == 'Female' ) Mrs. @else Mr. @endif
    &nbsp; {{$data['patient']->drfName}} &nbsp; {{$data['patient']->drlName}}
    Has sent you a lab request form that you will pass to the laboratory facility <br>
    please do fill up the inputs above if there's a blank.!
</p>

<a href="{{env('APP_URL')}}/api/inquiry/download/{{$pdf}}"> Click here to Download! </a>

{{-- <embed src="{{$pdf}}" width="800px" height="2100px" /> --}}