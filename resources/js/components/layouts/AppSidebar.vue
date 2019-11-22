<template>
    <aside class="main-sidebar" id="sidebar-wrapper">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="http://infyom.com/images/logo/blue_logo_150x150.jpg" class="img-circle"
                         alt="User Image"/>
                </div>
                <div class="pull-left info">
                    <p>{{username()}}</p>
                    <!-- Status -->
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <!-- Sidebar Menu -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header"></li>
                <!-- Optionally, you can add icons to the links -->
                <li><router-link to="/machines"><i class="fa fa-shopping-basket angle-left"></i> <span>Assets</span></router-link></li>
                <li class="active"><router-link to="/suppliers"><i class="fa fa-users"></i> <span>Suppliers</span></router-link></li>
                    <li class="treeview">
                    <router-link to="/job-card"><i class="fa fa-tag"></i> <span>Job Card</span>
                        <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                    </router-link>
                    <ul class="treeview-menu">
                        <li><router-link to="/external-service">External Services</router-link></li>
                        </ul>
                </li>
                <li class="treeview">
                    <router-link to="/checklists"><i class="fa fa-check-square-o"></i> <span>Checklists</span>
                        <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                    </router-link>
                    <ul class="treeview-menu">
                        <li><router-link to="/assign-checklist">Assign Checklists</router-link></li>
                        </ul>
                </li>
                <li><router-link to="/fuel"><i class="fa fa-database"></i> <span>Fuel</span></router-link></li>
                <li class="treeview">
                    <a href="#"><i class="fa fa-bar-chart"></i> <span>Reports</span>
                        <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                    </a>
                    <ul class="treeview-menu">
                         <li><router-link to="/jobcard-report">Job Cards</router-link></li>
                        <li><router-link to="/fuel-report">Fuels</router-link></li>
                        </ul>
                </li>
                <li class="treeview">
                    <a href="#"><i class="fa fa-cogs"></i> <span>Settings</span>
                        <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                    </a>
                    <ul class="treeview-menu">
                        <li style="display: none"><router-link to="/service-provider-types">Service Provider Types</router-link></li>
                        <li><router-link to="/users">Users</router-link></li>
                        <li><router-link to="/fuel-balance">Fuel Balance</router-link></li>
                        <li><router-link to="/track-by">Track By</router-link></li>
                        <li><router-link to="/service-types">Service Types</router-link></li>
                        <li><router-link to="/expense">Expenses</router-link></li>
                        <li><router-link to="/parts">Parts</router-link></li>
                        <li><router-link to="/projects">Projects</router-link></li>
                        <li><router-link to="/customers">Customers</router-link></li>
                        <li><router-link to="/mechanics">Mechanics</router-link></li>
                        <li><router-link to="/categories">Categories</router-link></li>
                        <li><router-link to="/job-type">Job types</router-link></li>
                    </ul>
                </li>
            </ul>
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>
</template>
<script>
    export default {
      methods:{
          username(){
              return User.name();
          }
      }
    }
</script>

<style scoped>

</style>
