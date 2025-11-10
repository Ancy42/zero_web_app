@extends('layouts.app')

@section('header-title', __('Add New Blog'))

@section('content')
    <div class="page-title">
        <div class="d-flex gap-2 align-items-center">
            <i class="fa-solid fa-shop"></i> {{ __('Add New Blog') }}
        </div>
    </div>

    <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card mt-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="">
                            <x-input label="Title" name="title" type="text" placeholder="Enter Title"
                                required="true" />
                        </div>

                        <div class="mt-3">
                            <label class="form-label">
                                {{ __('Select Category') }}
                                <span class="text-danger">*</span>
                            </label>
                            <select name="category" class="form-control select2" style="width: 100%">
                                <option value="" selected disabled>
                                    {{ __('Select Category') }}
                                </option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="text text-danger m-0">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-3">
                            <label for="tags" class="form-label fw-bold">@lang('Tags')</label>
                            <select id="tags" name="tags[]" class="form-control selectTags" multiple
                                style="width: 100%">
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>
                            <small>@lang('Write tag and Press enter to add tags')</small>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="mb-2">
                            <h5>
                                {{ __('Thumbnail') }}
                                <span class="text-primary">(880 x 440)</span>
                                <span class="text-danger">*</span>
                            </h5>
                        </div>
                        <x-image-picker name="thumbnail" />

                        @error('thumbnail')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-3">
                    <label for="" class="form-label">
                        {{ __('Description') }}
                        <span class="text-danger">*</span>
                    </label>
                     @hasPermission('admin.blog.generate.AI.data')
                        <button class="btn btn-sm btn-primary rounded mb-1" id="generateAi" type="button">
                             <span class="icon"></span> <strong>Generate AI</strong>
                         </button>
                    @endhasPermission
                    <div id="editor" style="max-height: 750px; overflow-y: auto; min-height: 200px">
                        {!! old('description') !!}
                    </div>
                    <input type="hidden" id="description" name="description" value="{{ old('description') }}">
                    @error('description')
                        <p class="text text-danger m-0">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex gap-3 flex-wrap justify-content-end align-items-center w-100">
                    <button type="reset" class="btn btn-lg btn-outline-secondary rounded py-2.5">
                        {{ __('Reset') }}
                    </button>
                    <button type="submit" class="btn btn-lg btn-primary rounded py-2.5 px-5">
                        {{ __('Submit') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $(".selectTags").select2({
                tags: true,
                placeholder: "Write tag and Press enter to add tags"
            })
        });
    </script>

    <script>
        const quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        'header': [1, 2, 3, 4, 5, 6, false]
                    }],
                    [{
                        'font': []
                    }],
                    ['bold', 'italic', 'underline', 'strike', 'blockquote'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'align': []
                    }],
                    [{
                        'script': 'sub'
                    }, {
                        'script': 'super'
                    }],
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }],
                    [{
                        'direction': 'rtl'
                    }],
                    [{
                        'color': []
                    }, {
                        'background': []
                    }],
                    ['link', 'image', 'video', 'formula']
                ]
            }
        });

        quill.on('text-change', function(delta, oldDelta, source) {
            document.getElementById('description').value = quill.root.innerHTML;
        });
    </script>

     <script>
        $(document).on('click', '#generateAi', function() {
            var title = $('#title').val();
            $('#description').val("Generating description・・・・・ Please wait ⏳");
            quill.clipboard.dangerouslyPasteHTML("<p><em>Generating description・・・・・ Please wait ⏳</em></p>");
            $.ajax({
                url: "{{ route('admin.blog.generate.AI.data') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    title: title
                },
                success: function(response) {
                    $('#description').val("");
                    quill.setText("");
                    console.log(response);

                    let lastResponse = "";
                    let fullText = response;
                    let index = 0;

                    function typeStep() {
                        if (index >= fullText.length) return;
                        lastResponse += fullText[index++];
                        $('#description').val(lastResponse);
                        quill.clipboard.dangerouslyPasteHTML(lastResponse);
                        quill.setSelection(quill.getLength(), 0);
                        setTimeout(typeStep, 10); // 10ms delay per character
                    }

                    typeStep();
                },
                error: function(error) {
                    if (error.responseJSON && error.responseJSON.errors) {
                        let firstError = Object.values(error.responseJSON.errors)[0][0];
                        toastr.error(firstError);
                    } else if (error.responseJSON && error.responseJSON.message) {
                        toastr.error(error.responseJSON.message);
                    } else {
                        toastr.error("Something went wrong");
                    }
                    $('#description').val("");
                    quill.setText("");
                }
            })
        });
    </script>
@endpush
