@extends('layouts.app')

@section('content')
	<h1>Create posts</h1>

	{!! Form::open(['action' => 'PostsController@store' , 'method' => 'POST' , 'enctype' => 'multipart/form-data']) !!}
	{{-- enctype is used for file uploading int the file function below --}}
    	
		<div class="form-group">
			{{ Form::label('title' , 'Title') }}
			{{ Form::text('title' , '' , ['class' => 'form-control' , 'placeholder' => 'Enter the title']) }}
		</div>

		<div class="form-group">
			{{ Form::label('body' , 'Post') }}
			{{ Form::textarea('body' , '' , ['id' => 'article-ckeditor' ,'class' => 'form-control' , 'placeholder' => 'Enter the post']) }}
		</div>
		<div class="form-group">
			{{ Form::file('cover_image') }}
		</div>
		{{ Form::submit('Submit' , ['class' => 'btn btn-primary']) }}

	{!! Form::close() !!}

@endsection