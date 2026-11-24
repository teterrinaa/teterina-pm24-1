<?php
require __DIR__ . "/db.php";

$sql = "select id, title, created_at from posts order by created_at desc limit 15";

$result = $pdo->prepare($sql);
$result->execute();
//ассоциативный массив месяц, чтобы они отображались корректно
$months = [
    1 => "января", 2 => "февраля", 3 => "марта", 4 => "апреля",
    5 => "мая", 6 => "июня", 7 => "июля", 8 => "августа",
    9 => "сентября", 10 => "октября", 11 => "ноября", 12 => "декабря",
];
?>


<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Учебные материалы — Студенческий файлообменник</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap&subset=cyrillic" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <symbol id="i-plus" viewBox="0 0 20 20"><path d="M10 3v14M3 10h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/></symbol>
    <symbol id="i-arrow" viewBox="0 0 20 20"><path d="M5 8l5 5 5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/></symbol>
    <symbol id="i-close" viewBox="0 0 20 20"><path d="M4 4l12 12M16 4L4 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/></symbol>
    <symbol id="i-info" viewBox="0 0 20 20"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="2" fill="none"/><path d="M10 5.5v5.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><circle cx="10" cy="14" r="1.1" fill="currentColor"/></symbol>
    <symbol id="i-clock" viewBox="0 0 12 12"><circle cx="6" cy="6" r="5" stroke="currentColor" stroke-width="1.5" fill="none"/><path d="M6 3.2V6l1.8 1.2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/></symbol>
  </svg>

  <div class="container">

    <header class="header">
      <a href="/" class="logo" aria-label="Студенческий файлообменник">
        <span class="logo__icon" aria-hidden="true"><i></i><i></i></span>
        <span class="logo__text">Студенческий<br>файлообменник</span>
      </a>
      <nav class="header__nav" aria-label="Аккаунт">
        <button type="button" class="header__link" data-open-modal="register">Регистрация</button>
        <button type="button" class="header__link" data-open-modal="login">Вход</button>
      </nav>
    </header>

    <main>
      <div class="page-head">
        <h1 class="page-head__title">Учебные материалы</h1>
        <a href="add-post.html" class="btn btn--primary btn--add" aria-label="Добавить пост">
          <svg width="20" height="20" aria-hidden="true"><use href="#i-plus"/></svg>
          <span class="btn__text">Добавить пост</span>
        </a>
      </div>

    <ul class="cards">
      <?php foreach ($result as $post): ?>
        <?php $date = strtotime($post['created_at']); ?>
        <li>
          <article class="card">
            <p class="card__date">
              <svg class="card__clock" width="12" height="12" aria-hidden="true"><use href="#i-clock"/></svg>
              <span class="card__prefix">Добавлено </span><?= date('j', $date) . ' ' . $months[(int) date('n', $date)] ?>
            </p>
            <h2 class="card__title"><a href="/material.php?id=<?= $post['id'] ?>" class="card__link"><?= $post['title'] ?></a></h2>
            <a href="/material.php?id=<?= $post['id'] ?>" class="btn btn--outline btn--sm card__btn" tabindex="-1" aria-hidden="true">Подробнее</a>
          </article>
        </li>
      <?php endforeach; ?>
    </ul>

      <button type="button" class="btn btn--outline btn--more" id="show-more">
        <svg width="20" height="20" aria-hidden="true"><use href="#i-arrow"/></svg>
        Показать еще
      </button>
  </main>

    <footer class="footer">
      <a href="mailto:info@gmail.com" class="footer__mail">info@gmail.com</a>
      <a href="#" class="footer__dev">Информация о разработчике</a>
    </footer>

  </div>

  <div class="modal" id="auth-modal" aria-hidden="true">
    <div class="modal__dialog" role="dialog" aria-modal="true" aria-label="Регистрация и вход">
      <button type="button" class="modal__close" data-close-modal aria-label="Закрыть">
        <svg width="20" height="20" aria-hidden="true"><use href="#i-close"/></svg>
      </button>

      <div class="tabs" role="tablist">
        <button type="button" class="tabs__btn" role="tab" data-tab="register">Регистрация</button>
        <button type="button" class="tabs__btn" role="tab" data-tab="login">Авторизация</button>
      </div>

      <form class="form" id="register-form" data-form="register" novalidate hidden>
        <div class="form__grid form__grid--register">
          <div class="field field--name">
            <input class="field__input" type="text" id="reg-name" name="name" placeholder=" " autocomplete="name" data-rule="name" required>
            <label class="field__label" for="reg-name">Ваше имя</label>
            <span class="field__error" aria-live="polite"></span>
          </div>
          <div class="field field--email">
            <input class="field__input" type="email" id="reg-email" name="email" placeholder=" " autocomplete="email" data-rule="email" required>
            <label class="field__label" for="reg-email">Email</label>
            <span class="field__error" aria-live="polite"></span>
          </div>
          <div class="field field--phone">
            <input class="field__input" type="tel" id="reg-phone" name="phone" placeholder=" " autocomplete="tel" data-rule="phone" required>
            <label class="field__label" for="reg-phone">Мобильный телефон</label>
            <span class="field__error" aria-live="polite"></span>
          </div>
          <div class="field field--password">
            <input class="field__input" type="password" id="reg-password" name="password" placeholder=" " autocomplete="new-password" data-rule="password" required>
            <label class="field__label" for="reg-password">Пароль</label>
            <span class="field__error" aria-live="polite"></span>
          </div>
          <div class="field field--confirm">
            <input class="field__input" type="password" id="reg-confirm" name="passwordConfirm" placeholder=" " autocomplete="new-password" data-rule="confirm" required>
            <label class="field__label" for="reg-confirm">Повторите пароль</label>
            <span class="field__error" aria-live="polite"></span>
          </div>
        </div>

        <label class="check">
          <input type="checkbox" name="agree" id="reg-agree">
          <span class="check__box" aria-hidden="true"></span>
          <span>Согласен на обработку <a href="http://slavdom10.dev.skillum.ru/law/" target="_blank" rel="noopener">персональных данных</a></span>
        </label>

        <button type="submit" class="btn btn--primary btn--block form__submit" id="reg-submit" disabled>Зарегистрироваться</button>

        <p class="form__note">
          <svg width="20" height="20" aria-hidden="true"><use href="#i-info"/></svg>
          Все поля обязательны для заполнения
        </p>
      </form>

      <form class="form" id="login-form" data-form="login" novalidate hidden>
        <div class="form__grid form__grid--login">
          <div class="field">
            <input class="field__input" type="email" id="login-email" name="email" placeholder=" " autocomplete="email" data-rule="email" required>
            <label class="field__label" for="login-email">Email</label>
            <span class="field__error" aria-live="polite"></span>
          </div>
          <div class="field">
            <input class="field__input" type="password" id="login-password" name="password" placeholder=" " autocomplete="current-password" data-rule="login-password" required>
            <label class="field__label" for="login-password">Пароль</label>
            <span class="field__error" aria-live="polite"></span>
          </div>
        </div>

        <button type="submit" class="btn btn--primary btn--block form__submit">Войти</button>

        <p class="form__note">
          <svg width="20" height="20" aria-hidden="true"><use href="#i-info"/></svg>
          Все поля обязательны для заполнения
        </p>
      </form>
    </div>
  </div>

  <script src="main.js"></script>
</body>
</html>