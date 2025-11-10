@extends('layouts.app')

@section('header-title', __('Add New Page'))

@section('content')
    <div class="container-fluid mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <h4 class="m-0">{{ __('Add New Page') }}</h4>

            <a href="{{ route('admin.page.index') }}" class="btn btn-sm btn-danger">
                <i class="fa fa-arrow-left"></i>
                {{ __('Back') }}
            </a>
        </div>

        <form action="{{ route('admin.page.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card border-0 rounded-12">
                <div class="card-body">
                    <div>
                        <x-input name='title' type="text" placeholder="Page Name" value="" label="Page Name" />
                    </div>

                    <div class="mt-3">
                        <label for="editor" class="fw-bold mb-2">{{ __('Content') }}</label>

                        @hasPermission('admin.page.generate.AI.data')
                            <button class="btn btn-sm btn-primary rounded mb-1" id="generateAi" type="button">
                                <span class="icon"></span> <strong>Generate AI</strong>
                            </button>
                        @endhasPermission

                        <div id="editor">
                            {!! old('content') !!}
                        </div>
                        <input type="hidden" id="description" name="content" value="{{ old('content') }}" />
                        @error('content')
                            <p class="text text-danger m-0">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button class="btn btn-primary px-4 py-2.5" type="submit">
                        {{ __('Submit') }}
                    </button>
                </div>
            </div>
        </form>

    </div>
@endsection
@push('scripts')
    <script>
        correctULTagFromQuill = (str) => {
            if (str) {
                let re = /(<ol><li data-list="bullet">)(.*?)(<\/ol>)/;
                let strArr = str.split(re);

                while (
                    strArr.findIndex((ele) => ele === '<ol><li data-list="bullet">') !== -1
                ) {
                    let index = strArr.findIndex(
                        (ele) => ele === '<ol><li data-list="bullet">'
                    );
                    if (index) {
                        strArr[index] = '<ul><li data-list="bullet">';
                        let endTagIndex = strArr.findIndex((ele) => ele === "</ol>");
                        strArr[endTagIndex] = "</ul>";
                    }
                }
                return strArr.join("");
            }
            return str;
        };

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
            document.getElementById('description').value = correctULTagFromQuill(quill.root.innerHTML);
        });
    </script>
    <script>
        $(document).on('click', '#generateAi', function() {
            var title = $('#title').val();
            $('#description').val("Generating description・・・・・ Please wait ⏳");
            quill.clipboard.dangerouslyPasteHTML("<p><em>Generating description・・・・・ Please wait ⏳</em></p>");
            $.ajax({
                url: "{{ route('admin.page.generate.AI.data') }}",
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
