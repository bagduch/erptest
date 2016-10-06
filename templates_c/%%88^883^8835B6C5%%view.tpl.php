<?php /* Smarty version 2.6.28, created on 2016-10-07 10:03:03
         compiled from ra/order/view.tpl */ ?>

        <div class="box box-warning collapsed-box">
            <div class="box-header with-border">
                <h3 class="box-title"> 
                    <button style="font-size: 20px;color:blue" type="button" class="btn btn-box-tool" data-widget="collapse">Search/Filter
                    </button>
                </h3>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div clas="row">
                    <form action="/admin/orders.php" method="post">
                        <div class="col-lg-6">
                            <table class="table" width="100%">
                                <tbody>
                                    <tr>
                                        <td width="15%" class="fieldlabel">Order ID</td>
                                        <td class="fieldarea"><input class="form-control" type="text" name="orderid" size="8" value="<?php echo $this->_tpl_vars['filterdata']['orders']['orderid']; ?>
"></td>

                                    </tr>
                                    <tr>
                                        <td class="fieldlabel">Order #</td>
                                        <td class="fieldarea">
                                            <input type="text" class="form-control" name="ordernum" size="20" value="<?php echo $this->_tpl_vars['filterdata']['orders']['ordernum']; ?>
">
                                        </td>

                                    </tr>
                                    <tr>
                                        <td class="fieldlabel">Date</td>
                                        <td class="fieldarea"><input type="text" name="orderdate" value="<?php echo $this->_tpl_vars['filterdata']['orders']['orderdate']; ?>
" class="form-control datepick"></td>

                                    </tr>
                                    <tr>
                                        <td class="fieldlabel">Amount</td>
                                        <td class="fieldarea"><input class="form-control" type="text" name="amount" value="<?php echo $this->_tpl_vars['filterdata']['orders']['amount']; ?>
" size="10"></td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-6">
                            <table class="table" width="100%">
                                <tbody>
                                    <tr>
                                        <td width="15%" class="fieldlabel">Client</td>
                                        <td class="fieldarea">
                                            <?php echo $this->_tpl_vars['clientdropdown']; ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fieldlabel">Payment Status</td>
                                        <td class="fieldarea">
                                            <select class="form-control" name="paymentstatus">
                                                <option <?php if ($this->_tpl_vars['filterdata']['orders']['paymentstatus'] == ""): ?>selected<?php endif; ?> value="">- Any -</option>
                                                <option <?php if ($this->_tpl_vars['filterdata']['orders']['paymentstatus'] == 'Paid'): ?>selected<?php endif; ?> value="Paid">Paid</option>
                                                <option <?php if ($this->_tpl_vars['filterdata']['orders']['paymentstatus'] == 'Unpaid'): ?>selected<?php endif; ?> value="Unpaid">Unpaid</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fieldlabel">Status</td>
                                        <td class="fieldarea">
                                            <select class="form-control" name="status">
                                                <option <?php if ($this->_tpl_vars['filterdata']['orders']['status'] == ""): ?>selected<?php endif; ?> value="">- Any -</option>
                                                <option <?php if ($this->_tpl_vars['filterdata']['orders']['status'] == 'Pending'): ?>selected<?php endif; ?> value="Pending" style="color:#cc0000">Pending</option>
                                                <option <?php if ($this->_tpl_vars['filterdata']['orders']['status'] == 'Active'): ?>selected<?php endif; ?> value="Active" style="color:#779500">Active</option>
                                                <option <?php if ($this->_tpl_vars['filterdata']['orders']['status'] == 'Cancelled'): ?>selected<?php endif; ?> value="Cancelled" style="color:#888888">Cancelled</option>
                                                <option <?php if ($this->_tpl_vars['filterdata']['orders']['status'] == 'Fraud'): ?>selected<?php endif; ?> value="Fraud" style="color:#000000">Fraud</option>
                                                <option <?php if ($this->_tpl_vars['filterdata']['orders']['status'] == 'Draft'): ?>selected<?php endif; ?> value="Draft" style="color:#f2d342">Draft</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fieldlabel">IP Address</td>
                                        <td class="fieldarea">
                                            <input class="form-control" type="text" name="orderip" value="<?php echo $this->_tpl_vars['filterdata']['orders']['orderip']; ?>
" data-inputmask="'alias': 'ip'" data-mask>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <img src="images/spacer.gif" height="8" width="1"><br>
                        <div align="center"><input type="submit" value="Search" class="btn btn-default"></div>
                    </form>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <?php echo $this->_tpl_vars['table']; ?>


<script type="text/javascript>">
    <?php $_from = $this->_tpl_vars['deletejs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['js']):
?>
            <?php echo $this->_tpl_vars['js']; ?>

    <?php endforeach; endif; unset($_from); ?>
</script>