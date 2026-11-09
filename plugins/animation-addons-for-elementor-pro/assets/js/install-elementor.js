document.addEventListener('DOMContentLoaded', () => {
  const installButton = document.getElementById('wcf-install-pro-elementor');
  if (installButton) {
    installButton.addEventListener('click', async e => {
      e.preventDefault();
      installButton.textContent = 'Installing...';
      installButton.disabled = true;
      try {
        const response = await fetch(wcfelementorAjax.ajax_url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: new URLSearchParams({
            action: 'wcf_pro_install_elementor_plugin',
            _ajax_nonce: wcfelementorAjax.nonce,
            slug: e.target.getAttribute('slug'),
            name: e.target.getAttribute('name')
          })
        });
        const result = await response.json();
        if (result.success) {
          installButton.textContent = 'Elementor Installed & Activated';
          window.location.reload(); // Reload the page to reflect changes
        } else {
          throw new Error(result.data.message || 'Installation failed.');
        }
      } catch (error) {
        installButton.textContent = 'Failed to Install';
        installButton.disabled = false;
        alert(error.message);
      }
    });
  }
});