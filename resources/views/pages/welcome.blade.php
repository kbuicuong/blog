
<!-- if it's in the layout folder you'd do layout.main -->
@extends('main')

@section('title', ' | Home')

@section('content')
        <div class="row">
            <div class="col-md-12">
                <!-- A lightweight, flexible component that can optionally extend the entire viewport to showcase key content on your site. -->
                <div class="jumbotron">
                    <h1>Welcome to my Blog!</h1>
                    <p class="lead">Thank you for visiting. This is my test website built with Laravel. Please read my latest post!</p>
                    <p><a class="btn btn-primary btn-lg" href="#" role="button">Popular post</a></p>
                </div>
            </div>
        </div> <!-- end of .row-->

        <div class="row">
            <div class="col-md-8"> 

                @foreach($posts as $post)

                    <div class="post">
                        <h3>{{ $post->title }}</h3>
                        <p> {{ substr(strip_tags($post->body), 0, 300) }}{{ strlen(strip_tags($post->body)) > 50 ? "...": "" }}</p>
                        <a href="{{ url('blog/'.$post->slug) }}" class="btn btn-primary">Read more</a>
                    </div>
                    <hr> <!-- divider -->
                  
                @endforeach
            </div>

            <div class="col-md-3 col-md-offset-1">

                <h2>Sidebar</h2>
            </div>



        </div>

        
    </div> <!-- end of .container -->
@endsection

