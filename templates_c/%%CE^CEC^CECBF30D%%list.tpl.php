<?php /* Smarty version 2.6.28, created on 2016-10-06 10:34:23
         compiled from ra/tickets/list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'debug', 'ra/tickets/list.tpl', 1, false),array('modifier', 'strlen', 'ra/tickets/list.tpl', 95, false),)), $this); ?>
<?php echo smarty_function_debug(array(), $this);?>

TEMPLATE START
<div id="tab0box" class="tabbox">
  <div id="tab_content">
    <form action="<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
" method="post">
      <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
        <tr>
          <td width="15%" class="fieldlabel"><?php echo $this->_tpl_vars['_ADMINLANG']['fields']['status']; ?>
</td>
          <td class="fieldarea">
            <select name="view" />
              <option value="any"<?php if ($this->_tpl_vars['ticketfilterdata']['view'] == 'any'): ?> selected<?php endif; ?>>
                <?php echo $this->_tpl_vars['_ADMINLANG']['global']['any']; ?>

              </option>
              <option value=""<?php if ($this->_tpl_vars['ticketfilterdata']['view'] == ""): ?> selected<?php endif; ?>>
                <?php echo $this->_tpl_vars['_ADMINLANG']['support']['awaitingreply']; ?>

              </option>
              <option value="flagged"<?php if ($this->_tpl_vars['ticketfilterdata']['view'] == 'flagged'): ?> selected<?php endif; ?>>
                <?php echo $this->_tpl_vars['_ADMINLANG']['support']['flagged']; ?>

              </option>
              <option value="active"<?php if ($this->_tpl_vars['ticketfilterdata']['view'] == 'active'): ?> selected<?php endif; ?>>
                <?php echo $this->_tpl_vars['_ADMINLANG']['support']['allactive']; ?>

              </option>
            <?php $_from = $this->_tpl_vars['ticketstatuses']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ticketstatus']):
?>
              <option value="<?php echo $this->_tpl_vars['ticketstatus']['title']; ?>
"<?php if ($this->_tpl_vars['ticketfilterdata']['view'] == $this->_tpl_vars['ticketstatus']['title']): ?> selected<?php endif; ?>>
                <?php echo $this->_tpl_vars['ticketstatus']['title']; ?>

              </option>
            <?php endforeach; endif; unset($_from); ?>
            </select>
          </td>
          <td width="15%" class="fieldlabel">
            <?php echo $this->_tpl_vars['_ADMINLANG']['fields']['client']; ?>

          </td>
          <td class="fieldarea">
                <input type="text" name="client" value="<?php echo $this->_tpl_vars['ticketfilterdata']['client']; ?>
" size="10" />
          </td>
        </tr>

        <tr>
        <td class="fieldlabel">
          <?php echo $this->_tpl_vars['_ADMINLANG']['support']['department']; ?>

        </td>
        <td>
          <select name="deptid">
            <option value="">
              <?php echo $this->_tpl_vars['_ADMINLANG']['global']['any']; ?>

            </option> 
          <?php $_from = $this->_tpl_vars['ticketdepts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ticketdepartment']):
?>

            <option value="<?php echo $this->_tpl_vars['ticketdepartment']['did']; ?>
"<?php if ($this->_tpl_vars['ticketdepartment']['id'] == $this->_tpl_vars['ticketfilterdata']['deptid']): ?> selected<?php endif; ?>> 
              <?php echo $this->_tpl_vars['ticketdepartment']['name']; ?>

        <?php endforeach; endif; unset($_from); ?>
            </option>
          </select>
        </td>

        <td class="fieldlabel">
          <?php echo $this->_tpl_vars['_ADMINLANG']['support']['ticketid']; ?>

        </td>
        <td class="fieldarea">
          <input type="text" name="ticketid" size="15">
        </td>
        <td class="fieldlabel">
          <?php echo $this->_tpl_vars['_ADMINLANG']['support']['subjectmessages']; ?>

        </td>
        <td class="fieldarea">
          <input type="text" name="subject" size="40" value="<?php echo $this->_tpl_vars['ticketfilterdata']['subject']; ?>
" />
        </td>
        <td class="fieldlabel">
          <?php echo $this->_tpl_vars['_ADMINLANG']['fields']['email']; ?>

        </td>
        <td>
          <input type="text" name="email" size="40" value="<?php echo $this->_tpl_vars['ticketfilterdata']['email']; ?>
" />
        </td>
      </tr>
    </table>
   
    <img src="images/spacer.gif" height="10" width="1"><br>
    <div align="center">
      <input type="submit" value=<?php echo $this->_tpl_vars['_ADMINLANG']['global']['searchfilter']; ?>
" class="button">
    </div>

  </form>

</div>
</div>
<div id="tab1box" class="tabbox">
  <div id="tab_content">
  </div>
</div>
<div id="tab2box" class="tabbox">
  <div id="tab_content">
</div>
</div>
<br />
<?php if (((is_array($_tmp=$this->_tpl_vars['ticketfilterdata']['tag'])) ? $this->_run_mod_handler('strlen', true, $_tmp) : strlen($_tmp)) > 0): ?><h2>Filtering Tickets for Tag <?php echo $this->_tpl_vars['ticketfilterdata']['tag']; ?>
</h2><?php endif; ?>

            
 
            
        
<?php echo $this->_tpl_vars['content']; ?>

<hr />