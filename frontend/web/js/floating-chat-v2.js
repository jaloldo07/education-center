console.log('🚀 Chat v2 Loading...');

(function () {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    function init() {
        console.log('✅ Chat v2 Initialized');

        var state = {
            currentContact: null,
            lastMessageId: 0,
            pollInterval: null
        };

        var chatButton = document.getElementById('chat-button');
        var chatPopup = document.getElementById('chat-popup');

        if (!chatButton || !chatPopup) {
            console.error('❌ Elements not found');
            return;
        }

        // Toggle chat
        chatButton.onclick = function () {
            if (chatPopup.style.display === 'none' || !chatPopup.style.display) {
                chatPopup.style.display = 'block';
                loadContacts();
            } else {
                chatPopup.style.display = 'none';
                stopPolling();
            }
        };

        document.getElementById('close-chat').onclick = function () {
            chatPopup.style.display = 'none';
            stopPolling();
        };

        // Tabs
        // Tabs - FIXED
        document.querySelectorAll('.tab-btn').forEach(function (btn) {
            btn.onclick = function () {
                console.log('Tab clicked:', this.getAttribute('data-tab')); // Debug
                var tab = this.getAttribute('data-tab');

                // Buttons
                document.querySelectorAll('.tab-btn').forEach(function (b) {
                    b.classList.remove('active');
                    b.style.background = 'rgba(255,255,255,0.2)';
                    b.style.color = 'white';
                });
                this.classList.add('active');
                this.style.background = 'white';
                this.style.color = '#667eea';

                // Content
                document.querySelectorAll('.tab-content').forEach(function (c) {
                    c.classList.remove('active');
                    c.style.display = 'none';
                });
                var targetContent = document.getElementById('tab-' + tab);
                targetContent.classList.add('active');
                targetContent.style.display = 'flex';

                // Load data
                if (tab === 'messages') loadContacts();
                else if (tab === 'support') loadTickets();
            };
        });

        // Back to contacts
        document.getElementById('back-to-contacts').onclick = function (e) {
            e.preventDefault();
            console.log('⬅️ Back clicked');
            stopPolling();
            document.getElementById('chat-window').style.display = 'none';
            document.getElementById('contact-list').style.display = 'block';
            loadContacts();
        };

        // Send message
        document.getElementById('send-btn').onclick = sendMessage;
        document.getElementById('message-input').onkeypress = function (e) {
            if (e.key === 'Enter') sendMessage();
        };

        // Support
        document.getElementById('new-ticket-btn').onclick = function () {
            document.getElementById('support-list').style.display = 'none';
            document.getElementById('ticket-form').style.display = 'block';
        };

        document.getElementById('back-to-tickets').onclick = showTicketList;
        document.getElementById('cancel-ticket').onclick = showTicketList;
        document.getElementById('submit-ticket').onclick = submitTicket;

        function loadContacts() {
            var container = document.getElementById('contact-list');
            container.innerHTML = '<div class="loading">Loading...</div>';

            fetch('/chat-api/get-contacts')
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.success && data.contacts.length > 0) {
                        var html = '';
                        data.contacts.forEach(function (c) {
                            html += '<div class="contact-item" onclick="window.chatOpenChat(' + c.id + ',\'' + c.name.replace(/'/g, "\\'") + '\')">';
                            html += '<div style="width:40px;height:40px;background:#667eea;border-radius:50%;color:white;display:flex;align-items:center;justify-content:center;font-size:18px;">👤</div>';
                            html += '<div style="flex:1"><strong>' + escapeHtml(c.name) + '</strong><br><small style="color:#999">' + c.role + '</small></div>';
                            if (c.unread > 0) {
                                html += '<span style="background:#ff4757;color:white;padding:4px 8px;border-radius:10px;font-size:12px;font-weight:bold;">' + c.unread + '</span>';
                            }
                            html += '</div>';
                        });
                        container.innerHTML = html;
                    } else {
                        container.innerHTML = '<div style="text-align:center;padding:20px;color:#999">No contacts</div>';
                    }
                })
                .catch(function () {
                    container.innerHTML = '<div style="text-align:center;padding:20px;color:red">Error loading</div>';
                });
        }


        // Unread count update
        function updateUnreadCount() {
            fetch('/chat-api/get-unread-count')
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.success) {
                        var badge = document.getElementById('total-unread');
                        if (data.count > 0) {
                            badge.textContent = data.count;
                            badge.style.display = 'flex';
                        } else {
                            badge.style.display = 'none';
                        }
                    }
                });
        }

        // Auto-update every 10 seconds
        setInterval(updateUnreadCount, 10000);
        updateUnreadCount(); // Initial call


        window.chatOpenChat = function (userId, name) {
            state.currentContact = userId;
            state.lastMessageId = 0;
            document.getElementById('current-contact-name').textContent = name;
            document.getElementById('contact-list').style.display = 'none';
            document.getElementById('chat-window').style.display = 'flex';
            loadMessages(userId);
            startPolling();
        };

        function loadMessages(userId) {
            var container = document.getElementById('messages-container');
            container.innerHTML = '<div style="text-align:center;padding:20px;color:#999">Loading...</div>';

            fetch('/chat-api/get-messages?userId=' + userId)
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.success) {
                        if (data.messages.length === 0) {
                            container.innerHTML = '<div style="text-align:center;padding:20px;color:#999">No messages yet</div>';
                        } else {
                            var html = '';
                            data.messages.forEach(function (m) {
                                html += '<div class="message ' + (m.is_mine ? 'mine' : '') + '">';
                                html += '<div class="message-bubble">' + escapeHtml(m.message);
                                html += '<div class="message-time">' + m.created_at + '</div></div></div>';
                            });
                            container.innerHTML = html;
                            container.scrollTop = container.scrollHeight;
                            if (data.messages.length > 0) {
                                state.lastMessageId = data.messages[data.messages.length - 1].id;
                            }
                        }
                    }
                });
                setTimeout(updateUnreadCount, 500);
                updateUnreadCount();
        }

        function sendMessage() {
            var input = document.getElementById('message-input');
            var msg = input.value.trim();
            if (!msg) return;

            var fd = new FormData();
            fd.append('receiver_id', state.currentContact);
            fd.append('message', msg);

            fetch('/chat-api/send', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.success) {
                        input.value = '';
                        var container = document.getElementById('messages-container');
                        var html = '<div class="message mine"><div class="message-bubble">' + escapeHtml(msg);
                        html += '<div class="message-time">Just now</div></div></div>';
                        container.innerHTML += html;
                        container.scrollTop = container.scrollHeight;
                        state.lastMessageId = data.message.id;
                    } else {
                        alert('Send failed');
                    }
                })
                .catch(function () { alert('Network error'); });
        }

        function startPolling() {
            stopPolling();
            state.pollInterval = setInterval(function () {
                if (!state.currentContact) return;
                fetch('/chat-api/get-new?userId=' + state.currentContact + '&lastId=' + state.lastMessageId)
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        if (data.success && data.messages.length > 0) {
                            var container = document.getElementById('messages-container');
                            data.messages.forEach(function (m) {
                                var html = '<div class="message ' + (m.is_mine ? 'mine' : '') + '">';
                                html += '<div class="message-bubble">' + escapeHtml(m.message);
                                html += '<div class="message-time">' + m.created_at + '</div></div></div>';
                                container.innerHTML += html;
                                state.lastMessageId = m.id;
                            });
                            container.scrollTop = container.scrollHeight;
                        }
                    });
            }, 3000);
        }

        function stopPolling() {
            if (state.pollInterval) {
                clearInterval(state.pollInterval);
                state.pollInterval = null;
            }
        }

        function loadTickets() {
            var container = document.getElementById('tickets-list');
            container.innerHTML = '<div style="text-align:center;padding:20px;color:#999">Loading...</div>';

            fetch('/support-api/get-tickets')
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.success && data.tickets.length > 0) {
                        var html = '';
                        data.tickets.forEach(function (t) {
                            html += '<div class="ticket-item" onclick="window.chatOpenTicket(' + t.id + ')">';
                            html += '<div style="width:40px;height:40px;background:#ff9800;border-radius:50%;color:white;display:flex;align-items:center;justify-content:center;">🎫</div>';
                            html += '<div style="flex:1"><strong>' + escapeHtml(t.subject) + '</strong><br><small style="color:#999">' + t.created_at + '</small></div>';
                            html += '</div>';
                        });
                        container.innerHTML = html;
                    } else {
                        container.innerHTML = '<div style="text-align:center;padding:20px;color:#999">No tickets</div>';
                    }
                });
        }

        window.chatOpenTicket = function (ticketId) {
            fetch('/support-api/get-ticket?id=' + ticketId)
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.success) {
                        var t = data.ticket;
                        var html = '<h4>' + escapeHtml(t.subject) + '</h4>';
                        html += '<p>' + escapeHtml(t.message) + '</p>';
                        if (t.admin_reply) {
                            html += '<div style="background:#f0f9ff;padding:15px;border-radius:8px;margin-top:20px;"><strong>Admin:</strong><p>' + escapeHtml(t.admin_reply) + '</p></div>';
                        }
                        document.getElementById('ticket-details').innerHTML = html;
                        document.getElementById('support-list').style.display = 'none';
                        document.getElementById('ticket-window').style.display = 'flex';
                    }
                });
        };

        function showTicketList() {
            document.getElementById('ticket-window').style.display = 'none';
            document.getElementById('ticket-form').style.display = 'none';
            document.getElementById('support-list').style.display = 'block';
            loadTickets();
        }

        function submitTicket() {
            var subj = document.getElementById('ticket-subject').value.trim();
            var msg = document.getElementById('ticket-message').value.trim();
            if (!subj || !msg) { alert('Fill all fields'); return; }

            var fd = new FormData();
            fd.append('subject', subj);
            fd.append('message', msg);

            fetch('/support-api/create', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.success) {
                        alert('Ticket created!');
                        document.getElementById('ticket-subject').value = '';
                        document.getElementById('ticket-message').value = '';
                        showTicketList();
                    }
                });
        }

        function escapeHtml(text) {
            var div = document.createElement('div');
            div.textContent = text || '';
            return div.innerHTML;
        }

        loadContacts();
        loadTickets();
    }





})();