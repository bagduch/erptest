<?php /* Smarty version 2.6.28, created on 2016-10-07 11:33:51
         compiled from ra/services/view.tpl */ ?>
<p><strong>Options:</strong> <a href="/admin/configservices.php?action=creategroup">Create Group</a> | <a href="/admin/configservices.php?action=create">Create Service</a></p>
<form method="post" action="configpservices.php?action=updatesort">
    <div class="tablebg">
        <table class="datatable" width="100%" border="0" cellspacing="1" cellpadding="3">
            <tbody>
                <tr>
                    <th style="text-align: center"><?php echo $this->_tpl_vars['langs']['servicename']; ?>
</th>
                    <th style="text-align: center"><?php echo $this->_tpl_vars['langs']['type']; ?>
</th>
                    <th style="text-align: center"><?php echo $this->_tpl_vars['langs']['sortorder']; ?>
</th>
                    <th style="text-align: center"><?php echo $this->_tpl_vars['langs']['paytype']; ?>
</th>
                    <th style="text-align: center"><?php echo $this->_tpl_vars['langs']['price']; ?>
</th>
                    <th style="text-align: center"><?php echo $this->_tpl_vars['langs']['autosetup']; ?>
</th>
                    <th style="text-align: center" width="20"></th>
                    <th style="text-align: center" width="20"></th>
                </tr>
                <?php $_from = $this->_tpl_vars['servicegroup']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['servicesg']):
?>
                    <tr>
                        <td colspan="6" style="background-color:#ffffdd;">
                            <div align="left"><b><?php echo $this->_tpl_vars['langs']['groupname']; ?>
:</b> <?php echo $this->_tpl_vars['servicesg']['group']['name']; ?>
 </div>
                        </td>
                        <td style="background-color:#ffffdd;" align="center">
                            <a href="/admin/configservices.php?action=editgroup&amp;ids=<?php echo $this->_tpl_vars['servicesg']['group']['id']; ?>
"><img src="images/edit.gif" width="16" height="16" border="0" alt="Edit"></a>
                        </td>
                        <td style="background-color:#ffffdd;" align="center">
                            <a href="#" onclick="<?php echo $this->_tpl_vars['servicesg']['group']['deletelink']; ?>
">
                                <img src="images/delete.gif" width="16" height="16" border="0" alt="Delete">
                            </a>
                        </td 
                    </tr>
                    <?php $_from = $this->_tpl_vars['servicesg']['service']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sid'] => $this->_tpl_vars['servicedata']):
?>
                        <tr style="text-align:center;">
                            <td><?php echo $this->_tpl_vars['servicedata']['name']; ?>
</td>
                            <td><?php echo $this->_tpl_vars['servicedata']['type']; ?>
</td>
                            <td><input type="text" name="so[<?php echo $this->_tpl_vars['sid']; ?>
]" value="<?php echo $this->_tpl_vars['servicedata']['order']; ?>
" size="5" style="font-size:10px;"></td>
                            <td><?php echo $this->_tpl_vars['servicedata']['paytype']; ?>
</td>
                            <td>-</td>
                            <td><?php echo $this->_tpl_vars['service']['autosetup']; ?>
</td>
                            <td><a href="/admin/configservices.php?action=edit&amp;id=<?php echo $this->_tpl_vars['sid']; ?>
"><img src="images/edit.gif" width="16" height="16" border="0" alt="Edit"></a></td>
                            <td><a href="#" onclick="<?php echo $this->_tpl_vars['servicedata']['deletelink']; ?>
"><img src="images/delete.gif" width="16" height="16" border="0" alt="Delete"></a></td>
                        </tr>
                    <?php endforeach; endif; unset($_from); ?>
                <?php endforeach; endif; unset($_from); ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td><div align="center"><input type="submit" value="Update Sorting" style="font-size:10px;"></div></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
<script type="text/javascript"> 
    <?php echo '
        function doDelete(id) {
            if (confirm("Are you sure you want to delete this product?")) {
                window.location = \'?sub=delete&id=\' + id + \'&token= '; ?>
<?php echo $this->_tpl_vars['csrfToken']; ?>
<?php echo '\';
            }
        }
        function doGroupDelete(id) {
            if (confirm("Are you sure you want to delete this product group?")) {
                window.location = \'?sub=deletegroup&id=\' + id + \'&token={$csrfToken}\';
            }
        }



    '; ?>

</script>