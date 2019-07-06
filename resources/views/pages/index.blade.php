@extends('layouts.full')

@section('content')
  <form method="post" action="{{ route('analysis.create') }}" enctype="multipart/form-data">
    @csrf
    <label for="upload">Text document</label>
    <input type="file" id="upload" name="upload" accept=".txt, .rtf, .md, .file">
    <button type="submit">Go!</button>
  </form>

  @if ($errors->any())
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  @endif
@endsection