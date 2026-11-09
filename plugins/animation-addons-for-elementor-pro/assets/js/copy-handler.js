/**
 * Animation Addons - Copy Handler
 * @version 2.0.0
 *
 * Handles the "Live Copy" button click and sends content to bridge
 * for cross-origin paste functionality
 */

(function() {
  'use strict';

  /**
   * Copy Handler Class
   */
  class AnimationAddonsCopyHandler {
    constructor() {
      // Get config from localized script
      const config = typeof aaeCopyConfig !== 'undefined' ? aaeCopyConfig : {};
      this.bridgeUrl = config.bridgeUrl || 'https://dev.animation-addons.com/bridge.html';
      this.apiEndpoint = config.apiUrl || '/wp-json/aae/v1/template';
      this.bridgeIframe = null;
      this.isBridgeReady = false;
      this.init();
    }

    /**
     * Initialize the handler
     */
    init() {
      // Wait for DOM to be ready
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => this.setup());
      } else {
        this.setup();
      }
    }

    /**
     * Setup event listeners and bridge
     */
    setup() {
      // Setup bridge iframe
      this.setupBridge();

      // Handle copy button clicks
      this.setupCopyHandlers();

      // Handle download button clicks
      this.setupDownloadHandlers();

      console.log('[AAE Copy Handler] Initialized');
    }

    /**
     * Setup bridge iframe
     */
    setupBridge() {
      // Check if iframe already exists
      this.bridgeIframe = document.getElementById('aae-bridge-iframe');

      if (!this.bridgeIframe) {
        this.bridgeIframe = document.createElement('iframe');
        this.bridgeIframe.id = 'aae-bridge-iframe';
        this.bridgeIframe.style.cssText = `
          position: fixed;
          top: -9999px;
          left: -9999px;
          width: 1px;
          height: 1px;
          opacity: 0;
          pointer-events: none;
          border: none;
        `;
        this.bridgeIframe.src = this.bridgeUrl;
        this.bridgeIframe.setAttribute('sandbox', 'allow-same-origin allow-scripts');
        this.bridgeIframe.setAttribute('title', 'Animation Addons Bridge');
        this.bridgeIframe.setAttribute('aria-hidden', 'true');

        document.body.appendChild(this.bridgeIframe);

        // Mark bridge as ready when iframe loads
        this.bridgeIframe.onload = () => {
          this.isBridgeReady = true;
          console.log('[AAE Copy Handler] Bridge ready');
        };

        // Setup message listener for bridge responses
        this.setupMessageListener();
      }
    }

    /**
     * Setup message listener for bridge communication
     */
    setupMessageListener() {
      window.addEventListener('message', (event) => {
        // Validate origin (should be from our bridge)
        try {
          const bridgeUrl = new URL(this.bridgeUrl);
          if (!event.origin || !event.origin.includes(bridgeUrl.hostname)) {
            return;
          }
        } catch (e) {
          return;
        }

        // Handle bridge ready message
        if (event.data.type === 'BRIDGE_READY') {
          this.isBridgeReady = true;
          console.log('[AAE Copy Handler] Bridge communication established');
        }
      });
    }

    /**
     * Setup copy button handlers
     */
    setupCopyHandlers() {
      // Use event delegation for dynamically added buttons
      document.addEventListener('click', (e) => {
        const copyButton = e.target.closest('.aae-live-copy-clickable');

        if (copyButton) {
          e.preventDefault();
          e.stopPropagation();

          const templateId = copyButton.getAttribute('data-id');
          if (templateId) {
            this.handleCopy(templateId, copyButton);
          }
        }
      });
    }

    /**
     * Setup download button handlers
     */
    setupDownloadHandlers() {
      document.addEventListener('click', (e) => {
        const downloadButton = e.target.closest('.aae-live-download-button');

        if (downloadButton) {
          e.preventDefault();
          e.stopPropagation();

          const templateId = downloadButton.getAttribute('data-id');
          if (templateId) {
            this.handleDownload(templateId, downloadButton);
          }
        }
      });
    }

    /**
     * Handle copy action
     */
    async handleCopy(templateId, buttonElement) {
      const wrapper = buttonElement.closest('.aae-live-copy-wrapper');

      try {
        // Show loading state
        this.setLoadingState(wrapper, true);

        // Fetch template data
        const templateData = await this.fetchTemplateData(templateId);

        if (!templateData.success) {
          throw new Error(templateData.error || 'Failed to fetch template');
        }

        // Copy to bridge
        const copyResult = await this.copyToBridge(
          templateData.data,
          { templateId, source: window.location.href }
        );

        if (copyResult.success) {
          // Show success notification
          this.showNotification(
            '✅ Content copied! You can now paste on any site.',
            'success'
          );

          // Update button state
          this.setCopiedState(wrapper, true);
        } else {
          throw new Error(copyResult.error || 'Failed to copy to bridge');
        }

      } catch (error) {
        console.error('[AAE Copy Handler] Copy failed:', error);
        this.showNotification(
          '❌ ' + (error.message || 'Failed to copy content'),
          'error'
        );
      } finally {
        // Remove loading state
        this.setLoadingState(wrapper, false);
      }
    }

    /**
     * Handle download action
     */
    async handleDownload(templateId, buttonElement) {
      try {
        // Show loading
        buttonElement.classList.add('aae-downloading');

        // Fetch template data
        const templateData = await this.fetchTemplateData(templateId);

        if (!templateData.success) {
          throw new Error(templateData.error || 'Failed to fetch template');
        }

        // Copy to bridge (for later pasting)
        const copyResult = await this.copyToBridge(
          templateData.data,
          { templateId, source: window.location.href, isDownload: true }
        );

        if (copyResult.success) {
          // Trigger file download
          this.downloadJsonFile(templateData.data, `template-${templateId}.json`);

          this.showNotification(
            '✅ Template downloaded and ready for pasting!',
            'success'
          );
        } else {
          throw new Error(copyResult.error || 'Failed to prepare for paste');
        }

      } catch (error) {
        console.error('[AAE Copy Handler] Download failed:', error);
        this.showNotification(
          '❌ ' + (error.message || 'Failed to download'),
          'error'
        );
      } finally {
        buttonElement.classList.remove('aae-downloading');
      }
    }

    /**
     * Fetch template data from WordPress API
     */
    async fetchTemplateData(templateId) {
      try {
        const response = await fetch(`${this.apiEndpoint}/${templateId}`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
          }
        });

        if (!response.ok) {
          if (response.status === 404) {
            return { success: false, error: 'Template not found' };
          }
          return { success: false, error: 'Failed to fetch template' };
        }

        const data = await response.json();

        if (data.success && data.data) {
          return { success: true, data: data.data };
        } else {
          return { success: false, error: data.message || 'Invalid response' };
        }

      } catch (error) {
        console.error('[AAE Copy Handler] API error:', error);
        return { success: false, error: error.message };
      }
    }

    /**
     * Copy content to bridge iframe
     */
    copyToBridge(content, metadata = {}) {
      return new Promise((resolve, reject) => {
        const requestId = Date.now() + Math.random();

        // Wait for bridge to be ready
        this.waitForBridge()
          .then(() => {
            // Setup message handler for this request
            const messageHandler = (event) => {
              // Validate origin
              try {
                const bridgeUrl = new URL(this.bridgeUrl);
                if (!event.origin || !event.origin.includes(bridgeUrl.hostname)) {
                  return;
                }
              } catch (e) {
                return;
              }

              // Check if this is our response
              if (event.data && event.data.requestId === requestId) {
                window.removeEventListener('message', messageHandler);

                if (event.data.type === 'COPY_SUCCESS') {
                  resolve({
                    success: true,
                    timestamp: event.data.timestamp
                  });
                } else if (event.data.type === 'ERROR') {
                  reject(new Error(event.data.error || 'Bridge operation failed'));
                } else {
                  reject(new Error('Unknown response from bridge'));
                }
              }
            };

            // Add message listener
            window.addEventListener('message', messageHandler);

            // Send copy command to bridge
            try {
              this.bridgeIframe.contentWindow.postMessage({
                type: 'COPY',
                requestId: requestId,
                content: content,
                metadata: {
                  ...metadata,
                  timestamp: Date.now(),
                  version: '2.0.0'
                }
              }, '*');

              // Set timeout
              setTimeout(() => {
                window.removeEventListener('message', messageHandler);
                reject(new Error('Bridge request timeout'));
              }, 15000);

            } catch (error) {
              window.removeEventListener('message', messageHandler);
              reject(new Error('Failed to send message to bridge'));
            }
          })
          .catch(reject);
      });
    }

    /**
     * Wait for bridge iframe to be ready
     */
    waitForBridge() {
      return new Promise((resolve, reject) => {
        // If already ready, resolve immediately
        if (this.isBridgeReady && this.bridgeIframe.contentWindow) {
          resolve();
          return;
        }

        // Wait for iframe to load
        if (this.bridgeIframe.contentWindow) {
          // Send ping to verify
          const pingHandler = (event) => {
            if (event.data && event.data.type === 'PONG') {
              window.removeEventListener('message', pingHandler);
              this.isBridgeReady = true;
              resolve();
            }
          };

          window.addEventListener('message', pingHandler);

          // Send ping
          try {
            this.bridgeIframe.contentWindow.postMessage({
              type: 'PING'
            }, '*');
          } catch (e) {
            window.removeEventListener('message', pingHandler);
            reject(new Error('Bridge not accessible'));
          }

          // Timeout fallback
          setTimeout(() => {
            window.removeEventListener('message', pingHandler);
            resolve(); // Assume ready after timeout
          }, 3000);
        } else {
          // Wait for iframe load event
          this.bridgeIframe.onload = () => {
            this.isBridgeReady = true;
            resolve();
          };

          // Fallback timeout
          setTimeout(() => {
            reject(new Error('Bridge initialization timeout'));
          }, 10000);
        }
      });
    }

    /**
     * Download JSON file
     */
    downloadJsonFile(data, filename) {
      const jsonString = JSON.stringify(data, null, 2);
      const blob = new Blob([jsonString], { type: 'application/json' });
      const url = URL.createObjectURL(blob);

      const link = document.createElement('a');
      link.href = url;
      link.download = filename;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);

      URL.revokeObjectURL(url);
    }

    /**
     * Set loading state on wrapper
     */
    setLoadingState(wrapper, isLoading) {
      if (!wrapper) return;

      if (isLoading) {
        wrapper.classList.add('aae-copying');
        wrapper.style.pointerEvents = 'none';
        wrapper.style.opacity = '0.7';
      } else {
        wrapper.classList.remove('aae-copying');
        wrapper.style.pointerEvents = '';
        wrapper.style.opacity = '';
      }
    }

    /**
     * Set copied state on wrapper
     */
    setCopiedState(wrapper, isCopied) {
      if (!wrapper) return;

      const icon = wrapper.querySelector('.aae-live-copy-icon');
      const buttonText = wrapper.querySelector('.aae-live-copy-button');

      if (isCopied) {
        wrapper.classList.add('aae-copied');
        if (icon) {
          icon.classList.add('copied');
        }
        if (buttonText) {
          buttonText.textContent = 'Copied!';
        }

        // Reset after 3 seconds
        setTimeout(() => {
          this.setCopiedState(wrapper, false);
        }, 3000);
      } else {
        wrapper.classList.remove('aae-copied');
        if (icon) {
          icon.classList.remove('copied');
        }
        if (buttonText) {
          buttonText.textContent = 'Live Copy';
        }
      }
    }

    /**
     * Show notification to user
     */
    showNotification(message, type = 'info') {
      // Remove existing notification
      const existing = document.querySelector('.aae-copy-notification');
      if (existing) {
        existing.remove();
      }

      // Create notification element
      const notification = document.createElement('div');
      notification.className = `aae-copy-notification aae-copy-notification-${type}`;
      notification.innerHTML = `
        <span class="aae-notification-message">${message}</span>
        <button class="aae-notification-close">&times;</button>
      `;

      // Add styles if not exists
      this.ensureNotificationStyles();

      document.body.appendChild(notification);

      // Setup close button
      const closeBtn = notification.querySelector('.aae-notification-close');
      if (closeBtn) {
        closeBtn.addEventListener('click', () => {
          notification.remove();
        });
      }

      // Auto remove after 5 seconds
      setTimeout(() => {
        if (notification.parentNode) {
          notification.style.animation = 'aae-slide-out 0.3s ease forwards';
          setTimeout(() => notification.remove(), 300);
        }
      }, 5000);
    }

    /**
     * Ensure notification styles are loaded
     */
    ensureNotificationStyles() {
      if (document.getElementById('aae-notification-styles')) {
        return;
      }

      const style = document.createElement('style');
      style.id = 'aae-notification-styles';
      style.textContent = `
        .aae-copy-notification {
          position: fixed;
          bottom: 24px;
          right: 24px;
          min-width: 300px;
          max-width: 400px;
          background: white;
          border-radius: 12px;
          box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
          z-index: 99999;
          display: flex;
          align-items: center;
          justify-content: space-between;
          padding: 16px 20px;
          animation: aae-slide-in 0.3s ease;
        }

        .aae-copy-notification-success {
          border-left: 4px solid #10b981;
        }

        .aae-copy-notification-error {
          border-left: 4px solid #ef4444;
        }

        .aae-copy-notification-info {
          border-left: 4px solid #3b82f6;
        }

        .aae-notification-message {
          font-size: 14px;
          color: #1f2937;
          font-weight: 500;
        }

        .aae-notification-close {
          background: none;
          border: none;
          font-size: 24px;
          color: #9ca3af;
          cursor: pointer;
          padding: 0;
          width: 24px;
          height: 24px;
          display: flex;
          align-items: center;
          justify-content: center;
          margin-left: 12px;
          transition: color 0.2s;
        }

        .aae-notification-close:hover {
          color: #374151;
        }

        @keyframes aae-slide-in {
          from {
            transform: translateX(100%);
            opacity: 0;
          }
          to {
            transform: translateX(0);
            opacity: 1;
          }
        }

        @keyframes aae-slide-out {
          from {
            transform: translateX(0);
            opacity: 1;
          }
          to {
            transform: translateX(100%);
            opacity: 0;
          }
        }

        @media (max-width: 768px) {
          .aae-copy-notification {
            right: 12px;
            left: 12px;
            bottom: 12px;
            min-width: auto;
            max-width: none;
          }
        }
      `;

      document.head.appendChild(style);
    }

    /**
     * Clear bridge content
     */
    async clearBridge() {
      return new Promise((resolve, reject) => {
        this.waitForBridge()
          .then(() => {
            const requestId = Date.now();

            const messageHandler = (event) => {
              if (event.data && event.data.requestId === requestId) {
                window.removeEventListener('message', messageHandler);
                resolve();
              }
            };

            window.addEventListener('message', messageHandler);

            try {
              this.bridgeIframe.contentWindow.postMessage({
                type: 'CLEAR',
                requestId: requestId
              }, '*');

              setTimeout(() => {
                window.removeEventListener('message', messageHandler);
                reject(new Error('Clear timeout'));
              }, 5000);
            } catch (error) {
              window.removeEventListener('message', messageHandler);
              reject(error);
            }
          })
          .catch(reject);
      });
    }

    /**
     * Get bridge status
     */
    async getBridgeStatus() {
      try {
        await this.waitForBridge();
        return {
          ready: this.isBridgeReady,
          url: this.bridgeUrl
        };
      } catch (error) {
        return {
          ready: false,
          error: error.message
        };
      }
    }
  }

  // Initialize the copy handler
  window.AAECopyHandler = new AnimationAddonsCopyHandler();

  // Expose utility functions
  window.aaeCopyHandler = {
    copy: (templateId) => window.AAECopyHandler.handleCopy(templateId, null),
    download: (templateId) => window.AAECopyHandler.handleDownload(templateId, null),
    clear: () => window.AAECopyHandler.clearBridge(),
    status: () => window.AAECopyHandler.getBridgeStatus()
  };

  console.log('[AAE Copy Handler] v2.0.0 loaded');

})();
