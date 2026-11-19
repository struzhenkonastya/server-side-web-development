(() => {
  'use strict';

  function localDigits(text) {
    let digits = String(text).replace(/\D/g, '');
    // Префикс +7, 7 или 8 не относится к 10 цифрам номера.
    if (/^[78]/.test(digits) && /^[\s(+]*[78]/.test(String(text).trimStart())) {
      digits = digits.slice(1);
    }
    return digits.slice(0, 10);
  }

  function format(local, hasInput) {
    if (!hasInput) return '';
    if (!local.length) return '+7 ';
    let result = '+7 (' + local.slice(0, 3);
    if (local.length >= 3) result += ')';
    if (local.length > 3) result += ' ' + local.slice(3, 6);
    if (local.length > 6) result += '-' + local.slice(6, 8);
    if (local.length > 8) result += '-' + local.slice(8, 10);
    return result;
  }

  function positionAfterDigits(text, n) {
    if (n <= 0) return text.includes('(') ? text.indexOf('(') + 1 : text.length;
    let count = 0;
    // +7 — префикс, поэтому отсчёт цифр с позиции 2.
    for (let pos = 2; pos < text.length; pos++) {
      if (/\d/.test(text[pos])) {
        count++;
        if (count >= n) return pos + 1;
      }
    }
    return text.length;
  }

  for (const field of document.querySelectorAll('input[data-phone-mask]')) {
    const check = () => {
      const digits = localDigits(field.value);
      field.setCustomValidity(field.value && digits.length !== 10
        ? 'Введите 10 цифр номера после +7 или 8' : '');
    };

    function applyFormat() {
      const raw = field.value;
      const caret = field.selectionStart ?? raw.length;
      const before = localDigits(raw.slice(0, caret)).length;
      const formatted = format(localDigits(raw), raw.trim().length > 0);
      field.value = formatted;
      try {
        const newCaret = positionAfterDigits(formatted, before);
        field.setSelectionRange(newCaret, newCaret);
      } catch (_) { /* На некоторых мобильных клавиатурах selectionRange недоступен. */ }
      check();
    }

    if (field.value.trim()) {
      field.value = format(localDigits(field.value), true);
    }
    check();
    field.addEventListener('input', applyFormat);
    field.addEventListener('blur', () => {
      if (localDigits(field.value).length === 0) field.value = '';
      check();
    });
  }
})();
