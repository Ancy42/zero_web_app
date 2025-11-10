@extends('layouts.app')

@section('header-title', __('Edit Plan'))

@section('content')
    <div class="page-title mb-3">
        <div class="d-flex gap-2 align-items-center">
            {{ __('Edit Plan') }}
        </div>
    </div>
    <form action="{{ route('admin.subscription-plan.update', $subscriptionPlan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-body">
                <div>
                    <x-input label="Name" name="name" type="text" placeholder="Enter Name" :value="$subscriptionPlan->name"
                        required="true" />
                </div>

                <div class="mt-3">
                    <label for="short_description" class="form-label">
                        {{ __('Short Description') }}
                    </label>
                    <textarea name="short_description" class="form-control @error('short_description') is-invalid @enderror"
                        rows="2" placeholder="Enter short description">{{ old('short_description', $subscriptionPlan->short_description) }}</textarea>
                    @error('short_description')
                        <p class="text text-danger m-0">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="description" class="form-label">
                        {{ __('Description') }}
                    </label>
                    <div id="editor" style="max-height: 750px; overflow-y: auto">
                        {!! old('description', $subscriptionPlan->description) !!}
                    </div>
                    <input type="hidden" id="description" name="description" value="{{ old('description', $subscriptionPlan->description) }}">
                    @error('description')
                        <p class="text text-danger m-0">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <x-input type="text" name="price" label="Price" placeholder="Price" :value="$subscriptionPlan->price"
                        required="true" onlyNumber="true" />
                </div>

                <div class="mt-3">
                    <x-input type="text" name="duration" label="Duration (days)" placeholder="Duration" :value="$subscriptionPlan->duration"
                        onlyNumber="true" />
                </div>

                <div class="mt-3">
                    <x-input type="text" name="sale_limit" label="Sell Limit" placeholder="Sell Limit" :value="$subscriptionPlan->sale_limit"
                        onlyNumber="true" />
                </div>

                <div class="mt-3 d-flex align-items-center gap-4 flex-wrap">
                    <label class="form-label m-0 fw-medium" for="is_popular">
                        {{ __('Is Popular') }}
                    </label>
                    <label class="switch mb-0">
                        <input id="is_popular" type="checkbox"
                            {{ $subscriptionPlan->is_popular ? 'checked' : '' }} name="is_popular">
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="d-flex gap-3 justify-content-end align-items-center my-3">
            <button type="submit" class="btn btn-lg btn-primary rounded py-2 px-5">
                {{ __('Update') }}
            </button>
        </div>
    </form>
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

        var editor = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [ { 'header': [1, 2, 3, 4, 5, 6, false] } ],
                    [ { 'font': [] } ],
                    [ 'bold', 'italic', 'underline', 'strike', 'blockquote' ],
                    [
                        { 'list': 'ordered' },
                        { 'list': 'bullet' }
                    ],
                    [ { 'align': [] } ],
                    [
                        { 'script': 'sub' },
                        { 'script': 'super' }
                    ],
                    [
                        { 'indent': '-1' },
                        { 'indent': '+1' }
                    ],
                    [ { 'direction': 'rtl' } ],
                    [
                        { 'color': [] },
                        { 'background': [] }
                    ],
                    ['link', 'image', 'video', 'formula']
                ]
            }
        });

        editor.on('text-change', function(delta, oldDelta, source) {
            document.getElementById('description').value = correctULTagFromQuill(editor.root.innerHTML);
        });
    </script>
@endpush
