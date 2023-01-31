<p>{{ "INI DATA PENGGUNA : " }}</p>

@foreach($data as $i => $dp)
<h3>{{ $i." ".$dp->user->nama." (".$dp->id.")" }}</h3>
<p>{{ $dp->user->email }}</p>
@endforeach