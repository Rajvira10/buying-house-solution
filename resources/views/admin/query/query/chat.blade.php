@extends('admin.layout')
@section('title', 'Query Chat')
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i class="ri-home-5-line"></i>
                                Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('queries.index') }}">Queries</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Query #{{ $query->query_no }}</li>
                    </ol>
                </nav>

                <div class="card">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-lg-3 border-end">
                                <div class="p-4">
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="font-size-16 mb-1">{{ $query->brand->buyer->user->username }}</h5>
                                            <p class="text-muted mb-0">Query Initiator</p>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <h5 class="font-size-14 mb-3">Chat Members:</h5>
                                        <ul class="list-unstyled mb-0">
                                            @foreach ($chat_members as $member)
                                                <li
                                                    class="d-flex align-items-center justify-content-between mb-2 p-2 border rounded hover-member">
                                                    <div class="d-flex align-items-center">
                                                        <div>
                                                            <h5 class="font-size-14 mb-0">{{ $member->username }}</h5>
                                                            <p class="text-muted font-size-12 mb-0">
                                                                {{ $member->roles[0]->name ?? 'Participant' }}</p>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    {{-- <div class="border rounded p-4">
                                        <div class="mb-3">
                                            <h5 class="font-size-14">Query Details:</h5>
                                            <p class="text-muted mb-0">{{ Str::limit($query->description, 100) }}</p>
                                        </div>
                                        <div>
                                            <h5 class="font-size-14">Status:</h5>
                                            <span class="badge bg-{{ $query->status == 'open' ? 'success' : 'danger' }}">
                                                {{ ucfirst($query->status) }}
                                            </span>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>

                            <!-- Chat Area -->
                            <div class="col-lg-9">
                                <div class="chat-box bg-light" style="height: 600px;">
                                    <!-- Chat Header -->
                                    <div class="p-3 border-bottom">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h5 class="font-size-16 mb-0">Query Chat</h5>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <button class="btn btn-light btn-sm" data-bs-toggle="tooltip"
                                                    title="Refresh Chat">
                                                    <i class="ri-refresh-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="chat-conversation p-3" id="chat-messages"
                                        style="height: 480px; overflow-y: auto;">
                                        <ul class="list-unstyled mb-0" id="message-list">
                                        </ul>
                                    </div>

                                    <div class="p-3 border-top">
                                        <form id="chat-form" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="query_id" value="{{ $query->id }}">
                                            <div class="row g-2">
                                                <div class="col-12" id="attachment-preview-container"
                                                    style="display: none;">
                                                    <div
                                                        class="alert alert-secondary d-flex justify-content-between align-items-center">
                                                        <span id="attachment-preview"></span>
                                                        <button type="button" id="cancel-attachment"
                                                            class="btn btn-danger btn-sm">
                                                            <i class="ri-close-circle-line"></i> Cancel
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="position-relative">
                                                        <input type="text" class="form-control" id="message-input"
                                                            name="message" placeholder="Enter your message...">
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <label for="file-input" class="btn btn-light" data-bs-toggle="tooltip"
                                                        title="Attach File">
                                                        <i class="ri-attachment-2"></i>
                                                    </label>
                                                    <input type="file" id="file-input" name="attachment" class="d-none">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="ri-send-plane-fill"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .right {
            display: flex;
            justify-content: flex-end;
        }

        .left {
            display: flex;
            justify-content: flex-start;
        }

        .chat-box {
            border-radius: 0.25rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .chat-conversation .conversation-list {
            position: relative;
            padding: 0.5rem 1rem;
        }

        .chat-conversation .left .conversation-list .ctext-wrap {
            background-color: #eef2f7;
            border-radius: 1rem 1rem 1rem 0;
            padding: 1rem 1.25rem;
            margin-right: 30%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            font-size: 14px;
            line-height: 1.5;
            transition: background-color 0.3s ease;
        }

        .chat-conversation .right .conversation-list .ctext-wrap {
            background-color: #727cf5;
            border-radius: 1rem 1rem 0 1rem;
            padding: 1rem 1.25rem;
            color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            font-size: 14px;
            line-height: 1.5;
            min-width: max-content;
            transition: background-color 0.3s ease;
        }

        .chat-conversation .conversation-list .ctext-wrap:hover {
            background-color: #dce1f1;
        }

        .chat-conversation .right .conversation-list .ctext-wrap:hover {
            background-color: #5b66e8;
        }


        .chat-conversation .conversation-name {
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .chat-conversation .chat-time {
            font-size: 0.75rem;
            color: #7c8a96;
            margin-top: 0.25rem;
        }

        .avatar-xs {
            height: 2rem;
            width: 2rem;
        }

        .avatar-lg {
            height: 4rem;
            width: 4rem;
        }

        #chat-messages::-webkit-scrollbar {
            width: 6px;
        }

        #chat-messages::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        #chat-messages::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        #chat-messages::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
@endsection

@section('custom-script')
    <script>
        $(document).ready(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();

            function formatDate(dateString) {
                const date = new Date(dateString);
                const options = {
                    year: 'numeric',
                    month: 'short',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit'
                };
                return date.toLocaleString('en-US', options);
            }

            function loadMessages() {
                $.ajax({
                    url: "{{ route('queries.get_messages') }}",
                    type: "GET",
                    data: {
                        query_id: {{ $query->id }}
                    },
                    success: function(response) {
                        $('#message-list').empty();
                        response.messages.forEach(function(message) {
                            appendMessage(message);
                        });
                        scrollToBottom();
                    }
                });
            }

            function appendMessage(message) {
                const isCurrentUser = message.user_id == {{ auth()->id() }};
                const messageHtml = `
            <li class="${isCurrentUser ? 'right' : 'left'}">
                <div class="conversation-list">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h5 class="conversation-name">${message.user.username}</h5>
                            <div class="ctext-wrap d-flex flex-column">
                                ${message.attachment ? `
                                        <div class="mb-1">
                                            <a href="${message.attachment}" target="_blank"  class="${isCurrentUser ? 'text-white' : 'text-dark'} flex align-items-center" style="cursor: pointer">
                                                <i class="ri-file-text-fill"></i> 
                                                <span>View Attachment</span>
                                                
                                            </a>
                                        </div>` : ''}
                                <p class="mb-0">${message.message}</p>
                            </div>
                            <p class="chat-time mb-0">
                                <i class="ri-time-line align-middle"></i>
                                <span class="align-middle">${formatDate(message.created_at)}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </li>
        `;
                $('#message-list').append(messageHtml);
            }

            function scrollToBottom() {
                const chatMessages = document.getElementById('chat-messages');
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            $('#chat-form').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                $.ajax({
                    url: "{{ route('queries.send_message') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#message-input').val('');
                        $('#file-input').val('');
                        $('#attachment-preview-container').hide();
                        appendMessage(response.message);
                        scrollToBottom();
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            });

            $('#file-input').on('change', function() {
                const fileName = $(this).val().split('\\').pop();
                if (fileName) {
                    $('#attachment-preview').text(fileName);
                    $('#attachment-preview-container').show();
                }
            });

            $('#cancel-attachment').on('click', function() {
                $('#file-input').val('');
                $('#attachment-preview-container').hide();
            });

            loadMessages();
            setInterval(loadMessages, 10000);
        });
    </script>
@endsection