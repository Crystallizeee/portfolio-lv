import './bootstrap';

// Alpine.js is bundled with Livewire, but we can add custom directives here
document.addEventListener('alpine:init', () => {
    // Typewriter effect component
    Alpine.data('typewriter', () => ({
        titles: [
            'ICT Risk Professional',
            'ISO 27001 Practitioner',
            'Offensive Security Enthusiast',
            'Home Lab Builder',
            'Blue Team Defender'
        ],
        currentIndex: 0,
        currentText: '',
        isDeleting: false,
        typeSpeed: 100,
        deleteSpeed: 50,
        pauseTime: 2000,
        
        init() {
            this.type();
        },
        
        type() {
            const fullText = this.titles[this.currentIndex];
            
            if (this.isDeleting) {
                this.currentText = fullText.substring(0, this.currentText.length - 1);
            } else {
                this.currentText = fullText.substring(0, this.currentText.length + 1);
            }
            
            let speed = this.isDeleting ? this.deleteSpeed : this.typeSpeed;
            
            if (!this.isDeleting && this.currentText === fullText) {
                speed = this.pauseTime;
                this.isDeleting = true;
            } else if (this.isDeleting && this.currentText === '') {
                this.isDeleting = false;
                this.currentIndex = (this.currentIndex + 1) % this.titles.length;
                speed = 500;
            }
            
            setTimeout(() => this.type(), speed);
        }
    }));

    // Command palette component
    Alpine.data('commandPalette', () => ({
        open: false,
        command: '',
        suggestions: [],
        commands: {
            'home': { label: 'Go to Home', action: () => window.scrollTo({ top: 0, behavior: 'smooth' }) },
            'about': { label: 'View About Section', action: () => document.getElementById('about')?.scrollIntoView({ behavior: 'smooth' }) },
            'experience': { label: 'View Experience', action: () => document.getElementById('experience')?.scrollIntoView({ behavior: 'smooth' }) },
            'lab': { label: 'View Home Lab', action: () => document.getElementById('lab')?.scrollIntoView({ behavior: 'smooth' }) },
            'contact': { label: 'Contact Me', action: () => window.location.href = 'mailto:contact@example.com' },
            'download_cv': { label: 'Download CV', action: () => alert('CV download would start here') },
        },
        
        init() {
            this.$watch('command', (value) => {
                if (value.length > 0) {
                    this.suggestions = Object.keys(this.commands)
                        .filter(cmd => cmd.includes(value.toLowerCase()))
                        .map(cmd => ({ name: cmd, ...this.commands[cmd] }));
                } else {
                    this.suggestions = Object.keys(this.commands).map(cmd => ({ name: cmd, ...this.commands[cmd] }));
                }
            });
            
            // Trigger initial suggestions
            this.suggestions = Object.keys(this.commands).map(cmd => ({ name: cmd, ...this.commands[cmd] }));
        },
        
        toggle() {
            this.open = !this.open;
            if (this.open) {
                this.$nextTick(() => this.$refs.commandInput?.focus());
            }
        },
        
        execute(cmdName) {
            const cmd = this.commands[cmdName];
            if (cmd) {
                cmd.action();
                this.open = false;
                this.command = '';
            }
        },
        
        handleEnter() {
            if (this.suggestions.length > 0) {
                this.execute(this.suggestions[0].name);
            }
        }
    }));

    // AI Chatbot Widget — Hardened against client-side abuse
    Alpine.data('chatWidget', () => ({
        isOpen: false,
        isLoading: false,
        hasInteracted: false,
        userInput: '',
        messages: [
            { role: 'bot', text: "Hi! 👋 I'm Beni's AI assistant. Ask me about his skills, experience, or projects!" }
        ],

        // ── Client-side rate limiting (DoS / resource abuse) ────────────────
        lastMessageTime: 0,
        sessionMessageCount: 0,
        MAX_SESSION_MESSAGES: 20,    // hard cap per page load
        MIN_MESSAGE_INTERVAL_MS: 2000, // min 2s between messages
        MAX_CONV_LENGTH: 50,          // max stored messages (prevents memory abuse)

        toggleChat() {
            // Configure marked for better list handling
            if (typeof marked !== 'undefined') {
                marked.setOptions({
                    breaks: true,
                    gfm: true
                });
            }
            this.isOpen = !this.isOpen;
            this.hasInteracted = true;
            if (this.isOpen) {
                this.$nextTick(() => {
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                    this.scrollToBottom();
                });
            }
        },

        /**
         * Lightweight client-side input sanitizer.
         * Strips HTML tags and normalizes whitespace before sending to backend.
         * The backend applies full sanitization — this is defense-in-depth.
         */
        sanitizeInput(text) {
            // Strip HTML tags
            const div = document.createElement('div');
            div.textContent = text;
            let clean = div.innerHTML;              // HTML-encoded
            // Decode back (we want plain text, not HTML entities in the output)
            const txt = document.createElement('textarea');
            txt.innerHTML = clean;
            clean = txt.value;
            // Collapse excessive whitespace
            clean = clean.replace(/\s{3,}/g, ' ').trim();
            // Hard truncate at 500 chars
            return clean.slice(0, 500);
        },

        /**
         * Client-side injection heuristic (quick check before even hitting server).
         * The server does the authoritative check; this just saves a round-trip.
         */
        looksLikeInjection(text) {
            const patterns = [
                /ignore\s+(previous|prior|above)\s+instructions?/i,
                /you\s+are\s+now\s+(a|an)/i,
                /forget\s+(everything|your\s+rules)/i,
                /pretend\s+(you\s+are|to\s+be)/i,
                /act\s+as\s+if/i,
                /system\s*:\s*\n/i,
                /reveal\s+your\s+(system\s+prompt|instructions?)/i,
                /\b(DAN|jailbreak)\b/i,
            ];
            return patterns.some(p => p.test(text));
        },

        async sendMessage() {
            const raw = this.userInput.trim();
            if (!raw || this.isLoading) return;

            // ── Session message cap ─────────────────────────────────────────
            if (this.sessionMessageCount >= this.MAX_SESSION_MESSAGES) {
                this.messages.push({
                    role: 'bot',
                    text: "You've reached the session limit. Please refresh the page to continue chatting."
                });
                this.userInput = '';
                this.scrollToBottom();
                return;
            }

            // ── Min interval (burst protection) ────────────────────────────
            const now = Date.now();
            if (now - this.lastMessageTime < this.MIN_MESSAGE_INTERVAL_MS) {
                this.messages.push({
                    role: 'bot',
                    text: 'Please wait a moment before sending another message.'
                });
                this.userInput = '';
                this.scrollToBottom();
                return;
            }

            // ── Client-side sanitization ────────────────────────────────────
            const msg = this.sanitizeInput(raw);
            if (!msg) return;

            // ── Quick injection heuristic ───────────────────────────────────
            if (this.looksLikeInjection(msg)) {
                this.messages.push({
                    role: 'bot',
                    text: "I can only help with questions about Beni's portfolio and professional background. 😊"
                });
                this.userInput = '';
                this.scrollToBottom();
                return;
            }

            // ── Conversation length guard (prevent memory abuse) ─────────────
            if (this.messages.length >= this.MAX_CONV_LENGTH) {
                this.messages = this.messages.slice(-10); // keep last 10
                this.messages.unshift({ role: 'bot', text: '[ Older messages cleared to save space ]' });
            }

            // ── Send ────────────────────────────────────────────────────────
            this.messages.push({ role: 'user', text: msg });
            this.userInput = '';
            this.isLoading = true;
            this.lastMessageTime = now;
            this.sessionMessageCount++;
            this.scrollToBottom();

            try {
                const res = await fetch('/api/chatbot', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ message: msg })
                });

                const data = await res.json();

                if (res.status === 429) {
                    this.messages.push({ role: 'bot', text: data.reply || "You're sending messages too fast. Please wait a moment." });
                } else if (res.ok) {
                    // data.reply is already sanitized by backend — safe to use with x-text
                    this.messages.push({ role: 'bot', text: data.reply });
                } else {
                    this.messages.push({ role: 'bot', text: data.reply || 'Sorry, something went wrong.' });
                }
            } catch (e) {
                this.messages.push({ role: 'bot', text: 'Network error. Please check your connection.' });
            } finally {
                this.isLoading = false;
                this.scrollToBottom();
            }
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.messagesContainer;
                if (container) container.scrollTop = container.scrollHeight;
            });
        }
    }));
});

// Global keyboard shortcut for command palette
document.addEventListener('keydown', (e) => {
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        Alpine.store('commandPaletteOpen', !Alpine.store('commandPaletteOpen'));
        document.dispatchEvent(new CustomEvent('toggle-command-palette'));
    }
});
