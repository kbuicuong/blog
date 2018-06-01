@extends('main')

@section('title', '| Edit Blog Post')

@section('stylesheets')

    {!! Html::style('css/select2.min.css') !!}

       <!-- Here because it's loaded at the bottom of the head -->
    <script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>

    <script>
        tinymce.init({
            selector: 'textarea',
            plugins: 'link code'

        });
    </script>

@endsection

@section('content')

	<div class="row">
		<!-- Model form binding-->
																	<!-- check the route list (get or put OR delete)-->
		{!! Form::model($post, ['route' => ['posts.update', $post->id], 'method' => 'PUT', 'files' => true]) !!}
		<div class="col-md-8">
			{{ Form::label('title', 'Title:') }}
			<!-- matches with the column name in the db-->
			{{ Form::text('title', null, ["class" => 'form-control input-lg'] ) }}

			{{ Form::label('slug', 'Slug:', ["class" => 'form-spacing-top']) }}
			{{ Form::text('slug', null, ["class" => 'form-control']) }}

			{{ Form::label('category_id', 'Category: ', ["class" => 'form-spacing-top']) }}
			{{ Form::select('category_id', $categories, $post->category_id, ['class' => 'form-control']) }}

			{{ Form::label('tags', 'Tags: ', ['class' => 'form-spacing-top']) }}
			{{ Form::select('tags[]', $tags, null, ['class' => 'form-control select2-multi', 'multiple' => 'multiple']) }}

			{{ Form::label('featured_image', 'Update Featured Image', ["class" => 'form-spacing-top']) }}
			{{ Form::file('featured_image') }}

			{{ Form::label('body', 'Body:', ["class" => 'form-spacing-top']) }}
			{{ Form::textarea('body', null, ["class" => 'form-control'] ) }}
		</div>

		<div class="col-md-4">
			<div class="well">
				<dl class="dl-horizontal">
					<label>URL Slug:</label>
					<p><a href="{{ url($post->slug) }}">{{ url($post->slug) }}</a></p>
				</dl>

				<dl class="dl-horizontal">
					<label>Category:</label>			
					<p>{{ $post->category->name }}</p>
				</dl>
				
				<dl class="dl-horizontal">
					<label>Created at:</label>
					<p>{{ date('M j, Y h:i a', strtotime($post->created_at)) }} </p>
				</dl>

				<dl class="dl-horizontal">
					<label>Last updated:</label>
					<p>{{ date('M j, Y h:i a', strtotime($post->updated_at)) }} </p>
				</dl>
				<hr>
				<hr>

				<div class="row">
					<div class="col-sm-6">
						{!! Html::linkRoute('posts.show', 'Cancel', array($post->id), array('class' => 'btn btn-danger btn-block') ) !!}
					</div>

					<div class="col-sm-6">
						{{ Form::submit('Save Changes', ["class" => 'btn btn-success btn-block']) }}
					</div>

				</div>

			</div>
		</div>
		{!! Form::close() !!}
	</div> <!-- End of the form -->

@stop

@section('scripts')
    {!! Html::script('js/select2.min.js') !!}

    <script type="text/javascript">
    	$('.select2-multi').select2();
    </script>

@endsection