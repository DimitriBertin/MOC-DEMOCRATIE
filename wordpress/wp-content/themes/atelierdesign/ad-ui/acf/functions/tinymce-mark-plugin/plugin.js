(function () {
  tinymce.PluginManager.add("heading_highlight", function (editor, url) {
    editor.on("init", function () {
      editor.formatter.register("heading-highlight", {
        inline: "mark",
        classes: "heading-highlight",
      });
    });

    var svgIcon =
      '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 -960 960 960"><path d="M84.8-7v-111.9h790.3v112H84.9Zm467-450L449.6-559.4 301.2-410q-3.7 3.4-3.7 8.4t3.7 8.5l85.1 84.6q3.5 3.5 8.5 3.5t8.5-3.5L551.9-457Zm-62.3-142 102.1 102.2 187.7-187.6q3.5-3.5 3.5-8.8t-3.5-9l-85-85q-3.6-3.4-8.9-3.4-5.3 0-8.8 3.5l-187 188Zm-59.7-20.1 181.6 181.5-168 168.2Q422.6-249 394.1-249q-28.6 0-49.2-20.5l-5-5-37.4 36.6H157l110-110.7-4.2-4.4q-20.8-20.2-20.9-49-.2-28.8 20.4-49.4l167.5-167.8Zm0 0 209.1-209q20-20 48-19.6 28.1.4 47.7 20.5l84.7 85.1q20 20.6 20 48.6t-20 48.1l-208 207.8L430-619.1Z"/></svg>';

    var pendingBlockFormat = null;
    var headingFormatRegex = /^h[1-6]$/i;
    var cleanupTimer = null;
    var CLEANUP_DELAY_MS = 120;

    function normalizeBlockFormat(value) {
      if (value == null) return "";

      var normalized = value;

      if (typeof normalized === "object") {
        if (normalized.format) {
          normalized = normalized.format;
        } else if (normalized.tag) {
          normalized = normalized.tag;
        } else {
          try {
            normalized = normalized.toString();
          } catch (error) {
            normalized = "";
          }
        }
      }

      if (typeof normalized !== "string") {
        normalized = String(normalized);
      }

      return normalized.replace(/[<>]/g, "").trim().toLowerCase();
    }

    function scheduleHighlightCleanup() {
      cancelHighlightCleanup();
      cleanupTimer = setTimeout(function () {
        cleanupTimer = null;
        removeInvalidHighlights();
      }, CLEANUP_DELAY_MS);
    }

    function cancelHighlightCleanup() {
      if (cleanupTimer) {
        clearTimeout(cleanupTimer);
        cleanupTimer = null;
      }
    }

    function isHeadingElement(element) {
      return /^H[1-6]$/.test(element.nodeName || "");
    }

    // Find parent highlighted mark of an element
    function findHighlightMark(element) {
      while (element && element.nodeName !== "BODY") {
        if (
          element.nodeName === "MARK" &&
          element.className.indexOf("heading-highlight") !== -1
        ) {
          return element;
        }
        element = element.parentNode;
      }
      return null;
    }

    function findHeading(element) {
      while (element && element.nodeName !== "BODY") {
        if (isHeadingElement(element)) {
          return element;
        }
        element = element.parentNode;
      }
      return null;
    }

    function unwrapHighlightMark(highlightMark) {
      if (!highlightMark || !highlightMark.parentNode) {
        return;
      }

      var parent = highlightMark.parentNode;

      while (highlightMark.firstChild) {
        parent.insertBefore(highlightMark.firstChild, highlightMark);
      }

      parent.removeChild(highlightMark);
    }

    function removeInvalidHighlights() {
      var body = editor.getBody();
      if (!body) return;

      var marks = body.querySelectorAll("mark.heading-highlight");
      if (!marks.length) return;

      var invalidMarks = [];

      for (var i = 0; i < marks.length; i++) {
        var mark = marks[i];
        if (!findHeading(mark)) {
          invalidMarks.push(mark);
        }
      }

      if (!invalidMarks.length) return;

      editor.undoManager.transact(function () {
        editor.undoManager.ignore(function () {
          for (var i = 0; i < invalidMarks.length; i++) {
            unwrapHighlightMark(invalidMarks[i]);
          }
        });
      });
    }

    // Custom toggle function to handle both adding and removing correctly
    function toggleHighlight() {
      var selection = editor.selection;
      var node = selection.getNode();
      var heading = findHeading(node);

      if (!heading) {
        return;
      }

      // Check if we're inside a highlighted mark
      var highlightMark = findHighlightMark(node);

      if (highlightMark) {
        // We are inside a highlight mark, so we need to remove it
        var bookmark = selection.getBookmark(2, true);
        unwrapHighlightMark(highlightMark);
        if (bookmark) {
          selection.moveToBookmark(bookmark);
        }
      } else {
        // We are not in a highlight mark, so apply the formatting
        editor.formatter.apply("heading-highlight");
      }
    }

    editor.addButton("heading-highlight", {
      title: "Heading Highlight",
      type: "button",
      icon: false,
      text: "",
      onclick: function () {
        toggleHighlight();
      },
      onPostRender: function (e) {
        var btnElm = e.target.getEl();
        var iconElm = document.createElement("button");
        iconElm.innerHTML = svgIcon;
        iconElm.style.display = "inline-grid";
        iconElm.style.placeItems = "center";
        iconElm.style.width = "26px";
        iconElm.style.height = "24px";

        btnElm.innerHTML = "";
        btnElm.appendChild(iconElm);

        var ctrl = this;
        function updateButtonState(node) {
          var currentNode =
            node || (editor.selection && editor.selection.getNode());

          var isInHeading = !!(currentNode && findHeading(currentNode));
          ctrl.disabled(!isInHeading);
          btnElm.style.cursor = isInHeading ? "pointer" : "not-allowed";
          btnElm.style.pointerEvents = isInHeading ? "auto" : "none";
          btnElm.style.opacity = isInHeading ? "1" : "0.4";

          var highlightMark = currentNode && findHighlightMark(currentNode);
          ctrl.active(!!highlightMark && isInHeading);
        }

        editor.on("ExecCommand", function (event) {
          if (event.command === "FormatBlock") {
            pendingBlockFormat = normalizeBlockFormat(event.value);
          }

          if (
            event.command === "mceApplyTextcolor" ||
            event.command === "mceRemoveTextcolor"
          ) {
            pendingBlockFormat = normalizeBlockFormat(
              event.value || event.parent || event.commandValue
            );
          }
        });

        editor.on("NodeChange", function (e) {
          var currentNode =
            e.element || (editor.selection && editor.selection.getNode());
          var isInHeading = !!(currentNode && findHeading(currentNode));

          if (
            !isInHeading &&
            (!pendingBlockFormat ||
              !headingFormatRegex.test(pendingBlockFormat))
          ) {
            scheduleHighlightCleanup();
          } else {
            cancelHighlightCleanup();
          }

          pendingBlockFormat = null;
          updateButtonState(currentNode);
        });

        // Initialize button state
        updateButtonState();
      },
    });
  });
})();
