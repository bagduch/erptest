<?php /* Smarty version 2.6.28, created on 2016-10-05 15:56:24
         compiled from ra/viewticket.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'ra/viewticket.tpl', 337, false),)), $this); ?>
<?php echo $this->_tpl_vars['infobox']; ?>

<div id="replyingadmin"> <?php if ($this->_tpl_vars['replyingadmin']): ?>
  <div class="errorbox"><?php echo $this->_tpl_vars['replyingadmin']['name']; ?>
 <?php echo $this->_tpl_vars['_ADMINLANG']['support']['viewedandstarted']; ?>
 @ <?php echo $this->_tpl_vars['replyingadmin']['time']; ?>
</div>
  <?php endif; ?> </div>
<h2>#<?php echo $this->_tpl_vars['tid']; ?>
 - <?php echo $this->_tpl_vars['subject']; ?>

  <select name="ticketstatus" id="ticketstatus" style="font-size:18px;">
    
        <?php $_from = $this->_tpl_vars['statuses']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['statusitem']):
?>
            <option<?php if ($this->_tpl_vars['statusitem']['title'] == $this->_tpl_vars['status']): ?> selected<?php endif; ?> style="color:<?php echo $this->_tpl_vars['statusitem']['color']; ?>
"><?php echo $this->_tpl_vars['statusitem']['title']; ?>

    </option>
    
        <?php endforeach; endif; unset($_from); ?>
    
  </select>
  <a href="#" onclick="$('#ticketstatus').val('Closed');
            $('#ticketstatus').trigger('change');
            return false"><?php echo $this->_tpl_vars['_ADMINLANG']['global']['close']; ?>
</a> <span class="ticketheader">Assigned To</span>
  <select id="flagto" style="font-size:18px;" name="flagto">
    <option value="0"><?php echo $this->_tpl_vars['_ADMINLANG']['global']['none']; ?>
</option>
    
        <?php $_from = $this->_tpl_vars['staff']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['staffmember']):
?>
            
    <option value="<?php echo $this->_tpl_vars['staffmember']['id']; ?>
"<?php if ($this->_tpl_vars['staffmember']['id'] == $this->_tpl_vars['flag']): ?> selected<?php endif; ?>><?php echo $this->_tpl_vars['staffmember']['name']; ?>
</option>
    
        <?php endforeach; endif; unset($_from); ?>
    
  </select>
</h2>
<div class="ticketlastreply"><?php echo $this->_tpl_vars['_ADMINLANG']['support']['lastreply']; ?>
: <?php echo $this->_tpl_vars['lastreply']; ?>
</div>
<?php if ($this->_tpl_vars['clientnotes']): ?>
<div id="clientsimportantnotes"> <?php $_from = $this->_tpl_vars['clientnotes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['note']):
?>
  <div class="ticketstaffnotes">
    <table>
      <tr>
        <td><?php echo $this->_tpl_vars['note']['adminuser']; ?>
</td>
        <td align="right"><?php echo $this->_tpl_vars['note']['modified']; ?>
</td>
        <td width="16"><a href="clientsnotes.php?userid=<?php echo $this->_tpl_vars['clientsdetails']['userid']; ?>
&action=edit&id=<?php echo $this->_tpl_vars['note']['id']; ?>
"><img src="images/edit.gif" width="16" height="16" align="absmiddle" /></a></td>
      </tr>
    </table>
    <div> <?php echo $this->_tpl_vars['note']['note']; ?>
 </div>
  </div>
  <?php endforeach; endif; unset($_from); ?> </div>
<?php endif; ?>

<?php $_from = $this->_tpl_vars['addons_html']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['addon_html']):
?>
<div style="margin-bottom:15px;"><?php echo $this->_tpl_vars['addon_html']; ?>
</div>
<?php endforeach; endif; unset($_from); ?>
<div class="">
  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
?action=viewticket&id=<?php echo $this->_tpl_vars['ticketid']; ?>
">
    <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
      <tr>
        <td width="15%" class="fieldlabel"><?php echo $this->_tpl_vars['_ADMINLANG']['support']['department']; ?>
</td>
        <td class="fieldarea"><select name="deptid">
            
                        <?php $_from = $this->_tpl_vars['departments']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['department']):
?>
                            
            <option value="<?php echo $this->_tpl_vars['department']['id']; ?>
"<?php if ($this->_tpl_vars['department']['id'] == $this->_tpl_vars['deptid']): ?> selected<?php endif; ?>><?php echo $this->_tpl_vars['department']['name']; ?>
</option>
            
                        <?php endforeach; endif; unset($_from); ?>
                    
          </select></td>
        <td width="15%" class="fieldlabel"><?php echo $this->_tpl_vars['_ADMINLANG']['fields']['clientid']; ?>
</td>
        <td class="fieldarea"><input type="text" name="userid" size="15" id="clientsearchval" value="<?php echo $this->_tpl_vars['userid']; ?>
" />
          <img src="images/icons/delete.png" alt="Cancel" class="absmiddle" id="clientsearchcancel" height="16" width="16">
          <div id="ticketclientsearchresults"></div></td>
      </tr>
      <tr>
        <td class="fieldlabel"><?php echo $this->_tpl_vars['_ADMINLANG']['fields']['subject']; ?>
</td>
        <td class="fieldarea"><input type="text" name="subject" value="<?php echo $this->_tpl_vars['subject']; ?>
" style="width:80%"></td>
        <td class="fieldlabel"><?php echo $this->_tpl_vars['_ADMINLANG']['support']['flag']; ?>
</td>
        <td class="fieldarea"></td>
      </tr>
      <tr>
        <td class="fieldlabel"><?php echo $this->_tpl_vars['_ADMINLANG']['fields']['status']; ?>
</td>
        <td class="fieldarea"><select name="status">
            
                        <?php $_from = $this->_tpl_vars['statuses']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['statusitem']):
?>
                            <option<?php if ($this->_tpl_vars['statusitem']['title'] == $this->_tpl_vars['status']): ?> selected<?php endif; ?> style="color:<?php echo $this->_tpl_vars['statusitem']['color']; ?>
"><?php echo $this->_tpl_vars['statusitem']['title']; ?>

            </option>
            
                        <?php endforeach; endif; unset($_from); ?>
                    
          </select></td>
        <td class="fieldlabel"><?php echo $this->_tpl_vars['_ADMINLANG']['support']['priority']; ?>
</td>
        <td class="fieldarea"><select name="priority">
            <option value="High"<?php if ($this->_tpl_vars['priority'] == 'High'): ?> selected<?php endif; ?>><?php echo $this->_tpl_vars['_ADMINLANG']['status']['high']; ?>
</option>
            <option value="Medium"<?php if ($this->_tpl_vars['priority'] == 'Medium'): ?> selected<?php endif; ?>><?php echo $this->_tpl_vars['_ADMINLANG']['status']['medium']; ?>
</option>
            <option value="Low"<?php if ($this->_tpl_vars['priority'] == 'Low'): ?> selected<?php endif; ?>><?php echo $this->_tpl_vars['_ADMINLANG']['status']['low']; ?>
</option>
          </select></td>
      </tr>
      <tr>
        <td class="fieldlabel"><?php echo $this->_tpl_vars['_ADMINLANG']['support']['ccrecipients']; ?>
</td>
        <td class="fieldarea"><input type="text" name="cc" value="<?php echo $this->_tpl_vars['cc']; ?>
" size="40">
          (<?php echo $this->_tpl_vars['_ADMINLANG']['transactions']['commaseparated']; ?>
)</td>
        <td class="fieldlabel"><?php echo $this->_tpl_vars['_ADMINLANG']['support']['mergeticket']; ?>
</td>
        <td class="fieldarea"><input type="text" name="mergetid" size="10">
          (<?php echo $this->_tpl_vars['_ADMINLANG']['support']['notocombine']; ?>
)</td>
      </tr>
    </table>
    <img src="images/spacer.gif" height="10" width="1"><br>
    <div align="center">
      <input type="submit" value="<?php echo $this->_tpl_vars['_ADMINLANG']['global']['savechanges']; ?>
" class="button">
    </div>
  </form>
</div>
<div id="tabs">
  <ul>
    <li id="tab0" class="tab"><a href="javascript:;"><?php echo $this->_tpl_vars['_ADMINLANG']['support']['addreply']; ?>
</a></li>
    <li id="tab1" class="tab"><a href="javascript:;"><?php echo $this->_tpl_vars['_ADMINLANG']['support']['addnote']; ?>
</a></li>
    <?php if ($this->_tpl_vars['numcustomfields']): ?>
    <li id="tab2" class="tab"><a href="javascript:;"><?php echo $this->_tpl_vars['_ADMINLANG']['setup']['customfields']; ?>
</a></li>
    <?php endif; ?>
    <li id="tab3" class="tab" onclick="loadTab(3, 'tickets', 0)"><a href="javascript:;"><?php echo $this->_tpl_vars['_ADMINLANG']['support']['clienttickets']; ?>
</a></li>
    <li id="tab4" class="tab" onclick="loadTab(4, 'clientlog', 0)"><a href="javascript:;"><?php echo $this->_tpl_vars['_ADMINLANG']['support']['clientlog']; ?>
</a></li>
        <li id="tab6" class="tab" onclick="loadTab(6, 'ticketlog', 0)"><a href="javascript:;"><?php echo $this->_tpl_vars['_ADMINLANG']['support']['ticketlog']; ?>
</a></li>
  </ul>
</div>
<div id="tab0box" class="tabbox">
  <div id="tab_content">
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
?action=viewticket&id=<?php echo $this->_tpl_vars['ticketid']; ?>
" enctype="multipart/form-data" name="replyfrm" id="replyfrm">
      <textarea name="message" id="replymessage" rows="14" style="width:100%;margin:0 0 10px 0;"><?php if ($this->_tpl_vars['signature']): ?>



                <?php echo $this->_tpl_vars['signature']; ?>
<?php endif; ?></textarea>
      <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
        <tr>
          <td width="15%" class="fieldlabel">Tools</td>
          <td class="fieldarea"><select name="postaction">
              <option value="return"><?php echo $this->_tpl_vars['_ADMINLANG']['support']['setansweredreturn']; ?>
</option>
              <option value="answered"><?php echo $this->_tpl_vars['_ADMINLANG']['support']['setansweredremain']; ?>
</option>
              
                                <?php $_from = $this->_tpl_vars['statuses']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['statusitem']):
?>
                                    <?php if ($this->_tpl_vars['statusitem']['id'] > 4): ?>
              <option value="setstatus<?php echo $this->_tpl_vars['statusitem']['id']; ?>
"><?php echo $this->_tpl_vars['_ADMINLANG']['support']['setto']; ?>
 <?php echo $this->_tpl_vars['statusitem']['title']; ?>
 <?php echo $this->_tpl_vars['_ADMINLANG']['support']['andremain']; ?>
</option>
              <?php endif; ?>
                                <?php endforeach; endif; unset($_from); ?>
                                
              <option value="close"><?php echo $this->_tpl_vars['_ADMINLANG']['support']['closereturn']; ?>
</option>
              <option value="note"><?php echo $this->_tpl_vars['_ADMINLANG']['support']['addprivatenote']; ?>
</option>
            </select>
            <input type="submit" value="<?php echo $this->_tpl_vars['_ADMINLANG']['support']['addresponse']; ?>
 &raquo;" name="postreply" class="btn-primary" id="postreplybutton" />
            <div style="float:right;">
              <input type="button" value="<?php echo $this->_tpl_vars['_ADMINLANG']['support']['insertpredef']; ?>
" class="btn" id="insertpredef" />
              <input type="button" value="<?php echo $this->_tpl_vars['_ADMINLANG']['support']['insertkblink']; ?>
" class="btn" onclick="window.open('supportticketskbarticle.php', 'kbartwnd', 'width=500,height=400,scrollbars=yes')" />
            </div>
            <div id="prerepliescontainer">
              <div class="box">
                <div style="float:right;">
                  <input type="text" id="predefq" size="25" value="<?php echo $this->_tpl_vars['_ADMINLANG']['global']['search']; ?>
" onfocus="this.value = (this.value == '<?php echo $this->_tpl_vars['_ADMINLANG']['global']['search']; ?>
') ? '' : this.value;" onblur="this.value = (this.value == '') ? '<?php echo $this->_tpl_vars['_ADMINLANG']['global']['search']; ?>
' : this.value;" />
                </div>
                <div id="prerepliescontent"><?php echo $this->_tpl_vars['predefinedreplies']; ?>
</div>
              </div>
            </div></td>
        </tr>
        <tr>
          <td class="fieldlabel"><?php echo $this->_tpl_vars['_ADMINLANG']['support']['attachments']; ?>
</td>
          <td class="fieldarea"><input type="file" name="attachments[]" size="85" />
            <a href="#" id="addfileupload"><img src="images/icons/add.png" align="absmiddle" border="0" /> <?php echo $this->_tpl_vars['_ADMINLANG']['support']['addmore']; ?>
</a><br />
            <div id="fileuploads"></div></td>
        </tr>
        <?php if ($this->_tpl_vars['userid']): ?>
        <tr>
          <td class="fieldlabel"><?php echo $this->_tpl_vars['_ADMINLANG']['support']['addbilling']; ?>
</td>
          <td class="fieldarea"><input type="text" name="billingdescription" size="60" value="<?php echo $this->_tpl_vars['_ADMINLANG']['support']['toinvoicedes']; ?>
" onfocus="if (this.value == '<?php echo $this->_tpl_vars['_ADMINLANG']['support']['toinvoicedes']; ?>
')
                                this.value = ''" />
            @
            <input type="text" name="billingamount" size="10" value="<?php echo $this->_tpl_vars['_ADMINLANG']['fields']['amount']; ?>
" />
            <select name="billingaction">
              <option value="3" />
               <?php echo $this->_tpl_vars['_ADMINLANG']['billableitems']['invoiceimmediately']; ?>

              </option>
              <option value="0" />
               <?php echo $this->_tpl_vars['_ADMINLANG']['billableitems']['dontinvoicefornow']; ?>

              </option>
              <option value="1" />
               <?php echo $this->_tpl_vars['_ADMINLANG']['billableitems']['invoicenextcronrun']; ?>

              </option>
              <option value="2" />
               <?php echo $this->_tpl_vars['_ADMINLANG']['billableitems']['addnextinvoice']; ?>

              </option>
            </select></td>
        </tr>
        <?php endif; ?>
      </table>
    </form>
  </div>
</div>
<div id="tab1box" class="tabbox">
  <div id="tab_content">
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
?action=viewticket&id=<?php echo $this->_tpl_vars['ticketid']; ?>
">
      <input type="hidden" name="postaction" value="note" />
      <textarea name="message" id="replymessage" rows="14" style="width:100%"></textarea>
      <br />
      <img src="images/spacer.gif" height="8" width="1" /> <br />
      <div align="center">
        <input type="submit" value="<?php echo $this->_tpl_vars['_ADMINLANG']['support']['addnote']; ?>
" class="button" name="postreply" />
      </div>
    </form>
  </div>
</div>
<div id="tab2box" class="tabbox">
  <div id="tab_content">
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
?action=viewticket&id=<?php echo $this->_tpl_vars['ticketid']; ?>
&sub=savecustomfields">
      <?php if (! $this->_tpl_vars['numcustomfields']): ?>
      <div align="center"><?php echo $this->_tpl_vars['_ADMINLANG']['support']['nocustomfields']; ?>
</div>
      <?php else: ?>
      <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
        <?php $_from = $this->_tpl_vars['customfields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['customfield']):
?>
        <tr>
          <td width="25%" class="fieldlabel"><?php echo $this->_tpl_vars['customfield']['name']; ?>
</td>
          <td class="fieldarea"><?php echo $this->_tpl_vars['customfield']['input']; ?>
</td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
      </table>
      <img src="images/spacer.gif" height="10" width="1" /><br />
      <div align="center">
        <input type="submit" value="<?php echo $this->_tpl_vars['_ADMINLANG']['global']['savechanges']; ?>
" class="button">
      </div>
    </form>
    <?php endif; ?> </div>
</div>
<div id="tab3box" class="tabbox">
  <div id="tab_content"> <img src="images/loading.gif" align="top" /> <?php echo $this->_tpl_vars['_ADMINLANG']['global']['loading']; ?>
 </div>
</div>
<div id="tab4box" class="tabbox">
  <div id="tab_content"> <img src="images/loading.gif" align="top" /> <?php echo $this->_tpl_vars['_ADMINLANG']['global']['loading']; ?>
 </div>
</div>
<div id="tab6box" class="tabbox">
  <div id="tab_content"> <img src="images/loading.gif" align="top" /> <?php echo $this->_tpl_vars['_ADMINLANG']['global']['loading']; ?>
 </div>
</div>
<br />
<?php if ($this->_tpl_vars['numnotes']): ?>
<h2><?php echo $this->_tpl_vars['_ADMINLANG']['support']['privatestaffnote']; ?>
</h2>
<?php $_from = $this->_tpl_vars['notes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['note']):
?>
<div class="ticketstaffnotes">
  <table>
    <tr>
      <td><?php echo $this->_tpl_vars['note']['admin']; ?>
</td>
      <td align="right"><?php echo $this->_tpl_vars['note']['date']; ?>
</td>
      <td width="16"><a href="#" onClick="doDeleteNote('<?php echo $this->_tpl_vars['note']['id']; ?>
');
                                    return false"><img src="images/delete.gif" alt="<?php echo $this->_tpl_vars['_ADMINLANG']['support']['deleteticketnote']; ?>
" border="0" /></a></td>
    </tr>
  </table>
  <div> <?php echo $this->_tpl_vars['note']['message']; ?>
 </div>
</div>
<?php endforeach; endif; unset($_from); ?>
        <?php endif; ?>

        <?php if ($this->_tpl_vars['relatedservices']): ?>
<div class="tablebg">
  <table class="datatable" id="relatedservicestbl" width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr>
      <th><?php echo $this->_tpl_vars['_ADMINLANG']['fields']['product']; ?>
</th>
      <th><?php echo $this->_tpl_vars['_ADMINLANG']['fields']['amount']; ?>
</th>
      <th><?php echo $this->_tpl_vars['_ADMINLANG']['fields']['billingcycle']; ?>
</th>
      <th><?php echo $this->_tpl_vars['_ADMINLANG']['fields']['signupdate']; ?>
</th>
      <th><?php echo $this->_tpl_vars['_ADMINLANG']['fields']['nextduedate']; ?>
</th>
      <th><?php echo $this->_tpl_vars['_ADMINLANG']['fields']['status']; ?>
</th>
    </tr>
    <?php $_from = $this->_tpl_vars['relatedservices']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['relatedservice']):
?>
      <tr<?php if ($this->_tpl_vars['relatedservice']['selected']): ?> class="rowhighlight"<?php endif; ?>>
    
      <td><?php echo $this->_tpl_vars['relatedservice']['name']; ?>
</td>
      <td><?php echo $this->_tpl_vars['relatedservice']['amount']; ?>
</td>
      <td><?php echo $this->_tpl_vars['relatedservice']['billingcycle']; ?>
</td>
      <td><?php echo $this->_tpl_vars['relatedservice']['regdate']; ?>
</td>
      <td><?php echo $this->_tpl_vars['relatedservice']['nextduedate']; ?>
</td>
      <td><?php echo $this->_tpl_vars['relatedservice']['status']; ?>
</td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
  </table>
</div>
<?php if ($this->_tpl_vars['relatedservicesexpand']): ?>
<div id="relatedservicesexpand" style="padding:2px 15px;text-align:right;"><a href="#" onclick="expandRelServices();
                    return false"><?php echo $this->_tpl_vars['_ADMINLANG']['support']['expand']; ?>
</a></div>
<?php endif; ?>
                <?php endif; ?>

            <?php if ($this->_tpl_vars['numnotes'] || $this->_tpl_vars['relatedservices']): ?><br />
            <?php endif; ?>
            <form method="post" action="supporttickets.php" id="ticketreplies">
  <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['ticketid']; ?>
" />
  <input type="hidden" name="action" value="split" />
  <div id="ticketreplies"> <?php $_from = $this->_tpl_vars['replies']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['reply']):
?>
                <div class="<?php if ($this->_tpl_vars['reply']['admin']): ?>staff<?php endif; ?>reply">
      <div class="leftcol">
                    <div class="submitter"> <?php if ($this->_tpl_vars['reply']['admin']): ?>
          <div class="name"><?php echo $this->_tpl_vars['reply']['admin']; ?>
</div>
          <div class="title"><?php echo $this->_tpl_vars['_ADMINLANG']['support']['staff']; ?>
</div>
          <?php if ($this->_tpl_vars['reply']['rating']): ?> <br />
          <?php echo $this->_tpl_vars['reply']['rating']; ?>
<br />
          <br />
          <?php endif; ?>
          
          <?php else: ?>
          <div class="name"><?php echo $this->_tpl_vars['reply']['clientname']; ?>
</div>
          <div class="title"> <?php if ($this->_tpl_vars['reply']['contactid']): ?>
                        <?php echo $this->_tpl_vars['_ADMINLANG']['fields']['contact']; ?>

                        <?php elseif ($this->_tpl_vars['reply']['userid']): ?>
                        <?php echo $this->_tpl_vars['_ADMINLANG']['fields']['client']; ?>

                        <?php else: ?> <a href="mailto:<?php echo $this->_tpl_vars['reply']['clientemail']; ?>
"><?php echo $this->_tpl_vars['reply']['clientemail']; ?>
</a> <?php endif; ?> </div>
          <?php if (! $this->_tpl_vars['reply']['userid'] && ! $this->_tpl_vars['reply']['contactid']): ?>
          <input type="button" value="<?php echo $this->_tpl_vars['_ADMINLANG']['support']['blocksender']; ?>
" onclick="window.location = '?action=viewticket&id=<?php echo $this->_tpl_vars['ticketid']; ?>
&blocksender=true&token=<?php echo $this->_tpl_vars['csrfToken']; ?>
'" class="btn-small" />
          <?php endif; ?>
          
          <?php endif; ?> </div>
                    <div class="tools">
          <div class="editbtns<?php if ($this->_tpl_vars['reply']['id']): ?>r<?php echo $this->_tpl_vars['reply']['id']; ?>
<?php else: ?>t<?php echo $this->_tpl_vars['ticketid']; ?>
<?php endif; ?>">
                        <input type="button" value="<?php echo $this->_tpl_vars['_ADMINLANG']['global']['edit']; ?>
" onclick="editTicket('<?php if ($this->_tpl_vars['reply']['id']): ?>r<?php echo $this->_tpl_vars['reply']['id']; ?>
<?php else: ?>t<?php echo $this->_tpl_vars['ticketid']; ?>
<?php endif; ?>')" class="btn-small btn-inverse" />
                        <?php if ($this->_tpl_vars['deleteperm']): ?>
                        <input type="button" value="<?php echo $this->_tpl_vars['_ADMINLANG']['global']['delete']; ?>
" onclick="<?php if ($this->_tpl_vars['reply']['id']): ?>doDeleteReply('<?php echo $this->_tpl_vars['reply']['id']; ?>
')<?php else: ?>doDeleteTicket()<?php endif; ?>" class="btn-small btn-danger" />
                        <?php endif; ?> </div>
          <div class="editbtns<?php if ($this->_tpl_vars['reply']['id']): ?>r<?php echo $this->_tpl_vars['reply']['id']; ?>
<?php else: ?>t<?php echo $this->_tpl_vars['ticketid']; ?>
<?php endif; ?>" style="display:none">
                        <input type="button" value="<?php echo $this->_tpl_vars['_ADMINLANG']['global']['save']; ?>
" onclick="editTicketSave('<?php if ($this->_tpl_vars['reply']['id']): ?>r<?php echo $this->_tpl_vars['reply']['id']; ?>
<?php else: ?>t<?php echo $this->_tpl_vars['ticketid']; ?>
<?php endif; ?>')" class="btn-small btn-success" />
                        <input type="button" value="<?php echo $this->_tpl_vars['_ADMINLANG']['global']['cancel']; ?>
" onclick="editTicketCancel('<?php if ($this->_tpl_vars['reply']['id']): ?>r<?php echo $this->_tpl_vars['reply']['id']; ?>
<?php else: ?>t<?php echo $this->_tpl_vars['ticketid']; ?>
<?php endif; ?>')" class="btn-small btn-inverse" />
                      </div>
        </div>
                  </div>
      <div class="rightcol">
                    <div class="quoteicon"><a href="#" onClick="quoteTicket('<?php if (! $this->_tpl_vars['reply']['id']): ?><?php echo $this->_tpl_vars['ticketid']; ?>
<?php endif; ?>', '<?php if ($this->_tpl_vars['reply']['id']): ?><?php echo $this->_tpl_vars['reply']['id']; ?>
<?php endif; ?>')"><img src="images/icons/quote.png" border="0" /></a><?php if ($this->_tpl_vars['reply']['id']): ?>
          <input type="checkbox" name="rids[]" value="<?php echo $this->_tpl_vars['reply']['id']; ?>
" />
          <?php endif; ?></div>
                    <div class="postedon">Posted <?php if ($this->_tpl_vars['reply']['friendlydate']): ?>on <?php echo $this->_tpl_vars['reply']['friendlydate']; ?>
<?php else: ?>today<?php endif; ?> at <?php echo $this->_tpl_vars['reply']['friendlytime']; ?>
</div>
                    <div class="msgwrap" id="content<?php if ($this->_tpl_vars['reply']['id']): ?>r<?php echo $this->_tpl_vars['reply']['id']; ?>
<?php else: ?>t<?php echo $this->_tpl_vars['ticketid']; ?>
<?php endif; ?>">
          <div class="message"> <?php echo $this->_tpl_vars['reply']['message']; ?>
 </div>
          <?php if ($this->_tpl_vars['reply']['numattachments']): ?> <br />
          <strong><?php echo $this->_tpl_vars['_ADMINLANG']['support']['attachments']; ?>
</strong> <br />
          <br />
          <?php $_from = $this->_tpl_vars['reply']['attachments']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['attachment']):
?>
          <?php if ($this->_tpl_vars['thumbnails']): ?>
          <div class="ticketattachmentcontainer"> <a href="../<?php echo $this->_tpl_vars['attachment']['dllink']; ?>
"><img src="../includes/thumbnail.php?<?php if ($this->_tpl_vars['reply']['id']): ?>rid=<?php echo $this->_tpl_vars['reply']['id']; ?>
<?php else: ?>tid=<?php echo $this->_tpl_vars['ticketid']; ?>
<?php endif; ?>&i=<?php echo $this->_tpl_vars['num']; ?>
" class="ticketattachmentthumb" /><br />
            <img src="images/icons/attachment.png" align="top" /> <?php echo $this->_tpl_vars['attachment']['filename']; ?>
</a><br />
                        <small><a href="<?php echo $this->_tpl_vars['attachment']['deletelink']; ?>
" onclick="return confirm('<?php echo ((is_array($_tmp=$this->_tpl_vars['_ADMINLANG']['support']['delattachment'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
')" style="color:#cc0000"><?php echo $this->_tpl_vars['_ADMINLANG']['support']['remove']; ?>
</a></small> </div>
          <?php else: ?> <a href="../<?php echo $this->_tpl_vars['attachment']['dllink']; ?>
"><img src="images/icons/attachment.png" align="absmiddle" /> <?php echo $this->_tpl_vars['attachment']['filename']; ?>
</a> <small><a href="<?php echo $this->_tpl_vars['attachment']['deletelink']; ?>
" onclick="return confirm('<?php echo ((is_array($_tmp=$this->_tpl_vars['_ADMINLANG']['support']['delattachment'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
')" style="color:#cc0000"><?php echo $this->_tpl_vars['_ADMINLANG']['support']['remove']; ?>
</a></small><br />
          <?php endif; ?>
          <?php endforeach; endif; unset($_from); ?>
          <div class="clear"></div>
          <?php endif; ?> </div>
                  </div>
      <div class="clear"></div>
    </div>
                <?php endforeach; endif; unset($_from); ?> </div>
  <p align="center"><a href="supportticketsprint.php?id=<?php echo $this->_tpl_vars['ticketid']; ?>
" target="_blank"><?php echo $this->_tpl_vars['_ADMINLANG']['support']['viewprintable']; ?>
</a><?php if ($this->_tpl_vars['repliescount'] > 1): ?><span style="float:right;">
    <input type="button" value="<?php echo $this->_tpl_vars['_ADMINLANG']['support']['splitticketdialogbutton']; ?>
" onclick="showDialog('splitticket')" class="btn-small" />
    </span><?php endif; ?></p>
  <?php echo $this->_tpl_vars['splitticketdialog']; ?>

  <input type="hidden" name="splitdeptid" id="splitdeptid" />
  <input type="hidden" name="splitsubject" id="splitsubject" />
  <input type="hidden" name="splitpriority" id="splitpriority" />
  <input type="hidden" name="splitnotifyclient" id="splitnotifyclient" />
</form>