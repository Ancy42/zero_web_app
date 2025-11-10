<div class="image-container lfm" data-container-id="id_{{ uniqid() }}">
    <label class="holder mainThumbnail">
        <img src="{{ $imagePath }}" alt="img"  />
    </label>

    <input type="hidden" class="form-control thumbnailAdd" value="{{ basename($value ?? '') }}" />

    <input class="thumbnailPath" name="{{ $name }}{{ $multiple ? '[]' : '' }}" type="hidden"
        value="{{ $value }}" @if ($multiple) multiple @endif />
</div>
