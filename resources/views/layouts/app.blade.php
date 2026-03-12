<x-layouts.base>
    @auth
        <div class="app-shell">
            @include('layouts.navbars.auth.nav')
            @include('layouts.navbars.auth.sidebar')

            <main class="app-workspace">
                <div class="app-shell-inner">
                    <div class="app-content-stack">
                        {{ $slot }}
                        @include('layouts.footers.auth.footer')
                    </div>
                </div>
            </main>
        </div>

        <script>
            if (!window.__colegiadosSidebarInit) {
                window.__colegiadosSidebarInit = true;

                (() => {
                    const root = document.documentElement;
                    const storageKey = 'colegiados.sidebar.open';

                    const syncSidebarState = () => {
                        const isOpen = window.localStorage.getItem(storageKey) === '1';
                        root.classList.toggle('app-sidebar-open', isOpen);
                    };

                    const closeSidebar = () => {
                        window.localStorage.setItem(storageKey, '0');
                        syncSidebarState();
                    };

                    const openUserModal = () => {
                        root.classList.add('app-user-modal-open');
                    };

                    const closeUserModal = () => {
                        root.classList.remove('app-user-modal-open');
                    };

                    const toggleSidebar = () => {
                        const isOpen = window.localStorage.getItem(storageKey) === '1';
                        window.localStorage.setItem(storageKey, isOpen ? '0' : '1');
                        syncSidebarState();
                    };

                    document.addEventListener('click', (event) => {
                        const toggle = event.target.closest('[data-sidebar-toggle]');
                        const dismiss = event.target.closest('[data-sidebar-dismiss]');
                        const sidebarLink = event.target.closest('.app-sidebar-panel a[wire\\:navigate]');
                        const userToggle = event.target.closest('[data-user-modal-toggle]');
                        const userDismiss = event.target.closest('[data-user-modal-dismiss]');
                        const userLink = event.target.closest('.app-user-modal-panel a[wire\\:navigate]');

                        if (toggle) {
                            event.preventDefault();
                            toggleSidebar();
                            return;
                        }

                        if (dismiss) {
                            event.preventDefault();
                            closeSidebar();
                            return;
                        }

                        if (userToggle) {
                            event.preventDefault();
                            openUserModal();
                            return;
                        }

                        if (userDismiss) {
                            event.preventDefault();
                            closeUserModal();
                            return;
                        }

                        if (sidebarLink) {
                            closeSidebar();
                        }

                        if (userLink) {
                            closeUserModal();
                        }
                    });

                    document.addEventListener('keydown', (event) => {
                        if (event.key === 'Escape') {
                            closeSidebar();
                            closeUserModal();
                        }
                    });

                    document.addEventListener('DOMContentLoaded', () => {
                        syncSidebarState();
                        closeUserModal();
                    });
                    document.addEventListener('livewire:navigated', () => {
                        syncSidebarState();
                        closeUserModal();
                    });
                })();
            }
        </script>
    @endauth

    @guest
        @include('layouts.navbars.guest.nav')
        {{ $slot }}
        @include('layouts.footers.guest.footer')
    @endguest
</x-layouts.base>
