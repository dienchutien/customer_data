<strong>Chăm luôn nhé:</strong><br>
@if(isset($a_EmailBody) && count($a_EmailBody) > 0)
@foreach($a_EmailBody as $EmailBody )
<strong>Email</strong><?php echo $EmailBody->email?><br>
<strong>SDT </strong><?php echo $EmailBody->phone?><br>
<strong>Dư án: </strong><?php echo $EmailBody->project?><br><hr>
@endforeach
@endif
