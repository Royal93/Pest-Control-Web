{{--
    Floating chatbot widget. Self-contained: no build step, no external JS
    dependencies (plain fetch + vanilla JS), so it can be dropped into any
    layout without touching your Vite/Alpine setup.

    Include this once, right before </body>, in your main layout —
    it renders on every page that extends it.
--}}
<div id="sp-chat-widget">
    <button id="sp-chat-toggle" aria-label="Open chat">
        <svg id="sp-chat-icon-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
        <svg id="sp-chat-icon-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>

    <div id="sp-chat-panel">
        <div id="sp-chat-header">
            <span>SP Pest Control Assistant</span>
        </div>
        <div id="sp-chat-messages">
            <div class="sp-chat-msg sp-chat-msg-bot">
                Hi! I can answer questions about our pests treated, plans, and pricing. What can I help with?
            </div>
        </div>
        <div id="sp-chat-typing" style="display:none;">Typing&hellip;</div>
        <form id="sp-chat-form">
            <input type="text" id="sp-chat-input" placeholder="Ask a question…" autocomplete="off" required>
            <button type="submit" aria-label="Send">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
            </button>
        </form>
    </div>
</div>

<style>
    #sp-chat-widget { position: fixed; bottom: 24px; right: 24px; z-index: 9999; font-family: 'Source Sans 3', sans-serif; }

    #sp-chat-toggle {
        width: 60px; height: 60px; border-radius: 9999px; border: none;
        background: var(--primary, #D1723C); color: #fff; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 8px 24px rgba(44, 52, 58, 0.25);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    #sp-chat-toggle:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(44, 52, 58, 0.32); }
    #sp-chat-toggle svg { width: 26px; height: 26px; }

    #sp-chat-panel {
        display: none;
        position: absolute; bottom: 76px; right: 0;
        width: 340px; max-width: calc(100vw - 32px);
        height: 460px; max-height: calc(100vh - 140px);
        background: #fff; border-radius: 16px; overflow: hidden;
        box-shadow: 0 16px 48px rgba(44, 52, 58, 0.28);
        flex-direction: column;
    }
    #sp-chat-widget.is-open #sp-chat-panel { display: flex; }

    #sp-chat-header {
        background: var(--secondary, #2C343A); color: #fff;
        padding: 14px 18px; font-weight: 600; font-size: 0.95rem;
        text-transform: uppercase; letter-spacing: 0.02em;
    }

    #sp-chat-messages {
        flex: 1; overflow-y: auto; padding: 16px; display: flex;
        flex-direction: column; gap: 10px; background: #F5F1EC;
    }

    .sp-chat-msg {
        max-width: 85%; padding: 10px 14px; border-radius: 14px;
        font-size: 0.875rem; line-height: 1.4; white-space: pre-wrap;
    }
    .sp-chat-msg-bot {
        align-self: flex-start; background: #fff; color: #333;
        border: 1px solid #e5e0d8; border-bottom-left-radius: 4px;
    }
    .sp-chat-msg-user {
        align-self: flex-end; background: var(--primary, #D1723C); color: #fff;
        border-bottom-right-radius: 4px;
    }

    #sp-chat-typing {
        padding: 4px 16px; font-size: 0.8rem; color: #8C8C8C; background: #F5F1EC;
    }

    #sp-chat-form {
        display: flex; border-top: 2px solid #e5e0d8; padding: 10px;
        gap: 8px; background: #fff;
    }
    #sp-chat-input {
        flex: 1; border: 2px solid #e5e0d8; border-radius: 10px;
        padding: 8px 12px; font-size: 0.875rem; outline: none;
    }
    #sp-chat-input:focus { border-color: var(--primary, #D1723C); }
    #sp-chat-form button {
        width: 40px; height: 40px; border-radius: 10px; border: none;
        background: var(--primary, #D1723C); color: #fff; cursor: pointer;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    #sp-chat-form button svg { width: 18px; height: 18px; }

    @media (max-width: 480px) {
        #sp-chat-panel { width: calc(100vw - 32px); }
    }
</style>

<script>
(function () {
    const widget   = document.getElementById('sp-chat-widget');
    const toggle   = document.getElementById('sp-chat-toggle');
    const iconOpen = document.getElementById('sp-chat-icon-open');
    const iconClose= document.getElementById('sp-chat-icon-close');
    const messages = document.getElementById('sp-chat-messages');
    const typing   = document.getElementById('sp-chat-typing');
    const form     = document.getElementById('sp-chat-form');
    const input    = document.getElementById('sp-chat-input');

    // Keeps the running conversation in the shape the API expects:
    // [{ role: 'user'|'assistant', content: '...' }, ...]
    let history = [];

    toggle.addEventListener('click', function () {
        const isOpen = widget.classList.toggle('is-open');
        iconOpen.style.display  = isOpen ? 'none' : 'block';
        iconClose.style.display = isOpen ? 'block' : 'none';
        if (isOpen) input.focus();
    });

    function addMessage(role, text) {
        const el = document.createElement('div');
        el.className = 'sp-chat-msg ' + (role === 'user' ? 'sp-chat-msg-user' : 'sp-chat-msg-bot');
        el.textContent = text;
        messages.appendChild(el);
        messages.scrollTop = messages.scrollHeight;
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        const text = input.value.trim();
        if (! text) return;

        addMessage('user', text);
        history.push({ role: 'user', content: text });
        input.value = '';
        typing.style.display = 'block';

        try {
            const response = await fetch('/api/chatbot', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    // Include the CSRF token if this route ends up sitting
                    // behind the 'web' middleware group instead of 'api' —
                    // harmless to send even if it's not needed.
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({ messages: history }),
            });

            const data = await response.json();
            const reply = data.reply || "Sorry, something went wrong — please try the contact form instead.";

            addMessage('assistant', reply);
            history.push({ role: 'assistant', content: reply });
        } catch (err) {
            addMessage('assistant', "Sorry, something went wrong — please try the contact form instead.");
        } finally {
            typing.style.display = 'none';
        }
    });
})();
</script>
