{* DO NOT EDIT THIS FILE! Use an override template instead. *}
{* eZXNewsletter - subscriptions list *}
{def $base_uri=concat( 'ezapprove2/view_approve_list/(offset)/', $view_parameters.offset, '/(limit)/', $view_parameters.limitkey )}

<form name="approve_list" method="post" action={$base_uri|ezurl}>

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'Elements awaiting approval'|i18n( 'ezxnewsletter' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{* Items per page selector. *}
<div class="context-toolbar">
<div class="block">
<div class="left">
<p>
{switch match=$limit}
{case match=25}
<a href={concat( $base_uri, '/(limit)/1' )|ezurl}>10</a>
<span class="current">25</span>
<a href={concat( $base_uri, '/(limit)/3' )|ezurl}>50</a>
{/case}

{case match=50}
<a href={concat( $base_uri, '/(limit)/1' )|ezurl}>10</a>
<a href={concat( $base_uri, '/(limit)/2' )|ezurl}>25</a>
<span class="current">50</span>
{/case}

{case}
<span class="current">10</span>
<a href={concat( $base_uri, '/(limit)/2' )|ezurl}>25</a>
<a href={concat( $base_uri, '/(limit)/3' )|ezurl}>50</a>
{/case}

{/switch}
</p>
</div>
<div class="break"></div>
</div>
</div>

{* Subscription list table. *}
<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'ezxnewsletter' )}" title="{'Invert selection.'|i18n( 'ezxnewsletter' )}" onclick="ezjs_toggleCheckboxes( document.approve_list, 'ApproveStatusIDArray[]' ); return false;" /></th>
    <th class="tight">{'Object ID'|i18n('ezxnewslettert')}</th>
    <th>{'Name'|i18n('ezxnewsletter')}</th>
    <th>{'Creator'|i18n('ezxnewsletter')}</th>
    <th>{'Started'|i18n('ezxnewsletter')}</th>
    <th>{'# Approved'|i18n('ezxnewsletter')}</th>
    <th>{'# Required'|i18n('ezxnewsletter')}</th>
{*    <th class="tight">{'Action'|i18n('ezxnewslettert')}</th>*}
</tr>
{foreach $approve_status_list as $approveStatus
         sequence array( bglight, bgdark ) as $seq}
<tr class="{$seq}">
    <td><input type="checkbox" name="ApproveStatusIDArray[]" value="{$approveStatus.id}" title="{'Select approval for removal. This will will mark the pending version as archived.'|i18n( 'ezxnewsletter' )}" /></td>
    <td class="number"><a href={concat( '/collaboration/item/full/', $approveStatus.collaborationitem_id )|ezurl}>{$approveStatus.contentobject_id}</a></td>
    <td><a href={concat( '/collaboration/item/full/', $approveStatus.collaborationitem_id )|ezurl}>{$approveStatus.object_version.name|wash}</a></td>
    <td><a href={$approveStatus.object_version.creator.main_node.url_alias|ezurl}>{$approveStatus.object_version.creator.name|wash}</a></td>
    <td>{$approveStatus.started|l10n(datetime)}</td>
    <td class="number">{$approveStatus.num_approved|wash}</td>
    <td class="number">{$approveStatus.num_approve_required|wash}</td>

{*    <td>{if fetch( ezapprove2, is_approver, hash( approve_status_id, $approveStatus.id ) )}
            {if array( 0, 2)|contains( $approveStatus.user_approve_status.approve_status )}
                <select name="ApproveStatus_{$approveStatus.id}">
                {if $approveStatus.user_approve_status.approve_status|eq(0)}<option value="0" {cond($approveStatus.user_approve_status.approve_status|eq(0), 'selected="selected"', '' )}>{$status_name_map[0]|wash}</option>{/if}
                <option value="2" {cond($approveStatus.user_approve_status.approve_status|eq(2), 'selected="selected"', '' )}>{$status_name_map[2]|wash}</option>
                <option value="1" {cond($approveStatus.user_approve_status.approve_status|eq(1), 'selected="selected"', '' )}>{$status_name_map[1]|wash}</option>
                </select>
                {if $approveStatus.contentobject.can_edit}
                    <a href={concat( 'content/edit/', $approveStatus.contentobject_id )|ezurl}><img src={'edit.gif'|ezimage} alt="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'Edit <%child_name>.'|i18n( 'design/admin/node/view/full',, hash( '%child_name', $approveStatus.contentobject.name ) )|wash}" /></a>
                {else}
                    <img src={'edit-disabled.gif'|ezimage} alt="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permissions to edit <%child_name>.'|i18n( 'design/admin/node/view/full',, hash( '%child_name', $approveStatus.contentobject.name ) )|wash}" /></a>
                {/if}
            {else}
                {$status_name_map[$approveStatus.user_approve_status.approve_status]|wash}
            {/if}
        {else}
            {'Creator'|i18n( 'design/admin/node/view/full' )}
        {/if}</td>*}
    </tr>
{/foreach}
</table>

{* Navigator. *}
<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=$base_uri
         item_count=$approve_status_count
         item_limit=$limit}
</div>

{* DESIGN: Content END *}</div></div></div>

{* Buttons. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<div align="right"><input class="button" type="submit" name="UpdateApproveStatusList" value="{'Update list'|i18n( 'ezapprove2' )}" title="{'Update approval statuses.'|i18n( 'ezxnewsletter' )}" /></div>
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</form>
