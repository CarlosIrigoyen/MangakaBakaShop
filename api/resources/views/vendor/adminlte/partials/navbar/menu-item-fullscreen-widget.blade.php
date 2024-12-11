<li class="nav-item">
    <a id="theme-toggle" class="nav-link" href="#" role="button">
        <i id="theme-icon" class="fas fa-sun"></i>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
    </a>
</li>

<script>
    const body = document.body;
    const themeToggleButton = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    const storedTheme = localStorage.getItem('theme');

    // Configurar tema inicial
    if (storedTheme) {
        body.classList.add(storedTheme);
        themeIcon.className = storedTheme === 'dark-mode' ? 'fas fa-moon' : 'fas fa-sun';
    }

    // Alternar tema
    themeToggleButton.addEventListener('click', (e) => {
        e.preventDefault(); // Prevenir recarga
        if (body.classList.contains('dark-mode')) {
            body.classList.remove('dark-mode');
            localStorage.setItem('theme', 'light-mode');
            themeIcon.className = 'fas fa-sun';
        } else {
            body.classList.add('dark-mode');
            localStorage.setItem('theme', 'dark-mode');
            themeIcon.className = 'fas fa-moon';
        }
    });
</script>

<style>
    /* Modo claro (por defecto) */
    body {
        background-color: #ffffff;
        color: #000000;
        transition: background-color 0.3s, color 0.3s;
    }

    /* Modo oscuro */
    body.dark-mode {
        background-color: #121212;
        color: #ffffff;
    }

    /* Botón alineado y del mismo tamaño */
    #theme-toggle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }

    #theme-toggle:hover {
        background-color: rgba(0, 0, 0, 0.1); /* Fondo de hover */
    }
</style>
