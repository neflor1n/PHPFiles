<style>
    /* Основные стили */
    .menu {
        width: 25%;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .menu h3 {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 15px;
        color: #333;
    }

    .menu ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .menu ul li {
        margin: 10px 0;
    }

    .menu ul li a {
        text-decoration: none;
        color: #007bff;
        font-size: 16px;
        transition: color 0.3s ease;
    }

    .menu ul li a:hover {
        color: #0056b3;
    }

    /* Адаптивность для экранов менее 768px */
    @media (max-width: 768px) {
        .content-wrapper {
            flex-direction: column;
        }

        .menu {
            width: 75%;
            margin-top: 20px;
        }

        header h1 {
            font-size: 24px;
        }

        .menu h3 {
            font-size: 16px;
        }

        .menu ul li a {
            font-size: 14px;
        }
    }

    @media (max-width: 1000px) {
        header h1 {
            font-size: 20px;
        }

        .menu h3 {
            font-size: 14px;
        }

        .menu ul li a {
            font-size: 12px;
        }

        .menu {
            padding: 10px;
        }
    }

</style>

<div class="menu">
    <h3>Naljad</h3>
    <ul>
        <li><a href="index.php">Kodu</a></li>
        <li><a href="?anecdote=1">Programmeerija apteek</a></li>
        <li><a href="?anecdote=2">Testimine ja vigu</a></li>
        <li><a href="?anecdote=3">Muutujad ja ajalugu</a></li>
        <li><a href="?anecdote=4">Debugimine</a></li>
        <li><a href="?anecdote=5">Kommentaarid koodis</a></li>
        <li><a href="?anecdote=6">Veebimeistri nali</a></li>
        <li><a href="?anecdote=7">Programmerija ja naine</a></li>
    </ul>
</div>
