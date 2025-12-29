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
});

// Global keyboard shortcut for command palette
document.addEventListener('keydown', (e) => {
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        Alpine.store('commandPaletteOpen', !Alpine.store('commandPaletteOpen'));
        document.dispatchEvent(new CustomEvent('toggle-command-palette'));
    }
});
