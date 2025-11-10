@extends('layouts.app')

@section('header-title', __('Appearance'))

@section('content')

    <div class="card mt-3 rounded-12">
        <div class="card-header py-3">
            <h5 class="m-0 card-title fz-20">{{ __('Home Screens') }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach ($homeThemes as $homeTheme)
                    <div class="col-md-4 mb-4 col-6 text-end">
                        <div class="card shadow-lg bg-white">
                             @if ($homeTheme->alias != 'upcoming')
                            <div class="card {{ $homeTheme->is_active ? 'active' : '' }}"
                                style="width: 100%; height: 450px; overflow: auto;">
                                <img src="{{ asset('assets/homeTheme/' . $homeTheme->alias . '.png') }}"
                                    alt="{{ $homeTheme->theme_name }}" class="w-100 h-auto d-block" loading="lazy">
                            </div>


                            <div class="position-absolute w-100 bottom-0 pt-3 rounded-bottom" style="background: linear-gradient(to top, #000000e6, #313030ad, #f4f4f400)">
                                <div style="float: left" class="ms-3">
                                    <h5 class="my-2 text-white" >{{ $homeTheme->theme_name }}</h5>
                                </div>
                                <div class="mb-3 me-3">
                                    <label class="switch mb-0" data-bs-toggle="tooltip" data-bs-placement="left"
                                        data-bs-title="{{ __('Update Theme status') }}">
                                        <a href="{{ route('admin.themeColor.status', $homeTheme->id) }}">
                                            <input type="checkbox" {{ $homeTheme->is_active ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </a>
                                    </label>
                                    @hasPermission('admin.offerBanner.index')
                                        <a href="{{ route('admin.offerBanner.index', $homeTheme->id) }}"
                                            class="btn btn-primary btn-sm" data-bs-toggle="tooltip"
                                            data-bs-placement="left" data-bs-title="{{ __('Edit Theme Image') }}">
                                            <img src="{{ asset('assets/icons-admin/edit.svg') }}" alt="icon" loading="lazy" />
                                        </a>
                                    @endhasPermission
                                </div>
                            </div>
                            @else
                            <div class="card" style="width: 100%; height: 450px; overflow: auto;">
                                <img src="{{ asset('assets/Coming_Soon.png') }}"
                                    alt="{{ $homeTheme->theme_name }}" class="w-100 h-100 d-block" loading="lazy">
                            </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>


    <form action="{{ route('admin.themeColor.update') }}" id="colorForm" method="POST">
        @csrf
        <div class="card mt-3 rounded-12">
            <div class="card-header d-flex gap-2 align-items-center justify-content-between flex-wrap py-3">
                <h5 class="m-0 card-title fz-20">{{ __('Selected Color') }}</h5>
            </div>
            <div class="card-body" id="colorBox">
                <div class="color-panel">
                    <button class="primary-color text-center" style="background: {{ $currentTheme['primary'] }} ;height:80px">
                        <span class="color">{{ $currentTheme['primary'] }}</span>
                        <span class="">{{ $currentTheme['theme_name'] }}</span>
                    </button>
                    <div class="variants" style="height: 80px">
                        @php
                            $allColors = $currentTheme;
                            unset($allColors['id'], $allColors['updated_at'], $allColors['created_at'], $allColors['is_default'], $allColors['theme_name'], $allColors['secondary'], $allColors['primary']);
                        @endphp
                        @foreach ($allColors as $color)
                            <div class="variant-item">
                                <div
                                    class="varint-color"style="background: {{ $color }};height:60px">
                                </div>
                                <span class="color">{{ $color }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <input type="hidden" name="selected_id" value="{{$currentTheme['id']}}"/>
        </div>
    </form>

    <div class="card mt-4 mb-3">
        <div class="card-header py-3">
            <h5 class="card-title fz-18 m-0">
                {{ __('Available Colors palette') }}
            </h5>
        </div>
        <div class="card-body">
            <div class="d-flex gap-3 flex-column w-100">
                @foreach ($themeColors as $themeColor)
                    @php
                    $allColors = clone $themeColor;
                    unset($allColors['id'], $allColors['updated_at'], $allColors['created_at'], $allColors['is_default'], $allColors['theme_name'], $allColors['secondary']);
                    @endphp
                        <div class="color-panel {{ $themeColor->is_default ? 'active' : '' }}"
                        onclick="setColor({{ json_encode($allColors) }}, {{ $themeColor['id'] }})">
                        <button class="primary-color text-center" style="background: {{ $themeColor->primary }} ;height:80px">
                            <span class="color">{{ $themeColor->primary }}</span>
                            <span class="">{{ $themeColor->theme_name }}</span>
                        </button>
                        <div class="variants" style="height: 80px">
                            @php
                                $shades = [50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950];
                            @endphp
                            @foreach ($shades as $shade)
                                <div class="variant-item">
                                    <div
                                        class="varint-color {{ $loop->index > 4 ? 'text-white' : '' }}"style="background: {{ $themeColor['variant_' . $shade] }};height:60px">
                                    </div>
                                    <span class="shade">{{ $shade }}</span>
                                    <span class="color">{{ $themeColor['variant_' . $shade] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
             @hasPermission('admin.themeColor.update')
                <div class="d-flex justify-content-end pe-0 mt-4">
                    <button type="button" class="btn btn-primary py-2.5 px-3 me-3" data-bs-toggle="modal"
                        data-bs-target="#colorModal">
                        <img src="{{ asset('assets/icons-admin/color.svg') }}" alt="icon" loading="lazy" />
                        {{ __('Change Color Palette') }}
                    </button>

                    <button type="button" onclick="event.preventDefault(); document.getElementById('colorForm').submit();" class="btn btn-primary py-2.5 px-3">
                        {{ __('Save And Update') }}
                    </button>
                </div>
            @endhasPermission
        </div>
    </div>



    <form action="{{ route('admin.themeColor.change') }}" method="post">
        @csrf
        <div class="modal fade" id="colorModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="colorModalLabel">
                            {{ __('Change Color Palette') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <input type="text" id="generatedColorVariants" name="generated_color_variants" hidden>

                        <div class="mb-3">
                            <label for="primary_color" class="form-label fw-bold fz-18">
                                {{ __('Select Your Primary Color') }}
                            </label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text p-0 h-100" style="border-radius: 0">
                                        <input type="color" id="colorPalette" name="primary_color"
                                            style="height: 100%" class="border" value="{{ $currentTheme['primary'] }}">
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Enter hex code e.g. #EE456B"
                                    id="colorInput" value="{{ $currentTheme['primary'] }}">
                            </div>
                        </div>
                    </div>
                    <div
                        class="border-bottom border-top d-flex align-items-center justify-content-between flex-wrap gap-3 px-3 py-2.5">
                        <button type="button" class="btn btn-light py-2.5 flex-grow-1" data-bs-dismiss="modal"
                            style="max-width: 50%;">
                            {{ __('Close') }}
                        </button>
                        @hasPermission('admin.themeColor.change')
                            <button type="submit" class="btn btn-primary py-2.5 flex-grow-1" style="max-width: 50%;">
                                {{ __('Save changes') }}
                            </button>
                        @endhasPermission
                    </div>
                    <div class="card-body pt-2">
                        <div class="mx-auto" id="colorPanel">
                            <div id="colorVariants" class="mt-2 variants"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('css')
    <style>
        .color-panel {
            display: flex;
            padding: 16px;
            border: 1px solid #D7DAE0;
            border-radius: 8px;

            &.active {
                border-color: var(--theme-color);
            }
        }

        .color-panel .primary-color {
            width: 140px;
            border: none;
            padding: 22px 10px;
            cursor: pointer;
            text-transform: capitalize;
            text-align: left;
            color: #fff;
            transition: all 0.3s;
            position: relative;
            border-radius: 6px;
            overflow: hidden;
        }

        .color-panel .primary-color .color {
            position: absolute;
            bottom: 0;
            right: 0;
            left: 0;
            background: #000;
            padding: 5px 8px;
            font-size: 12px;
            text-align: center;
            border: 2px dotted transparent;
        }
        .color-panel .primary-color .theme_name {
            font-size: 20px;
        }

        .color-panel .primary-color:hover {
            border: 2px dotted #ccc;
            box-shadow: 0 0 10px 5px rgba(0, 0, 0, 0.1);
            transform: scale(1.02);
        }

        .color-panel .primary-color .name {
            font-size: 16px;
            font-weight: 700;
        }

        .color-panel .variants {
            display: flex;
            flex-grow: 1;
            gap: 8px;
            cursor: pointer;
            margin-left: 16px;
            max-width: 1100px;
        }

        .color-panel .variants .variant-item {
            flex-grow: 1;

        }

        .color-panel .varint-color {
            padding: 5px 10px;
            width: 100%;
            height: 80px;
            border-radius: 8px;
        }

        .color-panel .variants .variant-item .shade {
            display: block;
            font-size: 12px;
            margin: 0;
            line-height: 16px;
        }

        .color-panel .variants .variant-item .color {
            font-size: 12px;
            color: #757575;
            line-height: 16px;
        }

        @media (max-width: 991px) {
            .color-panel .variants {
                flex-direction: column;
                margin-left: 0;
                gap: 0;
            }

            .color-panel .primary-color {
                width: 100%;
                color: #fff;
                border-radius: 0;
                text-align: left;
                height: 65px;
            }

            .color-panel .primary-color .color {
                background: transparent;
                top: 50%;
                transform: translateY(-50%);
            }

            .color-panel {
                flex-direction: column;
            }

            .color-panel .varint-color {
                padding: 5px 10px;
                width: 100%;
                height: 35px;
                border-radius: 0;
            }

            .color-panel .variants .variant-item .color,
            .color-panel .variants .variant-item .shade {
                display: none;
            }
        }

        .card.active {
            border: 2px solid var(--theme-color) !important;
            box-shadow: 0 0 10px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@push('scripts')
    <script>
        function setColor(colors, id) {
            console.log(id);

            let colorBox = document.getElementById('colorBox');
             const shades = [50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950];
             var elements = '';

            shades.forEach(shade => {

                elements += `<div class="variant-item">
                                <div
                                    class="varint-color text-white" style="background: ${colors['variant_' + shade]};height:60px">
                                </div>
                                <span class="shade">${colors['variant_' + shade]}</span>
                                <span class="color"${colors['variant_' + shade]}</span>
                            </div>`
                })

                var fullPalette = `<div class="color-panel">
                    <span class="primary-color" style="background: ${colors['primary']}; height:80px">
                        <span class="color">${colors['primary']}</span>
                    </span>
                    <div class="variants" style="height: 80px">

                        ${elements}

                    </div>
                </div>`
                $("input[name=selected_id]").val(id);
                colorBox.innerHTML = fullPalette;
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/chroma-js/2.1.0/chroma.min.js"></script>
    <script>
        $('#colorPalette').on('input', function() {
            $('#colorInput').val($(this).val());
            generateTailwindColors();
        });

        $('#colorInput').on('input', function() {
            $('#colorPalette').val($(this).val());
            generateTailwindColors();
        });

        generateTailwindColors();

        function generateTailwindColors() {
            $('#colorPanel').show();
            const baseColor = document.getElementById('colorInput').value || '#a855f7';
            const colorVariants = generatePalette(baseColor);
            displayColorVariants(colorVariants);

            document.getElementById('generatedColorVariants').value = JSON.stringify(colorVariants);
        }

        function generatePalette(baseColor) {
            const palette = {};

            palette[50] = chroma.mix(baseColor, '#ffffff', 0.9, 'rgb').hex(); // Lighten 90%
            palette[100] = chroma.mix(baseColor, '#ffffff', 0.8, 'rgb').hex(); // Lighten 80%
            palette[200] = chroma.mix(baseColor, '#ffffff', 0.6, 'rgb').hex(); // Lighten 60%
            palette[300] = chroma.mix(baseColor, '#ffffff', 0.4, 'rgb').hex(); // Lighten 40%
            palette[400] = chroma.mix(baseColor, '#ffffff', 0.2, 'rgb').hex(); // Lighten 20%
            palette[500] = /^#/.test(baseColor) ? baseColor : `#${baseColor}`; // Base color
            palette[600] = chroma.mix(baseColor, '#000000', 0.1, 'rgb').hex(); // Darken 10%
            palette[700] = chroma.mix(baseColor, '#000000', 0.2, 'rgb').hex(); // Darken 20%
            palette[800] = chroma.mix(baseColor, '#000000', 0.3, 'rgb').hex(); // Darken 30%
            palette[900] = chroma.mix(baseColor, '#000000', 0.4, 'rgb').hex(); // Darken 40%
            palette[950] = chroma.mix(baseColor, '#000000', 0.5, 'rgb').hex(); // Darken 50%
            return palette;
        }

        function displayColorVariants(variants) {
            const colorContainer = document.getElementById('colorVariants');
            colorContainer.innerHTML = ''; // Clear previous variants

            for (const [shade, color] of Object.entries(variants)) {
                const colorDiv = document.createElement('div');
                colorDiv.style.backgroundColor = color;
                colorDiv.classList.add('varint-color');
                colorDiv.style.padding = '8px 10px';
                colorDiv.style.color = chroma(color).luminance() > 0.5 ? '#000' : '#fff';
                colorDiv.innerText = `${shade}: ${color}`;
                colorContainer.appendChild(colorDiv);
            }
        }
    </script>
@endpush
