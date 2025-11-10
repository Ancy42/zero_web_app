@extends('layouts.app')

@section('header-title', __('Chats'))

@section('content')

    <style>
        #user-chat-wrapper {
            height: calc(100vh - 160px);
        }

        .chat-side-li-style {
            transition: background-color 0.2s ease-in-out;
        }

        .chat-side-li-style:hover {
            background: #E5E5EA !important;
        }

        .chat-side-ul li.active {
            background: #FFF0F2 !important;
            color: #DD3445;
        }


        .new-message-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: red;
            border-radius: 50%;
        }

        aside {
            width: 34% !important;
        }

        #chat-area {
            width: 65% !important;
        }

        @media (max-width: 1400px) {
            #chat-item-li {
                padding: 6px 8px !important;
                flex-direction: column !important;
                flex-direction: column-reverse !important;
                gap: 4px !important;

            }
        }

        @media (max-width: 992px) {
            #user-chat-wrapper {
                height: 100%;
            }

            #chat-messages {
                height: calc(100vh - 200px);
            }

            aside {
                height: calc(100vh - 100px);
                width: 100% !important;
            }

            #chat-area {
                width: 100% !important;
            }

            #chat-item-li .user-name {
                font-size: 12px;
            }

            #chat-item-li .user-last-message {
                font-size: 10px;
            }
        }

        @media (max-width: 576px) {
            #chat-item-li {
                padding: 4px 8px !important;
                display: block !important;
            }

            #chat-item-li {
                display: block;
            }

            #chat-item-li .user-name {
                font-size: 10px;
            }

            #chat-item-li .user-last-message {
                font-size: 8px;
            }


        }

        .message-container {
            background-color: yellow;
            display: inline-block;
            padding: 8px;
            border-radius: 8px;
            font-weight: 600;
        }
    </style>

    <div id="user-chat-wrapper" class="d-flex flex-column mb-2">
        <!-- Body -->
        <div class="flex-grow-1 overflow-hidden p-2">
            <div class="d-flex flex-column flex-lg-row h-100 bg-white p-2 rounded-4 overflow-hidden gap-3">
                <!-- Sidebar -->
                <aside class="border-end bg-light p-2 p-lg-2 rounded-4 overflow-auto">
                    <div class="bg-white rounded-4 h-100 overflow-auto">
                        <!-- Search -->
                        <h5 class="ps-2 ps-md-3 pt-2 pt-md-3 m-0">{{ __('Phone List') }}</h5>
                        <div class="border-bottom p-2 p-md-3 position-relative">
                            <input type="text" class="form-control ps-5" placeholder="Search Number" id="search">
                            <img src="{{ asset('assets/icons/shop-chat/search.svg') }}" alt="search"
                                class="position-absolute top-50 start-5 translate-middle-y ms-3"
                                style="width: 20px; height: 20px;">
                        </div>
                        <!-- users List -->
                        <ul class="list-unstyled m-0 p-2 chat-side-ul" id="phoneList">
                            <!-- Example Seller Item -->
                            @include('admin.whatsApp.userlist')
                        </ul>
                    </div>
                </aside>

                <!-- Chat Area -->
                <div id="chat-area" class="d-flex flex-column rounded-4 bg-light p-2 flex-grow-1">
                    <div class="bg-white rounded-4 shadow d-flex flex-column flex-grow-1 overflow-hidden">
                        <!-- Chat Header -->
                        <div class="bg-dark text-white px-4 py-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <img id="chat-logo" src="{{ asset('default/default.jpg') }}" class="rounded-circle"
                                    width="40" height="40">
                                <div>
                                    <div class="fw-semibold" id="chat-name" ></div>
                                    <div class="fw-semibold" id="chat-phone"></div>
                                    <small id="chat-status"></small>
                                </div>
                            </div>
                        </div>

                        <!-- Chat Messages -->
                        <div id="chat-messages" class="flex-grow-1 overflow-auto p-4">
                            <div class="d-flex flex-column align-items-center justify-content-center gap-2 mb-3"
                                style="height: calc(100% - 80px);">
                                <img src="{{ asset('default/default.jpg') }}" class="rounded-circle" width="40"
                                    height="40">
                                <div class="text-muted text-center">
                                    <p class="fw-bold">{{ __('No User Found') }}</p>
                                    <p class="small">{{ __('Please select a user to see their messages') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="mx-auto w-75">

                        <!-- Input Box -->
                        <form id="chat-form">
                            @csrf
                            <div id="chat-input-box" class="d-none d-flex align-items-center gap-2 p-3">
                                <div class="border rounded-4 w-100 d-flex align-items-start gap-2 py-1">
                                    <input type="hidden" name="phone_number" id="phone_number">
                                    <textarea rows="1" id="chat-input" type="text" class="border-0 px-4 py-2 w-75 flex-grow-1"
                                        placeholder="Type a message" name="message"
                                        style="word-break: break-all; overflow-y: auto; resize: none; outline: none !important;"></textarea>
                                    <button class="btn" type="button" onclick="handleSubmit()">
                                        <img src="{{ asset('assets/icons/shop-chat/sent-chat.svg') }}" class="img-fluid"
                                            width="24" height="24">
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        function fetchPhoneList() {
            const url = "{{ route('admin.whatsAppChat.phoneList') }}";

            $.get(url, function(response) {
                if (response) {
                    $('#phoneList').empty();
                    $('#phoneList').html(response);
                }
            });
        }

        function handleSubmit() {
            const url = "{{ route('admin.whatsAppChat.sendMessage') }}";
            const formData = $('#chat-form').serialize();
            const phone_number = $('#phone_number').val();
            $.post(url, formData, function(response) {
                if (response) {
                    $('#chat-messages').html(response);
                    $('#chat-input').val('');
                    connectPusherChat(phone_number)
                    fetchPhoneList();
                    const container = document.querySelector('#chat-messages');
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                }
            });
        }

        var channels = {};

        const connectPusherChat = (phone_number) => {
            if (phone_number !== '') {
                selectedNumber = phone_number;

                var channelName = 'whatsAppNumber.' + phone_number;

                if (channels[channelName]) {
                    pusher.unsubscribe(channelName);
                }

                var channel = pusher.subscribe(channelName);
                channels[channelName] = channel;

                channel.bind('whatsapp_chat', function(data) {
                    let mediaHtml = '';
                    if (data.message.media_urls) {
                        let ext = data.message.media_urls.split('.').pop().toLowerCase();
                        let path = `/storage/${data.message.media_urls}`;

                        if (['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'ico'].includes(ext)) {
                            mediaHtml =
                                `<img src="${path}" alt="Image" style="width: 120px; height: auto; margin: 5px 0;">`;
                        } else if (ext === 'pdf') {
                            mediaHtml =
                                `<a href="${path}" target="_blank" style="font-size: 32px; margin: 5px 0;">📄 View PDF</a>`;
                        } else {
                            mediaHtml =
                                `<a href="${path}" download style="font-size: 32px; margin: 5px 0;">📁 Download</a>`;
                        }
                    }
                    var html = `<div class="d-flex justify-content-start align-items-start gap-2">
                        <img src="{{ asset('default/default.jpg') }}" class="rounded-circle" width="30" height="30">
                        <div style="max-width: 85%;">
                            <div class="bg-light rounded px-3 py-2 mb-1" style="word-break: break-word; text-align: justify;">
                                ${data.message.body}
                                ${mediaHtml}</div>
                        </div>
                    </div>
                    <div class="time-section text-start mb-3 ms-4">
                        <small class="text-muted">${data.date}</small>
                    </div>`;
                    document.getElementById('chat-messages').innerHTML += html;

                    const container = document.querySelector('#chat-messages');
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                    fetchPhoneList();
                });
            }
        }

        $(document).ready(function() {

            $('#phoneList').on('click', '.chat-side-li-style', function() {
                $(".chat-side-li-style").removeClass("active");
                $(this).addClass("active");

                var phone_number = $(this).data('phone_number');
                var profile_name = $(this).data('profile_name');
                showMessage(phone_number);
                $('#chat-name').text(profile_name);
                $('#chat-phone').text('+' + phone_number);
                $('#phone_number').val(phone_number);
                // $('#chat-status').text('Online');
            });



            $("#search").on("input", function() {

                const search = $(this).val();
                const finalUrl = "{{ route('admin.whatsAppChat.index') }}" + "?search=" +
                    encodeURIComponent(search);
                if (search) {
                    $.get(finalUrl, function(response) {
                        if (response) {
                            $('#phoneList').html(response);
                        } else {
                            $('#phoneList').html('');
                        }
                    });
                } else {
                    fetchPhoneList();
                }

            });

            function showMessage(phone_number) {
                connectPusherChat(phone_number)
                const url = "{{ route('admin.whatsAppChat.messageShow', ':phone_number') }}".replace(
                    ':phone_number', phone_number);
                $.get(url, function(response) {
                    if (response) {
                        $('#chat-messages').html(response);
                        $('#chat-input-box').removeClass('d-none');
                        const container = document.querySelector('#chat-messages');
                        if (container) {
                            container.scrollTop = container.scrollHeight;
                        }
                    }
                });
            }
        });
    </script>
@endpush
