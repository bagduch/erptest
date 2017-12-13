<div>
    <ul class="nav nav-tabs account">

        <li {if $clientareaaction eq "addservices"}class="active"{/if}><a href="myorder.php"><i class="fa fa-cart-plus"></i>Place an Order</a></li>
        
        <li {if $clientareaaction eq "services"}class="active"{/if}><a href="clientarea.php?action=services"><i class="fa fa-exchange"></i>Active Connections</a></li>

        <li {if $clientareaaction eq "product"}class="active"{/if}><a href="clientarea.php?action=product"><i class="fa fa-plus-circle"></i>Add-Ons</a></li>

    </ul>
</div>
<div class="clear"></div>