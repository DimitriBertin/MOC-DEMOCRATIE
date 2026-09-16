(function () {
  tinymce.PluginManager.add("balance_text", function (editor, url) {
    editor.on("init", function () {
      editor.formatter.register("balance-text", {
        inline: "span",
        styles: {
          "text-wrap": "balance",
          display: "inline-block",
        },
      });
    });

    var svgIcon =
      '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="22" height="22" viewBox="0 -960 960 960"><path d="m587-137.5-143-143 143-143 53 54-52 52h94.5q30 0 51.3-21.3T755-390q0-30-21.3-51.3t-51.2-21.2H170v-75h512.5q61.5 0 104.5 43T830-390q0 61.5-43 104.5t-104.5 43H588l52 52-53 53Zm-417-105v-75h199.5v75H170Zm0-439.5v-75h620v75H170Z"/></svg>';

    // Find parent balanced span of an element
    function findBalancedSpan(element) {
      while (element && element.nodeName !== "BODY") {
        if (
          element.nodeName === "SPAN" &&
          element.style &&
          element.style.textWrap === "balance" &&
          element.style.display === "inline-block"
        ) {
          return element;
        }
        element = element.parentNode;
      }
      return null;
    }

    // Find the parent block element
    function findParentBlock(element) {
      var blockElements = [
        "P",
        "H1",
        "H2",
        "H3",
        "H4",
        "H5",
        "H6",
        "DIV",
        "BLOCKQUOTE",
        "PRE",
        "LI",
      ];

      while (element && element.nodeName !== "BODY") {
        if (blockElements.indexOf(element.nodeName) !== -1) {
          return element;
        }
        element = element.parentNode;
      }

      // If no specific block element found, return the body
      return editor.getBody();
    }

    // Custom toggle function to handle both adding and removing correctly
    function toggleBalanceText() {
      var selection = editor.selection;
      var node = selection.getNode();

      // Check if we're inside a balanced span
      var balancedSpan = findBalancedSpan(node);

      if (balancedSpan) {
        // We are inside a balanced span, so we need to remove it
        var parent = balancedSpan.parentNode;
        var fragment = document.createDocumentFragment();

        // Move all children of the span to the fragment
        while (balancedSpan.firstChild) {
          fragment.appendChild(balancedSpan.firstChild);
        }

        // Insert all the contents before the span
        parent.insertBefore(fragment, balancedSpan);

        // Remove the now-empty span
        parent.removeChild(balancedSpan);

        // Ensure the selection is maintained
        selection.select(node);
      } else {
        // Find the parent block element
        var blockElement = findParentBlock(node);

        // Create a new span with balance styles
        var span = editor.dom.create("span", {
          style: "text-wrap: balance; display: inline-block;",
        });

        // Save the selection
        var bookmark = selection.getBookmark();

        // Move all children of the block into the span
        while (blockElement.firstChild) {
          span.appendChild(blockElement.firstChild);
        }

        // Add the span to the block element
        blockElement.appendChild(span);

        // Restore selection
        selection.moveToBookmark(bookmark);
      }
    }

    editor.addButton("balance-text", {
      title: "Balance Text",
      type: "button",
      icon: false,
      text: "",
      onclick: function () {
        toggleBalanceText();
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
        editor.on("NodeChange", function (e) {
          // Check if we're inside a balanced span
          var balancedSpan = findBalancedSpan(e.element);
          ctrl.active(!!balancedSpan);
        });
      },
    });
  });
})();
