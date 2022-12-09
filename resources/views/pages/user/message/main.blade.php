<!DOCTYPE html>
<html>

<head>
    <title>Messages</title>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
    <meta name="title" content="Messages" />

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/message/general-style-plugins.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/message/style.css') }}" />
    <script src="{{ asset('assets/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        window.addEventListener("resize", () => {
            let vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty("--vh", `${vh}px`);
        });
        /** allow socket here */
        var socket_is_allowed = {{ ((\App\Models\Configs::where('name', 'is_node_on')->first())->value == '1') ? 'true' : 'false' }};
    </script>
</head>

<body>
    <input type="hidden" id="get_no_posts_name" value="No more posts" />

    <input type="hidden" class="seen_stories_users_ids" value="" />
    <input type="hidden" class="main_session" value="7611d833c08ebf32071f" />

    <div id="ajax_loading" class="tag_content tag_content_n_margin">
        <div class="ad-placement-header-footer"></div>
        <div id="contnet">

            <div class="tag_messages">
                <div class="wow_content">
                    <div class="tag_message_innr">
                        <div class="tag_msg_user_list">
                            <div class="valign tag_msg_header">
                                <div class="dropdown valign tag_msg_toolbar">
                                    <a href="{{ route('user.home') }}">
                                        <button type="button" class="btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                                                height="24">
                                                <path fill="currentColor" class="hover_path"
                                                    d="M20 20a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-9H1l10.327-9.388a1 1 0 0 1 1.346 0L23 11h-3v9zm-9-7v6h2v-6h-2z" />
                                                <path fill="currentColor"
                                                    d="M19 21H5a1 1 0 0 1-1-1v-9H1l10.327-9.388a1 1 0 0 1 1.346 0L23 11h-3v9a1 1 0 0 1-1 1zm-6-2h5V9.157l-6-5.454-6 5.454V19h5v-6h2v6z" />
                                            </svg>
                                        </button>
                                    </a>
                                </div>
                                <h2>Messages</h2>
                                <div class="dropdown valign tag_msg_toolbar"></div>
                            </div>
                            <form method="post" class="messages-search-users-form">
                                <div class="tag_msg_container">
                                    <div class="tag_msg_search messages-search-icon">
                                        <div class="search_input">
                                            <input type="search" name="query" id="query"
                                                placeholder="Search My Messages" />
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24">
                                                <path fill="currentColor"
                                                    d="M11 2c4.968 0 9 4.032 9 9s-4.032 9-9 9-9-4.032-9-9 4.032-9 9-9zm0 16c3.867 0 7-3.133 7-7 0-3.868-3.133-7-7-7-3.868 0-7 3.132-7 7 0 3.867 3.132 7 7 7zm8.485.071l2.829 2.828-1.415 1.415-2.828-2.829 1.414-1.414z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="tag_msg_body">
                                        <div class="tag_msg_body_content tag_scroll">
                                            <div class="tab-content messages-users-list">
                                                <div id="users-message" class="messages-chat-list tab-pane @if(isset($message_room)) @if($message_room->type == 'single') active @endif @else active @endif">

                                                    @if (count($single_rooms) > 0)

                                                        @foreach ($single_rooms as $room)
                                                            <div id="messageRoom_{{ $room->id }}"
                                                                class="notification-list pointer messages-list messages-recipients-list mobileopenlist"
                                                                onclick="getSingleRoomMessage(this, {{ $room->id }}, '{{ ucwords($room->internship->title) }}', '{{ ucwords($room->internship->department->name) }}', '{{ $room->internship->avatar }}');">
                                                                <div class="valign">
                                                                    <div class="notification-user-avatar avatar">
                                                                        <span class="new-message-alert hidden" style="background: #28a745;"></span>
                                                                        <img alt="Scottie"
                                                                            src="{{ asset('uploads/avatar/'.$room->internship->avatar) }}" />
                                                                    </div>
                                                                    <div class="notification-text">
                                                                        <div class="ajax-time"
                                                                            title="2022-08-28T18:22:19+00:00">
                                                                            {{ ucwords($room->internship->department->name) }}
                                                                        </div>
                                                                        <span
                                                                            class="truncate">{{ ucwords($room->internship->title) }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="empty_state"><svg xmlns="http://www.w3.org/2000/svg"
                                                                height="512" viewBox="0 0 32 32" width="512">
                                                                <path
                                                                    d="m26 32h-20c-3.314 0-6-2.686-6-6v-20c0-3.314 2.686-6 6-6h20c3.314 0 6 2.686 6 6v20c0 3.314-2.686 6-6 6z"
                                                                    fill="#ffe6e2"></path>
                                                                <g fill="#fd907e">
                                                                    <circle cx="10.667" cy="14.917"
                                                                        r="1.333"></circle>
                                                                    <path
                                                                        d="m12.447 17.183c-.673.507-1.113 1.32-1.113 2.233v.167h-2.834c-.273 0-.5-.227-.5-.5v-.333c0-1.013.82-1.833 1.833-1.833h1.667c.347 0 .673.1.947.266z">
                                                                    </path>
                                                                    <circle cx="21.333" cy="14.917"
                                                                        r="1.333"></circle>
                                                                    <path
                                                                        d="m24 18.75v.333c0 .273-.227.5-.5.5h-2.833v-.167c0-.913-.44-1.727-1.113-2.233.273-.167.6-.267.947-.267h1.667c1.012.001 1.832.821 1.832 1.834z">
                                                                    </path>
                                                                </g>
                                                                <circle cx="16" cy="14.583" fill="#fc573b"
                                                                    r="2"></circle>
                                                                <path
                                                                    d="m17.833 17.583h-3.667c-1.011 0-1.833.822-1.833 1.833v1c0 .276.224.5.5.5h6.333c.276 0 .5-.224.5-.5v-1c.001-1.01-.822-1.833-1.833-1.833z"
                                                                    fill="#fc573b"></path>
                                                            </svg> No message to show</div>
                                                    @endif

                                                </div>
                                                <div id="groups-message" class="messages-group-list tab-pane @if(isset($message_room)) @if($message_room->type == 'group') active @endif @endif">
                                                    @if (count($group_rooms) > 0)

                                                        @foreach ($group_rooms as $room)
                                                            <div id="messageRoom_{{ $room->id }}"
                                                                class="notification-list pointer messages-list messages-recipients-list mobileopenlist"
                                                                onclick="getGroupRoomMessage(this, {{ $room->id }}, '{{ ucwords($room->internship->title) }}', 'Group', '{{ $room->internship->avatar }}');">
                                                                <div class="valign">
                                                                    <div class="notification-user-avatar avatar">
                                                                        <span class="new-message-alert hidden" style="background: #28a745;"></span>
                                                                        <img alt="Scottie"
                                                                            src="{{ asset('uploads/avatar/'.$room->internship->avatar) }}" />
                                                                    </div>
                                                                    <div class="notification-text">
                                                                        <div class="ajax-time"
                                                                            title="2022-08-28T18:22:19+00:00">
                                                                            GROUP
                                                                        </div>
                                                                        <span
                                                                            class="truncate">{{ ucwords($room->internship->title) }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                    <div class="empty_state">
                                                        <svg height="512" viewBox="0 0 32 32" width="512"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="m26 32h-20c-3.314 0-6-2.686-6-6v-20c0-3.314 2.686-6 6-6h20c3.314 0 6 2.686 6 6v20c0 3.314-2.686 6-6 6z"fill="#f5e6fe" />
                                                            <g fill="#be63f9">
                                                                <circle cx="16" cy="9.667"
                                                                    r="1.667" />
                                                                <circle cx="20.667" cy="19"
                                                                    r="1.667" />
                                                                <circle cx="11.333" cy="19"
                                                                    r="1.667" />
                                                                <path
                                                                    d="m18.833 14.667h-5.667c-.276 0-.5-.224-.5-.5v-.333c0-1.011.822-1.833 1.833-1.833h3c1.011 0 1.833.822 1.833 1.833v.333c.001.276-.223.5-.499.5z" />
                                                                <path
                                                                    d="m23.5 24h-5.667c-.276 0-.5-.224-.5-.5v-.333c0-1.011.822-1.833 1.833-1.833h3c1.011 0 1.833.822 1.833 1.833v.333c.001.276-.223.5-.499.5z" />
                                                                <path
                                                                    d="m14.167 24h-5.667c-.276 0-.5-.224-.5-.5v-.333c0-1.011.822-1.833 1.833-1.833h3c1.011 0 1.833.822 1.833 1.833v.333c.001.276-.223.5-.499.5z" />
                                                            </g>
                                                            <path
                                                                d="m9.833 16.508c-.276 0-.5-.224-.5-.5 0-2.584 1.515-4.958 3.858-6.048.251-.116.548-.008.664.243.116.25.008.548-.243.664-1.993.927-3.28 2.945-3.28 5.141.001.276-.223.5-.499.5z"
                                                                fill="#d9a4fc" />
                                                            <path
                                                                d="m22.167 16.508c-.276 0-.5-.224-.5-.5 0-2.197-1.287-4.215-3.28-5.141-.25-.117-.359-.414-.243-.664.117-.25.413-.359.664-.243 2.344 1.09 3.858 3.464 3.858 6.048.001.276-.223.5-.499.5z"
                                                                fill="#d9a4fc" />
                                                            <path
                                                                d="m16 22.675c-.236 0-.468-.012-.697-.036-.274-.029-.474-.275-.445-.549s.271-.47.549-.445c.401.042.822.04 1.225-.004.272-.031.521.168.552.442.03.275-.168.521-.442.552-.244.026-.491.04-.742.04z"
                                                                fill="#d9a4fc" />
                                                        </svg>
                                                        No groups to show
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <ul class="valign nav nav-tabs tag_msg_switch" role="tablist">
                                            <li role="presentation">
                                                <a href="#users-message" class="@if(isset($message_room)) @if($message_room->type == 'single') active @endif @else active @endif" aria-controls="users-message"
                                                    role="tab" data-toggle="tab">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        width="24" height="24">
                                                        <path fill="currentColor"
                                                            d="M7.291 20.824L2 22l1.176-5.291A9.956 9.956 0 0 1 2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10a9.956 9.956 0 0 1-4.709-1.176zM7 12a5 5 0 0 0 10 0h-2a3 3 0 0 1-6 0H7z">
                                                        </path>
                                                    </svg>
                                                    Private
                                                </a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#groups-message" class="@if(isset($message_room)) @if($message_room->type == 'group') active @endif @endif" aria-controls="groups-message"
                                                    role="tab" data-toggle="tab">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        width="24" height="24">
                                                        <path fill="currentColor"
                                                            d="M10 19.748V16.4c0-1.283.995-2.292 2.467-2.868A8.482 8.482 0 0 0 9.5 13c-1.89 0-3.636.617-5.047 1.66A8.017 8.017 0 0 0 10 19.748zm8.88-3.662C18.485 15.553 17.17 15 15.5 15c-2.006 0-3.5.797-3.5 1.4V20a7.996 7.996 0 0 0 6.88-3.914zM9.55 11.5a2.25 2.25 0 1 0 0-4.5 2.25 2.25 0 0 0 0 4.5zm5.95 1a2 2 0 1 0 0-4 2 2 0 0 0 0 4zM12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z">
                                                        </path>
                                                    </svg>
                                                    Groups
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tag_msg_user_chat" id="tag_msg_right_prt">
                            <div class="text-sender-container">
                                <div class="valign tag_msg_header msg_usr_info_top_list">
                                    <div class="tag_msg_participant">
                                        <button type="button" class="btn chat_navigation"
                                            onclick="$('#tag_msg_right_prt').fadeOut(100);"><svg
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                                                height="24">
                                                <path
                                                    d="M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z"
                                                    fill="currentColor"></path>
                                            </svg></button>
                                        <div class="avatar" id="user-avatar-right"></div>
                                        <div class="name">
                                            <div id="user-name" class="bold truncate"></div>
                                            <p id="user-last-seen" class="msg_usr_lst_sen_main"></p>
                                        </div>
                                    </div>
                                    <div class="valign tag_msg_toolbar">
                                    </div>
                                    <div class="msg_progress" style="display: none;">
                                        <div class="indeterminate"></div>
                                    </div>
                                </div>
                                <div class="messages-load-more-messages view-more-wrapper hidden">
                                    <button type="button" class="btn btn-mat"></button>
                                </div>
                                <div class="messagejoint tag_msg_container">
                                    <div class="tag_msg_body tag_msg_main_body">
                                        <div class="messages-container tag_scroll">
                                            @if (!isset($message_room))
                                                <div class="messagejoint tag_msg_container">
                                                    <div class="tag_msg_body tag_msg_main_body">
                                                        <div class="messages-container tag_scroll">
                                                            <div class="no-messages valign empty_state">
                                                                <img
                                                                    src="{{ asset('assets/dist/img/select-message.png') }}" />
                                                                Choose one of your messages!
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="tag_msg_write">
                                        {{-- send form --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('pages.user.inc.messages')
</body>

</html>
