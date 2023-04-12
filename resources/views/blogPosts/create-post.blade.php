@extends('layout')

@section('main')
<main class="container" style="background-color: #fff;">
    <section id="contact-us">
        <h1 style="padding-top: 50px;">Create New Post!</h1>
        @if (session('status'))
            <p style="color: #fff; width: 100%; font-size: 18px; font-weight:600; text-align: center; background: #5cb85c; padding: 17px 0; margin-bottom: 6px">{{ session('status') }}</p>
        @endif
        <!-- Contact Form -->
        <div class="contact-form">
            <form action="{{ route('blog.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <!-- Title -->
                <label for="title"><span>Title</span></label>
                <input type="text" id="title" name="title" />
                @error("title")
                   <p style="color: red; margin-bottom:25px">{{ $message }}</p>
                @enderror
                <!-- Image -->
                <label for="image"><span>Image</span></label>
                <input type="file" id="image" name="image" />
                @error("image")
                   <p style="color: red; margin-bottom:25px">{{ $message }}</p>
                @enderror
                <!-- Drop down -->
                <label for="categories"><span>Choose a category:</span></label>
                <select name="category_id" id="categories">
                    <option selected disabled>Select option </option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    {{-- The $attributeValue field is/must be $validationRule --}}
                    <p style="color: red; margin-bottom:25px;">{{ $message }}</p>
                @enderror
                <!-- Body-->
                <br>
                <label for="body"><span>Body</span></label>
                <textarea id="body" name="body" cols="12"></textarea>
                @error("body")
                   <p style="color: red; margin-bottom:25px">{{ $message }}</p>
                @enderror
                <!-- Button -->
                <input type="submit" value="Submit" />
            </form>
        </div>

    </section>
</main>

@endsection
