<?php

/**
 * Add Custom Styles to WP Admin for fixed postbox
 * - when editing a post, the postbox-container-1 will be fixed to the right of the screen if it's in two columns
 */

function gg_admin_custom_css()
{
  echo '<style>
        #post-body.columns-2 #postbox-container-1 {
          position: fixed;
          top: 95px;
          right: 40px;
          transform: translateX(-100%);
          max-height: calc(100vh - 95px);
          overflow-x: visible;
          overflow-y: auto;
        }
        /* todo: remove later, once I find out why this was needed ever */
        #poststuff #postbox-container-2 {
          /* padding-bottom: 100vh; */
        }
        .media-upload-form .notice, .media-upload-form div.error, .wrap .notice, .wrap div.error, .wrap div.updated {
          margin: 5px 300px 15px 0px;
        }

        body:has(#inline-preview-container.ui-resizable) #wpcontent {
          height: calc(100svh - 32px);
          overflow-y: scroll;
          overflow-x: clip;
        }
        body:has(#inline-preview-container.ui-resizable) #footer-left {
          display: none;
        }
        body.acfe-modal-opened:has(#inline-preview-container.ui-resizable) {
          overflow: visible !important;
        }
      </style>
        ';
}
add_action('admin_head', 'gg_admin_custom_css');
