<?php /* Smarty version 2.6.28, created on 2016-10-07 11:44:04
         compiled from ra/homepage.tpl */ ?>
<!-- BEGIN WHMCS -->
<?php if ($this->_tpl_vars['maintenancemode']): ?>
<div class="errorbox" style="font-size:14px;"> <?php echo $this->_tpl_vars['_ADMINLANG']['home']['maintenancemode']; ?>
 </div>
<br />
<?php endif; ?>

<?php echo $this->_tpl_vars['infobox']; ?>


<h3><?php echo $this->_tpl_vars['_ADMINLANG']['global']['welcomeback']; ?>
 <?php echo $this->_tpl_vars['admin_username']; ?>
!</h3>

<?php if ($this->_tpl_vars['viewincometotals']): ?>
<div id="incometotals" style="float:right;position:relative;top:-35px;font-size:18px;"><a href="transactions.php"><img src="images/icons/transactions.png" align="absmiddle" border="0"> <b><?php echo $this->_tpl_vars['_ADMINLANG']['billing']['income']; ?>
</b></a> <img src="images/loading.gif" align="absmiddle" /> <?php echo $this->_tpl_vars['_ADMINLANG']['global']['loading']; ?>
</div>
<?php endif; ?>
<!-- END WHMCS -->

<div class="row">
  <div class="col-lg-3">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-clock-o"></i>Alerts</h3>
      </div>
      <div class="panel-body">
        <div class="row alert-success">
          <div class="col-xs-5"> <i class="fa fa-thumbs-o-up fa-5x"></i> </div>
          <div class="col-xs-5 text-right">
            <p class="alerts-heading">343</p>
            <p class="alerts-text">New Orders</p>
          </div>
        </div>
        <div class="row alert-success">
          <div class="col-xs-5"> <i class="fa fa-thumbs-o-up fa-5x"></i> </div>
          <div class="col-xs-5 text-right">
            <p class="alerts-heading">1256</p>
            <p class="alerts-text">Registrations</p>
          </div>
        </div>
        <div class="row alert-danger">
          <div class="col-xs-5"> <i class="fa fa-thumbs-o-down fa-5x"></i> </div>
          <div class="col-xs-5 text-right">
            <p class="alerts-heading">4</p>
            <p class="alerts-text">Errors</p>
          </div>
        </div>
        <div class="row alert-success">
          <div class="col-xs-5"> <i class="fa fa-thumbs-o-up fa-5x"></i> </div>
          <div class="col-xs-5 text-right">
            <p class="alerts-heading">11</p>
            <p class="alerts-text">Mentions</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-9">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Traffic Estimations for last 30 days</h3>
      </div>
      <div class="panel-body">
        <div id="shieldui-chart1"></div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-4">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Logins per week</h3>
      </div>
      <div class="panel-body">
        <div id="shieldui-chart2"></div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Budget</h3>
      </div>
      <div class="panel-body">
        <div id="shieldui-chart3"></div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Purchases</h3>
      </div>
      <div class="panel-body">
        <div id="shieldui-chart4"></div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> </h3>
      </div>
      Sales personnel Data
      <div class="panel-body">
        <div id="shieldui-grid1"></div>
      </div>
    </div>
  </div>
</div>
<!-- /.row -->
</div>
<!-- /#page-wrapper -->
<div id="page-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <h1 id="buttons" class="page-header">Buttons</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-6">
      <p>
        <button type="button" class="btn btn-default">Default</button>
        <button type="button" class="btn btn-primary">Primary</button>
        <button type="button" class="btn btn-success">Success</button>
        <button type="button" class="btn btn-info">Info</button>
        <button type="button" class="btn btn-warning">Warning</button>
        <button type="button" class="btn btn-danger">Danger</button>
        <button type="button" class="btn btn-link">Link</button>
      </p>
      <p>
        <button type="button" class="btn btn-default disabled">Default</button>
        <button type="button" class="btn btn-primary disabled">Primary</button>
        <button type="button" class="btn btn-success disabled">Success</button>
        <button type="button" class="btn btn-info disabled">Info</button>
        <button type="button" class="btn btn-warning disabled">Warning</button>
        <button type="button" class="btn btn-danger disabled">Danger</button>
        <button type="button" class="btn btn-link disabled">Link</button>
      </p>
      <p>
      <div class="btn-group">
        <button type="button" class="btn btn-default">Default</button>
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
        <ul class="dropdown-menu">
          <li><a href="#">Action</a></li>
          <li><a href="#">Another action</a></li>
          <li><a href="#">Something else here</a></li>
          <li class="divider"></li>
          <li><a href="#">Separated link</a></li>
        </ul>
      </div>
      <!-- /btn-group -->
      <div class="btn-group">
        <button type="button" class="btn btn-primary">Primary</button>
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
        <ul class="dropdown-menu">
          <li><a href="#">Action</a></li>
          <li><a href="#">Another action</a></li>
          <li><a href="#">Something else here</a></li>
          <li class="divider"></li>
          <li><a href="#">Separated link</a></li>
        </ul>
      </div>
      <!-- /btn-group -->
      <div class="btn-group">
        <button type="button" class="btn btn-success">Success</button>
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
        <ul class="dropdown-menu">
          <li><a href="#">Action</a></li>
          <li><a href="#">Another action</a></li>
          <li><a href="#">Something else here</a></li>
          <li class="divider"></li>
          <li><a href="#">Separated link</a></li>
        </ul>
      </div>
      <!-- /btn-group -->
      <div class="btn-group">
        <button type="button" class="btn btn-info">Info</button>
        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
        <ul class="dropdown-menu">
          <li><a href="#">Action</a></li>
          <li><a href="#">Another action</a></li>
          <li><a href="#">Something else here</a></li>
          <li class="divider"></li>
          <li><a href="#">Separated link</a></li>
        </ul>
      </div>
      <!-- /btn-group -->
      <div class="btn-group">
        <button type="button" class="btn btn-warning">Warning</button>
        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
        <ul class="dropdown-menu">
          <li><a href="#">Action</a></li>
          <li><a href="#">Another action</a></li>
          <li><a href="#">Something else here</a></li>
          <li class="divider"></li>
          <li><a href="#">Separated link</a></li>
        </ul>
      </div>
      <!-- /btn-group -->
      </p>
      <p>
        <button type="button" class="btn btn-primary btn-lg">Large button</button>
        <button type="button" class="btn btn-primary">Default button</button>
        <button type="button" class="btn btn-primary btn-sm">Small button</button>
        <button type="button" class="btn btn-primary btn-xs">Mini button</button>
      </p>
    </div>
    <div class="col-lg-6">
      <p>
        <button type="button" class="btn btn-default btn-lg btn-block">Block level button</button>
      </p>
      <p>
      <div class="btn-group btn-group-justified"> <a href="#" class="btn btn-default">Left</a> <a href="#" class="btn btn-default">Right</a> <a href="#" class="btn btn-default">Middle</a> </div>
      </p>
      <p>
      <div class="btn-toolbar">
        <div class="btn-group">
          <button type="button" class="btn btn-default">1</button>
          <button type="button" class="btn btn-default">2</button>
          <button type="button" class="btn btn-default">3</button>
          <button type="button" class="btn btn-default">4</button>
        </div>
        <div class="btn-group">
          <button type="button" class="btn btn-default">5</button>
          <button type="button" class="btn btn-default">6</button>
          <button type="button" class="btn btn-default">7</button>
        </div>
        <div class="btn-group">
          <button type="button" class="btn btn-default">8</button>
          <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"> Dropdown <span class="caret"></span> </button>
            <ul class="dropdown-menu">
              <li><a href="#">Dropdown link</a></li>
              <li><a href="#">Dropdown link</a></li>
              <li><a href="#">Dropdown link</a></li>
            </ul>
          </div>
        </div>
      </div>
      </p>
      <p>
      <div class="btn-group-vertical">
        <button type="button" class="btn btn-default">Button</button>
        <button type="button" class="btn btn-default">Button</button>
        <button type="button" class="btn btn-default">Button</button>
        <button type="button" class="btn btn-default">Button</button>
      </div>
      </p>
    </div>
  </div>
  <!-- /.row -->
  
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <h1 id="navs">Navs</h1>
      </div>
    </div>
  </div>
  <!-- /.row -->
  
  <div class="row">
    <div class="col-lg-4">
      <h2 id="nav-tabs">Tabs</h2>
      <div class="bs-example">
        <ul class="nav nav-tabs" style="margin-bottom: 15px;">
          <li class="active"><a href="#home" data-toggle="tab">Home</a></li>
          <li><a href="#profile" data-toggle="tab">Profile</a></li>
          <li class="disabled"><a>Disabled</a></li>
          <li class="dropdown"> <a class="dropdown-toggle" data-toggle="dropdown" href="#">Dropdown <span class="caret"></span> </a>
            <ul class="dropdown-menu">
              <li><a href="#dropdown1" data-toggle="tab">Action</a></li>
              <li class="divider"></li>
              <li><a href="#dropdown2" data-toggle="tab">Another action</a></li>
            </ul>
          </li>
        </ul>
        <div id="myTabContent" class="tab-content">
          <div class="tab-pane fade active in" id="home">
            <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
          </div>
          <div class="tab-pane fade" id="profile">
            <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit.</p>
          </div>
          <div class="tab-pane fade" id="dropdown1">
            <p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork.</p>
          </div>
          <div class="tab-pane fade" id="dropdown2">
            <p>Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life echo park Austin. Cred vinyl keffiyeh DIY salvia PBR, banh mi before they sold out farm-to-table VHS viral locavore cosby sweater.</p>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <h2 id="nav-pills">Pills</h2>
      <div class="bs-example">
        <ul class="nav nav-pills">
          <li class="active"><a href="#">Home</a></li>
          <li><a href="#">Profile</a></li>
          <li class="disabled"><a href="#">Disabled</a></li>
          <li class="dropdown"> <a class="dropdown-toggle" data-toggle="dropdown" href="#">Dropdown <span class="caret"></span> </a>
            <ul class="dropdown-menu">
              <li><a href="#">Action</a></li>
              <li><a href="#">Another action</a></li>
              <li><a href="#">Something else here</a></li>
              <li class="divider"></li>
              <li><a href="#">Separated link</a></li>
            </ul>
          </li>
        </ul>
      </div>
      <br>
      <div class="bs-example">
        <ul class="nav nav-pills nav-stacked" style="max-width: 300px;">
          <li class="active"><a href="#">Home</a></li>
          <li><a href="#">Profile</a></li>
          <li class="disabled"><a href="#">Disabled</a></li>
          <li class="dropdown"> <a class="dropdown-toggle" data-toggle="dropdown" href="#">Dropdown <span class="caret"></span> </a>
            <ul class="dropdown-menu">
              <li><a href="#">Action</a></li>
              <li><a href="#">Another action</a></li>
              <li><a href="#">Something else here</a></li>
              <li class="divider"></li>
              <li><a href="#">Separated link</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-lg-4">
      <h2 id="nav-breadcrumbs">Breadcrumbs</h2>
      <div class="bs-example">
        <ul class="breadcrumb">
          <li class="active">Home</li>
        </ul>
        <ul class="breadcrumb">
          <li><a href="#">Home</a></li>
          <li class="active">Library</li>
        </ul>
        <ul class="breadcrumb" style="margin-bottom: 5px;">
          <li><a href="#">Home</a></li>
          <li><a href="#">Library</a></li>
          <li class="active">Data</li>
        </ul>
      </div>
    </div>
  </div>
  <!-- /.row -->
  
  <div class="row">
    <div class="col-lg-4">
      <h2 id="pagination">Pagination</h2>
      <div class="bs-example">
        <ul class="pagination">
          <li class="disabled"><a href="#">&laquo;</a></li>
          <li class="active"><a href="#">1</a></li>
          <li><a href="#">2</a></li>
          <li><a href="#">3</a></li>
          <li><a href="#">4</a></li>
          <li><a href="#">5</a></li>
          <li><a href="#">&raquo;</a></li>
        </ul>
        <ul class="pagination pagination-lg">
          <li class="disabled"><a href="#">&laquo;</a></li>
          <li class="active"><a href="#">1</a></li>
          <li><a href="#">2</a></li>
          <li><a href="#">3</a></li>
          <li><a href="#">&raquo;</a></li>
        </ul>
        <ul class="pagination pagination-sm">
          <li class="disabled"><a href="#">&laquo;</a></li>
          <li class="active"><a href="#">1</a></li>
          <li><a href="#">2</a></li>
          <li><a href="#">3</a></li>
          <li><a href="#">4</a></li>
          <li><a href="#">5</a></li>
          <li><a href="#">&raquo;</a></li>
        </ul>
      </div>
    </div>
    <div class="col-lg-4">
      <h2 id="pager">Pager</h2>
      <div class="bs-example">
        <ul class="pager">
          <li><a href="#">Previous</a></li>
          <li><a href="#">Next</a></li>
        </ul>
      </div>
      <div class="bs-example">
        <ul class="pager">
          <li class="previous disabled"><a href="#">&larr; Older</a></li>
          <li class="next"><a href="#">Newer &rarr;</a></li>
        </ul>
      </div>
    </div>
  </div>
  <!-- /.row -->
  
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <h1 id="indicators">Indicators</h1>
      </div>
    </div>
  </div>
  <!-- /.row -->
  
  <div class="row">
    <div class="col-lg-12">
      <h2>Alerts</h2>
      <div class="bs-example">
        <div class="alert alert-dismissable alert-warning">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <h4>Warning!</h4>
          <p>Best check yo self, you're not looking too good. Nulla vitae elit libero, a pharetra augue. Praesent commodo cursus magna, <a href="#" class="alert-link">vel scelerisque nisl consectetur et</a>.</p>
        </div>
      </div>
    </div>
  </div>
  <!-- /.row -->
  
  <div class="row">
    <div class="col-lg-4">
      <div class="alert alert-dismissable alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Oh snap!</strong> <a href="#" class="alert-link">Change a few things up</a> and try submitting again. </div>
    </div>
    <div class="col-lg-4">
      <div class="alert alert-dismissable alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Well done!</strong> You successfully read <a href="#" class="alert-link">this important alert message</a>. </div>
    </div>
    <div class="col-lg-4">
      <div class="alert alert-dismissable alert-info">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Heads up!</strong> This <a href="#" class="alert-link">alert needs your attention</a>, but it's not super important. </div>
    </div>
  </div>
  <!-- /.row -->
  
  <div class="row">
    <div class="col-lg-4">
      <h2>Labels</h2>
      <div class="bs-example" style="margin-bottom: 40px;"> <span class="label label-default">Default</span> <span class="label label-primary">Primary</span> <span class="label label-success">Success</span> <span class="label label-warning">Warning</span> <span class="label label-danger">Danger</span> <span class="label label-info">Info</span> </div>
    </div>
    <div class="col-lg-4">
      <h2>Badges</h2>
      <div class="bs-example">
        <ul class="nav nav-pills">
          <li class="active"><a href="#">Home <span class="badge">42</span></a></li>
          <li><a href="#">Profile <span class="badge"></span></a></li>
          <li><a href="#">Messages <span class="badge">3</span></a></li>
        </ul>
      </div>
    </div>
  </div>
  <!-- /.row -->
  
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <h1 id="progress">Progress bars</h1>
      </div>
      <h3 id="progress-basic">Basic</h3>
      <div class="bs-example">
        <div class="progress">
          <div class="progress-bar" style="width: 60%;"></div>
        </div>
      </div>
      <h3 id="progress-alternatives">Contextual alternatives</h3>
      <div class="bs-example">
        <div class="progress" style="margin-bottom: 9px;">
          <div class="progress-bar progress-bar-info" style="width: 20%"></div>
        </div>
        <div class="progress" style="margin-bottom: 9px;">
          <div class="progress-bar progress-bar-success" style="width: 40%"></div>
        </div>
        <div class="progress" style="margin-bottom: 9px;">
          <div class="progress-bar progress-bar-warning" style="width: 60%"></div>
        </div>
        <div class="progress">
          <div class="progress-bar progress-bar-danger" style="width: 80%"></div>
        </div>
      </div>
      <h3 id="progress-striped">Striped</h3>
      <div class="bs-example">
        <div class="progress progress-striped" style="margin-bottom: 9px;">
          <div class="progress-bar progress-bar-info" style="width: 20%"></div>
        </div>
        <div class="progress progress-striped" style="margin-bottom: 9px;">
          <div class="progress-bar progress-bar-success" style="width: 40%"></div>
        </div>
        <div class="progress progress-striped" style="margin-bottom: 9px;">
          <div class="progress-bar progress-bar-warning" style="width: 60%"></div>
        </div>
        <div class="progress progress-striped">
          <div class="progress-bar progress-bar-danger" style="width: 80%"></div>
        </div>
      </div>
      <h3 id="progress-animated">Animated</h3>
      <div class="bs-example">
        <div class="progress progress-striped active">
          <div class="progress-bar" style="width: 45%"></div>
        </div>
      </div>
      <h3 id="progress-stacked">Stacked</h3>
      <div class="bs-example">
        <div class="progress">
          <div class="progress-bar progress-bar-success" style="width: 35%"></div>
          <div class="progress-bar progress-bar-warning" style="width: 20%"></div>
          <div class="progress-bar progress-bar-danger" style="width: 10%"></div>
        </div>
      </div>
    </div>
  </div>
  <!-- /.row -->
  
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <h1 id="container">Containers</h1>
      </div>
      <div class="bs-example">
        <div class="jumbotron">
          <h1>Jumbotron</h1>
          <p>This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
          <p><a class="btn btn-primary btn-lg">Learn more</a></p>
        </div>
      </div>
    </div>
  </div>
  <!-- /.row -->
  
  <div class="row">
    <div class="col-lg-12">
      <h2>List groups</h2>
    </div>
  </div>
  <!-- /.row -->
  
  <div class="row">
    <div class="col-lg-4">
      <div class="bs-example">
        <ul class="list-group">
          <li class="list-group-item"> <span class="badge">14</span> Cras justo odio </li>
          <li class="list-group-item"> <span class="badge">2</span> Dapibus ac facilisis in </li>
          <li class="list-group-item"> <span class="badge">1</span> Morbi leo risus </li>
        </ul>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="bs-example">
        <div class="list-group"> <a href="#" class="list-group-item active">Cras justo odio </a> <a href="#" class="list-group-item">Dapibus ac facilisis in </a> <a href="#" class="list-group-item">Morbi leo risus </a> </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="bs-example">
        <div class="list-group"> <a href="#" class="list-group-item">
          <h4 class="list-group-item-heading">List group item heading</h4>
          <p class="list-group-item-text">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
          </a> <a href="#" class="list-group-item">
          <h4 class="list-group-item-heading">List group item heading</h4>
          <p class="list-group-item-text">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
          </a> </div>
      </div>
    </div>
  </div>
  <!-- /.row -->
  
  <div class="row">
    <div class="col-lg-12">
      <h2>Panels</h2>
    </div>
  </div>
  <!-- /.row -->
  
  <div class="row">
    <div class="col-lg-4">
      <div class="panel panel-default">
        <div class="panel-body"> Basic panel </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading">Panel heading</div>
        <div class="panel-body"> Panel content </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-body"> Panel content </div>
        <div class="panel-footer">Panel footer</div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Panel primary</h3>
        </div>
        <div class="panel-body"> Panel content </div>
      </div>
      <div class="panel panel-success">
        <div class="panel-heading">
          <h3 class="panel-title">Panel success</h3>
        </div>
        <div class="panel-body"> Panel content </div>
      </div>
      <div class="panel panel-warning">
        <div class="panel-heading">
          <h3 class="panel-title">Panel warning</h3>
        </div>
        <div class="panel-body"> Panel content </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="panel panel-danger">
        <div class="panel-heading">
          <h3 class="panel-title">Panel danger</h3>
        </div>
        <div class="panel-body"> Panel content </div>
      </div>
      <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title">Panel info</h3>
        </div>
        <div class="panel-body"> Panel content </div>
      </div>
    </div>
  </div>
  <!-- /.row -->
  
  <div class="row">
    <div class="col-lg-12">
      <h2>Wells</h2>
    </div>
  </div>
  <!-- /.row -->
  
  <div class="row">
    <div class="col-lg-4">
      <div class="well"> Look, I'm in a well! </div>
    </div>
    <div class="col-lg-4">
      <div class="well well-sm"> Look, I'm in a small well! </div>
    </div>
    <div class="col-lg-4">
      <div class="well well-lg"> Look, I'm in a large well! </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <h1>Typograhy <small>Text and Headers</small></h1>
    </div>
    <div class="col-lg-4">
      <h1>Heading 1 <small>Sub-heading</small></h1>
      <h2>Heading 2 <small>Sub-heading</small></h2>
      <h3>Heading 3 <small>Sub-heading</small></h3>
      <h4>Heading 4 <small>Sub-heading</small></h4>
      <h5>Heading 5 <small>Sub-heading</small></h5>
      <h6>Heading 6 <small>Sub-heading</small></h6>
    </div>
    <div class="col-lg-4">
      <h1>Example Body Copy Text</h1>
      <p class="lead">This is an example of lead body copy.</p>
      <p>This is an example of standard paragraph text. This is an example of <a href="#">link anchor text</a> within body copy.</p>
      <p><small>This is an example of small, fine print text.</small></p>
      <p><strong>This is an example of strong, bold text.</strong></p>
      <p><em>This is an example of emphasized, italic text.</em></p>
      <h1>Alignment Classes</h1>
      <p class="text-left">Left aligned text.</p>
      <p class="text-center">Center aligned text.</p>
      <p class="text-right">Right aligned text.</p>
    </div>
    <div class="col-lg-4">
      <h1>Emphasis Classes</h1>
      <p class="text-muted">This is an example of muted text.</p>
      <p class="text-primary">This is an example of primary text.</p>
      <p class="text-success">This is an example of success text.</p>
      <p class="text-info">This is an example of info text.</p>
      <p class="text-warning">This is an example of warning text.</p>
      <p class="text-danger">This is an example of danger text.</p>
    </div>
  </div>
  <!-- /.row -->
  
  <div class="row">
    <div class="col-lg-4">
      <h1>Abbreviations</h1>
      <p>The abbreviation of the word attribute is <abbr title="attribute">attr</abbr>.</p>
      <p><abbr title="HyperText Markup Language" class="initialism">HTML</abbr> is an abbreviation for a programming language.</p>
      <h1>Addresses</h1>
      <address>
      <strong>Twitter, Inc.</strong><br>
      795 Folsom Ave, Suite 600<br>
      San Francisco, CA 94107<br>
      <abbr title="Phone">P:</abbr> (123) 456-7890
      </address>
      <address>
      <strong>Full Name</strong><br>
      <a href="mailto:#">first.last@example.com</a>
      </address>
    </div>
    <div class="col-lg-4">
      <h1>Blockquotes</h1>
      <h2>Default Blockquote</h2>
      <blockquote>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
      </blockquote>
      <h2>Blockquote with Citation</h2>
      <blockquote>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
        <small>Someone famous in <cite title="Source Title">Source Title</cite></small> </blockquote>
      <h2>Right Aligned Blockquote</h2>
      <blockquote class="pull-right">
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
      </blockquote>
    </div>
    <div class="col-lg-4">
      <h1>Lists</h1>
      <h2>Unordered List</h2>
      <ul>
        <li>List Item</li>
        <li>List Item</li>
        <ul>
          <li>List Item</li>
          <li>List Item</li>
          <li>List Item</li>
        </ul>
        <li>List Item</li>
      </ul>
      <h2>Ordered List</h2>
      <ol>
        <li>List Item</li>
        <li>List Item</li>
        <li>List Item</li>
      </ol>
      <h2>Unstyled List</h2>
      <ul class="list-unstyled">
        <li>List Item</li>
        <li>List Item</li>
        <li>List Item</li>
      </ul>
      <h2>Inline List</h2>
      <ul class="list-inline">
        <li>List Item</li>
        <li>List Item</li>
        <li>List Item</li>
      </ul>
    </div>
  </div>
  <!-- /.row -->
  
  <div class="row">
    <div class="col-lg-4">
      <h1>Descriptions</h1>
      <dl>
        <dt>Standard Description List</dt>
        <dd>Description Text</dd>
        <dt>Description List Title</dt>
        <dd>Description List Text</dd>
      </dl>
      <dl class="dl-horizontal">
        <dt>Horizontal Description List</dt>
        <dd>Description Text</dd>
        <dt>Description List Title</dt>
        <dd>Description List Text</dd>
      </dl>
    </div>
    <div class="col-lg-4">
      <h1>Code</h1>
      <p>This is an example of an inline code element within body copy. Wrap inline code within a <code>&lt;code&gt;...&lt;/code&gt;</code> tag.</p>
      <pre>This is an example of preformatted text.</pre>
    </div>
  </div>
  <!-- /.row -->
  
  <div class="row">
    <div class="col-lg-12 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-12 </div>
      </div>
    </div>
  </div>
  <!-- /.row -->
  
  <div class="row">
    <div class="col-lg-6 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-6 </div>
      </div>
    </div>
    <div class="col-lg-6 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-6 </div>
      </div>
    </div>
  </div>
  <!-- /.row -->
  
  <div class="row">
    <div class="col-lg-4 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-4 </div>
      </div>
    </div>
    <div class="col-lg-4 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-4 </div>
      </div>
    </div>
    <div class="col-lg-4 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-4 </div>
      </div>
    </div>
  </div>
  <!-- /.row -->
  
  <div class="row">
    <div class="col-lg-3 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-3 </div>
      </div>
    </div>
    <div class="col-lg-3 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-3 </div>
      </div>
    </div>
    <div class="col-lg-3 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-3 </div>
      </div>
    </div>
    <div class="col-lg-3 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-3 </div>
      </div>
    </div>
  </div>
  <!-- /.row -->
  
  <div class="row">
    <div class="col-lg-2 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-2 </div>
      </div>
    </div>
    <div class="col-lg-2 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-2 </div>
      </div>
    </div>
    <div class="col-lg-2 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-2 </div>
      </div>
    </div>
    <div class="col-lg-2 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-2 </div>
      </div>
    </div>
    <div class="col-lg-2 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-2 </div>
      </div>
    </div>
    <div class="col-lg-2 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-2 </div>
      </div>
    </div>
  </div>
  <!-- /.row -->
  
  <div class="row">
    <div class="col-lg-1 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-1 </div>
      </div>
    </div>
    <div class="col-lg-1 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-1 </div>
      </div>
    </div>
    <div class="col-lg-1 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-1 </div>
      </div>
    </div>
    <div class="col-lg-1 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-1 </div>
      </div>
    </div>
    <div class="col-lg-1 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-1 </div>
      </div>
    </div>
    <div class="col-lg-1 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-1 </div>
      </div>
    </div>
    <div class="col-lg-1 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-1 </div>
      </div>
    </div>
    <div class="col-lg-1 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-1 </div>
      </div>
    </div>
    <div class="col-lg-1 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-1 </div>
      </div>
    </div>
    <div class="col-lg-1 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-1 </div>
      </div>
    </div>
    <div class="col-lg-1 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-1 </div>
      </div>
    </div>
    <div class="col-lg-1 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-1 </div>
      </div>
    </div>
  </div>
  <!-- /.row -->
  
  <div class="row">
    <div class="col-lg-8 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-8 </div>
      </div>
    </div>
    <div class="col-lg-4 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-4 </div>
      </div>
    </div>
  </div>
  <!-- /.row -->
  
  <div class="row">
    <div class="col-lg-3 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-3 </div>
      </div>
    </div>
    <div class="col-lg-6 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-6 </div>
      </div>
    </div>
    <div class="col-lg-3 text-center">
      <div class="panel panel-default">
        <div class="panel-body"> .col-lg-3 </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-6">
      <form role="form">
        <div class="form-group">
          <label>Text Input</label>
          <input class="form-control">
          <p class="help-block">Example block-level help text here.</p>
        </div>
        <div class="form-group">
          <label>Text Input with Placeholder</label>
          <input class="form-control" placeholder="Enter text">
        </div>
        <div class="form-group">
          <label>Static Control</label>
          <p class="form-control-static">email@example.com</p>
        </div>
        <div class="form-group">
          <label>File input</label>
          <input type="file">
        </div>
        <div class="form-group">
          <label>Text area</label>
          <textarea class="form-control" rows="3"></textarea>
        </div>
        <div class="form-group">
          <label>Checkboxes</label>
          <div class="checkbox">
            <label>
              <input type="checkbox" value="">
              Checkbox  1 </label>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" value="">
              Checkbox  2 </label>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" value="">
              Checkbox  3 </label>
          </div>
        </div>
        <div class="form-group">
          <label>Inline Checkboxes</label>
          <label class="checkbox-inline">
            <input type="checkbox">
            1 </label>
          <label class="checkbox-inline">
            <input type="checkbox">
            2 </label>
          <label class="checkbox-inline">
            <input type="checkbox">
            3 </label>
        </div>
        <div class="form-group">
          <label>Radio Buttons</label>
          <div class="radio">
            <label>
              <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
              Radio  1 </label>
          </div>
          <div class="radio">
            <label>
              <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
              Radio  2 </label>
          </div>
          <div class="radio">
            <label>
              <input type="radio" name="optionsRadios" id="optionsRadios3" value="option3">
              Radio  3 </label>
          </div>
        </div>
        <div class="form-group">
          <label>Inline Radio Buttons</label>
          <label class="radio-inline">
            <input type="radio" name="optionsRadiosInline" id="optionsRadiosInline1" value="option1" checked>
            1 </label>
          <label class="radio-inline">
            <input type="radio" name="optionsRadiosInline" id="optionsRadiosInline2" value="option2">
            2 </label>
          <label class="radio-inline">
            <input type="radio" name="optionsRadiosInline" id="optionsRadiosInline3" value="option3">
            3 </label>
        </div>
        <div class="form-group">
          <label>Selects</label>
          <select class="form-control">
            <option>1</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
            <option>5</option>
          </select>
        </div>
        <div class="form-group">
          <label>Multiple Selects</label>
          <select multiple class="form-control">
            <option>1</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
            <option>5</option>
          </select>
        </div>
        <button type="submit" class="btn btn-default">Submit Button</button>
        <button type="reset" class="btn btn-default">Reset Button</button>
      </form>
    </div>
    <div class="col-lg-6">
      <h1>Disabled Form States</h1>
      <form role="form">
        <fieldset disabled>
          <div class="form-group">
            <label for="disabledSelect">Disabled input</label>
            <input class="form-control" id="disabledInput" type="text" placeholder="Disabled input" disabled>
          </div>
          <div class="form-group">
            <label for="disabledSelect">Disabled select menu</label>
            <select id="disabledSelect" class="form-control">
              <option>Disabled select</option>
            </select>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox">
              Disabled Checkbox </label>
          </div>
          <button type="submit" class="btn btn-primary">Disabled Button</button>
        </fieldset>
      </form>
      <h1>Form Validation</h1>
      <form role="form">
        <div class="form-group has-success">
          <label class="control-label" for="inputSuccess">Input with success</label>
          <input type="text" class="form-control" id="inputSuccess">
        </div>
        <div class="form-group has-warning">
          <label class="control-label" for="inputWarning">Input with warning</label>
          <input type="text" class="form-control" id="inputWarning">
        </div>
        <div class="form-group has-error">
          <label class="control-label" for="inputError">Input with error</label>
          <input type="text" class="form-control" id="inputError">
        </div>
      </form>
      <h1>Input Groups</h1>
      <form role="form">
        <div class="form-group input-group"> <span class="input-group-addon"> </span>
          <input type="text" class="form-control" placeholder="Username">
        </div>
        <div class="form-group input-group">
          <input type="text" class="form-control">
          <span class="input-group-addon">.00</span> </div>
        <div class="form-group input-group"> <span class="input-group-addon"><i class="fa fa-eur"></i></span>
          <input type="text" class="form-control" placeholder="Font Awesome Icon">
        </div>
        <div class="form-group input-group"> <span class="input-group-addon">$</span>
          <input type="text" class="form-control">
          <span class="input-group-addon">.00</span> </div>
        <div class="form-group input-group">
          <input type="text" class="form-control">
          <span class="input-group-btn">
          <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
          </span> </div>
      </form>
    </div>
  </div>
</div>
</div>