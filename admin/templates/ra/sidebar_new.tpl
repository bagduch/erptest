<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,300italic,700&subset=latin,cyrillic-ext,latin-ext,cyrillic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="templates/{$template}/css/jquery.multilevelpushmenu.css">

<div id="menu">
    <div class="user-panel">
        <div class="pull-left image">
            <img src="templates/{$template}/dist/img/avatar.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
            <p>{$adminsonline}</p>
            <a href="#"><i class="fa fa-circle text-success" style="color: #33ff99;"></i> Online</a>
        </div>

    </div>

</div>
{literal}
    <script type="text/javascript">
        var arrMenu = [
            {
                title: 'All Categories',
                icon: 'fa fa-reorder',
                items: [
                    {
                        name: "Home",
                        icon: "fa fa-home",
                        link: "index.php"
                    },
                    {
                        name: 'Customers',
                        icon: 'fa fa-users',
                        link: '#',
                        items: [
                            {
                                title: "Customers",
                                icon: 'fa fa-users',
                                items: [
                                    {
                                        name: "View Customers",
                                        icon: "",
                                        link: "clients.php"
                                    },
                                    {
                                        name: "Add New Client",
                                        icon: "",
                                        link: "clientsadd.php"
                                    },
                                   
                                    {
                                        name: "Cancellation Requests",
                                        icon: "",
                                        link: "cancelrequests.php"
                                    },
                                    {
                                        name: "Manage Affiliates",
                                        icon: "",
                                        link: "affiliates.php"
                                    }

                                ]
                            }
                        ]
                    },
                    {
                        name: 'Orders',
                        icon: 'fa fa-shopping-cart',
                        link: '#',
                        items: [
                            {
                                title: "Orders",
                                icon: 'fa fa-shopping-cart',
                                items: [
                                    {
                                        name: 'List Orders',
                                        icon: '',
                                        link: 'orders.php',
                                    },
                                    {
                                        name: 'Pending Orders',
                                        icon: '',
                                        link: 'orders.php?status=Pending',
                                    },
                                    {
                                        name: 'Active Orders',
                                        icon: '',
                                        link: 'orders.php?status=Active',
                                    },
                                    {
                                        name: 'Cancelled Orders',
                                        icon: '',
                                        link: 'orders.php?status=Cancelled',
                                    },
                                    {
                                        name: 'Add New Order',
                                        icon: '',
                                        link: 'ordersadd.php',
                                    }
                                ]
                            }
                        ]

                    },
                  
                    {
                        name: 'Billing',
                        icon: 'fa fa-files-o',
                        link: '#',
                        items: [
                            {
                                title: "Billing",
                                icon: 'fa fa-files-o',
                                items: [
                                    {
                                        name: 'Transactions',
                                        icon: '',
                                        link: 'transactions.php',
                                    },
                                    {
                                        name: 'Gateway Logs',
                                        icon: '',
                                        link: 'gatewaylog.php',
                                    },
                                    {
                                        name: 'All Invoices',
                                        icon: '',
                                        link: 'invoices.php',
                                    },
                                    {
                                        name: 'Overdue',
                                        icon: '',
                                        link: 'invoices.php?status=Overdue',
                                    },
                                    {
                                        name: 'Refunded',
                                        icon: '',
                                        link: 'invoices.php?status=Refunded',
                                    }, {
                                        name: 'Collections',
                                        icon: '',
                                        link: 'invoices.php?status=Collections',
                                    }
                                ]
                            }
                        ]
                    }, {
                        name: 'Support',
                        icon: 'fa fa-envelope-o',
                        link: '#',
                        items: [
                            {
                                title: "Support",
                                icon: 'fa fa-envelope-o',
                                items: [
                                    {
                                        name: 'Support Overview',
                                        icon: '',
                                        link: 'supportcenter.php',
                                    },
                                    {
                                        name: 'View Tickets',
                                        icon: '',
                                        link: 'supporttickets.php?action=list',
                                    },
                                    {
                                        name: 'Open Ticket',
                                        icon: '',
                                        link: 'supporttickets.php?action=open',
                                    },
                                    {
                                        name: 'Predefined Replies',
                                        icon: '',
                                        link: 'supportticketpredefinedreplies.php',
                                    }
                                ]
                            }
                        ]
                    }, {
                        name: 'Reports',
                        icon: 'fa fa-line-chart',
                        link: '#',
                        items: [
                            {
                                title: "Reports",
                                icon: 'fa fa-line-chart',
                                items: [
                                    {
                                        name: 'All Reports',
                                        icon: '',
                                        link: 'reports.php',
                                    },
                                    {
                                        name: 'GST Calculator',
                                        icon: '',
                                        link: 'reports.php?report=sales_tax_liability',
                                    },
                                    {
                                        name: 'Annual Income Report',
                                        icon: '',
                                        link: 'reports.php?report=annual_income_report',
                                    },
                                    {
                                        name: 'Signup Report',
                                        icon: '',
                                        link: 'reports.php?report=new_customers',
                                    }
                                ]
                            }
                        ]
                    }, {
                        name: 'Utilities',
                        icon: 'fa fa-wrench',
                        link: '#',
                        items: [
                            {
                                title: "Utilities",
                                icon: 'fa fa-wrench',
                                items: [
                                    {
                                        name: 'Announcements',
                                        icon: '',
                                        link: 'supportannouncements.php',
                                    },
                                    {
                                        name: 'Knowledgebase',
                                        icon: '',
                                        link: 'supportkb.php',
                                    },
                                    {
                                        name: 'Network Notices',
                                        icon: '',
                                        link: 'networkissues.php',
                                    },
                                    {
                                        name: 'Activity Log',
                                        icon: '',
                                        link: 'systemactivitylog.php',
                                    },
                                    {
                                        name: 'Admin Log',
                                        icon: '',
                                        link: 'systemadminlog.php',
                                    },
                                    {
                                        name: 'Module/API Log',
                                        icon: '',
                                        link: 'systemmodulelog.php',
                                    }
                                    ,
                                    {
                                        name: 'Email Message Log',
                                        icon: '',
                                        link: 'systememaillog.php',
                                    }
                                    ,
                                    {
                                        name: 'Ticket Mail Import Log',
                                        icon: '',
                                        link: 'systemmailimportlog.php',
                                    }

                                ]
                            }
                        ]
                    }, {
                        name: 'System',
                        icon: 'fa fa-cogs',
                        link: '#',
                        items: [
                            {
                                title: "System",
                                icon: 'fa fa-cogs',
                                items: [
                                    {
                                        name: 'General Settings',
                                        icon: '',
                                        link: 'configgeneral.php',
                                    },
                                    {
                                        name: 'Automation Settings',
                                        icon: '',
                                        link: 'configauto.php',
                                    },
                                     {
                                        name: "Client Groups",
                                        icon: "",
                                        link: "configclientgroups.php"
                                    },
                                    {
                                        name: "Client Fields",
                                        icon: "",
                                        link: "clientfields.php"
                                    },
                                    {
                                        name: 'Email Templates',
                                        icon: '',
                                        link: 'configemailtemplates.php',
                                    },
                                    {
                                        name: 'Addon Modules',
                                        icon: '',
                                        link: 'configaddonmods.php',
                                    },
                                    {
                                        name: 'Currencies',
                                        icon: '',
                                        link: 'configcurrencies.php',
                                    },
                                    {
                                        name: 'Payment Gateways',
                                        icon: '',
                                        link: 'configgateways.php',
                                    },
                                    {
                                        name: 'Tax Rules',
                                        icon: '',
                                        link: 'configtax.php',
                                    },
                                    {
                                        name: 'Promotions',
                                        icon: '',
                                        link: 'configpromotions.php',
                                    },

                                      {
                        name: 'Services',
                        icon: 'fa fa-files-o',
                        link: '#',
                        items: [
                            {
                                title: "Services",
                                icon: 'fa fa-files-o',
                                items: [
                                    {
                                        name: 'Services',
                                        icon: '',
                                        link: 'configservices.php',
                                    },
                                         {
                                        name: 'Product',
                                        icon: '',
                                        link: 'configproducts.php',
                                    },
                                    {
                                        name: 'Create Service Group',
                                        icon: '',
                                        link: 'configservices.php?action=creategroup',
                                    },
                                    {
                                        name: 'Custom Fields',
                                        icon: '',
                                        link: 'configcustomfieldsgroup.php',
                                    },
                                    {
                                        name: 'Service Products',
                                        icon: '',
                                        link: 'configaddons.php',
                                    }
                                ]
                            }
                        ]
                    },
                                    {
                                        name: 'Staff Management',
                                        icon: 'fa fa-user-circle-o',
                                        link: '#',
                                        items: [
                                            {
                                                title: "Staff Management",
                                                icon: 'fa fa-user-circle-o',
                                                items: [
                                                    {
                                                        name: "Administrator Users",
                                                        icon: '',
                                                        link: 'configadmins.php',
                                                    },
                                                    {
                                                        name: "Administrator Roles",
                                                        icon: '',
                                                        link: 'configadminroles.php',
                                                    },
                                                   
                                                ]
                                            }
                                        ]
                                    }

                                ]
                            }
                        ]
                    }
                ]
            }
        ];

    </script>
{/literal}