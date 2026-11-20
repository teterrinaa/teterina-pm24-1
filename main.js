'use strict';


const modal = document.getElementById('auth-modal');
const forms = {
  register: document.getElementById('register-form'),
  login: document.getElementById('login-form'),
};
const tabButtons = modal.querySelectorAll('[data-tab]');
let lastFocused = null;

function switchTab(name) {
  Object.entries(forms).forEach(([key, form]) => { form.hidden = key !== name; });
  tabButtons.forEach((btn) => {
    const active = btn.dataset.tab === name;
    btn.classList.toggle('is-active', active);
    btn.setAttribute('aria-selected', String(active));
  });
}

function openModal(name) {
  lastFocused = document.activeElement;
  switchTab(name);
  modal.classList.add('is-open');
  modal.setAttribute('aria-hidden', 'false');
  document.body.classList.add('no-scroll');
  setTimeout(() => forms[name].querySelector('input').focus(), 260);
}

function closeModal() {
  modal.classList.remove('is-open');
  modal.setAttribute('aria-hidden', 'true');
  document.body.classList.remove('no-scroll');
  if (lastFocused) lastFocused.focus();
}

document.querySelectorAll('[data-open-modal]').forEach((el) =>
  el.addEventListener('click', () => openModal(el.dataset.openModal)));
modal.querySelectorAll('[data-close-modal]').forEach((el) =>
  el.addEventListener('click', closeModal));
tabButtons.forEach((btn) =>
  btn.addEventListener('click', () => switchTab(btn.dataset.tab)));
modal.addEventListener('mousedown', (e) => { if (e.target === modal) closeModal(); });
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && modal.classList.contains('is-open')) closeModal();
});

const rules = {
  name(value) {
    if (!value.trim()) return 'Введите имя';
    if (!/^[А-Яа-яЁё\s-]+$/.test(value)) return 'Только русские буквы, пробелы и дефисы';
    return '';
  },
  email(value) {
    if (!value.trim()) return 'Введите email';
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(value)) return 'Введите корректный email';
    return '';
  },
  phone(value) {
    if (!value.trim()) return 'Введите телефон';
    if (!/^[+\d\s()-]+$/.test(value)) return 'Недопустимые символы в номере';
    const digits = value.replace(/\D/g, '');
    if (!/^[78]9\d{9}$/.test(digits)) return 'Введите мобильный номер, например +7 912 345-67-89';
    return '';
  },
  password(value) {
    if (!value) return 'Введите пароль';
    if (value.length < 6) return 'Минимум 6 символов';
    if (/^\d+$/.test(value)) return 'Пароль не может состоять только из цифр';
    return '';
  },
  confirm(value, form) {
    if (!value) return 'Повторите пароль';
    if (value !== form.elements.password.value) return 'Пароли не совпадают';
    return '';
  },
  'login-password'(value) {
    return value ? '' : 'Введите пароль';
  },
};

function validateField(input) {
  const form = input.form;
  const message = rules[input.dataset.rule](input.value, form);
  input.setCustomValidity(message);
  const field = input.closest('.field');
  field.classList.toggle('is-invalid', !input.validity.valid);
  field.querySelector('.field__error').textContent = input.validationMessage;
  return input.validity.valid;
}

function validateForm(form) {
  let firstInvalid = null;
  form.querySelectorAll('[data-rule]').forEach((input) => {
    if (!validateField(input) && !firstInvalid) firstInvalid = input;
  });
  if (firstInvalid) firstInvalid.focus();
  return !firstInvalid;
}

function resetForm(form) {
  form.reset();
  form.querySelectorAll('.field').forEach((f) => {
    f.classList.remove('is-invalid');
    f.querySelector('.field__error').textContent = '';
  });
  form.querySelectorAll('[data-rule]').forEach((i) => i.setCustomValidity(''));
  const submit = form.querySelector('#reg-submit');
  if (submit) submit.disabled = true;
}

Object.values(forms).forEach((form) => {
  form.querySelectorAll('[data-rule]').forEach((input) => {
    input.addEventListener('blur', () => { if (input.value) validateField(input); });
    input.addEventListener('input', () => {
      if (input.closest('.field').classList.contains('is-invalid')) validateField(input);
      if (input.dataset.rule === 'password') {
        const confirm = form.elements.passwordConfirm;
        if (confirm && confirm.value) validateField(confirm);
      }
    });
  });
});

const agree = document.getElementById('reg-agree');
const regSubmit = document.getElementById('reg-submit');
agree.addEventListener('change', () => { regSubmit.disabled = !agree.checked; });

function collectData(form) {
  const data = Object.fromEntries(new FormData(form).entries());
  if ('agree' in data) data.agree = true;
  return data;
}

forms.register.addEventListener('submit', (e) => {
  e.preventDefault();
  if (!agree.checked) return;                  
  if (!validateForm(forms.register)) return;
  console.log('Регистрация:', collectData(forms.register));
  resetForm(forms.register);
  closeModal();
});

forms.login.addEventListener('submit', (e) => {
  e.preventDefault();
  if (!validateForm(forms.login)) return;
  console.log('Авторизация:', collectData(forms.login));
  resetForm(forms.login);
  closeModal();
});
