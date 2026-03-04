(function () {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    function init() {
        var state = {
            currentContact: null,
            currentGroup: null,
            currentTicket: null,
            lastMessageId: 0,
            pollInterval: null
        };

        var chatButton = document.getElementById('chat-button');
        var chatPopup = document.getElementById('chat-popup');

        if (!chatButton || !chatPopup) return;

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
        var tabButtons = document.querySelectorAll('.tab-btn');
        var tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var tabName = this.getAttribute('data-tab');

                tabButtons.forEach(function (b) {
                    b.classList.remove('active');
                    b.style.background = 'rgba(255,255,255,0.2)';
                    b.style.color = 'white';
                });

                this.classList.add('active');
                this.style.background = 'white';
                this.style.color = '#667eea';

                tabContents.forEach(function (content) {
                    content.style.display = 'none';
                    content.classList.remove('active');
                });

                var activeTab = document.getElementById('tab-' + tabName);
                if (activeTab) {
                    activeTab.style.display = 'flex';
                    activeTab.classList.add('active');

                    if (tabName === 'messages') loadContacts();
                    else if (tabName === 'support') loadTickets();
                }
            });
        });

        // Student/Group toggle
        var showStudentsBtn = document.getElementById('show-students-btn');
        var showGroupsBtn = document.getElementById('show-groups-btn');
        if (showStudentsBtn && showGroupsBtn) {
            showStudentsBtn.onclick = function () {
                this.style.background = '#667eea';
                this.style.color = 'white';
                showGroupsBtn.style.background = '#e0e0e0';
                showGroupsBtn.style.color = '#666';
                document.getElementById('contact-list').style.display = 'block';
                var gl = document.getElementById('group-list');
                if (gl) gl.style.display = 'none';
                loadContacts();
            };
            showGroupsBtn.onclick = function () {
                this.style.background = '#667eea';
                this.style.color = 'white';
                showStudentsBtn.style.background = '#e0e0e0';
                showStudentsBtn.style.color = '#666';
                document.getElementById('contact-list').style.display = 'none';
                var gl = document.getElementById('group-list');
                if (gl) gl.style.display = 'block';
                loadGroups();
            };
        }

        // Back buttons
        document.getElementById('back-to-contacts').onclick = function (e) {
            e.preventDefault();
            stopPolling();
            document.getElementById('chat-window').style.display = 'none';
            document.getElementById('contact-list').style.display = 'block';
        };

        var backToGroupsBtn = document.getElementById('back-to-groups');
        if (backToGroupsBtn) {
            backToGroupsBtn.onclick = function (e) {
                e.preventDefault();
                document.getElementById('group-chat-window').style.display = 'none';
                document.getElementById('group-list').style.display = 'block';
            };
        }

        // Send
        document.getElementById('send-btn').onclick = sendMessage;
        document.getElementById('message-input').onkeypress = function (e) {
            if (e.key === 'Enter') sendMessage();
        };

        var groupSendBtn = document.getElementById('group-send-btn');
        if (groupSendBtn) {
            groupSendBtn.onclick = sendGroupMessage;
            document.getElementById('group-message-input').onkeypress = function (e) {
                if (e.key === 'Enter') sendGroupMessage();
            };
        }

        // Clear/Delete buttons
        var clearChatBtn = document.getElementById('clear-chat-btn');
        if (clearChatBtn) {
            clearChatBtn.onclick = function (e) {
                e.preventDefault();
                if (!state.currentContact || !confirm('Chat tarixini tozalamoqchimisiz? Bu amalni ortga qaytarib bo\'lmaydi.')) return;
                var fd = new FormData();
                fd.append('user_id', state.currentContact);
                
                // YANGI LINK (clear-data):
                fetch('/message/clear-data', { method: 'POST', body: fd })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('messages-container').innerHTML = '<div style="text-align:center;padding:20px;color:#999">Chat tozalandi</div>';
                            showNotification('✅ Chat tozalandi!');
                        }
                    });
            };
        }

        var clearGroupBtn = document.getElementById('clear-group-chat-btn');
        if (clearGroupBtn) {
            clearGroupBtn.onclick = function (e) {
                e.preventDefault();
                if (!state.currentGroup || !confirm('Guruh chatini tozalamoqchimisiz? Bu amalni ortga qaytarib bo\'lmaydi.')) return;
                var fd = new FormData();
                fd.append('group_id', state.currentGroup);
                
                // YANGI LINK (clear-group-data):
                fetch('/message/clear-group-data', { method: 'POST', body: fd })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('group-messages-container').innerHTML = '<div style="text-align:center;padding:20px;color:#999">Guruh tozalandi</div>';
                            showNotification('✅ Guruh tozalandi!');
                        }
                    });
            };
        }

        var deleteTicketBtn = document.getElementById('delete-ticket-btn');
        if (deleteTicketBtn) {
            deleteTicketBtn.onclick = function (e) {
                e.preventDefault();
                if (!state.currentTicket || !confirm('Tiketni o\'chirib tashlaysizmi? Bu amalni ortga qaytarib bo\'lmaydi.')) return;
                var fd = new FormData();
                fd.append('ticket_id', state.currentTicket);
                fetch('/support-api/delete-ticket', { method: 'POST', body: fd })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('✅ Tiket o\'chirildi!');
                            showTicketList();
                        }
                    });
            };
        }

        // Support
        document.getElementById('new-ticket-btn').onclick = function () {
            document.getElementById('support-list').style.display = 'none';
            document.getElementById('ticket-form').style.display = 'block';
        };
        document.getElementById('back-to-tickets').onclick = showTicketList;
        document.getElementById('cancel-ticket').onclick = showTicketList;
        document.getElementById('submit-ticket').onclick = submitTicket;

        // Global functions
        window.chatOpenChat = function (userId, name) {
            state.currentContact = userId;
            state.lastMessageId = 0;
            document.getElementById('current-contact-name').textContent = name;
            document.getElementById('contact-list').style.display = 'none';
            document.getElementById('chat-window').style.display = 'flex';
            loadMessages(userId);
            startPolling();
        };

        window.chatOpenGroup = function (groupId, groupName, studentCount) {
            state.currentGroup = groupId;
            document.getElementById('current-group-name').textContent = groupName;
            document.getElementById('group-student-count').textContent = '(' + studentCount + ' talaba)';
            document.getElementById('group-list').style.display = 'none';
            document.getElementById('group-chat-window').style.display = 'flex';
            loadGroupMessages(groupId);
        };

        window.chatOpenTicket = function (ticketId) {
            state.currentTicket = ticketId;
            fetch('/support-api/get-ticket?id=' + ticketId)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        var t = data.ticket;
                        var html = '<div style="padding:20px;"><h4>' + escapeHtml(t.subject) + '</h4><p>' + escapeHtml(t.message) + '</p>';
                        if (t.admin_reply) html += '<div style="background:#f0f9ff;padding:15px;border-radius:8px;margin-top:20px;"><strong>Admin:</strong><p>' + escapeHtml(t.admin_reply) + '</p></div>';
                        html += '</div>';
                        document.getElementById('ticket-details').innerHTML = html;
                        document.getElementById('support-list').style.display = 'none';
                        document.getElementById('ticket-window').style.display = 'flex';
                    }
                });
        };

        function loadContacts() {
            var container = document.getElementById('contact-list');
            container.innerHTML = '<div style="text-align:center;padding:20px;">Yuklanmoqda...</div>';
            // YANGI LINK:
            fetch('/message/get-contacts')
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.contacts.length > 0) {
                        var html = '';
                        data.contacts.forEach(c => {
                            html += '<div onclick="window.chatOpenChat(' + c.id + ',\'' + c.name.replace(/'/g, "\\'") + '\')" style="display:flex;align-items:center;gap:12px;padding:12px;cursor:pointer;border-radius:8px;margin:8px 0;">';
                            html += '<div style="width:40px;height:40px;background:#667eea;border-radius:50%;color:white;display:flex;align-items:center;justify-content:center;">👤</div>';
                            html += '<div style="flex:1"><strong>' + escapeHtml(c.name) + '</strong><br><small style="color:#999">' + c.role + '</small></div>';
                            if (c.unread > 0) html += '<span style="background:#ff4757;color:white;padding:4px 8px;border-radius:10px;font-size:12px;">' + c.unread + '</span>';
                            html += '</div>';
                        });
                        container.innerHTML = html;
                    } else {
                        container.innerHTML = '<div style="text-align:center;padding:20px;color:#999">Kontaktlar mavjud emas</div>';
                    }
                });
        }

        function loadGroups() {
            var container = document.getElementById('group-list');
            if (!container) return;
            container.innerHTML = '<div style="text-align:center;padding:20px;">Yuklanmoqda...</div>';
            // YANGI LINK:
            fetch('/message/get-groups')
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.groups.length > 0) {
                        var html = '';
                        data.groups.forEach(g => {
                            html += '<div onclick="window.chatOpenGroup(' + g.id + ',\'' + g.name.replace(/'/g, "\\'") + '\',' + g.student_count + ')" style="display:flex;align-items:center;gap:12px;padding:12px;cursor:pointer;border-radius:8px;margin:8px 0;">';
                            html += '<div style="width:40px;height:40px;background:#ff9800;border-radius:50%;color:white;display:flex;align-items:center;justify-content:center;">📢</div>';
                            html += '<div style="flex:1"><strong>' + escapeHtml(g.name) + '</strong><br><small style="color:#999">' + g.student_count + ' ta talaba</small></div></div>';
                        });
                        container.innerHTML = html;
                    } else {
                        container.innerHTML = '<div style="text-align:center;padding:20px;color:#999">Guruhlar mavjud emas</div>';
                    }
                });
        }

        function loadMessages(userId) {
            var container = document.getElementById('messages-container');
            container.innerHTML = '<div style="text-align:center;padding:20px;">Yuklanmoqda...</div>';
            // YANGI LINK:
            fetch('/message/get-messages?userId=' + userId)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        if (data.messages.length === 0) {
                            container.innerHTML = '<div style="text-align:center;padding:20px;color:#999">Xabarlar yo\'q</div>';
                        } else {
                            var html = '';
                            data.messages.forEach(m => {
                                var bgColor = m.is_mine
                                    ? (m.is_group ? 'linear-gradient(135deg,#ff9800 0%,#ff5722 100%)' : 'linear-gradient(135deg,#667eea 0%,#764ba2 100%)')
                                    : 'white';
                                var textColor = m.is_mine ? 'white' : '#333';
                                var shadow = m.is_mine ? '' : 'box-shadow:0 1px 3px rgba(0,0,0,0.1);';

                                html += '<div style="margin-bottom:12px;display:flex;' + (m.is_mine ? 'justify-content:flex-end;' : '') + '">';
                                html += '<div style="max-width:70%;padding:10px 15px;border-radius:18px;background:' + bgColor + ';color:' + textColor + ';' + shadow + '">';
                                html += escapeHtml(m.message);
                                if (m.is_group) html += '<div style="font-size:10px;opacity:0.8;margin-top:2px;">📢 Guruh xabari</div>';
                                html += '<div style="font-size:11px;opacity:0.7;margin-top:4px;">' + m.created_at + '</div></div></div>';
                            });
                            container.innerHTML = html;
                            container.scrollTop = container.scrollHeight;
                            if (data.messages.length > 0) state.lastMessageId = data.messages[data.messages.length - 1].id;
                        }
                    }
                    setTimeout(updateUnreadCount, 500);
                });
        }

        function loadGroupMessages(groupId) {
            var container = document.getElementById('group-messages-container');
            if (!container) return;
            container.innerHTML = '<div style="text-align:center;padding:20px;">Yuklanmoqda...</div>';
            // YANGI LINK:
            fetch('/message/get-group-messages?groupId=' + groupId)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        if (data.messages.length === 0) {
                            container.innerHTML = '<div style="text-align:center;padding:20px;color:#999">Xabarlar yo\'q</div>';
                        } else {
                            var html = '';
                            data.messages.forEach(m => {
                                html += '<div style="margin-bottom:12px;display:flex;justify-content:flex-end;"><div style="max-width:70%;padding:10px 15px;border-radius:18px;background:linear-gradient(135deg,#ff9800 0%,#ff5722 100%);color:white;">';
                                html += escapeHtml(m.message) + '<div style="font-size:11px;opacity:0.7;margin-top:4px;">📢 ' + m.created_at + '</div></div></div>';
                            });
                            container.innerHTML = html;
                            container.scrollTop = container.scrollHeight;
                        }
                    }
                });
        }

        function sendMessage() {
            var input = document.getElementById('message-input');
            var msg = input.value.trim();
            if (!msg) return;
            var fd = new FormData();
            fd.append('receiver_id', state.currentContact);
            fd.append('message', msg);
            // YANGI LINK:
            fetch('/message/send', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        input.value = '';
                        var container = document.getElementById('messages-container');
                        container.innerHTML += '<div style="margin-bottom:12px;display:flex;justify-content:flex-end;"><div style="max-width:70%;padding:10px 15px;border-radius:18px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white;">' + escapeHtml(msg) + '<div style="font-size:11px;opacity:0.7;margin-top:4px;">Hozirgina</div></div></div>';
                        container.scrollTop = container.scrollHeight;
                        state.lastMessageId = data.message.id;
                    }
                });
        }

        function sendGroupMessage() {
            var input = document.getElementById('group-message-input');
            var msg = input.value.trim();
            if (!msg) return;
            var fd = new FormData();
            fd.append('group_id', state.currentGroup);
            fd.append('message', msg);
            // YANGI LINK:
            fetch('/message/send-group-message', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        input.value = '';
                        var container = document.getElementById('group-messages-container');
                        if (container.innerHTML.includes('Xabarlar yo\'q')) container.innerHTML = '';
                        container.innerHTML += '<div style="margin-bottom:12px;display:flex;justify-content:flex-end;"><div style="max-width:70%;padding:10px 15px;border-radius:18px;background:linear-gradient(135deg,#ff9800 0%,#ff5722 100%);color:white;">' + escapeHtml(msg) + '<div style="font-size:11px;opacity:0.7;margin-top:4px;">📢 Yuborildi: ' + data.sent_count + '</div></div></div>';
                        container.scrollTop = container.scrollHeight;
                        showNotification('✅ ' + data.sent_count + ' ta talabaga yuborildi!');
                    }
                });
        }

        function updateUnreadCount() {
            fetch('/support-api/count')
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        var badge = document.getElementById('total-unread');
                        if (badge) { // Element borligini tekshiramiz
                            badge.textContent = data.count;
                            badge.style.display = data.count > 0 ? 'flex' : 'none';
                        }
                    }
                });
        }

        function startPolling() {
            stopPolling();
            state.pollInterval = setInterval(() => {
                if (!state.currentContact) return;
                // YANGI LINK (get-new):
                fetch('/message/get-new?userId=' + state.currentContact + '&lastId=' + state.lastMessageId)
                    .then(r => r.json())
                    .then(data => {
                        if (data.success && data.messages.length > 0) {
                            var container = document.getElementById('messages-container');
                            data.messages.forEach(m => {
                                var bgColor = m.is_mine
                                    ? (m.is_group ? 'linear-gradient(135deg,#ff9800 0%,#ff5722 100%)' : 'linear-gradient(135deg,#667eea 0%,#764ba2 100%)')
                                    : 'white';
                                var textColor = m.is_mine ? 'white' : '#333';
                                var shadow = m.is_mine ? '' : 'box-shadow:0 1px 3px rgba(0,0,0,0.1);';

                                var html = '<div style="margin-bottom:12px;display:flex;' + (m.is_mine ? 'justify-content:flex-end;' : '') + '"><div style="max-width:70%;padding:10px 15px;border-radius:18px;background:' + bgColor + ';color:' + textColor + ';' + shadow + '">' + escapeHtml(m.message);
                                if (m.is_group) html += '<div style="font-size:10px;opacity:0.8;margin-top:2px;">📢 Guruh xabari</div>';
                                html += '<div style="font-size:11px;opacity:0.7;margin-top:4px;">' + m.created_at + '</div></div></div>';
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
            container.innerHTML = '<div style="text-align:center;padding:20px;">Yuklanmoqda...</div>';
            fetch('/support-api/get-tickets')
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.tickets.length > 0) {
                        var html = '';
                        data.tickets.forEach(t => {
                            html += '<div onclick="window.chatOpenTicket(' + t.id + ')" style="display:flex;align-items:center;gap:12px;padding:12px;border:1px solid #e0e0e0;border-radius:8px;cursor:pointer;margin:8px 0;">';
                            html += '<div style="width:40px;height:40px;background:#ff9800;border-radius:50%;color:white;display:flex;align-items:center;justify-content:center;">🎫</div>';
                            html += '<div style="flex:1"><strong>' + escapeHtml(t.subject) + '</strong><br><small style="color:#999">' + t.created_at + '</small></div></div>';
                        });
                        container.innerHTML = html;
                    } else {
                        container.innerHTML = '<div style="text-align:center;padding:20px;color:#999">Tiketlar yo\'q</div>';
                    }
                });
        }

        function showTicketList() {
            state.currentTicket = null;
            document.getElementById('ticket-window').style.display = 'none';
            document.getElementById('ticket-form').style.display = 'none';
            document.getElementById('support-list').style.display = 'block';
            loadTickets();
        }

        function submitTicket() {
            var subj = document.getElementById('ticket-subject').value.trim();
            var msg = document.getElementById('ticket-message').value.trim();

            if (!subj || !msg) {
                showNotification("Barcha maydonlarni to'ldiring!");
                return;
            }

            var fd = new FormData();
            fd.append('subject', subj);
            fd.append('message', msg);

            fetch('/support-api/create', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        showNotification("Tiket muvaffaqiyatli yuborildi!");
                        document.getElementById('ticket-subject').value = '';
                        document.getElementById('ticket-message').value = '';
                        showTicketList();
                    } else {
                        showNotification("Xatolik yuz berdi, qayta urinib ko'ring.");
                    }
                })
                .catch(err => {
                    showNotification("Server bilan ulanishda xatolik.");
                    console.error(err);
                });
        }

        // Birlamchi Notification Funksiyasi (showToast nomini ham shunga uladik)
        function showNotification(msg) {
            var n = document.createElement('div');
            n.textContent = msg;
            n.style.cssText = 'position:fixed;top:20px;right:20px;background:#4caf50;color:white;padding:15px 20px;border-radius:8px;z-index:10000;box-shadow:0 2px 5px rgba(0,0,0,0.2);';
            document.body.appendChild(n);
            setTimeout(() => document.body.removeChild(n), 3000);
        }
        
        // Agar kodingizda showToast ishlatilgan bo'lsa, uni showNotification ga yo'naltiramiz
        function showToast(msg) {
            showNotification(msg);
        }

        function escapeHtml(text) {
            var div = document.createElement('div');
            div.textContent = text || '';
            return div.innerHTML;
        }

        // Check if user is teacher
        // YANGI LINK:
        fetch('/message/get-contacts')
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    fetch('/message/get-groups')
                        .then(r => r.json())
                        .then(groupData => {
                            if (groupData.success) {
                                var toggle = document.getElementById('teacher-toggle');
                                if (toggle) toggle.style.display = 'flex';
                            }
                        })
                        .catch(() => { });
                }
            });

        setInterval(updateUnreadCount, 10000);
        loadContacts();
        loadTickets();
        updateUnreadCount();
    }
})();