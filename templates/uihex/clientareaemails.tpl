<div class="account-wrap">

{include file="$template/clientareadetailslinks.tpl"}


    <div class="titleline"></div>
        <div class="row details">
            <!--<div class="col-md-12">

                <h3 class="page-header"><span aria-hidden="true" class="icon icon-envelope"></span> {$LANG.clientareaemails}  </h3>

                <blockquote class="page-information hidden">
                    <p>{$LANG.emailstagline}</p>
                </blockquote>
            </div>-->
            <div class="table-wrap">
                <div class="resultsbox">
                    <p class="tbldesc">{$LANG.emailstagline}</p>
                    <p class="tblnum">{$numitems} {$LANG.recordsfound}, {$LANG.page} {$pagenumber} {$LANG.pageof} {$totalpages}</p>
                    <table class="table table-striped table-condensed">
                        <thead>
                            <tr>
                                <th{if $orderby eq "date"} class="headerSort{$sort}"{/if}><a href="clientarea.php?action=emails&orderby=date">{$LANG.clientareaemailsdate}</a></th>
                                <th{if $orderby eq "subject"} class="headerSort{$sort}"{/if}><a href="clientarea.php?action=emails&orderby=subject">{$LANG.clientareaemailssubject}</a></th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                    {foreach from=$emails item=email}
                            <tr>
                                <td>{$email.date}</td>
                                <td>{$email.subject}</td>
                                <td><input type="button" class="btn btn-default btn-sm pull-right" value="{$LANG.emailviewmessage}" onclick="popupWindow('viewemail.php?id={$email.id}','emlmsg',650,400)" /></td>
                            </tr>
                    {foreachelse}
                            <tr>
                                <td colspan="3" class="textcenter">{$LANG.norecordsfound}</td>
                            </tr>
                    {/foreach}
                        </tbody>
                    </table>
                    {include file="$template/clientarearecordslimit.tpl" clientareaaction=$clientareaaction}

                    <ul class="pagination">
                        <li class="prev{if !$prevpage} disabled{/if}"><a href="{if $prevpage}clientarea.php?action=emails{if $q}&q={$q}{/if}&amp;page={$prevpage}{else}javascript:return false;{/if}">&larr; {$LANG.previouspage}</a></li>
                        <li class="next{if !$nextpage} disabled{/if}"><a href="{if $nextpage}clientarea.php?action=emails{if $q}&q={$q}{/if}&amp;page={$nextpage}{else}javascript:return false;{/if}">{$LANG.nextpage} &rarr;</a></li>
                    </ul>
                </div>   
            </div>     
        </div>

    </div>