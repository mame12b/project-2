<!-- Socket.io -->
<script src="{{ asset('assets/socket.io/socket.io.min.js') }}"></script>
<script>
    var socket;
    var room_data = null;
    $(function() {
        @if (isset($message_room))
        @if ($message_room->type == 'single')
            getSingleRoomMessage('#messageRoom_{{ $message_room->id }}', {{ $message_room->id }}, '{{ ucwords($message_room->user->getName()) }}', '{{ ucwords($message_room->internship->title) }}', '{{ $message_room->internship->avatar }}')
        @else
            getGroupRoomMessage('#messageRoom_{{ $message_room->id }}', {{ $message_room->id }}, '{{ ucwords($message_room->internship->title) }}', 'Group', '{{ $message_room->internship->avatar }}');
        @endif
        @endif

        if(socket_is_allowed) {
            var host = "localhost:3000";
            socket = io(host);

            socket.emit('user_connection', ['u', {{ auth()->user()->id }}]);

            // when there is new private message
            socket.on('new_private_message', (data) => {
                var location = window.location.href;
                var loc_arr = location.split('/')
                if(loc_arr[loc_arr.length - 1] == data.room){
                    let html = renderOthersMessage(data)
                    $('.messages-container').append(html);
                    scrollToTop();
                }else{
                    $(`#messageRoom_${data.room}`).find('.new-message-alert').removeClass('hidden');
                }
            })

            // when user start typing
            socket.on('new_typing_private_message', (data) => {
                var location = window.location.href;
                var loc_arr = location.split('/')
                if(loc_arr[loc_arr.length - 1] == data.room_id){
                    let typingView = $('.messages-container').children().last().find('.message-typing').html();
                    if(typingView == '' || typingView == null){
                        $('.messages-container').children().last().find('.message-typing').html(`
                        <img class="user-avatar-left" src="{{ asset('uploads/avatar/${data.sender.avatar}') }}" alt="Profile Picture">
                        <div class="typing_loader_prnt">
                            <div class="typing_loader"></div>
                        </div>`);
                    }
                    scrollToTop();
                }
            });

            // hwen user stop typing
            socket.on('delete_typing_private_message', (data) => {
                var location = window.location.href;
                var loc_arr = location.split('/')
                if(loc_arr[loc_arr.length - 1] == data.room_id){
                    let typingView = $('.messages-container').children().last().find('.message-typing').html();
                    if(typingView != '' || typingView != null){
                        $('.messages-container').children().last().find('.message-typing').html(``);
                    }
                    scrollToTop();
                }
            });

            // when user delete message
            socket.on('user_delete_private_message', (data) => {
                var location = window.location.href;
                var loc_arr = location.split('/')
                if(loc_arr[loc_arr.length - 1] == data.room_id){
                    $('.messages-container').find(`#messageId_${data.id}`).fadeOut(100);
                    $(`#messageId_${data.id}`).remove();
                    scrollToTop();
                }
            });

            // when there is new group message
            socket.on('new_group_message', (data) => {
                var location = window.location.href;
                var loc_arr = location.split('/')
                if(loc_arr[loc_arr.length - 1] == data.room){
                    let html = renderOthersMessage(data)
                    $('.messages-container').append(html);
                    scrollToTop();
                }else{
                    $(`#messageRoom_${data.room}`).find('.new-message-alert').removeClass('hidden');
                }
            })

            // when user delete message
            socket.on('user_delete_group_message', (data) => {
                var location = window.location.href;
                var loc_arr = location.split('/')
                if(loc_arr[loc_arr.length - 1] == data.room_id){
                    $('.messages-container').find(`#messageId_${data.id}`).fadeOut(100);
                    $(`#messageId_${data.id}`).remove();
                    scrollToTop();
                }
            });

            // when user start typing
            socket.on('new_typing_group_message', (data) => {
                var location = window.location.href;
                var loc_arr = location.split('/')
                if(loc_arr[loc_arr.length - 1] == data.room_id){
                    let typingView = $('.messages-container').children().last().find('.message-typing').html();
                    if(typingView == '' || typingView == null){
                        $('.messages-container').children().last().find('.message-typing').html(`
                        <img class="user-avatar-left" src="{{ asset('uploads/avatar/${data.sender.avatar}') }}" alt="Profile Picture">
                        <div class="typing_loader_prnt">
                            <div class="typing_loader"></div>
                        </div>`);
                    }
                    scrollToTop();
                }
            });

            // hwen user stop typing
            socket.on('delete_typing_group_message', (data) => {
                var location = window.location.href;
                var loc_arr = location.split('/')
                if(loc_arr[loc_arr.length - 1] == data.room_id){
                    let typingView = $('.messages-container').children().last().find('.message-typing').html();
                    if(typingView != '' || typingView != null){
                        $('.messages-container').children().last().find('.message-typing').html(``);
                    }
                    scrollToTop();
                }
            });
        }
    })

    function scrollToTop(){
        $(".messages-container").animate({
            scrollTop: $('.messages-container')[0].scrollHeight
        }, 2000);
    }

    function ajaxRequestUrl() {
        return "{{ getenv('APP_URL') }}/api/user/";
    }

    function getSingleRoomMessage(e, room_id, name, title, avatar) {
        room_data = null;
        $(`#messageRoom_${room_id}`).find('.new-message-alert').addClass('hidden');
        window.history.pushState({
            state: 'new'
        }, '', "/user/messages/" + room_id);
        $('.messages-chat-list>.notification-list').attr('style', '');
        $('.messages-group-list>.notification-list').attr('style', '');
        $('.text-sender-container').find('.msg_progress').fadeIn(100);
        $(e).attr('style', 'background:#007bff3b;');
        $.get(ajaxRequestUrl() + 'get-message/' + room_id, function(data) {
            $('#user-avatar-right').html(
                `<img  src="{{ asset('uploads/avatar/${avatar}') }}" width="42" height="42" alt="User" class="rounded-circle">`
                )
            $('#user-name').html(`<a target="_blank" class="truncate" href="#" >${name}</a>`);
            $('#user-last-seen').html(`<span class="online-text" > ${title} </span>`)
            let footer = `
            <form method="post" class="sendMessages" enctype="multipart/form-data">
                <div class="valign">
                    <div class="tag_write_msg">
                        <textarea name="text" onfocusout="handleTyping('private', event, 'onfocusout');" onkeydown="handleTyping('private', event, 'onkeydown');" class="auto-resize tag_msg_write_tarea auto" id="textMessage" placeholder="Type a message.." rows="1"
                            style="height: 0px"></textarea>
                    </div>
                    <button onclick="sendToRoom(this, ${room_id}, 'private');" class="btn btn-main btn-mat send-button" type="button"
                        style="background-color: rgb(165, 39, 41);color: white;">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                            <path d="M0 0h24v24H0V0z" fill="none"></path>
                            <path fill="currentColor"
                                d="M3.4 20.4l17.45-7.48c.81-.35.81-1.49 0-1.84L3.4 3.6c-.66-.29-1.39.2-1.39.91L2 9.12c0 .5.37.93.87.99L17 12 2.87 13.88c-.5.07-.87.5-.87 1l.01 4.61c0 .71.73 1.2 1.39.91z">
                            </path>
                        </svg><span>Send</span>
                    </button>
                </div>
            </form>
            `;
            if (data.length == 0) {
                let html = `
                <div class="no-messages valign empty_state">
                    <img src="{{ asset('assets/dist/img/no-message.png') }}">
                    No messages yet here.
                </div>`;
                $('.messages-container').html(html);
            } else {
                let html = '';
                for (let i = 0; i < data.length; i++) {
                    if (data[i].sender_type == 'User') {
                        html += renderOwnMessage('private',data[i]);
                    } else {
                        html += renderOthersMessage(data[i]);
                    }
                }
                $('.messages-container').html(html);
                scrollToTop();
            }
            $('.tag_msg_write').html(footer);
            $('.text-sender-container').find('.msg_progress').fadeOut(100);
        }).fail(function(data) {
            $('.text-sender-container').find('.msg_progress').fadeOut(100);
            console.log('Error ', data);
        });
    }

    function getGroupRoomMessage(e, room_id, name, title, avatar) {
        room_data = null;
        $(`#messageRoom_${room_id}`).find('.new-message-alert').addClass('hidden');
        window.history.pushState({
            state: 'new'
        }, '', "/user/messages/" + room_id);
        $('.messages-chat-list>.notification-list').attr('style', '');
        $('.messages-group-list>.notification-list').attr('style', '');
        $('.text-sender-container').find('.msg_progress').fadeIn(100);
        $(e).attr('style', 'background:#007bff3b;');
        $.get(ajaxRequestUrl() + 'get-message/' + room_id, function(data) {
            $('#user-avatar-right').html(
                `<img  src="{{ asset('uploads/avatar/${avatar}') }}" width="42" height="42" alt="User" class="rounded-circle">`
                )
            $('#user-name').html(`<a target="_blank" class="truncate" href="#" >${name}</a>`);
            $('#user-last-seen').html(`<span class="online-text" > ${title} </span>`)
            let footer = `
            <form method="post" class="sendMessages" enctype="multipart/form-data">
                <div class="valign">
                    <div class="tag_write_msg">
                        <textarea name="text" onfocusout="handleTyping('group', event, 'onfocusout');" onkeydown="handleTyping('group', event, 'onkeydown');"  class="auto-resize tag_msg_write_tarea auto" id="textMessage" placeholder="Type a message.." rows="1"
                            style="height: 0px"></textarea>
                    </div>
                    <button onclick="sendToRoom(this, ${room_id}, 'group');" class="btn btn-main btn-mat send-button" type="button"
                        style="background-color: rgb(165, 39, 41);color: white;">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                            <path d="M0 0h24v24H0V0z" fill="none"></path>
                            <path fill="currentColor"
                                d="M3.4 20.4l17.45-7.48c.81-.35.81-1.49 0-1.84L3.4 3.6c-.66-.29-1.39.2-1.39.91L2 9.12c0 .5.37.93.87.99L17 12 2.87 13.88c-.5.07-.87.5-.87 1l.01 4.61c0 .71.73 1.2 1.39.91z">
                            </path>
                        </svg><span>Send</span>
                    </button>
                </div>
            </form>
            `;
            if (data.length == 0) {
                let html = `
                <div class="no-messages valign empty_state">
                    <img src="{{ asset('assets/dist/img/no-message.png') }}">
                    No messages yet here.
                </div>`;
                $('.messages-container').html(html);
            } else {
                let html = '';
                for (let i = 0; i < data.length; i++) {
                    if (data[i].sender_type == 'User') {
                        if(data[i].sender.id == {{ auth()->user()->id }}){
                            html += renderOwnMessage('group',data[i]);
                        }else{
                            html += renderOthersMessage(data[i]);
                        }
                    } else {
                        html += renderOthersMessage(data[i]);
                    }
                }
                $('.messages-container').html(html);
                scrollToTop();
            }
            $('.tag_msg_write').html(footer);
            $('.text-sender-container').find('.msg_progress').fadeOut(100);
        }).fail(function(data) {
            $('.text-sender-container').find('.msg_progress').fadeOut(100);
            console.log('Error ', data);
        });
    }

    function sendToRoom(e, room_id, type) {
        var text = $('#textMessage').val();
        if (text == undefined || text == null || text == '') return false;
        $(e).html('loading...');
        $(e).attr('disabled', true);

        $('.no-messages').remove();

        $('.messages-container').append(mockSendingMessage(text));
        $('#textMessage').val('');
        $('#textMessage').html('');

        scrollToTop();

        $.get(ajaxRequestUrl() + 'send-message/' + room_id + '?text=' + text, function(data) {
            let html = renderOwnMessage(type,data.data)
            $('#messageInProgress').remove()
            $('.messages-container').append(html);
            if(socket_is_allowed) {
                socket.emit('send_'+type+'_message', {
                    sender: data.data.sender,
                    receiver: data.data.receiver,
                    text: text,
                    room: room_id,
                    id: data.data.id
                });
            }
            $(e).html('send');
            $(e).attr('disabled', false);
            $('.text-sender-container').find('.msg_progress').fadeOut(100);
        }).fail(function(data) {
            $('#messageInProgress').remove();
            $(e).html('send');
            $(e).attr('disabled', false);
            $('.text-sender-container').find('.msg_progress').fadeOut(100);
            console.log('Error ', data);
        });


    }

    function renderOwnMessage(e, message) {
        return `
        <div class="message-contnaier valign" id="messageId_${message.id}">
            <div class="messages-wrapper messages-text tag_msg_wrapper message-model outgoing"
                data-message-id="2" data-toggle="tooltip" title="12 w"
                data-placement="left">
                <p class="message-text" id="message_text_reply_2" dir="auto"
                    style="background: #a52729; color:#fff !important;">
                    ${message.text}
                </p>
                <div class="clear"></div>
                <div class="message-media" id="message_media_reply_2"
                    style="background: #a52729 !important;"></div>
                <div class="valign like-stat stat-item post-like-status">
                    <span class="like-emo post-reactions-icons-m-2"></span>
                </div>
                <div class="clear"></div>
            </div>
            <div class="dropup valign tag_chat_opts">
                <div class="pointer deleteMessage" onclick="deleteMessage('${e}',${message.id});">
                    <svg xmlns="http://www.w3.org/2000/svg" height="19"
                        viewBox="0 0 24 24" width="19" opacity="0.5">
                        <path fill="currentColor"
                            d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-5-9h10v2H7v-2z">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="clear"></div>
            <div class="message-typing"></div>
            <div class="message-seen" style="display: none;"></div>
        </div>
        `;
    }

    function renderOthersMessage(message) {
        return `
        <div class="message-contnaier valign" id="messageId_${message.id}">
            <a href="#"
                data-ajax="?link1=timeline&amp;u=Scot">
                <img class="message-user-image"
                    src="{{ asset('uploads/avatar/${message.sender.avatar}') }}"
                    alt="Profile Picture">
            </a>
            <div class="messages-wrapper messages-text tag_msg_wrapper message-model incoming"
                data-message-id="1" data-toggle="tooltip" title="12 w"
                data-placement="right">
                <p class="message-text" id="message_text_reply_1" dir="auto">
                    ${message.text}
                </p>
                <div class="clear"></div>
                <div class="message-media" id="message_media_reply_1"></div>
                <div class="valign like-stat stat-item post-like-status">
                    <span class="like-emo post-reactions-icons-m-1"></span>
                </div>
                <div class="clear"></div>
            </div>
            <div class="dropup valign tag_chat_opts">
            </div>
            <div class="clear"></div>
            <div class="message-typing"></div>
        </div>
        `;
    }

    function mockSendingMessage(message) {
        return `
        <div class="message-contnaier valign" id="messageInProgress">
            <div class="messages-wrapper messages-text tag_msg_wrapper message-model outgoing"
                data-message-id="2" data-toggle="tooltip" title="12 w"
                data-placement="left" style="opacity: .65;">
                <p class="message-text" id="message_text_reply_2" dir="auto"
                    style="background: #a52729; color:#fff !important;">
                    ${message}
                </p>
                <div class="clear"></div>
                <div class="message-media" id="message_media_reply_2"
                    style="background: #a52729 !important;"></div>
                <div class="valign like-stat stat-item post-like-status">
                    <span class="like-emo post-reactions-icons-m-2"></span>
                </div>
                <div class="clear"></div>
            </div>
            <div class="dropup valign tag_chat_opts">
            </div>
            <div class="clear"></div>
            <div class="message-typing"></div>
            <div class="message-seen" style="display: none;"></div>
        </div>
        `;
    }

    function deleteMessage(e,message_id){
        $('.text-sender-container').find('.msg_progress').fadeIn(100);
        $(`#messageId_${message_id}>.messages-wrapper`).attr('style', 'opacity: .55;');
        $.get(ajaxRequestUrl() + 'delete-message/' + message_id, function(data) {
            if(data.status == 200){
                $('.messages-container').find(`#messageId_${message_id}`).fadeOut(100);
                $(`#messageId_${message_id}`).remove();
                $('.text-sender-container').find('.msg_progress').fadeOut(100);
                if(socket_is_allowed) {
                    if(e == 'private'){
                        socket.emit('delete_private_message', {
                            receiver : data.data.receiver,
                            receiverType: 'i',
                            room_id : data.data.room_id,
                            id : data.data.id
                        })
                    }else if(e == 'group'){
                        socket.emit('delete_group_message', {
                            receiver : data.data.receiver,
                            room_id : data.data.room_id,
                            id : data.data.id
                        })
                    }
                }
            }else{
                $(`#messageId_${message_id}>.messages-wrapper`).attr('style', '');
                $('.text-sender-container').find('.msg_progress').fadeOut(100);
            }
        }).fail(function(data) {
            console.log('Error ', data);
            $('.text-sender-container').find('.msg_progress').fadeOut(100);
        });
    }

    function handleTyping(s, e, r){
        let location = window.location.href;
        let loc_arr = location.split('/');
        let room_id = loc_arr[loc_arr.length - 1];

        if(room_data == null){
            room_data = 'occ';
            $.get(ajaxRequestUrl() + 'get-room/' + room_id, (data)=>{
                room_data = data.data;
            })
        }

        // for private message
        if(s == 'private'){
            // if the user focus out from the text area
            if(r == 'onfocusout'){
                // delete typing
                if(room_data != null && room_data != 'occ' && socket_is_allowed){
                    socket.emit('delete_typing_private_message', {
                        receiver : room_data.internship,
                        sender : room_data.user,
                        receiverType: 'i',
                        room_id : room_id
                    })
                }
            }
            // if the user start typing
            else {

                if(e.keyCode == 13 && e.ctrlKey) {
                    // send message
                    sendToRoom('.send-button', room_id, 'private');
                    // delete typing
                    if(room_data != null && room_data != 'occ' && socket_is_allowed){
                        socket.emit('delete_typing_private_message', {
                            receiver : room_data.internship,
                            sender : room_data.user,
                            receiverType: 'i',
                            room_id : room_id
                        })
                    }
                }
                else{
                    // emit typing
                    if(room_data != null && room_data != 'occ' && socket_is_allowed){
                        socket.emit('typing_private_message', {
                            receiver : room_data.internship,
                            sender : room_data.user,
                            receiverType: 'i',
                            room_id : room_id
                        })
                    }
                }
            }
        }else if(s == 'group'){
            // if the user focus out from the text area
            if(r == 'onfocusout'){
                // delete typing
                if(room_data != null && room_data != 'occ' && socket_is_allowed){
                    socket.emit('delete_typing_group_message', {
                        receiver : [...room_data.receiver, room_data.internship],
                        sender : room_data.user,
                        room_id : room_id
                    })
                }
            }
            // if the user start typing
            else {

                if(e.keyCode == 13 && e.ctrlKey) {
                    // send message
                    sendToRoom('.send-button', room_id, 'group');
                    // delete typing
                    if(room_data != null && room_data != 'occ' && socket_is_allowed){
                        socket.emit('delete_typing_group_message', {
                            receiver : [...room_data.receiver, room_data.internship],
                            sender : room_data.user,
                            room_id : room_id
                        })
                    }
                }
                else{
                    // emit typing
                    if(room_data != null && room_data != 'occ' && socket_is_allowed){
                        socket.emit('typing_group_message', {
                            receiver : [...room_data.receiver, room_data.internship],
                            sender : room_data.user,
                            room_id : room_id
                        })
                    }
                }
            }
        }
    }
</script>
