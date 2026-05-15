export function initializeTheme(): void {
    if (typeof window === 'undefined') {
        return;
    }

    const root = document.documentElement;

    root.classList.remove('dark');
    root.style.colorScheme = 'light';
    localStorage.removeItem('appearance');
    document.cookie = 'appearance=;path=/;max-age=0;SameSite=Lax';
}
