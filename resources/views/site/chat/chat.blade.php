@extends('layouts.default')

@section('title', 'Чат')

@section('content')
    <main class="main inner_page_main inner_page_chat">

        <section class="the_content_section">
            <div class="inner_section">

                <div class="breadcrumbs inner_section">
                    <p itemscope itemtype="http://schema.org/BreadcrumbList">
								<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
									<a href="/" itemprop="item"><span itemprop="name">Главная</span></a>
									<meta itemprop="position" content="1">
								</span>
                        <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
									<span itemprop="item"><span itemprop="name">Чат</span></span>
									<meta itemprop="position" content="2">
								</span>
                    </p>
                </div>

                <h1>Чат</h1>

                <div class="chat_window">
                    <div class="chat_window_head">
                        <span class="chat_title">Чат</span>
                        <!--
                                                        <div class="chat_window_head_controls">
                                                            <a href="#" class="window_control1"></a>
                                                            <a href="#" class="window_control2"></a>
                                                            <a href="#" class="window_control3"></a>
                                                        </div>
                        -->
                    </div>

                    <div class="chat_window_content clearfix">
                        <div class="chat_window_content_left">
                            {{--<div class="chat_window_search">--}}
                            {{--<form>--}}
                            {{--<input type="text" name="search_query" placeholder="Поиск">--}}
                            {{--<input type="submit" name="submit1" value=" ">--}}
                            {{--</form>--}}
                            {{--</div>--}}
                            <div class="chat_window_content_left_inner modern-skin scrollable-native"
                                 id="chat-thread-container">
                            </div>
                        </div><!--/chat_window_content_left-->

                        <div class="chat_window_content_right">
                            <div class="chat_window_content_right_inner modern-skin scrollable-native"
                                 id="chat-message-container">
                            </div><!--/chat_window_content_left_inner-->
                        </div><!--/chat_window_content_right-->
                    </div>

                    <div class="chat_window_bottom clearfix">
                        <!--
                                                        <div class="chat_window_bottom_left">
                                                            <a href="#" class="personal"></a>
                                                            <a href="#" class="messages"><span>4</span></a>
                                                            <a href="#" class="settings"></a>
                                                        </div>
                        -->

                        <div class="chat_window_bottom_right clearfix">
                            <!--
                                                                <a href="#" class="attach"></a>
                            -->
                            <div class="reply_form">
                                <textarea name="reply" placeholder="Сообщение"></textarea>

                                <a href="#" class="smile"></a>

                                <input type="submit" name="submit1" value=" " onclick="sendForm()">
                            </div><!--/reply_form-->
                        </div>
                    </div><!--/chat_window_bottom-->
                </div><!--/chat_window-->

            </div>
        </section><!--/the_content_section-->

    </main><!--/main-->

@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.4/socket.io.js"></script>
    <script>
        const socket = io('https://poolbuddy:8080', {
            query: "api_token=<?= Auth::user()->api_token ?>"
        });
        let chats;

        const defaultAvatarUrl = '<?= \App\Models\Image::getDefaultImage()['url'] ?>';
        const activeChat = <?= $threadId ?>;
        const $messageContainer = $('#chat-message-container');
        const me_id =  <?= Auth::user()->id ?>;
        const $text = $('[name="reply"]');

        socket.on('connect', s => {
            console.log('connected')
        });
        socket.on('chat_message_received', data => {
            let message = data;
            if (message.sender_id === me_id) {
                $messageContainer.append(renderOwnMessage(message));
            } else {
                console.log(message);
                $messageContainer.append(renderPartnerMessage(message));
            }
            $messageContainer.scrollTop($messageContainer.prop('scrollHeight'));
            console.log('received_message', data);
        });

        socket.on('chat_message_list', data => {
            let html = '';
            console.log('chat message list', data);
            data.messages.reverse().forEach(message => {
                if (message.sender_id === me_id) {
                    html += renderOwnMessage(message.text, new Date(message.updated_at));
                } else {
                    html += renderPartnerMessage(message);
                }
            });
            $messageContainer.html(html);
            $messageContainer.scrollTop($messageContainer.prop('scrollHeight'));
        });
        socket.on('chat_list', data => {
            chats = data;
            let html = '';
            chats.forEach(function (chat) {
                html += renderThread(chat);
            });

            $('#chat-thread-container').html(html);
            console.log('chat_list', data);
        });
        sendMessage = (text, receiver_id) => {
            socket.emit('chat_message_send', {
                text: text || Math.random(),
                receiver_id: receiver_id
            });
            $messageContainer.append(renderOwnMessage(text, new Date()));
            $messageContainer.scrollTop($messageContainer.prop('scrollHeight'));
        };
        getMessages = (offset, receiver_id) => {
            socket.emit('chat_message_list_request', {
                offset,
                receiver_id
            });
            console.log('MESSAGE LIST')
        };
        getChatLists = () => {
            socket.emit('chat_list_request');
        };

        sendForm = () => {
            const text = $text.val();
            sendMessage(text, activeChat);
            $text.val('');
        };

        getChatLists();

        renderThread = chat => {
            const date = new Date(chat.updated_at);
            const chat_id = chat.receiver_id === me_id ? chat.sender_id : chat.receiver_id;
            const name = chat.receiver_id === me_id ? chat.sender_name : chat.receiver_name;
            return '<div class="message_item" data-chat-item="' + chat_id + '"><a href="/chat/' + chat_id + '">' +
                '            <div class="img"><img src="' + (chat.avatar.url === undefined ? chat.avatar.url : defaultAvatarUrl) + '" alt="" /></div>' +
                '' +
                '            <div class="text">' +
                '            <p class="name">' + name + '</p>' +
                '' +
                '        <p class="message_body">' +
                chat.text +
                '            </p>' +
                '            <span class="time">' + date.getDate() + '.' + date.getMonth() + '.' + date.getFullYear() + '</span>' +
                '        </div>\n' +
                '        </a></div>';
        };

        renderOwnMessage = (message, date) => {
            return '<div class="message_item message_item_reply">' +
                '    <div class="text">' +
                '        <p class="message_body">' +
                '            ' + message +
                '        </p>' +
                '        <span class="time">' + date.getDate() + '.' + date.getMonth() + '.' + date.getFullYear() + '</span>\n' +
                '    </div>' +
                '</div>'
        };

        renderPartnerMessage = message => {
            const date = new Date(message.updated_at);
            let partner = getPartnerInfo(message.sender_id);
            return '<div class="message_item">\n' +
                '    <div class="img"><img src="' + partner.avatar + '" alt="" /></div>\n' +
                '    <div class="text">\n' +
                '        <p class="name">' + partner.name + '</p>\n' +
                '        <p class="message_body">\n' +
                message.text +
                '        </p>\n' +
                '        <span class="time">' + date.getDate() + '.' + date.getMonth() + '.' + date.getFullYear() + '</span>\n' +
                '    </div>\n' +
                '</div>';
        };

        //возвращает отсортированный список чатов
        getPartners = () => {
            return chats.map(item => {
                let avatar = item.avatar.url ? item.avatar.url : defaultAvatarUrl;
                return item.receiver_id === me_id ?
                    {id: item.sender_id, name: item.sender_name, avatar: avatar} :
                    {id: item.receiver_id, name: item.receiver_name, avatar: avatar}
            });
        };

        //возвращает объект {avatar_url, name}
        getPartnerInfo = id => {
            return getPartners().filter(item => item.id === id)[0]
        };

        if (activeChat) {
            getMessages(0, activeChat);
        }

    </script>
@endpush