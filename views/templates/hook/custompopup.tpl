{*
 * This software is provided "as is" without warranty of any kind.
 *
 * Made by PrestaCraft
 *
 * Visit my website (http://prestacraft.com) for future updates, new articles and other awesome modules.
 *
 * @author     PrestaCraft
 * @copyright  PrestaCraft
*}

{if $popup_enabled}
    {literal}
        <link rel="stylesheet" href="{/literal}{$tingle_css}{literal}">
        <link rel="stylesheet" href="{/literal}{$popup_css}{literal}">
        <script src="{/literal}{$tingle}{literal}"></script>
        <script src="{/literal}{$cookie}{literal}"></script>
        <script>
            if (typeof id_lang === 'undefined') {
                var id_lang = {/literal}{Context::getContext()->language->id}{literal};
            }

            {/literal}{if !$popup_cookie && $popup_cookie == 0}{literal}
                prestacraftDeleteCookie('responsive_popup_{/literal}{Context::getContext()->shop->id}{literal}');
            {/literal}{/if}{literal}

            if (prestacraftGetCookie('responsive_popup_{/literal}{Context::getContext()->shop->id}{literal}') != 'yes') {

                {/literal}{if $popup_delay > 0}{literal}
                setTimeout(function(){
                {/literal}{/if}{literal}
                    var modal = new tingle.modal({
                        footer: true,
                        stickyFooter: false,
                        closeMethods: [{/literal}{$closetype|unescape: "html" nofilter}{literal}],
                        closeLabel: "Close",
                        cssClass: ['custom-class-1', 'custom-class-2'],
                        onOpen: function() {
                        },
                        onClose: function() {
                            {/literal}{if $popup_cookie && $popup_cookie > 0}{literal}
                            prestacraftSetCookie('responsive_popup_{/literal}{Context::getContext()->shop->id}{literal}',
                                'yes', {/literal}{$popup_cookie*0.000694}{literal});
                            {/literal}{/if}{literal}
                        },
                        beforeClose: function() {
                            return true; // close the modal
                        }
                    });

                    var content = "{/literal}{$content_{Context::getContext()->language->id}|unescape: "html" nofilter}{literal}";
                    // set content
                    modal.setContent(content);

                    // close button
                    modal.addFooterBtn('x', 'prestacraft-close', function() {
                        modal.close();
                    });

                    {/literal}{if $footer}{literal}
                        {/literal}{if $footer_type == 'button' || $footer_type == 'text_buttons'}{literal}
                            {/literal}{if $footer_button1_enabled}{literal}
                                modal.addFooterBtn('{/literal}{$button1_text_{Context::getContext()->language->id}|unescape: "html" nofilter}{literal}', 'tingle-btn prestacraft-button1', function() {
                                    {/literal}{if $button1_url_{Context::getContext()->language->id}}{literal}
                                        {/literal}{if $button1_new_tab}{literal}
                                            window.open(
                                                '{/literal}{$button1_url_{Context::getContext()->language->id}|unescape: "html" nofilter}{literal}',
                                                '_blank'
                                            );
                                        {/literal}{else}{literal}
                                            window.location.href = "{/literal}{$button1_url_{Context::getContext()->language->id}|unescape: "html" nofilter}{literal}";
                                        {/literal}{/if}{literal}
                                    {/literal}{/if}{literal}
                                    {/literal}{if $button1_act_close}{literal}
                                        modal.close();
                                    {/literal}{/if}{literal}
                                });
                            {/literal}{/if}{literal}

                            {/literal}{if $footer_button2_enabled}{literal}
                                modal.addFooterBtn('{/literal}{$button2_text_{Context::getContext()->language->id}|unescape: "html" nofilter}{literal}', 'tingle-btn prestacraft-button2', function() {
                                    {/literal}{if $button2_url_{Context::getContext()->language->id}}{literal}
                                        {/literal}{if $button2_new_tab}{literal}
                                            window.open(
                                                '{/literal}{$button2_url_{Context::getContext()->language->id}|unescape: "html" nofilter}{literal}',
                                                '_blank'
                                            );
                                        {/literal}{else}{literal}
                                            window.location.href = "{/literal}{$button2_url_{Context::getContext()->language->id}|unescape: "html" nofilter}{literal}";
                                        {/literal}{/if}{literal}
                                    {/literal}{/if}{literal}
                                    {/literal}{if $button2_act_close}{literal}
                                        modal.close();
                                    {/literal}{/if}{literal}
                                });
                            {/literal}{/if}{literal}
                        {/literal}{/if}{literal}


                        {/literal}{if $footer_type == 'text' || $footer_type == 'text_buttons'}{literal}
                        modal.addFooterBtn('{/literal}{$footer_text_{Context::getContext()->language->id}|unescape: "html" nofilter}{literal}', 'prestacraft-special-text', function() {
                        });
                        {/literal}{/if}{literal}
                    {/literal}{/if}{literal}

                    modal.open();
                    {/literal}{if $popup_delay > 0}{literal}
                },  {/literal}{$popup_delay*1000}{literal});
                {/literal}{/if}{literal}

            }
        </script>
    {/literal}

    {literal}
        <style>
            .tingle-modal-box__content {
                background-color:{/literal} {$popup_color}{literal} !important;
                padding:{/literal} {$padding}{literal}px;
                padding-top:{/literal} {$top_padding}{literal}px;
            }
            {/literal}{if $back_color}{literal}
            .tingle-modal--visible {
                background-color: {/literal}{$back_color}{literal};
            }
            {/literal}{/if}{literal}
            .prestacraft-close:hover {
                color: {/literal}{$button_hover_color}{literal};
            }
            .prestacraft-close {
                color: {/literal}{$button_color}{literal};
                top: {/literal}{$button_top_padding}{literal}px;
                font-size: {/literal}{$button_size}{literal}px !important;
            }

            {/literal}{if !$footer}{literal}
            .tingle-modal-box__footer {
                height: 1px;
                padding: 0;
                background-color: {/literal}{$popup_color}{literal} !important;
            }
            {/literal}{else}{literal}
            .tingle-modal-box__footer {
                background-color: {/literal}{$footer_background}{literal};
                text-align: {/literal}{$footer_align}{literal};
            }
            {/literal}{/if}{literal}

            .prestacraft-special-text {
                text-align: {/literal}{$footer_align}{literal};
                {/literal}{if $footer_type == 'text_buttons'}{literal}
                margin-top: 20px;
                {/literal}{/if}{literal}
            }

            .prestacraft-button1 {
                background-color: {/literal}{$footer_button1_background}{literal};
            }

            .prestacraft-button2 {
                background-color: {/literal}{$footer_button2_background}{literal};
            }
        </style>
    {/literal}
{/if}
