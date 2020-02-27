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
               
                   <li class="treeview">
                  <a href="#"><i class="fa fa-shopping-basket angle-left"></i> <span>Requisitions</span>
                        <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><router-link to="/requisitions" class="fa fa-circle-o"> All</router-link></li>
                        <li><router-link to="/reversed-requisitions" class="fa fa-circle-o"> Reversed</router-link></li>

                        </ul>
                </li>                 
                  <li class="treeview">
                  <a href="#"><i class="fa fa-tag"></i> <span>Job Card</span>
                        <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><router-link to="/job-card" class="fa fa-circle-o"> All</router-link></li>
                        <li><router-link to="/reversed-jobcards" class="fa fa-circle-o"> Reversed</router-link></li>

                        </ul>
                </li>

                     <li class="treeview">
                    <router-link to="/checklists"><i class="fa fa-check-square-o"></i> <span>Checklists</span>
                        <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                    </router-link>
                    <ul class="treeview-menu">
                        <li><router-link to="/assign-checklist" class="fa fa-circle-o"> Assign Checklists</router-link></li>
                        </ul>
                </li>             

                <li class="treeview">
                  <a href="#"><i class="fa fa-database"></i> <span>Fuel</span>
                        <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><router-link to="/fuel" class="fa fa-circle-o"> All</router-link></li>
                        <li><router-link to="/reversed-fuel" class="fa fa-circle-o"> Reversed</router-link></li>

                        </ul>
                </li>
                <li class="treeview">
                    <a href="#"><i class="fa fa-bar-chart"></i> <span>Workshop Reports</span>
                        <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                    </a>
                    <ul class="treeview-menu">
                         <li><router-link to="/jobcard-report" class="fa fa-circle-o"> Stock Issue</router-link></li>
                        <li><router-link to="/fuel-report" class="fa fa-circle-o"> Daily Fuel Issue</router-link></li>
                        <li><router-link to="/km-per-hr" class="fa fa-circle-o"> KM Per Hr</router-link></li>
                        <li><router-link to="/diesel-analysis" class="fa fa-circle-o"> Diesel Analysis</router-link></li>
                         <li><router-link to="/dpt-consumption" class="fa fa-circle-o"> Department Consumption%</router-link></li>
                         <li><router-link to="/partners" class="fa fa-circle-o"> Partner Reports</router-link></li>
                        </ul>
                </li>
                <li class="treeview">
                    <a href="#"><i class="fa fa-cogs"></i> <span>Settings</span>
                        <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><router-link to="/users" class="fa fa-circle-o"> Users</router-link></li> 
                        <li style="display:none"><router-link to="/fuel-types" class="fa fa-circle-o"> Fuel Types</router-link></li>
                        <li><router-link to="/track-by" class="fa fa-circle-o"> Track By</router-link></li>
                        <li><router-link to="/service-types" class="fa fa-circle-o"> Service Types</router-link></li>
                        <li><router-link to="/expense" class="fa fa-circle-o"> Other Charges</router-link></li>
                        <li><router-link to="/parts" class="fa fa-circle-o"> Parts</router-link></li>
                        <li><router-link to="/accounts" class="fa fa-circle-o"> Ledgers</router-link></li>
                        <li><router-link to="/uoms" class="fa fa-circle-o"> Uoms</router-link></li>
                        <li><router-link to="/projects" style="display:none"> Projects</router-link></li>
                        <li><router-link to="/customers" class="fa fa-circle-o"> Customers</router-link></li>
                         <li><router-link to="/price-list" class="fa fa-circle-o"> Price Lists</router-link></li>
                        <li><router-link to="/mechanics" class="fa fa-circle-o"> Mechanics</router-link></li>
                        <li><router-link to="/categories" class="fa fa-circle-o"> Categories</router-link></li>
                        <li><router-link to="/job-type" class="fa fa-circle-o"> Job types</router-link></li>
                        <li><router-link to="/jobcard-category" class="fa fa-circle-o"> Jobcard Categories</router-link></li>
                          <li style="display:none"><router-link to="/fuel-category" class="fa fa-circle-o"> Fuel Categories</router-link></li>
                        <li><router-link to="/checklist-tool" class="fa fa-circle-o"> Checklist Tools</router-link></li>
                        <li><router-link to="/where-to-charge" class="fa fa-circle-o"> Where To Charge</router-link></li>
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
