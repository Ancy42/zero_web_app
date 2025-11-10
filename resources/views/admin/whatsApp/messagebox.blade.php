@foreach ($contact->messages as $message)
    @if ($message->direction == 'inbound')
        <div class="d-flex 'justify-content-end align-items-start gap-2">
            <img src="{{ asset('default/default.jpg') }}" class="rounded-circle" width="30" height="30">
            <div style="max-width: 85%;">
                <div class="bg-light rounded px-3 py-2 mb-1" style="word-break: break-word; text-align: justify;">
                    {{ $message->body }}
                    @if (!empty($message->media_urls))
                        @php
                            $path = asset('storage/' . $message->media_urls);
                            $ext = strtolower(pathinfo($message->media_urls, PATHINFO_EXTENSION));
                        @endphp

                        @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'ico']))
                            <img src="{{ $path }}" alt="Image"
                                style="width: 120px; height: auto; margin: 5px 0;">
                        @elseif ($ext === 'pdf')
                            <a href="{{ $path }}" target="_blank" style="font-size: 32px; margin: 5px 0;">📄
                                View PDF</a>
                        @else
                            <a href="{{ $path }}" download style="font-size: 32px; margin: 5px 0;">📁
                                Download</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        <div class="time-section text-start mb-3 ms-4">
            <small class="text-muted">{{ $message->created_at->format('d M Y h:i A') }}</small>
        </div>
    @else
        <div class="d-flex justify-content-end align-items-start gap-2">
            <div style="max-width: 85%;">
                <div class="rounded text-white px-3 py-2 mb-1"
                    style="word-break: break-word; text-align: justify; background-color: #EE456B">
                    {{ $message->body }}
                </div>
            </div>
            <img src="{{ asset('default/default.jpg') }}" class="rounded-circle" width="30" height="30">
        </div>
        <div class="time-section text-end mb-3 me-4">
            <small class="text-muted">{{ $message->created_at->format('d M Y h:i A') }}</small>
        </div>
    @endif
@endforeach
