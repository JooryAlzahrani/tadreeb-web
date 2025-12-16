const ARABIC_CONTENT = {
  'login-h2': 'تسجيل الدخول',
  'register-h2': 'التسجيل في تدريب',
  'ph-email-auth': 'أدخل بريدك الإلكتروني',
  'ph-password-auth': 'كلمة المرور',
  'ph-confirm-auth': 'تأكيد كلمة المرور',
  'ph-full-name': 'الاسم الكامل',
  'btn-login': 'دخول',
  'btn-register': 'تسجيل',
  'link-no-account': 'ليس لديك حساب؟',
  'link-has-account': 'لديك حساب بالفعل؟',
  'link-register': 'التسجيل',
  'link-login': 'دخول',
  'btn-show': 'عرض',
  'btn-hide': 'إخفاء',
};

const ENGLISH_CONTENT = {
  'login-h2': 'Login',
  'register-h2': 'Register with Tadreeb',
  'ph-email-auth': 'Enter your email',
  'ph-password-auth': 'Password',
  'ph-confirm-auth': 'Confirm Password',
  'ph-full-name': 'Full name',
  'btn-login': 'Login',
  'btn-register': 'Register',
  'link-no-account': 'Don’t have an account?',
  'link-has-account': 'Already have an account?',
  'link-register': 'Register',
  'link-login': 'Login',
  'btn-show': 'Show',
  'btn-hide': 'Hide',
};

document.addEventListener('DOMContentLoaded', () => {
  const darkModeToggle = document.getElementById('dark-mode-toggle');
  const languageToggle = document.getElementById('language-toggle');
  const body = document.body;
  const html = document.documentElement;

  const isDarkMode = localStorage.getItem('darkMode') === 'enabled';
  if (isDarkMode) body.classList.add('dark-mode');

  function updateDarkModeIcon(isDark) {
    if (!darkModeToggle) return;
    darkModeToggle.setAttribute('data-mode', isDark ? 'dark' : 'light');
  }

  function updatePasswordToggleText(lang, isShow) {
    const content = lang === 'ar' ? ARABIC_CONTENT : ENGLISH_CONTENT;
    return isShow ? content['btn-show'] : content['btn-hide'];
  }

  function setLanguage(lang) {
    const content = lang === 'ar' ? ARABIC_CONTENT : ENGLISH_CONTENT;
    html.lang = lang;
    html.dir = (lang === 'ar') ? 'rtl' : 'ltr';

    document.querySelectorAll('[data-translate-key]').forEach(el => {
      const key = el.getAttribute('data-translate-key');
      if (!content[key]) return;

      if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') el.placeholder = content[key];
      else el.textContent = content[key];
    });

    document.querySelectorAll('.password-toggle').forEach(btn => {
      const isShowing = btn.dataset.state === 'show';
      btn.textContent = updatePasswordToggleText(lang, isShowing);
    });

    const logoImg = document.querySelector('.auth-logo img');
    if (logoImg) logoImg.alt = (lang === 'ar') ? 'شعار تدريب' : 'Tadreeb Logo';

    localStorage.setItem('language', lang);
    updateDarkModeIcon(body.classList.contains('dark-mode'));
  }

  if (languageToggle) {
    languageToggle.addEventListener('click', () => {
      const currentLang = html.lang || 'en';
      setLanguage(currentLang === 'en' ? 'ar' : 'en');
    });
  }

  if (darkModeToggle) {
    darkModeToggle.addEventListener('click', () => {
      body.classList.toggle('dark-mode');
      const isDark = body.classList.contains('dark-mode');
      localStorage.setItem('darkMode', isDark ? 'enabled' : 'disabled');
      updateDarkModeIcon(isDark);
    });
  }

  window.togglePassword = function(btn) {
    const input = btn.previousElementSibling;
    const lang = html.lang || 'en';

    if (input.type === 'password') {
      input.type = 'text';
      btn.textContent = updatePasswordToggleText(lang, false);
      btn.dataset.state = 'hide';
    } else {
      input.type = 'password';
      btn.textContent = updatePasswordToggleText(lang, true);
      btn.dataset.state = 'show';
    }
  };

  setLanguage(localStorage.getItem('language') || 'en');
  updateDarkModeIcon(body.classList.contains('dark-mode'));
});
