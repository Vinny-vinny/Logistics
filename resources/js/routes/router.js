import Vue from 'vue';
import VueRouter from "vue-router";
import Login from "../components/auth/Login";
import Register from "../components/auth/Register";
import ServiceTypes from "../components/maintenance/servicetypes/Index";
import TrackBy from "../components/maintenance/trackby/Index";
import FuelBalance from "../components/maintenance/fuelbalance/Index";
import Machines from "../components/maintenance/machines/Index";
import JobCard from "../components/maintenance/jobcard/Index";
import Fuel from "../components/fuels/Index";
import Expense from "../components/expenses/Index";
import Checklist from "../components/checklists/Index";
import AssignChecklist from "../components/checklists/assign/Index";
import AppShow from "../components/maintenance/jobcard/AppShow";
import ExternalService from "../components/maintenance/jobcard/externalservice/Index";
import JobcardReport from "../components/reports/jobs/JobcardReport";
import FuelReport from "../components/reports/fuels/FuelReport";
import Parts from "../components/parts/Index";
import Category from "../components/categories/Index";
import JobCategory from "../components/maintenance/jobcard/categories/Index";
import ShowFuel from "../components/fuels/ShowFuel";
import Users from "../components/users/Index";
import projects from "../components/projects/Index";
import customers from "../components/customers/Index";
import Mechanics from "../components/mechanics/Index";
import JobType from "../components/maintenance/jobtypes/Index";
import Types from "../components/fuels/types/Index";
import Requisition from "../components/requisitions/Index";
import JobCardForm from "../components/maintenance/jobcard/JobCardForm";
import ShowRequisition from "../components/requisitions/ShowRequisition";

Vue.use(VueRouter);
function guard(to, from, next) {
    if (User.loggedIn()) {
        next();
    } else {
        next('/login');
    }
}
const routes = [
    {path:'/login',component: Login},
    {path:'/signup',component: Register},
    {path:'/users',component: Users, beforeEnter: guard},
    {path:'/service-types',component: ServiceTypes, beforeEnter: guard},
    {path:'/track-by',component: TrackBy, beforeEnter: guard},
    {path:'/fuel-balance',component: FuelBalance, beforeEnter: guard},
    {path:'/machines',component: Machines, beforeEnter: guard},
    {path:'/job-card',component:JobCard, beforeEnter: guard},
    {path:'/fuel/:id',component:ShowFuel, beforeEnter: guard},
    {path:'/fuel',component:Fuel, beforeEnter: guard},
    {path:'/expense',component:Expense, beforeEnter: guard},
    {path:'/checklists',component:Checklist, beforeEnter: guard},
    {path:'/assign-checklist',component:AssignChecklist, beforeEnter: guard},
    {path:'/job-card/:id',component:AppShow, beforeEnter: guard},
    {path:'/external-service',component:ExternalService, beforeEnter: guard},
    {path:'/jobcard-report',component:JobcardReport, beforeEnter: guard},
    {path:'/fuel-report',component:FuelReport, beforeEnter: guard},
    {path:'/parts',component:Parts, beforeEnter: guard},
    {path:'/categories',component:Category, beforeEnter: guard},
    {path:'/projects',component:projects, beforeEnter: guard},
    {path:'/customers',component:customers, beforeEnter: guard},
    {path:'/mechanics',component:Mechanics, beforeEnter: guard},
    {path:'/job-type',component:JobType, beforeEnter: guard},
    {path:'/fuel-types',component:Types, beforeEnter: guard},
    {path:'/jobcard-category',component:JobCategory, beforeEnter: guard},
    {path:'/requisitions',component:Requisition, beforeEnter: guard},
    {path:'/jobcard-form/:id',component:JobCardForm, beforeEnter: guard},
    {path:'/requisition/:id',component:ShowRequisition, beforeEnter: guard}
];

export default new VueRouter({
    routes,
    mode: 'history'
});
