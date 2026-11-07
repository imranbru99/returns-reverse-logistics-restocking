import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.documentElement.classList.toggle(
    'dark',
    localStorage.getItem('theme') === 'dark'
        || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),
);

Alpine.start();
