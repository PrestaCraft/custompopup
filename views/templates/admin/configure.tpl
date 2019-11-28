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

<link rel="stylesheet" type="text/css" href="{$module_dir}views/css/back.css">
<script src="{$colorpicker_path}"></script>
<script src="{$module_dir}/views/js/back.js"></script>

{if isset($errors)}
    <div class="pc-alert pc-errors">
        {$errors}
        <br />
        <small>Click to dismiss</small>
    </div>
{/if}

{if isset($success)}
    <div class="pc-alert pc-success">
        {$success}
        <br />
        <small>Click to dismiss</small>
    </div>
{/if}

{if isset($multistore)}
    <div class="pc-alert pc-informations">
        <small>You are running multistore. Please note that all settings will be saved
            to currently selected shop.</small>
    </div>
{/if}

<div role="tabpanel" class="prestacraft">
    <!-- Nav tabs -->
    <div class="col-lg-3 col-md-4 col-xs-12 prestacraft-left">
        <div class="menu-container">
            <div class="logo-container">
                <img src="{$module_dir}/views/img/pc_logo.png" class="pc-logo">
            </div>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" {if !isset($smarty.post.TAB_1) &&
                !isset($smarty.post.TAB_2) &&
                !isset($smarty.post.TAB_3) &&
                !isset($smarty.post.TAB_4)}class="active"{else}{if isset($smarty.post.TAB_1)}class="active"{/if}{/if}>
                    <a href="#settings" aria-controls="home" role="tab" data-toggle="tab">
                        <i class="icon-cogs"></i>&nbsp;&nbsp;&nbsp;{l s='Main settings' mod='custompopup'}</a>
                </li>

                <li role="presentation" {if isset($smarty.post.TAB_2)}class="active"{/if}>
                    <a href="#customizestyle" aria-controls="profile" role="tab" data-toggle="tab">
                        <i class="icon-pencil"></i>&nbsp;&nbsp;&nbsp;{l s='Customize style' mod='custompopup'}
                    </a>
                </li>

                <li role="presentation" {if isset($smarty.post.TAB_3)}class="active"{/if}>
                    <a href="#extras" aria-controls="profile" role="tab" data-toggle="tab">
                        <i class="icon-remove"></i>&nbsp;&nbsp;&nbsp;{l s='Close & Footer' mod='custompopup'}
                    </a>
                </li>

                <li role="presentation" {if isset($smarty.post.TAB_4)}class="active"{/if}>
                    <a href="#display" aria-controls="profile" role="tab" data-toggle="tab">
                        <i class="icon-eye-open"></i>&nbsp;&nbsp;&nbsp;{l s='Display on pages' mod='custompopup'}
                    </a>
                </li>
            </ul>
        </div>

        <div class="pc-info">
            <div class="pc-checker">
                {$VERSION_CHECKER}
            </div>
            <br/>
            <div>
            <a href="http://prestacraft.com/custom-popup-notification" target="_blank" class="bottom-button btn-official-module">{l s='Official module website' mod='custompopup'}</a>

            <a href="https://github.com/PrestaCraft/custompopup/issues" target="_blank" class="bottom-button btn-issue">{l s='Report an issue' mod='custompopup'}</a>
            </div>

            {include file='./extras/prestacraft.tpl'}
                <br />
            {include file='./extras/paypal.tpl'}
                <br /><br />
        </div>
    </div>

    <div class="col-md-8 col-lg-9 col-xs-12">
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane {if !isset($smarty.post.TAB_1) &&
            !isset($smarty.post.TAB_2) &&
            !isset($smarty.post.TAB_3) &&
            !isset($smarty.post.TAB_4)}active{else}{if isset($smarty.post.TAB_1)}active{/if}{/if}" id="settings">
                {$TAB_SETTINGS}</div>

            <div role="tabpanel" class="tab-pane {if isset($smarty.post.TAB_2)}active{/if}" id="customizestyle">
                <div class="row">
                <div class="col-xs-12 col-sm-6">{$TAB_CUSTOMIZE_STYLE}</div>
                <div class="col-xs-12 col-sm-6">{$TAB_CUSTOMIZE_CLOSE}</div>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane {if isset($smarty.post.TAB_3)}active{/if}" id="extras">
                {$TAB_CLOSE_AND_FOOTER}
            </div>

            <div role="tabpanel" class="tab-pane {if isset($smarty.post.TAB_4)}active{/if}" id="display">
                <div class="pc-frame">
                    <p>You can select here one or many available <strong>hooks</strong>.</p>
                    <p><strong>Hooks</strong> are special places in Your store where you may display this popup.</p>
                    <p>If you are unfamiliar with is, please read about them in PrestaShop documentation.
                    You should also find "PrestaShop hook list" for your PS version.</p>
                    <p>Quick "cheat sheet" is in table below:</p>
                    <table border="1" class="pc-table">
                        <tr>
                            <td style="font-weight:bold;">Where do you want to display popup?</td>
                            <td style="font-weight:bold;">Hook</td>
                            <td style="font-weight:bold;">Explanation</td>
                        </tr>
                        <tr>
                            <td>Each page</td>
                            <td>displayFooter</td>
                            <td>Your store footer is displayed in each page, that's why you can "hook" a popup there</td>
                        </tr>
                        <tr>
                            <td>Homepage</td>
                            <td>displayHome</td>
                            <td>Hook which is executed only in Homepage</td>
                        </tr>
                    </table>

                    <p>I won't explain each hook because their names should be self-explanatory
                        and the list of hooks may vary between PrestaShop versions.
                    You may also use your own hooks or a hook created by any module.</p>

                    <p>If this list of hooks is outdated - a button will appear and you will be able
                    to synchronize this list with your available hooks.</p>

                    <p>Remember, this popup will have effect in FrontOffice only.</p>
                </div>

                {if ($IF_REQUIRE_HOOK_UPDATE)}
                    {include file='./hook_button.tpl'}
                {else}
                    <div style="text-align: center;margin: 10px 0;color: green;font-size: 14px;">
                        Hook list is up to date.
                    </div>
                {/if}

                {$TAB_DISPLAY}
            </div>
        </div>
    </div>
</div>