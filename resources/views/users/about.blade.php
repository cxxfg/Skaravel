@extends('layouts.app')
@section('title')
About you
@endsection
@section('content')
<script type="text/javascript" src="{{ asset('/js/tinymce/tinymce.min.js') }}"></script>
<script type="text/javascript">
	tinymce.init({
		selector : "textarea",
		plugins : ["advlist autolink lists link image charmap print preview anchor", "searchreplace visualblocks code fullscreen", "insertdatetime media table contextmenu paste"],
		toolbar : "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
	});
</script>
<form method="post" action='{{ url('/user/'.$user->id.'/edit-about') }}'>
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<div class="form-group">
		<textarea name='about'class="form-control">{{ old('about') }}</textarea>
	</div>
	<input type="submit" name='publish' class="btn btn-success" value = "Publish"/>
</form>
@endsection