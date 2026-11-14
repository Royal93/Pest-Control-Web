{{--
    Notification bell for the admin portal and SP SALES.
    Pass: $bellUser (the logged-in admin or rep), $feedUrl, $readAllUrl, $tone ('dark' on the
    secondary-coloured mobile bar, 'light' on the page). resources/js/app.js renders the list,
    polls $feedUrl every 30 seconds, and keeps every bell on the page in sync.
--}}
@php($bellData = \App\Http\Controllers\NotificationController::payload($bellUser))
<div class="notif-bell relative"
     data-feed="{{ $feedUrl }}"
     data-read-all="{{ $readAllUrl }}"
     data-csrf="{{ csrf_token() }}"
     data-initial='@json($bellData)'>
    <button type="button"
            class="notif-btn relative w-11 h-11 flex items-center justify-center rounded-lg border {{ ($tone ?? 'light') === 'dark' ? 'border-white/30 text-white' : 'border-line bg-white text-ink hover:border-primary' }}"
            aria-label="Notifications" aria-expanded="false">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 0 0-4-5.66V5a2 2 0 1 0-4 0v.34A6 6 0 0 0 6 11v3.2c0 .53-.21 1.04-.6 1.4L4 17h5m6 0v1a3 3 0 1 1-6 0v-1m6 0H9"/>
        </svg>
        <span class="notif-count absolute -top-1.5 -right-1.5 min-w-[1.25rem] h-5 px-1 rounded-full bg-primary text-white text-[11px] font-bold leading-5 text-center {{ $bellData['unread'] > 0 ? '' : 'hidden' }}">
            {{ $bellData['unread'] > 99 ? '99+' : $bellData['unread'] }}
        </span>
    </button>

    <div class="notif-panel hidden fixed left-2 right-2 top-[4.5rem] md:absolute md:left-auto md:right-0 md:top-12 md:w-96 z-50 bg-white text-ink border-2 border-line rounded-xl shadow-xl overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 border-b border-line">
            <span class="font-display uppercase text-sm">Notifications</span>
            <button type="button" class="notif-read-all text-xs font-semibold text-primary disabled:text-inkFaint" @disabled($bellData['unread'] === 0)>Mark all as read</button>
        </div>
        <ul class="notif-list max-h-[70vh] overflow-y-auto"></ul>
    </div>
</div>
