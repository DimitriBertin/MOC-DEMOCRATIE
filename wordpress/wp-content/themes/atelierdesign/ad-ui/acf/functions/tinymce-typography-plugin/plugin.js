(function () {
  tinymce.PluginManager.add("typography_selector", function (editor) {
    var DEFAULT_TYPOGRAPHY_CONFIG =
      (window && window.typographySelectorDefaultConfig) || [];

    function cloneConfig(config) {
      try {
        return JSON.parse(JSON.stringify(config));
      } catch (error) {
        return DEFAULT_TYPOGRAPHY_CONFIG;
      }
    }

    function resolveTypographyConfig() {
      var config = editor.getParam("typography_selector_config");

      if (!config && window.typographySelectorConfig) {
        config = window.typographySelectorConfig;
      }

      if (typeof config === "string") {
        try {
          config = JSON.parse(config);
        } catch (error) {
          config = null;
        }
      }

      if (!Array.isArray(config) || !config.length) {
        config = DEFAULT_TYPOGRAPHY_CONFIG;
      }

      return cloneConfig(config);
    }

    var TYPOGRAPHY_CONFIG = resolveTypographyConfig();

    var MENU_STYLE_ID = "typography-selector-menu-style";

    function ensureMenuStyles() {
      if (typeof document === "undefined") return;
      if (document.getElementById(MENU_STYLE_ID)) return;

      var style = document.createElement("style");
      style.id = MENU_STYLE_ID;
      style.type = "text/css";
      style.appendChild(
        document.createTextNode(
          [
            ".mce-menu-item .mce-caret { border-left: 4px solid currentColor !important; }",
            ".mce-menu .mce-menu-item.mce-active .mce-caret, .mce-menu .mce-menu-item.mce-active:focus .mce-caret, .mce-menu .mce-menu-item.mce-active:hover .mce-caret { border-left-color: #FFF !important; }",
            ".mce-menu .mce-menu-item.mce-selected:not(.mce-active) .mce-caret, .mce-menu .mce-menu-item.mce-selected:not(.mce-active):focus .mce-caret, .mce-menu .mce-menu-item.mce-selected:not(.mce-active):hover .mce-caret { border-left-color: #111827 !important; }",
            ".mce-menu .mce-carret { border-left-color: curentColor !important; }",
            ".mce-menu .typography-menu-item:hover:not(.mce-active),",
            ".mce-menu .typography-menu-item.mce-selected:not(.mce-active),",
            ".mce-menu .typography-menu-submenu:hover:not(.mce-active),",
            ".mce-menu .mce-menu-item.mce-selected:not(.mce-active), .mce-menu .mce-menu-item:not(.mce-active):hover,",
            ".mce-menu .typography-menu-submenu.mce-selected:not(.mce-active){",
            "background-color:#f3f4f6 !important;",
            "color:#111827 !important;",
            "}",
            ".mce-menu .typography-menu-item:hover:not(.mce-active) .mce-text,",
            ".mce-menu .typography-menu-item.mce-selected:not(.mce-active) .mce-text,",
            ".mce-menu .typography-menu-submenu:hover:not(.mce-active) .mce-text,",
            ".mce-menu .typography-menu-submenu.mce-selected:not(.mce-active) .mce-text{",
            "color:#111827 !important;",
            "}",
          ].join("")
        )
      );

      document.head.appendChild(style);
    }

    ensureMenuStyles();

    function eachFormat(callback) {
      TYPOGRAPHY_CONFIG.forEach(function (entry) {
        if (entry.items && Array.isArray(entry.items)) {
          entry.items.forEach(callback);
        } else {
          callback(entry);
        }
      });
    }

    var FORMAT_KEYS = [];
    var DEFAULT_FORMAT_KEY = null;
    eachFormat(function (item) {
      FORMAT_KEYS.push(item.key);
      if (item.default === true && !DEFAULT_FORMAT_KEY) {
        DEFAULT_FORMAT_KEY = item.key;
      }
    });

    var formatsRegistered = false;
    var editorIsReady = false;

    function setControlActive(ctrl, state) {
      ctrl.active(state);
      if (ctrl.getEl) {
        var el = ctrl.getEl();
        if (el) {
          if (state) {
            el.classList.add("mce-active");
          } else {
            el.classList.remove("mce-active");
          }
        }
      }
    }

    function registerFormats() {
      if (formatsRegistered) return;
      formatsRegistered = true;
      eachFormat(function (item) {
        editor.formatter.register(item.key, item.format);
      });
    }

    editor.on("init", function () {
      editorIsReady = true;
      if (editor.formatter && editor.formatter.register) {
        registerFormats();
      }

      // Set up auto-apply default format for plain paragraphs
      if (DEFAULT_FORMAT_KEY) {
        editor.on("NodeChange", function (e) {
          // Small delay to ensure the node is properly set up
          setTimeout(function () {
            applyDefaultFormatIfNeeded();
          }, 10);
        });

        // Also apply on focus if needed
        editor.on("focus", function () {
          setTimeout(function () {
            applyDefaultFormatIfNeeded();
          }, 10);
        });
      }
    });

    function ensureFormats() {
      if (formatsRegistered) return true;
      if (!editorIsReady) return false;
      if (!editor.formatter || !editor.formatter.register) return false;
      registerFormats();
      return true;
    }

    function clearOtherFormats(activeKey) {
      if (!ensureFormats()) return;
      FORMAT_KEYS.forEach(function (key) {
        if (key === activeKey) return;
        editor.formatter.remove(key);
      });
    }

    function isFormatActive(format) {
      if (!ensureFormats()) return false;
      return editor.formatter.match(format);
    }

    function isAnyFormatActive() {
      if (!ensureFormats()) return false;
      var active = false;

      for (var i = 0; i < FORMAT_KEYS.length; i++) {
        if (editor.formatter.match(FORMAT_KEYS[i])) {
          active = true;
          break;
        }
      }

      return active;
    }

    function isPlainParagraph(node) {
      if (!node) return false;
      if (node.nodeName !== "P") return false;

      // Check if the paragraph has no classes or only has alignment classes
      var className = node.className || "";
      var hasTypographyClass = false;

      // Check if any of our typography classes are present
      var classes = className.split(" ").filter(function (c) {
        return c.trim().length > 0;
      });

      for (var i = 0; i < classes.length; i++) {
        var cls = classes[i];
        // Ignore alignment and other non-typography classes
        if (
          cls !== "alignleft" &&
          cls !== "aligncenter" &&
          cls !== "alignright" &&
          cls !== "alignjustify"
        ) {
          hasTypographyClass = true;
          break;
        }
      }

      return !hasTypographyClass;
    }

    function applyDefaultFormatIfNeeded() {
      if (!DEFAULT_FORMAT_KEY) return;
      if (!ensureFormats()) return;
      if (isAnyFormatActive()) return;

      var node = editor.selection.getNode();
      if (isPlainParagraph(node)) {
        editor.undoManager.transact(function () {
          editor.formatter.apply(DEFAULT_FORMAT_KEY);
        });
      }
    }

    function toggleFormat(format) {
      if (!ensureFormats()) {
        editor.on("init", function handle() {
          editor.off("init", handle);
          toggleFormat(format);
        });
        return;
      }

      // If the clicked format is already active, do nothing to avoid reverting to default
      var currentlyActive = editor.formatter.match(format);
      if (currentlyActive) {
        return;
      }

      editor.undoManager.transact(function () {
        editor.focus();
        clearOtherFormats(format);
        editor.formatter.remove(format);
        editor.formatter.apply(format);
      });
    }

    function setupToggle(menuItem, format) {
      var ctrl = menuItem;

      function attach() {
        if (!ensureFormats()) return;

        function handler(state) {
          setControlActive(ctrl, state);
        }

        editor.formatter.formatChanged(format, handler);

        ctrl.on("remove", function () {
          editor.formatter.formatChanged(format, handler, true);
        });

        handler(isFormatActive(format));
      }

      if (!ensureFormats()) {
        editor.on("init", function once() {
          editor.off("init", once);
          attach();
        });
      } else {
        attach();
      }
    }

    function setupParentToggle(menuItem, keys) {
      var ctrl = menuItem;

      function attach() {
        if (!ensureFormats()) return;

        function handler() {
          var isActive = false;
          for (var i = 0; i < keys.length; i++) {
            if (isFormatActive(keys[i])) {
              isActive = true;
              break;
            }
          }
          setControlActive(ctrl, isActive);
        }

        keys.forEach(function (key) {
          editor.formatter.formatChanged(key, handler);
        });

        ctrl.on("remove", function () {
          keys.forEach(function (key) {
            editor.formatter.formatChanged(key, handler, true);
          });
        });

        handler();
      }

      if (!ensureFormats()) {
        editor.on("init", function once() {
          editor.off("init", once);
          attach();
        });
      } else {
        attach();
      }
    }

    function createMenuItem(item) {
      return {
        text: item.text,
        onclick: function () {
          toggleFormat(item.key);
        },
        onPostRender: function () {
          var el = this.getEl();
          if (el) {
            el.classList.add("typography-menu-item");
          }
          setupToggle(this, item.key);
        },
      };
    }

    function createMenu() {
      return TYPOGRAPHY_CONFIG.map(function (entry) {
        if (entry.items && Array.isArray(entry.items)) {
          return {
            text: entry.text,
            menu: entry.items.map(createMenuItem),
            onPostRender: function () {
              var el = this.getEl();
              if (el) {
                el.classList.add("typography-menu-submenu");
              }
              setupParentToggle(
                this,
                entry.items.map(function (item) {
                  return item.key;
                })
              );
            },
          };
        }

        return createMenuItem(entry);
      });
    }

    editor.addButton("typography-selector", {
      text: "Typography",
      tooltip: "Typography Styles",
      type: "menubutton",
      icon: false,
      menu: createMenu(),
      onPostRender: function () {
        var ctrl = this;
        var updateState = function () {
          if (!editor.hasFocus()) {
            setControlActive(ctrl, false);
            return;
          }

          setControlActive(ctrl, isAnyFormatActive());
        };

        setControlActive(ctrl, false);

        editor.on("NodeChange", updateState);
        editor.on("focus", updateState);
        editor.on("blur", updateState);

        ctrl.on("remove", function () {
          editor.off("NodeChange", updateState);
          editor.off("focus", updateState);
          editor.off("blur", updateState);
        });

        updateState();
      },
    });
  });
})();
