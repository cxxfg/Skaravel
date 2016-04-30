@extends('layouts.app')
@section('title')
	User List
@endsection
@section('content')
	<table class="table-condensed table-bordered">
		<tr>
			<th>Username</th>
			<th>Role</th>
			<th>Email</th>
			<th>Posts</th>
			<th>Comments</th>
			<th>Created at</th>
		</tr>
		@foreach($users as $user)
		<tr>
			<td><a href="{{ url('/user/'.$user->id) }}">{{ $user->name }}</a></td>
			<td>{{ $user->role }}</td>
			<td>{{ $user->email }}</td>
			<td><a href="{{ url('/user/'.$user->id.'/posts') }}">{{ count($user->posts) }}</a></td>
			<td>{{ count($user->comments) }}</td>
			<td>{{ $user->created_at }}</td>
		</tr>
		@endforeach
	</table>
@endsection