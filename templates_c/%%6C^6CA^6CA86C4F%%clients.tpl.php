<?php /* Smarty version 2.6.28, created on 2016-10-07 12:31:58
         compiled from ra/client/clients.tpl */ ?>


<div class="row">
    <div class="col-xs-12">
        <div class="box box-warning collapsed-box">
            <div class="box-header with-border">
                <h3 class="box-title"> 
                    <button style="font-size: 20px;color:blue" type="button" class="btn btn-box-tool" data-widget="collapse">Search/Filter
                    </button>
                </h3>
                <!-- /.box-tools -->
            </div>
            <div class="box-body">
                <div clas="row">
                    <form action="clients.php" method="post">
                        <table class="form table" width="100%" border="0" cellspacing="2" cellpadding="3">
                            <tbody>
                                <tr>
                                    <td class="fieldlabel"><?php echo $this->_tpl_vars['lang']['clientnamelang']; ?>
</td>
                                    <td class="fieldarea"><input class="form-control" type="text" name="clientname" value="<?php echo $this->_tpl_vars['filterdata']['clientname']; ?>
"></td>
                                    <td class="fieldlabel"><?php echo $this->_tpl_vars['lang']['companynamelang']; ?>
</td>
                                    <td class="fieldarea"><input type="text" class="form-control" name="companyname" value="<?php echo $this->_tpl_vars['filterdata']['companyname']; ?>
"></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel"><?php echo $this->_tpl_vars['lang']['emaillang']; ?>
</td>
                                    <td class="fieldarea">
                                        <input type="text" class="form-control" name="email" value="<?php echo $this->_tpl_vars['filterdata']['email']; ?>
">
                                    </td>
                                    <td class="fieldlabel"><?php echo $this->_tpl_vars['lang']['addresslang']; ?>
</td>
                                    <td class="fieldarea">
                                        <input type="text" class="form-control" name="address" value="<?php echo $this->_tpl_vars['filterdata']['address']; ?>
">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel"><?php echo $this->_tpl_vars['lang']['statuslang']; ?>
</td>
                                    <td class="fieldarea">
                                        <select class="form-control" name="status">
                                            <option  value=""><?php echo $this->_tpl_vars['lang']['anylang']; ?>
</option>
                                            <option <?php if ($this->_tpl_vars['filterdata']['status'] == 'Active'): ?>selected<?php endif; ?> value="<?php echo $this->_tpl_vars['lang']['activelang']; ?>
"><?php echo $this->_tpl_vars['lang']['activelang']; ?>
</option>
                                            <option <?php if ($this->_tpl_vars['filterdata']['status'] == 'Inactive'): ?>selected<?php endif; ?> value="<?php echo $this->_tpl_vars['lang']['inactivelang']; ?>
"><?php echo $this->_tpl_vars['lang']['inactivelang']; ?>
</option>
                                            <option <?php if ($this->_tpl_vars['filterdata']['status'] == 'Closed'): ?>selected<?php endif; ?> value="<?php echo $this->_tpl_vars['lang']['closelang']; ?>
"><?php echo $this->_tpl_vars['lang']['closelang']; ?>
</option>
                                        </select>
                                    </td>
                                    <td class="fieldlabel"><?php echo $this->_tpl_vars['lang']['statelang']; ?>
</td>
                                    <td class="fieldarea"><input type="text" class="form-control" name="state" value="<?php echo $this->_tpl_vars['filterdata']['state']; ?>
"></td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel"><?php echo $this->_tpl_vars['lang']['clientgrouplang']; ?>
</td>
                                    <td class="fieldarea">
                                        <select class="form-control" name="clientgroup">
                                            <option value="">- Any -</option>
                                            <?php $_from = $this->_tpl_vars['clientgroups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['clientgroup']):
?>
                                                <option <?php if ($this->_tpl_vars['filterdata']['clientgroup'] == $this->_tpl_vars['id']): ?>selected<?php endif; ?> value="<?php echo $this->_tpl_vars['id']; ?>
"><?php echo $this->_tpl_vars['clientgroup']; ?>
</option>
                                            <?php endforeach; endif; unset($_from); ?>
                                        </select>
                                    </td>
                                    <td class="fieldlabel"><?php echo $this->_tpl_vars['lang']['phonenumberlang']; ?>
</td>
                                    <td class="fieldarea">
                                        <input type="text" class="form-control" name="phonenumber" value="<?php echo $this->_tpl_vars['filterdata']['phonenumber']; ?>
">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fieldlabel"><?php echo $this->_tpl_vars['lang']['currencylang']; ?>
</td>
                                    <td class="fieldarea">
                                        <select class="form-control" name="currency">
                                            <option value="">- Any -</option>
                                            <?php $_from = $this->_tpl_vars['currencys']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['currency']):
?>
                                                <option <?php if ($this->_tpl_vars['filterdata']['currency'] == $this->_tpl_vars['id']): ?>selected<?php endif; ?> value="<?php echo $this->_tpl_vars['id']; ?>
"><?php echo $this->_tpl_vars['currency']; ?>
</option>
                                            <?php endforeach; endif; unset($_from); ?>
                                        </select>
                                    </td>
                                    <td class="fieldlabel"><?php echo $this->_tpl_vars['lang']['cardlast4lang']; ?>
</td>
                                    <td class="fieldarea">
                                        <input type="text" class="form-control" name="cardlastfour" value="<?php echo $this->_tpl_vars['filterdata']['cardlastfour']; ?>
"></td>
                                </tr>
                            </tbody>
                        </table>
                        <p align="center"><input type="submit" value="Search" class="btn btn-default"></p>
                    </form>
                </div>
            </div>
        </div>




        <form method="post" action="/admin/clients.php?filter=1">
            <div class="tablebg">
                <?php if ($this->_tpl_vars['table']): ?>
                    <?php echo $this->_tpl_vars['table']; ?>

                <?php endif; ?>
            </div>
        </form>

    </div>
</div>
<div class="clear"></div>
