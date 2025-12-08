@extends('storefront.layouts.app')

@section('content')
    <div class="container pt-5 pb-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span><i class="fa fa-comments me-2"></i> Hỗ trợ trực tuyến</span>
                        <small id="connection-status" class="badge bg-success">Online</small>
                    </div>

                    <div class="card-body p-0">
                        <div id="chat-box" class="p-3"
                             style="height: 400px; overflow-y: auto; background-color: #f8f9fa;">
                            @if(isset($messages) && count($messages) > 0)
                                @foreach($messages as $message)
                                    @php
                                        // LOGIC MỚI: Dùng data_get để lấy thông tin người gửi từ quan hệ participation
                                        $senderId = data_get($message, 'participation.messageable_id');
                                        $senderType = data_get($message, 'participation.messageable_type');

                                        // So sánh với người dùng hiện tại ($participant)
                                        $isMe = $senderId === $participant->id && $senderType === get_class($participant);

                                        $body = data_get($message, 'body');
                                        $createdAt = data_get($message, 'created_at');
                                        $timeDisplay = $createdAt instanceof \Carbon\Carbon ? $createdAt->format('H:i') : \Carbon\Carbon::parse($createdAt)->format('H:i');
                                    @endphp
                                    <div
                                        class="d-flex mb-3 {{ $isMe ? 'justify-content-end' : 'justify-content-start' }}"
                                        data-id="{{ data_get($message, 'id') }}">
                                        <div
                                            class="p-3 rounded shadow-sm {{ $isMe ? 'bg-primary text-white' : 'bg-white text-dark border' }}"
                                            style="max-width: 75%;">
                                            <div class="message-text">{{ $body }}</div>
                                            <div
                                                class="small {{ $isMe ? 'text-white-50' : 'text-muted' }} mt-1 text-end"
                                                style="font-size: 0.7rem;">
                                                {{ $timeDisplay }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top-0 p-3">
                        <form id="chat-form" class="d-flex gap-2">
                            <input type="text" id="message-input" class="form-control"
                                   placeholder="Nhập tin nhắn của bạn..." autocomplete="off" required>
                            <button class="btn btn-primary" type="submit" id="send-btn">
                                <i class="fa fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<!-- @formatter:off -->
@push('scripts')
    <script>
        (function($) {
            $(document).ready(function() {
                const chatBox = $('#chat-box');

                // Kiểm tra element tồn tại
                if (chatBox.length > 0) {
                    const messageInput = $('#message-input');

                    // Lấy ID tin nhắn cuối một cách an toàn
                    let lastMessageId = {{ data_get(collect($messages)->last(), 'id', 0) }};

                    function scrollToBottom() {
                        chatBox.scrollTop(chatBox[0].scrollHeight);
                    }

                    scrollToBottom();

                    function appendMessage(text, isMe, time, id) {
                        const justify = isMe ? 'justify-content-end' : 'justify-content-start';
                        const bgClass = isMe ? 'bg-primary text-white' : 'bg-white text-dark border';
                        const timeClass = isMe ? 'text-white-50' : 'text-muted';

                        const msgHtml = `
                        <div class="d-flex mb-3 ${justify}" data-id="${id}">
                            <div class="p-3 rounded shadow-sm ${bgClass}" style="max-width: 75%;">
                                <div class="message-text">${text}</div>
                                <div class="small ${timeClass} mt-1 text-end" style="font-size: 0.7rem;">
                                    ${time}
                                </div>
                            </div>
                        </div>
                    `;
                        chatBox.append(msgHtml);
                        scrollToBottom();
                        if (id > lastMessageId) lastMessageId = id;
                    }

                    $('#chat-form').on('submit', function(e) {
                        e.preventDefault();
                        const message = messageInput.val().trim();
                        if (!message) return;

                        $('#send-btn').prop('disabled', true);

                        $.ajax({
                            url: '{{ route("chat.send") }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                message: message
                            },
                            success: function(response) {
                                if (response.success) {
                                    messageInput.val('');
                                    appendMessage(response.message.body, true, response.message.created_at, response.message.id);

                                    if (response.bot_reply) {
                                        setTimeout(() => {
                                            appendMessage(response.bot_reply.body, false, response.bot_reply.created_at, response.bot_reply.id);
                                        }, 500);
                                    }
                                }
                            },
                            error: function() {
                                alert('Có lỗi xảy ra, vui lòng thử lại.');
                            },
                            complete: function() {
                                $('#send-btn').prop('disabled', false);
                                messageInput.focus();
                            }
                        });
                    });

                    // Polling
                    setInterval(function() {
                        $.get('{{ route("chat.fetch") }}', { last_id: lastMessageId }, function(response) {
                            if (response.messages && response.messages.length > 0) {
                                response.messages.forEach(function(msg) {
                                    if ($(`.d-flex[data-id="${msg.id}"]`).length === 0) {
                                        appendMessage(msg.body, msg.is_me, msg.created_at, msg.id);
                                    }
                                });
                            }
                        });
                    }, 3000);
                }
            });
        })(jQuery);
    </script>
@endpush
<!-- @formatter:on -->
