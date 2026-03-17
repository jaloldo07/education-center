(function () {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    function init() {
        var state = {
            currentContact: null,
            currentCourse: null, // 🔥 currentGroup o'rniga
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

        // 🔥 Student/Course toggle (Group o'rniga Course)
        var showStudentsBtn = document.getElementById('show-students-btn');
        var showCoursesBtn = document.getElementById('show-courses-btn'); // show-groups-btn edi
        if (showStudentsBtn && showCoursesBtn) {
            showStudentsBtn.onclick = function () {
                this.style.background = '#667eea';
                this.style.color = 'white';
                showCoursesBtn.style.background = '#e0e0e0';
                showCoursesBtn.style.color = '#666';
                document.getElementById('contact-list').style.display = 'block';
                var cl = document.getElementById('course-list'); // group-list edi
                if (cl) cl.style.display = 'none';
                loadContacts();
            };
            showCoursesBtn.onclick = function () {
                this.style.background = '#667eea';
                this.style.color = 'white';
                showStudentsBtn.style.background = '#e0e0e0';
                showStudentsBtn.style.color = '#666';
                document.getElementById('contact-list').style.display = 'none';
                var cl = document.getElementById('course-list');
                if (cl) cl.style.display = 'block';
                loadCourses(); // loadGroups() edi
            };
        }

        // Back buttons
        document.getElementById('back-to-contacts').onclick = function (e) {
            e.preventDefault();
            stopPolling();
            document.getElementById('chat-window').style.display = 'none';
            document.getElementById('contact-list').style.display = 'block';
        };

        var backToCoursesBtn = document.getElementById('back-to-courses'); // back-to-groups edi
        if (backToCoursesBtn) {
            backToCoursesBtn.onclick = function (e) {
                e.preventDefault();
                document.getElementById('course-chat-window').style.display = 'none'; // group-chat-window edi
                document.getElementById('course-list').style.display = 'block'; // group-list edi
            };
        }

        // Send
        document.getElementById('send-btn').onclick = sendMessage;
        document.getElementById('message-input').onkeypress = function (e) {
            if (e.key === 'Enter') sendMessage();
        };

        var courseSendBtn = document.getElementById('course-send-btn'); // group-send-btn edi
        if (courseSendBtn) {
            courseSendBtn.onclick = sendCourseMessage; // sendGroupMessage edi
            document.getElementById('course-message-input').onkeypress = function (e) { // group-message-input edi
                if (e.key === 'Enter') sendCourseMessage();
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

        var clearCourseBtn = document.getElementById('clear-course-chat-btn'); // clear-group-chat-btn edi
        if (clearCourseBtn) {
            clearCourseBtn.onclick = function (e) {
                e.preventDefault();
                if (!state.currentCourse || !confirm('Kurs chatini tozalamoqchimisiz? Bu amalni ortga qaytarib bo\'lmaydi.')) return;
                var fd = new FormData();
                fd.append('course_id', state.currentCourse); // group_id edi
                
                fetch('/message/clear-course-data', { method: 'POST', body: fd }) // clear-group-data edi
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('course-messages-container').innerHTML = '<div style="text-align:center;padding:20px;color:#999">Kurs chati tozalandi</div>'; // group-messages-container edi
                            showNotification('✅ Kurs chati tozalandi!');
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

        window.chatOpenCourse = function (courseId, courseName, studentCount) { // chatOpenGroup edi
            state.currentCourse = courseId; // currentGroup edi
            document.getElementById('current-course-name').textContent = courseName; // current-group-name edi
            document.getElementById('course-student-count').textContent = '(' + studentCount + ' talaba)'; // group-student-count edi
            document.getElementById('course-list').style.display = 'none'; // group-list edi
            document.getElementById('course-chat-window').style.display = 'flex'; // group-chat-window edi
            loadCourseMessages(courseId); // loadGroupMessages edi
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

        function loadCourses() { // loadGroups edi
            var container = document.getElementById('course-list'); // group-list edi
            if (!container) return;
            container.innerHTML = '<div style="text-align:center;padding:20px;">Yuklanmoqda...</div>';
            fetch('/message/get-courses') // get-groups edi
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.courses.length > 0) { // groups edi
                        var html = '';
                        data.courses.forEach(c => { // groups edi
                            html += '<div onclick="window.chatOpenCourse(' + c.id + ',\'' + c.name.replace(/'/g, "\\'") + '\',' + c.student_count + ')" style="display:flex;align-items:center;gap:12px;padding:12px;cursor:pointer;border-radius:8px;margin:8px 0;">';
                            html += '<div style="width:40px;height:40px;background:#ff9800;border-radius:50%;color:white;display:flex;align-items:center;justify-content:center;">📢</div>';
                            html += '<div style="flex:1"><strong>' + escapeHtml(c.name) + '</strong><br><small style="color:#999">' + c.student_count + ' ta talaba</small></div></div>';
                        });
                        container.innerHTML = html;
                    } else {
                        container.innerHTML = '<div style="text-align:center;padding:20px;color:#999">Kurslar mavjud emas</div>';
                    }
                });
        }

        function loadMessages(userId) {
            var container = document.getElementById('messages-container');
            container.innerHTML = '<div style="text-align:center;padding:20px;">Yuklanmoqda...</div>';
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
                                    ? (m.is_course ? 'linear-gradient(135deg,#ff9800 0%,#ff5722 100%)' : 'linear-gradient(135deg,#667eea 0%,#764ba2 100%)') // is_group edi
                                    : 'white';
                                var textColor = m.is_mine ? 'white' : '#333';
                                var shadow = m.is_mine ? '' : 'box-shadow:0 1px 3px rgba(0,0,0,0.1);';

                                html += '<div style="margin-bottom:12px;display:flex;' + (m.is_mine ? 'justify-content:flex-end;' : '') + '">';
                                html += '<div style="max-width:70%;padding:10px 15px;border-radius:18px;background:' + bgColor + ';color:' + textColor + ';' + shadow + '">';
                                html += escapeHtml(m.message);
                                if (m.is_course) html += '<div style="font-size:10px;opacity:0.8;margin-top:2px;">📢 Kurs xabari</div>'; // is_group edi
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

        function loadCourseMessages(courseId) { // loadGroupMessages edi
            var container = document.getElementById('course-messages-container'); // group-messages-container edi
            if (!container) return;
            container.innerHTML = '<div style="text-align:center;padding:20px;">Yuklanmoqda...</div>';
            fetch('/message/get-course-messages?courseId=' + courseId) // get-group-messages edi
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

        function sendCourseMessage() { // sendGroupMessage edi
            var input = document.getElementById('course-message-input'); // group-message-input edi
            var msg = input.value.trim();
            if (!msg) return;
            var fd = new FormData();
            fd.append('course_id', state.currentCourse); // group_id edi
            fd.append('message', msg);
            fetch('/message/send-course-message', { method: 'POST', body: fd }) // send-group-message edi
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        input.value = '';
                        var container = document.getElementById('course-messages-container'); // group-messages-container edi
                        if (container.innerHTML.includes('Xabarlar yo\'q')) container.innerHTML = '';
                        container.innerHTML += '<div style="margin-bottom:12px;display:flex;justify-content:flex-end;"><div style="max-width:70%;padding:10px 15px;border-radius:18px;background:linear-gradient(135deg,#ff9800 0%,#ff5722 100%);color:white;">' + escapeHtml(msg) + '<div style="font-size:11px;opacity:0.7;margin-top:4px;">📢 Yuborildi: ' + data.sent_count + '</div></div></div>';
                        container.scrollTop = container.scrollHeight;
                        showNotification('✅ ' + data.sent_count + ' ta talabaga yuborildi!');
                    }
                });
        }

        // 🔥 Yangi xabarlarni kuzatish uchun o'zgaruvchini e'lon qilamiz
        var previousUnreadCount = -1;

        function updateUnreadCount() {
            // Chat va Support xabarlarini bir vaqtda (parallel) tekshiramiz
            Promise.all([
                fetch('/message/count').then(r => r.json()).catch(() => ({ success: false, count: 0 })),
                fetch('/support-api/count').then(r => r.json()).catch(() => ({ success: false, count: 0 }))
            ]).then(results => {
                var chatData = results[0];
                var supportData = results[1];

                // Umumiy o'qilmagan xabarlar sonini hisoblaymiz
                var totalUnread = 0;
                if (chatData.success) totalUnread += parseInt(chatData.count) || 0;
                if (supportData.success) totalUnread += parseInt(supportData.count) || 0;

                // Qizil dumaloq (badge) ni yangilaymiz
                var badge = document.getElementById('total-unread');
                if (badge) {
                    badge.textContent = totalUnread > 9 ? '9+' : totalUnread;
                    badge.style.display = totalUnread > 0 ? 'flex' : 'none';
                }

                // 🔥 PUSH NOTIFICATION VA OVOZ MANTIG'I
                if (previousUnreadCount !== -1 && totalUnread > previousUnreadCount) {
                    // Ekranga toast chiqarish
                    if (typeof showToast === 'function') {
                        showToast("Sizda yangi xabar bor! 💬", "success");
                    }
                    
                    // Ovoz chiqarish
                    try {
                        var audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
                        audio.volume = 0.5; // Ovoz balandligi (0.0 dan 1.0 gacha)
                        
                        // Brauzer siyosatiga ko'ra ovoz ishlashi uchun foydalanuvchi saytga biror marta bosgan bo'lishi kerak
                        let playPromise = audio.play();
                        if (playPromise !== undefined) {
                            playPromise.catch(error => { console.log("Brauzer ovozni blokladi."); });
                        }
                    } catch(e) { }
                }

                // Joriy holatni saqlab qo'yamiz
                previousUnreadCount = totalUnread;
            });
        }

        function startPolling() {
            stopPolling();
            state.pollInterval = setInterval(() => {
                if (!state.currentContact) return;
                fetch('/message/get-new?userId=' + state.currentContact + '&lastId=' + state.lastMessageId)
                    .then(r => r.json())
                    .then(data => {
                        if (data.success && data.messages.length > 0) {
                            var container = document.getElementById('messages-container');
                            data.messages.forEach(m => {
                                var bgColor = m.is_mine
                                    ? (m.is_course ? 'linear-gradient(135deg,#ff9800 0%,#ff5722 100%)' : 'linear-gradient(135deg,#667eea 0%,#764ba2 100%)') // is_group edi
                                    : 'white';
                                var textColor = m.is_mine ? 'white' : '#333';
                                var shadow = m.is_mine ? '' : 'box-shadow:0 1px 3px rgba(0,0,0,0.1);';

                                var html = '<div style="margin-bottom:12px;display:flex;' + (m.is_mine ? 'justify-content:flex-end;' : '') + '"><div style="max-width:70%;padding:10px 15px;border-radius:18px;background:' + bgColor + ';color:' + textColor + ';' + shadow + '">' + escapeHtml(m.message);
                                if (m.is_course) html += '<div style="font-size:10px;opacity:0.8;margin-top:2px;">📢 Kurs xabari</div>'; // is_group edi
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

        function showNotification(msg) {
            var n = document.createElement('div');
            n.textContent = msg;
            n.style.cssText = 'position:fixed;top:20px;right:20px;background:#4caf50;color:white;padding:15px 20px;border-radius:8px;z-index:10000;box-shadow:0 2px 5px rgba(0,0,0,0.2);';
            document.body.appendChild(n);
            setTimeout(() => document.body.removeChild(n), 3000);
        }
        
        function showToast(msg) {
            showNotification(msg);
        }

        function escapeHtml(text) {
            var div = document.createElement('div');
            div.textContent = text || '';
            return div.innerHTML;
        }

        // Check if user is teacher
        fetch('/message/get-contacts')
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    fetch('/message/get-courses') // get-groups edi
                        .then(r => r.json())
                        .then(courseData => { // groupData edi
                            if (courseData.success) {
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