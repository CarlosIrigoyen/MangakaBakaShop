<li class="nav-item">
    <button id="theme-toggle" class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center"
        style="width: 50px; height: 50px;">
        <i id="theme-icon" class="fas fa-sun"></i>
    </button>
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
    themeToggleButton.addEventListener('click', () => {
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

    /* Botón redondo con sombra */
    #theme-toggle {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: none;
    }

    #theme-toggle:hover {
        background-color: #007bff; /* Color de hover */
        color: #ffffff;
    }
</style>
