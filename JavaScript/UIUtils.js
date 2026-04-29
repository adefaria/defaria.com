function showModal(msg, inputToFocus) {
    var overlay = document.createElement('div');
    overlay.className = 'modal-overlay';
    
    var content = document.createElement('div');
    content.className = 'modal-content';
    
    // Simple message structure
    var msgP = document.createElement('p');
    msgP.innerText = msg;
    msgP.style.color = 'var(--text-color)'; // Ensure visibility
    
    var closeBtn = document.createElement('button');
    closeBtn.className = 'modal-btn';
    closeBtn.innerText = 'OK';
    
    // Handle Enter or Escape key to close
    var handleKey = function(e) {
        if (e.key === 'Enter' || e.key === 'Escape' || e.keyCode === 13 || e.keyCode === 27 || e.which === 13 || e.which === 27) {
            e.preventDefault();
            closeAndCleanup();
        }
    };
    document.addEventListener('keydown', handleKey);

    function closeAndCleanup() {
        if (document.body.contains(overlay)) {
            document.body.removeChild(overlay);
        }
        document.removeEventListener('keydown', handleKey);
        if (inputToFocus) {
            inputToFocus.value = '';
            inputToFocus.focus();
        }
    }

    // Cleanup listener on click close
    closeBtn.onclick = function() {
        closeAndCleanup();
    };
    
    content.appendChild(msgP);
    content.appendChild(closeBtn);
    overlay.appendChild(content);
    document.body.appendChild(overlay);
    
    // Close on overlay click
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            closeAndCleanup();
        }
    });

    closeBtn.focus();
}

function copyToClipboard(text) {
  var success = false;
  if (navigator.clipboard && window.isSecureContext) {
    navigator.clipboard.writeText(text).then(function() {
      showToast("Copied " + text + " to clipboard");
    }).catch(function(err) {
      console.error('Could not copy text: ', err);
    });
    return;
  } else {
    var textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.position = "fixed";
    textArea.style.left = "-999999px";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    try {
      document.execCommand('copy');
      success = true;
    } catch (err) {
      console.error('Fallback: Oops, unable to copy', err);
    }
    document.body.removeChild(textArea);
    if (success) {
      showToast("Copied " + text + " to clipboard");
    }
  }
}

function showToast(msg) {
  var toast = document.createElement("div");
  toast.innerText = msg;
  toast.style.position = "fixed";
  toast.style.bottom = "20px";
  toast.style.left = "50%";
  toast.style.transform = "translateX(-50%)";
  toast.style.backgroundColor = "var(--text-color, #333)";
  toast.style.color = "var(--bg-color, #fff)";
  toast.style.padding = "10px 20px";
  toast.style.borderRadius = "5px";
  toast.style.zIndex = "10000";
  toast.style.opacity = "0";
  toast.style.transition = "opacity 0.3s";
  document.body.appendChild(toast);
  
  // Trigger reflow
  void toast.offsetWidth;
  toast.style.opacity = "1";
  
  setTimeout(function() {
    toast.style.opacity = "0";
    setTimeout(function() {
      if (document.body.contains(toast)) {
        document.body.removeChild(toast);
      }
    }, 300);
  }, 2000);
}
