import Lenis from "lenis";

// Check if the current device is a desktop
var isDesktop =
  !/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
    navigator.userAgent
  ) && /Mac|Win|Linux/i.test(navigator.platform);

// Check if the current device is not a Mac
var isNotMac = !/Mac/i.test(navigator.platform);

// Run code only on desktop devices which are not Mac
// if (isDesktop && isNotMac) {
const lenis = new Lenis({
  smoothTouch: false,
  smoothWheel: true,
  autoRaf: true,
});

if (!isDesktop) {
  lenis.destroy();
}

// When virtual-scroll starts, add class to html, when it stops, remove class
const html = document.querySelector("html");
let virtualScrollingTimeout = null;
lenis.on("virtual-scroll", (e) => {
  if (virtualScrollingTimeout !== null) {
    clearTimeout(virtualScrollingTimeout);
  }

  html.classList.add("lenis-virtual-scrolling");

  virtualScrollingTimeout = setTimeout(() => {
    html.classList.remove("lenis-virtual-scrolling");
  }, 100);
});

// Disable and re-enable lenis on click
window.addEventListener("click", function (e) {
  if (!e.target.closest("header")) {
    if (isDesktop) {
      lenis.stop();
      lenis.start();
    }
  }
});

// Mutation observer to check if #mobileMenu has class of .active
// if so, then temporarily disable lenis
const header = document.querySelector("header");
if (header !== null) {
  const observer = new MutationObserver(function (mutations) {
    mutations.forEach(function (mutationRecord) {
      setTimeout(() => {
        if (isDesktop) {
          if (mutationRecord.target.classList.contains("mobile-menu-active")) {
            lenis.stop();
          } else {
            lenis.start();
          }
        } else {
          if (mutationRecord.target.classList.contains("mobile-menu-active")) {
            document
              .querySelectorAll("html, body")
              .forEach((el) => el.classList.add("!overflow-hidden"));
          } else {
            document
              .querySelectorAll("html, body")
              .forEach((el) => el.classList.remove("!overflow-hidden"));
          }
        }
      }, 100);
    });
  });

  observer.observe(header, {
    attributes: true,
    attributeFilter: ["class"],
  });
}

// Clicking into any iframe should disable lenis
const iframes = document.querySelectorAll("iframe");
if (iframes !== null) {
  iframes.forEach(function (iframe) {
    try {
      if (iframe.contentWindow === null) return;
      if (iframe.contentWindow.document === null) return;
      if (iframe.contentWindow.document.body === null) return;

      iframe.contentWindow.document.body.addEventListener("click", function () {
        if (isDesktop) {
          lenis.stop();
          lenis.start();
        }
      });
    } catch (error) {
      // Silently catch cross-origin access errors
      // This happens when iframe content is from a different domain
      console.warn(
        "Cross-origin iframe detected, skipping lenis integration:",
        iframe.src
      );
    }
  });
}

// If any 'dialog' element is opened, disable lenis and add overflow-hidden to html and body
document.addEventListener("DOMContentLoaded", function () {
  const dialogs = document.querySelectorAll("dialog");
  if (dialogs !== null) {
    dialogs.forEach(function (dialog) {
      const observer = new MutationObserver(function (mutations) {
        mutations.forEach((mutationRecord) => {
          if (mutationRecord.target.hasAttribute("open")) {
            setTimeout(() => {
              if (isDesktop) {
                lenis.stop();
              } else {
                html.classList.add("overflow-hidden");
                document.body.classList.add("overflow-hidden");
              }
            }, 500);
          } else {
            setTimeout(() => {
              if (isDesktop) {
                lenis.start();
              } else {
                html.classList.remove("overflow-hidden");
                document.body.classList.remove("overflow-hidden");
              }
            }, 100);
          }
        });
      });
      observer.observe(dialog, {
        attributes: true,
        attributeFilter: ["open"],
      });
    });
  }
});

document.body.addEventListener("touchStart", function () {});

/**
 * CookieYes Extension
 */
document.addEventListener("DOMContentLoaded", function () {
  const cookieYesBodyWrapper = document.querySelector(
    ".cky-preference-body-wrapper"
  );
  if (cookieYesBodyWrapper) {
    cookieYesBodyWrapper.setAttribute("data-lenis-prevent", "");
  }
});
