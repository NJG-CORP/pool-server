@extends('layouts.default')

@section('title', 'Чат')

@section('content')
    <main class="main inner_page_main inner_page_chat">

        <section class="the_content_section">
            <div class="inner_section">

                <div class="breadcrumbs inner_section">
                    <p itemscope itemtype="http://schema.org/BreadcrumbList">
								<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
									<a href="#" itemprop="item"><span itemprop="name">Главная</span></a>
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
                            <div class="chat_window_content_left_inner modern-skin scrollable"
                                 id="chat-thread-container">
                            </div>
                        </div><!--/chat_window_content_left-->

                        <div class="chat_window_content_right">
                            <div class="chat_window_content_right_inner modern-skin scrollable"
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.4/socket.io.js"></script>
    <script>
        const socket = io('http://95.213.251.73:8080', {
            query: "api_token=<?= Auth::user()->api_token ?>"
        });
        let chats;

        const defaultAvatarUrl = '<?= \App\Models\Image::getDefaultImage()['url'] ?>';
        const activeChat = <?= $threadId ?>;
        const $messageContainer = $('#chat-message-container');
        const me_id = <?= Auth::user()->id ?>;
        const $text = $('[name="reply"]');

        socket.on('connect', s => {
            console.log('connected')
        });
        socket.on('chat_message_received', data => {
            console.log('received_message', data);
        });
        socket.on('chat_message_list', data => {
            let html = '';
            console.log(data);
            data.messages.forEach(function (message) {
                html += renderMessage(message, defaultAvatarUrl);
            });

            $messageContainer.html(html);
            console.log('message_list', data);
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
        };
        getMessages = (offset, receiver_id) => {
            socket.emit('chat_message_list_request', {
                offset,
                receiver_id
            });
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
            return '<div class="message_item"><a href="/chat/' + chat_id + '">' +
                '            <div class="img"><img src="' + (chat.avatar.url === undefined ? chat.avatar.url : defaultAvatarUrl) + '" alt="" /></div>' +
                '' +
                '            <div class="text">' +
                '            <p class="name">' + chat.sender_name + '</p>' +
                '' +
                '        <p class="message_body">' +
                chat.text +
                '            </p>' +
                '' +
                '            <span class="new_messages">2</span>' +
                '            <span class="time">' + date.getDate() + '</span>' +
                '        </div>\n' +
                '        </a></div>';
        };

        renderMessage = (message, avatar) => {
            const date = new Date(message.updated_at);

            return message.sender_id === me_id ? '<div class="message_item message_item_reply">' +
                '    <div class="text">' +
                '        <p class="message_body">' +
                '            ' + message.text +
                '        </p>' +
                '        <span class="time">' + date.getDate() + '</span>' +
                '    </div>' +
                '</div>' : '' +
                '<div class="message_item">\n' +
                '    <div class="img"><img src="' + avatar + '" alt="" /><span class="mark check"></span></div>\n' +
                '    <div class="text">\n' +
                '        <p class="name">Светлана Петрова</p>\n' +
                '        <p class="message_body">\n' +
                message.text +
                '        </p>\n' +
                '        <span class="time">' + date.getDate() + '</span>\n' +
                '    </div>\n' +
                '</div>';
        };

        getMessages(0, activeChat);
    </script>
@endsection
