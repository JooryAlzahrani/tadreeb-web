/* =====================================================
   Tadreeb.js (Robust: works for all pages incl. login/register)
   ===================================================== */

const ENGLISH_CONTENT = {
  'nav-home': 'Home',
  'nav-internships': 'Internships',
  'nav-about': 'Who We Are',
  'nav-contact': 'Contact Us',
  'nav-login': 'Login',
  'nav-register': 'Register',
  'nav-admin': 'Admin',
  'nav-reviews': 'Reviews',

  'lang-en': 'English',
  'lang-ar': 'عربي',
  'mode-text': 'Dark Mode',

  'hero-h1': 'Welcome to Tadreeb',
  'hero-p1': 'The Centralized Internship Platform for Saudi University Students',
  'hero-p2': 'Verified internship opportunities with alumni guidance',

  'login-h2': 'Login',
  'register-h2': 'Register',
  'ph-email-auth': 'Enter your email',
  'ph-password-auth': 'Password',
  'ph-confirm-auth': 'Confirm Password',
  'ph-full-name': 'Full name',

  'btn-login': 'Login',
  'btn-register': 'Register',
  'btn-show': 'Show',
  'btn-hide': 'Hide',

  'link-no-account': 'Don’t have an account?',
  'link-has-account': 'Already have an account?',
  'link-register': 'Register',
  'link-login': 'Login',
};

// إذا عندك ARABIC_CONTENT أصلاً (مثل اللي أرسلتيه قبل) خلّيه كما هو.
// لو ما كان موجود، هذا الحد الأدنى عشان زر الترجمة يشتغل:
const ARABIC_CONTENT = window.ARABIC_CONTENT || {
  'nav-home': 'الرئيسية',
  'nav-internships': 'فرص التدريب',
  'nav-about': 'من نحن',
  'nav-contact': 'تواصل معنا',
  'nav-login': 'تسجيل الدخول',
  'nav-register': 'التسجيل',
  'nav-admin': 'الإدارة',
  'nav-reviews': 'المراجعات',

  'lang-en': 'English',
  'lang-ar': 'عربي',
  'mode-text': 'الوضع الليلي',

  'hero-h1': 'مرحباً بك في تدريب',
  'hero-p1': 'منصة تدريب موحّدة لطلاب الجامعات السعودية',
  'hero-p2': 'فرص تدريب موثوقة مع تجارب الطلاب',

  'login-h2': 'تسجيل الدخول',
  'register-h2': 'التسجيل',
  'ph-email-auth': 'أدخل بريدك الإلكتروني',
  'ph-password-auth': 'كلمة المرور',
  'ph-confirm-auth': 'تأكيد كلمة المرور',
  'ph-full-name': 'الاسم الكامل',

  'btn-login': 'دخول',
  'btn-register': 'تسجيل',
  'btn-show': 'إظهار',
  'btn-hide': 'إخفاء',

  'link-no-account': 'ليس لديك حساب؟',
  'link-has-account': 'لديك حساب بالفعل؟',
  'link-register': 'التسجيل',
  'link-login': 'تسجيل الدخول',
};

function ensureToggleMarkup(toggleEl, type) {
  if (!toggleEl) return;

  // إذا التوجّل فاضي (زي login/register) بنبني له نفس الشكل
  if (toggleEl.children.length === 0) {
    if (type === 'mode') {
      toggleEl.innerHTML = `
        <span class="toggle-track"><span class="toggle-slider"></span></span>
        <span class="mode-text" data-translate-key="mode-text">Dark Mode</span>
      `;
    } else if (type === 'lang') {
      toggleEl.innerHTML = `
        <span class="lang-text" data-translate-key="lang-en">English</span>
        <span class="toggle-track"><span class="toggle-slider"></span></span>
        <span class="lang-text" data-translate-key="lang-ar">عربي</span>
      `;
    }
  }
}

function applyTranslation(lang) {
  const dict = (lang === 'ar') ? ARABIC_CONTENT : ENGLISH_CONTENT;

  // dir + lang على الـ html
  document.documentElement.lang = (lang === 'ar') ? 'ar' : 'en';
  document.documentElement.dir  = (lang === 'ar') ? 'rtl' : 'ltr';

  // ترجمة النصوص + placeholders
  document.querySelectorAll('[data-translate-key]').forEach((el) => {
    const key = el.getAttribute('data-translate-key');
    const val = dict[key];
    if (!val) return;

    const tag = el.tagName.toLowerCase();

    // Inputs/Textareas: غالباً نترجم placeholder
    if ((tag === 'input' || tag === 'textarea') && el.hasAttribute('placeholder')) {
      el.setAttribute('placeholder', val);
      return;
    }

    // زر من نوع input (rare)
    if (tag === 'input' && (el.type === 'button' || el.type === 'submit') && el.hasAttribute('value')) {
      el.value = val;
      return;
    }

    // باقي العناصر: نترجم النص
    el.textContent = val;
  });

  // خزّن اللغة
  localStorage.setItem('tadreeb_lang', lang);
}

function setDarkMode(isDark) {
  document.body.classList.toggle('dark', !!isDark);
  localStorage.setItem('tadreeb_dark', isDark ? '1' : '0');

  const toggle = document.getElementById('dark-mode-toggle');
  if (toggle) toggle.setAttribute('data-mode', isDark ? 'dark' : 'light');
}

function initLanguageToggle() {
  const langToggle = document.getElementById('language-toggle');
  ensureToggleMarkup(langToggle, 'lang');

  if (!langToggle) return;

  const saved = localStorage.getItem('tadreeb_lang') || 'en';
  langToggle.setAttribute('data-lang', saved);
  applyTranslation(saved);

  langToggle.addEventListener('click', () => {
    const current = langToggle.getAttribute('data-lang') || 'en';
    const next = (current === 'en') ? 'ar' : 'en';
    langToggle.setAttribute('data-lang', next);
    applyTranslation(next);
  });
}

function initDarkModeToggle() {
  const modeToggle = document.getElementById('dark-mode-toggle');
  ensureToggleMarkup(modeToggle, 'mode');

  const saved = localStorage.getItem('tadreeb_dark');
  setDarkMode(saved === '1');

  if (!modeToggle) return;

  modeToggle.addEventListener('click', () => {
    const isDark = document.body.classList.contains('dark');
    setDarkMode(!isDark);
  });
}

// دعم show/hide password (للصفحات اللي تستخدم onclick="togglePassword(this)")
window.togglePassword = function (btn) {
  // يعمل مع span أو button (admin-login يستخدم button) :contentReference[oaicite:3]{index=3}
  const wrapper = btn.closest('.password-wrapper, .admin-password-row') || btn.parentElement;
  if (!wrapper) return;

  const input = wrapper.querySelector('input[type="password"], input[type="text"]');
  if (!input) return;

  const showNow = (btn.getAttribute('data-state') || 'show') === 'show';
  input.type = showNow ? 'text' : 'password';
  btn.setAttribute('data-state', showNow ? 'hide' : 'show');

  // ترجمة النص حسب اللغة الحالية
  const lang = localStorage.getItem('tadreeb_lang') || 'en';
  const dict = (lang === 'ar') ? ARABIC_CONTENT : ENGLISH_CONTENT;
  btn.textContent = showNow ? (dict['btn-hide'] || 'Hide') : (dict['btn-show'] || 'Show');
};

document.addEventListener('DOMContentLoaded', () => {
  initDarkModeToggle();
  initLanguageToggle();
});
