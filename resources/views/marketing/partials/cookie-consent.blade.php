<div x-data="{ show: !localStorage.getItem('cookies_accepted') }" x-show="show" x-transition
    class="fixed bottom-4 left-4 right-4 z-50 mx-auto max-w-xl rounded-xl border border-slate-700 bg-slate-900 p-4 text-sm text-slate-200 shadow-xl md:left-auto">
    <p>We use essential cookies for locale and theme preferences.</p>
    <button type="button" class="mt-3 rounded-lg bg-teal-600 px-3 py-1 text-white"
        x-on:click="localStorage.setItem('cookies_accepted','1'); show=false">Accept</button>
</div>
