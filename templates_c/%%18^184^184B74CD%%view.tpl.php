<?php /* Smarty version 2.6.28, created on 2016-10-10 15:49:11
         compiled from ra/clientsservices/view.tpl */ ?>

<div style="float:left;width:100%;">
    <div id="servicecontent">
        <?php echo $this->_tpl_vars['content']; ?>

        <table>
            <tr>
                <td>
                    <form method="get" action="/admin/clientsservices.php" name="frm2" id="frm2">
                        <input type="hidden" name="userid" value="2">&nbsp;&nbsp;&nbsp; Products: 

                        <?php if ($this->_tpl_vars['servicesarr']): ?>
                            <select name="id" size="1" onchange="submit()">
                                <?php $_from = $this->_tpl_vars['servicesarr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['serviceid'] => $this->_tpl_vars['row']):
?>
                                    <option value="<?php echo $this->_tpl_vars['serviceid']; ?>
" <?php if ($this->_tpl_vars['id'] == $this->_tpl_vars['serviceid']): ?>Selected<?php endif; ?> style="background-color:<?php echo $this->_tpl_vars['row'][0]; ?>
"><?php echo $this->_tpl_vars['row'][1]; ?>
</option>
                                <?php endforeach; endif; unset($_from); ?>
                            </select> 
                        <?php endif; ?>
                        <input type="submit" value="Go" class="btn btn-success">
                    </form>
                </td>

                <td align="right">

                    <input type="button" value="Upgrade/Downgrade" class="btn" onclick="window.open('clientsupgrade.php?id=29', '', 'width=750,height=350,scrollbars=yes')">
                    <input type="button" value="Move Product/Service" class="btn" onclick="window.open('clientsmove.php?type=hosting&amp;id=29', 'movewindow', 'width=500,height=300,top=100,left=100,scrollbars=yes')"> &nbsp;&nbsp;&nbsp;

                </td>
            </tr>
            </tbody>
        </table>

        <div id="modcmdresult" style="display:none;"></div>
        <img src="images/spacer.gif" height="10" width="1"><br>
        <div class="contentbox">
                    </div>
        <br>
        <form method="post" action="?userid=<?php echo $this->_tpl_vars['userid']; ?>
&amp;id=<?php echo $this->_tpl_vars['id']; ?>
<?php if ($this->_tpl_vars['aid']): ?>&aid=<?php echo $this->_tpl_vars['aid']; ?>
<?php endif; ?>" name="frm1" id="frm1">
            <input type="hidden" name="__fpfrm1" value="1">
            <div class="row" style="padding:15px">
                <div class="panel panel-info">
                    <div class="panel-heading"><h4 class="panel-title">Order Details</h4></div>
                    <div class="panel-body">

                        <div class="col-xs-6">
                            <table>
                                <tr>
                                    <td><label for="orderid">Order #</label></td>
                                    <td><?php echo $this->_tpl_vars['services']['orderid']; ?>
- <a href="orders.php?action=view&id=<?php echo $this->_tpl_vars['services']['orderid']; ?>
" class="btn btn-primary">View Order</a></td>
                                </tr>
                                <tr>
                                    <td><label for="orderid">Service</label></td>
                                    <td>
                                        <select class="form-control">
                                            <?php echo $this->_tpl_vars['servicedrop']; ?>

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="#description">Description (Address)</label></td>
                                    <td><input id="description" name="description" type="text" class="form-control" value="<?php echo $this->_tpl_vars['services']['description']; ?>
"></td>
                                </tr>
                                <tr>
                                    <td><label for="#status">Order Status</label></td>
                                    <td><?php echo $this->_tpl_vars['status']; ?>
</td>
                                </tr>
                                <tr>
                                    <td><label for="#promocode">Promotion Code</label></td>
                                    <td>
                                        <select class="form-control" id="promocode" name="promocode">

                                            <option value="">None</option>
                                            <?php if ($this->_tpl_vars['promo']): ?>
                                                <?php $_from = $this->_tpl_vars['promo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['promoid'] => $this->_tpl_vars['row']):
?>
                                                    <option value="<?php echo $this->_tpl_vars['promoid']; ?>
"><?php echo $this->_tpl_vars['row']; ?>
</option>
                                                <?php endforeach; endif; unset($_from); ?>
                                            <?php endif; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="#firstpaymentamount">Subscription ID</label></td>
                                    <td><input class="form-control"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-xs-6">
                            <table>
                                <tr>
                                    <td><label for="#regdate">Registration Date</label></td>
                                    <td><input id="regdate" name="regdate" type="text" class="form-control" value="<?php echo $this->_tpl_vars['services']['regdate']; ?>
"></td>
                                </tr>
                                <tr>
                                    <td><label for="#firstpaymentamount">First Payment Amount</label></td>
                                    <td><input id="firstpaymentamount" type="text" class="form-control" value="<?php echo $this->_tpl_vars['services']['firstpaymentamount']; ?>
"></td>
                                </tr>
                                <tr>
                                    <td><label for="#firstpaymentamount">Recurring Amount</label></td>
                                    <td><input id="amount" name="amount" type="text" class="form-control" value="<?php echo $this->_tpl_vars['services']['amount']; ?>
"></td>
                                </tr>
                                <tr>
                                    <td><label for="#nextduedate">Next Due Date</label></td>
                                    <td><input id="nextduedate" name="nextduedate" type="text" class="form-control" value="<?php echo $this->_tpl_vars['services']['nextduedate']; ?>
"></td>

                                </tr>
                                <tr>
                                    <td><label for="#amount">Billing Cycle</label></td>
                                    <td><?php echo $this->_tpl_vars['billingcycle']; ?>
</td>
                                </tr>
                                <tr>
                                    <td><label for="#paymentmethod">Payment Method</label></td>
                                    <td><?php echo $this->_tpl_vars['paymentmethod']; ?>
</td>
                                </tr>

                            </table>
                        </div>

                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row" style="padding:15px">
                <div class="panel panel-success">
                    <div class="panel-heading"><h4 class="panel-title">Customer Fields</h4></div>
                    <div class="panel-body">

                        <div class="customefield">
                            <?php if ($this->_tpl_vars['servicefield']): ?>
                                <table>
                                    <?php $_from = $this->_tpl_vars['servicefield']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['fieldid'] => $this->_tpl_vars['fields']):
?>
                                        <?php if ($this->_tpl_vars['fields']['fieldtype'] == 'text'): ?>
                                            <tr>
                                                <td><label for="#custome<?php echo $this->_tpl_vars['fieldid']; ?>
"><?php echo $this->_tpl_vars['fields']['fieldname']; ?>
</label></td>
                                                <td><input class="form-control" id="custome<?php echo $this->_tpl_vars['fieldid']; ?>
" name="customefield[<?php echo $this->_tpl_vars['fieldid']; ?>
]" value="<?php echo $this->_tpl_vars['fields']['value']; ?>
"></td>
                                            </tr>                  
                                        <?php elseif ($this->_tpl_vars['fields']['fieldtype'] == 'date'): ?>
                                            <tr>
                                                <td><label for="#custome<?php echo $this->_tpl_vars['fieldid']; ?>
"><?php echo $this->_tpl_vars['fields']['fieldname']; ?>
</label></td>
                                                <td><input class="form-control datecontroller" id="custome<?php echo $this->_tpl_vars['fieldid']; ?>
" name="customefield[<?php echo $this->_tpl_vars['fieldid']; ?>
]" value="<?php echo $this->_tpl_vars['fields']['value']; ?>
"></td>
                                            </tr>
                                        <?php elseif ($this->_tpl_vars['fields']['fieldtype'] == 'more'): ?>
                                            <?php $_from = $this->_tpl_vars['fields']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['childrenfield']):
?>
                                                <tr>
                                                    <td><label for="#custome<?php echo $this->_tpl_vars['childrenfield']['cfid']; ?>
"><?php echo $this->_tpl_vars['childrenfield']['fieldname']; ?>
</label></td>
                                                    <td><input class="form-control" id="custome<?php echo $this->_tpl_vars['childrenfield']['cfid']; ?>
" name="customefield[<?php echo $this->_tpl_vars['childrenfield']['cfid']; ?>
]" value="<?php echo $this->_tpl_vars['childrenfield']['value']; ?>
"></td>
                                                </tr>
                                            <?php endforeach; endif; unset($_from); ?>
                                        <?php else: ?>
                                        <?php endif; ?>
                                    <?php endforeach; endif; unset($_from); ?>
                                </table>   
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row" style="padding:15px">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h4 class="panel-title">Addon Services/Product  
                            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
                                Add 
                            </button>
                        </h4>
                    </div>
                    <div class="panel-body">
                        <?php if (isset ( $this->_tpl_vars['services']['addon'] )): ?>
                            <table class="datatable" style="width:100%">
                                <tr>
                                    <th>Reg Date</th>
                                    <th>Name</th>
                                    <th>First Payment</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Billing Cycle</th>
                                    <th>Action</th>
                                </tr>
                                <?php $_from = $this->_tpl_vars['services']['addon']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['addons']):
?>
                                    <tr>
                                        <td><?php echo $this->_tpl_vars['addons']['regdate']; ?>
</td>
                                        <td><?php echo $this->_tpl_vars['addons']['name']; ?>
</td>
                                        <td><?php echo $this->_tpl_vars['addons']['firstpaymentamount']; ?>
</td>
                                        <td><?php echo $this->_tpl_vars['addons']['amount']; ?>
</td>
                                        <td><?php echo $this->_tpl_vars['addons']['servicestatus']; ?>
</td>
                                        <td><?php echo $this->_tpl_vars['addons']['billingcycle']; ?>
</td>
                                        <td>
                                            <a href="#" class="btn btn-primary">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <a href="#" onclick="doDeleteAddon(<?php echo $this->_tpl_vars['addons']['id']; ?>
)" class="btn btn-danger">
                                                <i class="fa fa-minus-circle" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; endif; unset($_from); ?>
                            </table>
                        <?php else: ?>
                            <div class="alert alert-danger" role="alert">
                                This addon doen't have any addon

                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Addons</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="#addonname">Addon Name</label>
                                    <select class='form-control' id='addonname' name='addonid'>
                                        <option value="0">Please Choose Addons</option>
                                        <?php $_from = $this->_tpl_vars['addons']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['addon']):
?>
                                            <option value="<?php echo $this->_tpl_vars['addon']['id']; ?>
"><?php echo $this->_tpl_vars['addon']['name']; ?>
</option>
                                        <?php endforeach; endif; unset($_from); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="payment">Payment Method</label>
                                    <?php echo $this->_tpl_vars['paymentmethod']; ?>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary addonaddbutton">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <br>
            <div align="center"><input type="submit" value="Save Changes" class="btn btn-primary"> <input type="reset" value="Cancel Changes" class="btn"><br>
                <a href="#" onclick="showDialog('delete');" style="color:#cc0000"><strong>Delete</strong></a></div></form>

        <br>

        <div class="contentbox">
            <table align="center"><tbody><tr><td>
                            <strong>Send Message</strong>
                        </td><td>
                            <form method="post" action="clientsemails.php?userid=<?php echo $this->_tpl_vars['userid']; ?>
" name="frm3" id="frm3">
                                <input type="hidden" name="__fpfrm3" value="1">
                                <input type="hidden" name="action" value="send">
                                <input type="hidden" name="type" value="product">
                                <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['id']; ?>
">
                                <?php if ($this->_tpl_vars['emaildropdown']): ?>
                                    <select name="messagename">
                                        <?php $_from = $this->_tpl_vars['emaildropdown']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
                                            <option value="<?php echo $this->_tpl_vars['row']; ?>
"><?php echo $this->_tpl_vars['row']; ?>
</option>
                                        <?php endforeach; endif; unset($_from); ?>
                                    </select>
                                <?php endif; ?>
                                <input type="submit" value="Send Message" class="btn">
                            </form>
                        </td>
                        <td>
                            <form method="post" action="clientsemails.php?userid=2" name="frm4" id="frm4">
                                <input type="hidden" name="__fpfrm4" value="1">
                                <input type="hidden" name="action" value="send">
                                <input type="hidden" name="type" value="product">
                                <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['id']; ?>
">
                                <input type="hidden" name="messagename" value="defaultnewacc">
                                <input type="submit" value="Resend Product Welcome Email" class="btn">
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <?php $_from = $this->_tpl_vars['test']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
            <?php echo $this->_tpl_vars['row']; ?>

        <?php endforeach; endif; unset($_from); ?>
        <form method="post" action="whois.php" target="_blank" id="frmWhois">
            <input type="hidden" name="domain" value="">
        </form>
    </div>
</div>
<div class="clear"></div>

<?php echo '
    <script type="text/javascript">

        $(document).ready(function () {
            $(\'.datecontroller\').datepicker({
                format: \'yyyy-mm-dd\',
                startDate: \'+1d\'
            });

            $(".addonaddbutton").click(function () {
                $("#frm1").submit();
            });


        });
        function doDeleteAddon(id) {
            if (confirm("Are you sure you want to delete this addon?")) {
                window.location = \'/admin/clientsservices.php?userid='; ?>
<?php echo $this->_tpl_vars['userid']; ?>
<?php echo '&action=deladdon&aid=\' + id + \'&token='; ?>
<?php echo $this->_tpl_vars['token']; ?>
<?php echo '\';
            }
        }
        function runModuleCommand(cmd, custom) {
            $("#mod" + cmd).dialog("close");

            $("#modcmdbtns").css("filter", "alpha(opacity=20)");
            $("#modcmdbtns").css("-moz-opacity", "0.2");
            $("#modcmdbtns").css("-khtml-opacity", "0.2");
            $("#modcmdbtns").css("opacity", "0.2");
            var position = $("#modcmdbtns").position();

            $("#modcmdworking").css("position", "absolute");
            $("#modcmdworking").css("top", position.top);
            $("#modcmdworking").css("left", position.left);
            $("#modcmdworking").css("padding", "9px 50px 0");
            $("#modcmdworking").fadeIn();

            var reqstr = "userid=1&id=35&modop=" + cmd + "&token=0951db7664024f53758d62b7cb94336b96566473";
            if (custom)
                reqstr += "&ac=" + custom;
            else if (cmd == "suspend")
                reqstr += "&suspreason=" + encodeURIComponent($("#suspreason").val()) + "&suspemail=" + $("#suspemail").is(":checked");

            $.post("clientsservices.php", reqstr,
                    function (data) {
                        if (data.substr(0, 9) == "redirect|") {
                            window.location = data.substr(9);
                        } else {
                            $("#servicecontent").html(data);
                        }
                    });

        }
    
    </script>

'; ?>


