@php
    $response = Http::post('https://products.razinsoft.com/fetch-update/' . config('installer.productId'), [
        'key' => Abedin\Maker\Lib\Traits\ManagerTrait::getPurchaseKey(),
    ]);
    $data = $response->json()['data'];

    $logPath = storage_path('change.json');
    $logs = [];
    if (file_exists($logPath)) {
        $logContent = file_get_contents($logPath);
        $logs = json_decode($logContent, true);
    }
@endphp
<!-- update section  -->
<section class="update_section_container">
    @if ($data['is_expired'])
        <div class="expire_section_container">
            <div class="w-full">
                <div class="expire_notice_container">
                    <p>Your support expired on {{ $data['expired_at'] }}.</p>

                    <p>{{ $data['diff'] }} Ago</p>
                </div>
                <div class="update_heading mt-4 ">
                    <p>
                        Support Expired
                    </p>
                    <p>Renew support to get help from the <span class="text-blue">Author</span> for 6 months.</p>
                </div>
            </div>
        </div>
    @endif

    @if ($data['version'] && $data['version'] > config('app.version'))
        <!-- notes  -->
        <div class="notes_container">
            <p class="heading text-black">Important Notes</p>

            <div class="note">1. Do not click "Update Now" if the application is customized. Your changes will be lost.
            </div>
            <div class="note">2. Take a backup of files and database before updating.</div>
        </div>
    @endif

    <!-- update info  -->

    <div class="update_info_container">
        @if ($data['version'] && $data['version'] > config('app.version'))
            <div class="new_update_container">
                <div class="header">
                    <div class="folder_icon">
                        <img src="{{ asset('assets/icons-admin/market/folder.svg') }}" alt="">
                    </div>
                    <div class="flex flex-col gap-2">
                        <p class="heading text-white">New Update Available</p>

                        <button class="btn_version">
                            Version: {{ $data['version'] }}
                        </button>
                    </div>
                    <a class=" common_btn" style="margin-left: auto" type="btn" href="{{ route('updater.index') }}">
                        <img src="{{ asset('assets/icons-admin/download2.svg') }}" alt="">
                        Update Now
                    </a>
                </div>

                <div class="notes">
                    <p class="text-white"><span class="text-yellow">Note *</span> You will be logged out after the
                        update. Log in again to use the application.</p>
                </div>
            </div>
        @else
            <div class="new_update_container">
                <div class="header">
                    <div class="folder_icon">
                        <img width="75px" src="{{ asset('assets/icons-admin/market/folder-check.svg') }}"
                            alt="">
                    </div>
                    <div class="flex flex-col gap-2">
                        <p class="heading text-white">Your app is running the latest version</p>


                        <button class="btn_version">
                            Version: {{ $data['version'] }}
                        </button>
                    </div>
                </div>
            </div>
        @endif


        <div class="update_summary_container">
            <div class="border-b w-full">
                <p class="heading pb-4 w-full">Update Summary</p>
            </div>

            @forelse ($logs as $log)
                <div class="version_container">
                    <p>Version: {{ $log['version'] }} (Release date: {{ $log['date'] }})</p>
                    <ul>
                        @foreach ($log['notes'] as $note)
                            <li>{{ $note }}</li>
                        @endforeach
                    </ul>
                </div>
            @empty
            @endforelse
        </div>
    </div>
</section>
