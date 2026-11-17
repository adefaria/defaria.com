(function () {
  if (window.top === window.self && window.location.search.indexOf('standalone=1') === -1) {
    var currentUrl = window.location.pathname + window.location.search + window.location.hash;
    window.location.replace('/?url=' + encodeURIComponent(currentUrl));
    return;
  }

  function detectTheme() {
    var t = null;
    try {
      if (window.parent && window.parent.document && window.self !== window.top) {
        t = window.parent.document.documentElement.getAttribute('data-theme');
      }
    } catch (e) {}
    if (!t) {
      try { t = localStorage.getItem('user_theme_override'); } catch (e) {}
    }
    if (!t) {
      try {
        var m = document.cookie.match(/(^| )user_theme_override=([^;]+)/);
        if (m) t = m[2];
      } catch (e) {}
    }
    if (!t && window.matchMedia) {
      t = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }
    return t || 'dark';
  }

  document.documentElement.setAttribute('data-theme', detectTheme());

  window.addEventListener('message', function (event) {
    if (event.data && event.data.type === 'themeChange' && event.data.theme) {
      document.documentElement.setAttribute('data-theme', event.data.theme);
    }
  });

  if (window.self !== window.top) {
    try {
      var obs = new MutationObserver(function () {
        try {
          var pt = window.parent.document.documentElement.getAttribute('data-theme');
          if (pt) document.documentElement.setAttribute('data-theme', pt);
        } catch (e) {}
      });
      obs.observe(window.parent.document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
    } catch (e) {}
  }

  window.addEventListener('DOMContentLoaded', function () {
    if (window.self !== window.top) {
      try {
        if (document.title) window.parent.document.title = document.title;
        var footerDate = window.parent.document.getElementById('footer-mod-date');
        var meta = document.querySelector('meta[name="last-modified"]');
        var dateStr = meta ? meta.getAttribute('content') : document.lastModified;
        if (footerDate && dateStr) {
          var pageName = document.title.replace(' - Andrew DeFaria', '');
          footerDate.textContent = pageName + ': Last modified ' + dateStr;
        }
      } catch (e) {}
    }
  });
})();

window.playBandTrack = function (src, title, rowEl) {
  var audio = document.getElementById('band-audio-player');
  var label = document.getElementById('band-now-playing-title');
  if (!audio) return false;

  var rows = document.querySelectorAll('.band-track-row');
  for (var i = 0; i < rows.length; i++) {
    rows[i].classList.remove('playing');
  }
  if (rowEl) {
    rowEl.classList.add('playing');
  }

  if (label) {
    label.textContent = title;
  }
  audio.src = src;
  audio.play().catch(function () {});
  return false;
};
