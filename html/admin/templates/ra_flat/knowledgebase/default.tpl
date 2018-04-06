<div class="card">


    <div class="col-md-12">

        <div class="header">

        </div>
        <div class="content">
            <div class="row">
                <div class="left-vertical-tabs">
                    <ul class="nav nav-stacked" role="tablist">
                        <li class="active">
                            <a href="#tab1" data-toggle="tab">Add Category</a>
                        </li>
                        <li>
                            <a href="#tab2" data-toggle="tab">Add Article</a>
                        </li>
                    </ul>
                </div>
                <div class="right-text-tabs">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                            <form class="col-md-6" method="post" action="/admin/supportkb.php?catid=0&amp;addcategory=true">
                                <input type="hidden" name="token" value="fce9572b09d3139bb9446656d52693ca9e54ba47">
                                <div class="form-group">
                                    <label>Category Name</label>
                                    <input class="form-control" type="text" name="catname" size="40">
                                </div>
                                <div class="form-group">
                                    <label class="checkbox checked">
                                        <span class="icons"><span class="first-icon fa fa-square"></span><span class="second-icon fa fa-check-square "></span></span>
                                        <input type="checkbox" name="hidden">
                                        Tick to Hide
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <input class="form-control" type="text" name="description" size="100">
                                </div>
                                <div align="center"><input type="submit" value="Add Category" class="btn btn-default"></div>
                            </form>
                        </div>
                        <div class="tab-pane" id="tab2">
                            You cannot add an article to the top level category
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <p>You are here: <a href="/admin/supportkb.php">Knowledgebase Home</a> </p>
        <p><b>Categories</b></p>

        <table width="100%"><tbody><tr>
                    <td width="33%"><img src="../images/folder.gif" align="absmiddle"> <a href="/admin/supportkb.php?catid=13"><b>dsadsa</b></a> (0) <a href="/admin/supportkb.php?action=editcat&amp;id=13"><img src="images/edit.gif" align="absmiddle" border="0" alt="Edit"></a> <a href="#" onclick="doDeleteCat(13)"><img src="images/delete.gif" align="absmiddle" border="0" alt="Delete"></a><br>dasdasdas</td><td width="33%"><img src="../images/folder.gif" align="absmiddle"> <a href="/admin/supportkb.php?catid=11"><b>test</b></a> (0) <a href="/admin/supportkb.php?action=editcat&amp;id=11"><img src="images/edit.gif" align="absmiddle" border="0" alt="Edit"></a> <a href="#" onclick="doDeleteCat(11)"><img src="images/delete.gif" align="absmiddle" border="0" alt="Delete"></a><br>test</td></tr></tbody></table>
        <p><b>No Articles Found</b></p>
    </div>
    <div style="clear:both"></div>
</div>