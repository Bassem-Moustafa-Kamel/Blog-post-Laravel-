@extends('layouts.app')

@section('content')
	<h1>Create posts</h1>

	{!! Form::open(['action' => ['PostsController@update' , $post->id] , 'method' => 'POST' , 'enctype' => 'multipart/form-data']) !!}
	{{-- We add $post->id to know which post is updating --}}
    	
		<div class="form-group">
			{{ Form::label('title' , 'Title') }}
			{{ Form::text('title' , $post->title , ['class' => 'form-control' , 'placeholder' => 'Enter the title']) }}
			{{-- We add $post->title and $post->body to make the values already exit --}}
		</div>

		<div class="form-group">
			{{ Form::label('body' , 'Post') }}
			{{ Form::textarea('body' , $post->body , ['id' => 'article-ckeditor' ,'class' => 'form-control' , 'placeholder' => 'Enter the post']) }}
		</div>
		<div class="form-group">
			{{ Form::file('cover_image') }}
		</div>
		{{ Form::hidden('_method' , 'PUT') }}
		{{ Form::submit('Submit' , ['class' => 'btn btn-primary']) }}

	{!! Form::close() !!}

@endsection