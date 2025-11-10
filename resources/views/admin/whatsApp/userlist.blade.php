@foreach ($contacts as $contact)
    <li class="chat-user-item d-flex justify-content-between align-items-start p-3 mb-2 rounded bg-white chat-side-li-style"
        style="cursor: pointer;" data-phone_number="{{ $contact->phone_number }}" data-profile_name="{{ $contact->profile_name ?? '' }}">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('default/default.jpg') }}" class="rounded-circle border" width="40" height="40" />
            <div>
                <div class="fw-bold">{{ $contact->profile_name }}</div>
                <small
                    class="text-muted">+{{ $contact->phone_number }}</small>
            </div>
        </div>
        <small class="text-muted">{{ \Carbon\Carbon::parse($contact->last_message_at)->diffForHumans() }}</small>
    </li>
@endforeach
