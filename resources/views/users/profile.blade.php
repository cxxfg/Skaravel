@extends('layouts.app')
@section('title')
	{{ $user->name }}
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
<div>
  <ul class="list-group">
    <li class="list-group-item">
      Joined on {{$user->created_at->format('M d,Y \a\t h:i a') }}
    </li>
    <li class="list-group-item panel-body">
      <table class="table-padding">
        <style>
          .table-padding td{
            padding: 3px 8px;
          }
        </style>
        <tr>
          <td>Total Posts</td>
          <td> {{$posts_count}}</td>
          @if($author && $posts_count)
          <td><a href="{{ url('/myposts')}}">Show All</a></td>
          @endif
        </tr>
        <tr>
          <td>Published Posts</td>
          <td>{{$posts_active_count}}</td>
          @if($posts_active_count)
          <td><a href="{{ url('/user/'.$user->id.'/posts')}}">Show All</a></td>
          @endif
        </tr>
        <tr>
          <td>Posts in Draft </td>
          <td>{{$posts_draft_count}}</td>
          @if($author && $posts_draft_count)
          <td><a href="{{ url('mydrafts')}}">Show All</a></td>
          @endif
        </tr>
      </table>
    </li>
    <li class="list-group-item">
      Total Comments {{$comments_count}}
    </li>
  </ul>
</div>

<!-- About -->
@if(!empty($aboutme) || (Auth::user()->name == $user->name))
<div class="panel panel-default">
  <div class="panel-heading"><h3>About User</h3></div>
  <div class="panel-body">
  @if((Auth::user()->name == $user->name))
    <form action="{{ url('/user/'.$user->id.'/edit-about')}}" method="get">
        @if(empty($aboutme))
          <p>Click <a href="{{ url('/user/'.$user->id.'/edit-about') }}">here</a> to write something about yourself</p>
        @else
          {!! $user->aboutme !!}
          <a href="{{ url('/user/'.$user->id.'/edit-about') }}"><b>Edit</b></a>
        @endif
    </form>
  @else
    {!! $user->aboutme !!}
  @endif
  </div>
</div>
@endif

<!-- Posts -->
<div class="panel panel-default">
  <div class="panel-heading"><h3>Latest Posts</h3></div>
  <div class="panel-body">
    @if(!empty($latest_posts[0]))
    @foreach($latest_posts as $latest_post)
      <p>
        <strong><a href="{{ url('/'.$latest_post->slug) }}">{{ $latest_post->title }}</a></strong>
        <span class="well-sm">On {{ $latest_post->created_at->format('M d,Y \a\t h:i a') }}</span>
      </p>
    @endforeach
    @else
    <p>You have not written any blog posts.</p>
    @endif
  </div>
</div>

<!-- Comments -->
<div class="panel panel-default">
  <div class="panel-heading"><h3>Latest Comments</h3></div>
  <div class="list-group">
    @if(!empty($latest_comments[0]))
    @foreach($latest_comments as $latest_comment)
      <div class="list-group-item">
        <p><i>{{ $latest_comment->body }}</i> in <a href="{{ url('/'.$latest_comment->post->slug) }}">{{ $latest_comment->post->title }}</a> ({{ $latest_comment->created_at->format('M d,Y \a\t h:i a') }})</p>
      </div>
    @endforeach
    @else
    <div class="list-group-item">
      <p>You have not made any comments.</p>
    </div>
    @endif
  </div>
</div>
@endsection