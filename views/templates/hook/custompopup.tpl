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

{if $pc_popup_enabled}
    {literal}
        <link rel="stylesheet" href="{/literal}{$pc_css_tingle}{literal}">
        <link rel="stylesheet" href="{/literal}{$pc_css_popup}{literal}">
        <script src="{/literal}{$pc_js_tingle}{literal}"></script>
        <script src="{/literal}{$pc_js_cookie}{literal}"></script>
        <script>
            if (typeof id_lang === 'undefined') {
                var id_lang = {/literal}{Context::getContext()->language->id}{literal};
            }

            {/literal}{if !$pc_popup_cookie && $pc_popup_cookie == 0}{literal}
                prestacraftDeleteCookie('responsive_popup_{/literal}{Context::getContext()->shop->id}{literal}');
            {/literal}{/if}{literal}

            if (prestacraftGetCookie('responsive_popup_{/literal}{Context::getContext()->shop->id}{literal}') != 'yes') {

                {/literal}{if $pc_popup_delay > 0}{literal}
                setTimeout(function(){
                {/literal}{/if}{literal}
                    var modal = new tingle.modal({
                        footer: true,
                        stickyFooter: false,
                        closeMethods: [{/literal}{$pc_closetype|unescape: "html" nofilter}{literal}],
                        closeLabel: "Close",
                        cssClass: ['custom-class-1', 'custom-class-2'],
                        onOpen: function() {
                        },
                        onClose: function() {
                            {/literal}{if $pc_popup_cookie && $pc_popup_cookie > 0}{literal}
                            prestacraftSetCookie('responsive_popup_{/literal}{Context::getContext()->shop->id}{literal}',
                                'yes', {/literal}{$pc_popup_cookie*0.000694}{literal});
                            {/literal}{/if}{literal}
                        },
                        beforeClose: function() {
                            return true; // close the modal
                        }
                    });

                    var content = "{/literal}{$pc_content_{Context::getContext()->language->id}|unescape: "html" nofilter}{literal}";
                    // set content
                    modal.setContent(content);

                    // close button
                    modal.addFooterBtn('x', 'prestacraft-close', function() {
                        modal.close();
                    });

                    {/literal}{if $pc_footer}{literal}
                        {/literal}{if $pc_footer_type == 'button' || $pc_footer_type == 'text_buttons'}{literal}
                            {/literal}{if $pc_footer_button1_enabled}{literal}
                                modal.addFooterBtn('{/literal}{$pc_button1_text_{Context::getContext()->language->id}|unescape: "html" nofilter}{literal}', 'tingle-btn prestacraft-button1', function() {
                                    {/literal}{if $pc_button1_act_close}{literal}
                                        modal.close();
                                    {/literal}{/if}{literal}
                                    {/literal}{if $pc_button1_url_{Context::getContext()->language->id}}{literal}
                                        {/literal}{if $pc_button1_new_tab}{literal}
                                            window.open(
                                                '{/literal}{$pc_button1_url_{Context::getContext()->language->id}|unescape: "html" nofilter}{literal}',
                                                '_blank'
                                            );
                                        {/literal}{else}{literal}
                                            window.location.href = "{/literal}{$pc_button1_url_{Context::getContext()->language->id}|unescape: "html" nofilter}{literal}";
                                        {/literal}{/if}{literal}
                                    {/literal}{/if}{literal}
                                });
                            {/literal}{/if}{literal}

                            {/literal}{if $pc_footer_button2_enabled}{literal}
                                modal.addFooterBtn('{/literal}{$pc_button2_text_{Context::getContext()->language->id}|unescape: "html" nofilter}{literal}', 'tingle-btn prestacraft-button2', function() {
                                    {/literal}{if $pc_button2_act_close}{literal}
                                        modal.close();
                                    {/literal}{/if}{literal}
                                    {/literal}{if $pc_button2_url_{Context::getContext()->language->id}}{literal}
                                        {/literal}{if $pc_button2_new_tab}{literal}
                                            window.open(
                                                '{/literal}{$pc_button2_url_{Context::getContext()->language->id}|unescape: "html" nofilter}{literal}',
                                                '_blank'
                                            );
                                        {/literal}{else}{literal}
                                            window.location.href = "{/literal}{$pc_button2_url_{Context::getContext()->language->id}|unescape: "html" nofilter}{literal}";
                                        {/literal}{/if}{literal}
                                    {/literal}{/if}{literal}
                                });
                            {/literal}{/if}{literal}
                        {/literal}{/if}{literal}


                        {/literal}{if $pc_footer_type == 'text' || $pc_footer_type == 'text_buttons'}{literal}
                        modal.addFooterBtn('{/literal}{$pc_footer_text_{Context::getContext()->language->id}|unescape: "html" nofilter}{literal}', 'prestacraft-special-text', function() {
                        });
                        {/literal}{/if}{literal}
                    {/literal}{/if}{literal}

                    modal.open();
                    {/literal}{if $pc_popup_delay > 0}{literal}
                },  {/literal}{$pc_popup_delay*1000}{literal});
                {/literal}{/if}{literal}
            }
        </script>
    {/literal}

    {literal}
        <style>
            .tingle-modal-box__content {
                background-color:{/literal} {$pc_popup_color}{literal} !important;
                padding:{/literal} {$pc_padding}{literal}px;
                padding-top:{/literal} {$pc_top_padding}{literal}px;
            }
            {/literal}{if $pc_back_color}{literal}
            .tingle-modal--visible {
                background-color: {/literal}{$pc_back_color}{literal};
            }
            {/literal}{/if}{literal}
            .prestacraft-close:hover {
                color: {/literal}{$pc_button_hover_color}{literal};
            }
            .prestacraft-close {
                color: {/literal}{$pc_button_color}{literal};
                top: {/literal}{$pc_button_top_padding}{literal}px;
                font-size: {/literal}{$pc_button_size}{literal}px !important;
            }

            {/literal}{if !$pc_footer}{literal}
            .tingle-modal-box__footer {
                height: 1px;
                padding: 0;
                background-color: {/literal}{$pc_popup_color}{literal} !important;
            }
            {/literal}{else}{literal}
            .tingle-modal-box__footer {
                background-color: {/literal}{$pc_footer_background}{literal};
                text-align: {/literal}{$pc_footer_align}{literal};
            }
            {/literal}{/if}{literal}

            .prestacraft-special-text {
                text-align: {/literal}{$pc_footer_align}{literal};
                {/literal}{if $pc_footer_type == 'text_buttons'}{literal}
                margin-top: 20px;
                {/literal}{/if}{literal}
            }

            .prestacraft-button1 {
                background-color: {/literal}{$pc_footer_button1_background}{literal};
            }

            .prestacraft-button2 {
                background-color: {/literal}{$pc_footer_button2_background}{literal};
            }
        </style>
    {/literal}
{/if}
