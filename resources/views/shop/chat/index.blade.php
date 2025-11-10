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
            background: #c0e8da !important;
            color: #025616;
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
    </style>

    <div id="user-chat-wrapper" class="d-flex flex-column mb-2">
        <!-- Body -->
        <div class="flex-grow-1 overflow-hidden p-2">
            <div class="d-flex flex-column flex-lg-row h-100 bg-white p-2 rounded-4 overflow-hidden gap-3">
                <!-- Sidebar -->
                <aside class="border-end bg-light p-2 p-lg-2 rounded-4 overflow-auto">
                    <div class="bg-white rounded-4 h-100 overflow-auto">
                        <!-- Search -->
                        <h5 class="ps-2 ps-md-3 pt-2 pt-md-3 m-0">{{ __('Customer List') }}</h5>
                        <div class="border-bottom p-2 p-md-3 position-relative">
                            <input type="text" class="form-control ps-5" placeholder="Search User" id="search">
                            <img src="{{ asset('assets/icons/shop-chat/search.svg') }}" alt="search"
                                class="position-absolute top-50 start-5 translate-middle-y ms-3"
                                style="width: 20px; height: 20px;">
                        </div>
                        <!-- users List -->
                        <ul class="list-unstyled m-0 p-2 chat-side-ul">
                            <!-- Example Seller Item -->
                            <li class="chat-user-item d-flex justify-content-between align-items-start p-3 mb-2 rounded bg-white chat-side-li-style active"
                                style="cursor: pointer;">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ asset('default/default.jpg') }}" class="rounded-circle border"
                                        width="40" height="40" />
                                    <div>
                                        <div class="fw-bold">{{ __('No Users Found') }}</div>
                                        <small class="text-muted">{{ __('No Messages') }}</small>
                                    </div>
                                </div>
                                <small class="text-muted">{{ __('0 min ago') }}</small>
                            </li>
                            <!-- Repeat as needed -->
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
                                    <div class="fw-semibold" id="chat-name">{{ __('No Users') }}</div>
                                    <small id="chat-status"></small>
                                </div>
                            </div>
                        </div>

                        <!-- Chat Messages -->
                        <div id="chat-messages" class="flex-grow-1 overflow-auto p-4">
                            <div id="chat-loader"
                                class="text-center d-flex justify-content-center align-items-center h-100 d-none">
                                <div class="spinner-border text-danger" role="status"></div>
                            </div>
                            <!-- User Message -->
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
                            <div id="chat-input-box" class="d-none d-flex align-items-center gap-2 p-3">
                                <div class="border rounded-4 w-100 d-flex align-items-start gap-2 py-1">
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
        var isSendingMessage = false;
        let selectedUserId = null;
        let messageLists = '';

        $(document).ready(function() {
            connectPusherChat();
            fetchUsers();
        })

        let allUsersLists = [];

        document.addEventListener("DOMContentLoaded", function() {
            const textarea = document.getElementById("chat-input");

            textarea.addEventListener("keydown", function(event) {
                // Check if Enter is pressed without Shift
                if (event.key === "Enter" && !event.shiftKey) {
                    event.preventDefault(); // Prevents newline
                    handleSubmit(); // Call your submit function
                }
            });
        });

        $(document).on('click', '.chat-user-item', function() {
            $('#chat-logo').attr('src', $(this).data('profile-image'));
            $('#chat-name').text($(this).data('user-name'));

            const userID = $(this).data('id');
            $('.chat-user-item').removeClass('active');
            $(this).addClass('active');
            $(`#user-unreadMessage${userID}`).hide();
            $('#unread-message-badge').addClass('d-none');

            let loadderChat = ''

            loadderChat = `
                <div id="chat-loader" class="text-center d-flex justify-content-center align-items-center h-100 d-none">
                     <div class="spinner-border text-danger" role="status"></div>
                </div>
            `

            $('#chat-messages').html(loadderChat);

            selectedUserId = userID;

            fetchMessages(userID);

            window.history.replaceState({}, '', window.location.href.split('?')[0] + '?user=' + userID);

        });

        const handleSubmit = () => {
            const message = ($('textarea[name="message"]').val() || '').trim();
            if (message == '') {
                return
            }
            const userID = $('.chat-user-item.active').data('id');

            messageLists = `
                <div class="d-flex justify-content-end align-items-start gap-2 mb-3">
                    <div style="max-width: 75%;">
                        <div class="bg-light rounded px-3 py-2 mb-1" style="word-break: break-word; text-align: justify;">${linkify(message)}</div>
                        </div>
                        <img src="{{ $shop->logo }}" class="rounded-circle" width="40" height="40">
                    </div>
            `;

            $('#chat-messages').append(messageLists);

            messageLists = '';

            $(`.user-last-message${userID}`).text(message);

            setTimeout(() => {
                $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
            }, 1);

            $('#chat-input').val('');

            setTimeout(() => {
                sendMessage(selectedUserId || userID, message);
            }, 100);

        }

        $(document).on('submit', '#chat-form', function(e) {
            e.preventDefault();
            handleSubmit();
        })

        const fetchUnreadMessages = async () => {
            await $.ajax({
                url: "/api/unread-messages",
                method: "GET",
                data: {
                    shop_id: "{{ $shop->id }}"
                },
                dataType: 'json',
                success: function(response) {

                    if (response.data.unread_messages > 0) {
                        $('#unread-message-badge').text(response.data.unread_messages).removeClass(
                            'd-none');
                    } else {
                        $('#unread-message-badge').addClass('d-none');
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        const fetchUsers = async () => {
            await $.ajax({
                url: "/shop/get-users",
                method: "GET",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    search: $('#search').val()
                },
                dataType: 'json',
                success: function(users) {
                    allUsersLists = users.data.data;
                    let userList = '';

                    if (users.data.data.length == 0) {
                        userList += `
                        <li class="chat-user-item d-flex justify-content-between align-items-start p-3 mb-2 rounded bg-white chat-side-li-style active"
                            style="cursor: pointer;">
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ asset('default/default.jpg') }}" class="rounded-circle border"
                                    width="40" height="40" />
                                <div>
                                    <div class="fw-bold">{{ __('No Users Found') }}</div>
                                    <small class="text-muted">{{ __('No Messages') }}</small>
                                </div>
                            </div>
                            <small class="text-muted">{{ __('0 min ago') }}</small>
                        </li>
                    `;
                    }
                    users.data.data.forEach(user => {
                        userList += `
                            <li id="chat-item-li" class="chat-user-item d-flex justify-content-between align-items-start p-3 mb-2 rounded bg-white chat-side-li-style"
                                style="cursor: pointer;" data-id="${user.user?.id}" data-user-name="${user.user?.name}" data-profile-image="${user.user.profile_photo}" data-status="${user.user?.last_online}">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="${user.user?.profile_photo}" class="rounded-circle border" width="40" height="40" />
                                    <div style="max-width: 75%;">
                                    <div class="user-name fw-bold m-0 p-0 d-flex align-items-center gap-1" style="word-break: break-all;">
                                        <span>${user.user?.name.slice(0, 10)}${user.user?.name.length > 10 ? '...' : ''}</span>
                                        ${user.unread_message_user > 0 ? `<span class="badge rounded-3 bg-danger" style="font-size: 8px;" id="user-unreadMessage${user.user?.id}">${user.unread_message_user}</span>` : ``}
                                    </div>
                                    <p class="user-last-message text-muted m-0" style="word-break: break-all;">
                                        ${user?.last_message.slice(0, 10)}${user?.last_message.length > 10 ? '...' : ''}
                                    </p>
                                    </div>
                                </div>
                                <div>
                                    <small class="text-muted" style="font-size: 8px;">${user?.last_message_time}</small>
                                </div>
                            </li>
                        `;
                    });

                    $('.chat-side-ul').html(userList);


                    if (selectedUserId) {
                        $(`#chat-item-li[data-id="${selectedUserId}"]`).addClass('active');
                    }

                    if (window.location.href.includes('user')) {
                        checkHasUser();
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            })
        }


        const fetchMessages = async (userID) => {

            $("#chat-loader").removeClass('d-none')

            await $.ajax({
                url: `/shop/get-message`,
                method: "GET",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    user_id: userID,
                },
                dataType: 'json',
                success: function(messages) {

                    let messageList = '';
                    let userOnline = messages.data.data.some(message => message.user_active_status ==
                        true)

                    if (userOnline) {
                        $('#chat-status').css('color', 'green').text('Online');
                    } else {
                        $('#chat-status').css('color', 'red').text('Offline');
                    }

                    messages.data.data.forEach(message => {
                        const isUser = message.type === 'user';

                        if (isUser) {
                            const hasProduct = message.product;
                            const hasNoMessage = message.message == null;
                            if (hasProduct && hasNoMessage) {

                                // Show product card only
                                messageList += `
                                <div class="d-flex justify-content-start align-items-start gap-2">
                                    <img src="${message.user?.profile_photo}" class="rounded-circle" width="30" height="30">
                                    <div class="d-flex gap-2 border rounded p-2">
                                        <img src="${message.product?.thumbnail}" class="rounded object-fit-cover" width="40" height="40" />
                                        <div>
                                            <div class="fw-semibold small">${message.product?.name}</div>
                                            <div class="d-flex justify-content-between align-items-center mt-1">
                                                <strong class="me-2">
                                                    $${message.product?.discount_price > 0 ? message.product?.discount_price : message.product?.price }
                                                </strong>
                                                <div class="text-warning small">
                                                    ★${message.product?.rating} <span class="text-muted">(${message.product?.total_reviews})</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="time-section text-start mb-3 ms-4">
                                    <small class="text-muted">${formatDate(message.created_at) }</small>
                                </div>
                            `;
                            } else {
                                // Show text messages
                                messageList += `
                                <div class="d-flex justify-content-start align-items-start gap-2">
                                    <img src="${message.user?.profile_photo}" class="rounded-circle" width="30" height="30">
                                    <div style="max-width: 85%;">
                                        <div class="bg-light rounded px-3 py-2 mb-1" style="word-break: break-word; text-align: justify;">${linkify(message?.message)}</div>
                                    </div>
                                </div>
                                <div class="time-section text-start mb-3 ms-4">
                                <small class="text-muted">${formatDate(message.created_at) }</small>
                                </div>
                            `;
                            }
                        } else {
                            messageList += `
                            <div class="d-flex justify-content-end align-items-start gap-2">
                                <div style="max-width: 85%;">
                                    <div class="rounded text-white px-3 py-2 mb-1" style="word-break: break-word; text-align: justify; background-color: #025616">
                                        ${linkify(message?.message)}
                                        </div>
                                </div>
                                <img src="${message.shop?.logo}" class="rounded-circle" width="30" height="30">
                            </div>
                            <div class="time-section text-end mb-3 me-4">
                                <small class="text-muted">${formatDate(message.created_at) }</small>
                            </div>
                        `;
                        }

                    });


                    $('#chat-messages').html(messageList);

                    $("#chat-loader").addClass('d-none')
                    setTimeout(() => {
                        $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
                    }, 1);
                    $('#chat-input-box').removeClass('d-none');
                    $(`#user-unreadMessage${userID}`).hide();
                    // scrollBottom()
                },
                error: function(error) {
                    console.log(error);
                    $('#chat-input-box').addClass('d-none');
                }
            })
        }


        function linkify(text) {
            if (text) {
                const urlRegex = /(\bhttps?:\/\/[^\s]+)/g;
                return text.replace(urlRegex, function(url) {
                    const safeUrl = $('<div>').text(url).html(); // escape HTML
                    return `<a href="${safeUrl}" target="_blank" rel="noopener noreferrer">${safeUrl}</a>`;
                });
            }
            return text;
        }

        function checkHasUser() {
            const userID = window.location.href.split('user=')[1];

            let user = allUsersLists.find(user => user.id == userID);

            $('.chat-user-item').each(function() {
                if ($(this).data('id') == userID) {
                    $(this).addClass('active');
                    $('#chat-logo').attr('src', $(this).data('profile-image'));
                    $('#chat-name').text($(this).data('user-name'));

                    if ($(this).data('status') == true) {
                        $('#chat-status').css('color', 'green');
                        $('#chat-status').text('Online');
                    } else {
                        $('#chat-status').css('color', 'red');
                        $('#chat-status').text('Offline');
                    }
                    $(`#user-unreadMessage${userID}`).hide();
                }
            });

            fetchMessages(userID);
        }

        const sendMessage = (userID, message) => {

            if (!message) {
                return;
            }

            isSendingMessage = true

            $.ajax({
                url: `/shop/send-message`,
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    user_id: userID,
                    message: message,
                    type: "shop",
                },
                dataType: 'json',
                success: function(data) {
                    fetchUsers();
                    // fetchMessages(userID);
                    updateOnlineStatus();
                    isSendingMessage = false;
                },
                error: function(error) {
                    isSendingMessage = false;
                }
            })
        }


        const connectPusherChat = () => {
            var channel = pusher.subscribe('chat_shop_{{ $shop->id }}');
            channel.bind('send-message-to-shop', function(data) {
                fetchUsers();
                fetchUnreadMessageCountOnShop();
                if (selectedUserId === data.user_id) {
                    fetchMessages(selectedUserId);
                }
            });
        }

        let timeoutTwo;
        $('#search').on('keyup', function() {
            clearTimeout(timeoutTwo);
            timeoutTwo = setTimeout(function() {
                currentPage = 1;
                callPagination = true;
                fetchUsers();
            }, 500);
        });

        const updateOnlineStatus = () => {
            $.ajax({
                type: 'POST',
                url: "/update/last/seen",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {}
            });
        }

        const fetchUnreadMessageCountOnShop = () => {
            $.ajax({
                url: "/api/unread-messages",
                method: "GET",
                data: {
                    shop_id: shopID
                },
                dataType: 'json',
                success: function(response) {

                    if (response.data.unread_messages > 0) {
                        $('#unread-message-badge').text(response.data.unread_messages).removeClass(
                            'd-none');
                    } else {
                        $('#unread-message-badge').addClass('d-none');
                    }

                    console.log(response);

                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        const formatDate = (dateStr) => {
            const date = new Date(dateStr);
            const hours = date.getHours();
            const minutes = date.getMinutes().toString().padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            const hour12 = (hours % 12) || 12;
            const day = date.getDate().toString().padStart(2, '0');
            const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
            ];
            const month = monthNames[date.getMonth()];
            const year = date.getFullYear();
            return `${hour12}:${minutes} ${ampm}, ${day} ${month}, ${year}`;
        }
    </script>
@endpush
