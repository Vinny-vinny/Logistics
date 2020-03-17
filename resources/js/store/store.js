import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    state:{
        path_to:'/',
        service_types:{},
        track_by:{},
        fuel_balance:{},
        provider_type:{},
        supplier:{},
        machine:{},
        job_card:{},
        expense:{},
        fuels:{},
        fuel_supplier:{},
        checklists:{},
        assign_checklists:{},
        external_services:{},
        jobs:{},
        fuel_reports:{},
        parts:{},
        categories:{},
        mechanics:{},
        users:{},
        job_type:{},
        customers:{},
        jobcard_categories:{},
        fuel_types:{},
        requisitions:{},
        checklist_tool:{},
        km_hrs:{},
        diesels:{},
        department_consumptions:{},
        partners:{},
        dates:{},
        fuel_categories:{},
        charges:{},
        req_data:{}
    },
    mutations:{
     pathTo(state, to) {
     state.path_to = to;
     },
        updateServiceType(state,service){
         state.service_types = service;
        },
        updateTrack(state,track){
         state.track_by = track;
        },
        updateFuelBalance(state,fuel){
         state.fuel_balance = fuel;
        },
        updateProviderType(state,provider){
         state.provider_type = provider;
        },
        updateSupplier(state,supplier){
         state.supplier = supplier;
        },
        updateMachine(state,machine){
         state.machine = machine;
        },
        updateJobcard(state,job){
         state.job_card = job;
        },
        updateExpense(state,expense){
         state.expense = expense;
        },
        updateFuel(state,fuel){
         state.fuels = fuel;
        },
        updateFuelSupplier(state,supplier){
         state.fuel_supplier = supplier;
        },
        updateChecklist(state,checklist){
         state.checklists = checklist;
        },
        updateAssignChecklist(state,assign){
         state.assign_checklists = assign;
        },
        updateExternalServices(state,service){
         state.external_services = service;
        },
        listJobReports(state,jobs){
         state.jobs = jobs;
        },
        listFuelReports(state,fuel){
         state.fuel_reports = fuel;
        },
        updatePart(state,part){
         state.parts = part;
        },
        updateCategory(state,category){
         state.categories = category;
        },
        updateUser(state,user){
         state.users = user;
        },
        updateMechanic(state,mechanic){
         state.mechanics = mechanic;
        },
        updateJobType(state,job_type){
         state.job_type = job_type;
        },
        updateCustomer(state,customer){
         state.customers = customer;
        },
        updateJobcardCategory(state,category){
         state.jobcard_categories = category;
        },
        updateFuelType(state,type){
         state.fuel_types = type;
        },
        updateRequisition(state,rq){
         state.requisitions = rq;
        },
        updateTool(state,tool){
         state.checklist_tool = tool;
        },
        listKMHRReports(state,km){
         state.km_hrs = km;
        },
        listDieselReports(state,diesel){
         state.diesels = diesel;
        },
        listconsumptionReports(state,dept){
         state.department_consumptions = dept;
        },
        listPartnersReports(state,partner){
         state.partners = partner;
        },
        getPeriod(state,dates){
        state.dates = dates
        },
        updateFuelCategory(state,category){
        state.fuel_categories = category;
        },
        updateCharge(state,charge){
            state.charges = charge;
        },
        reqFormData(state,data){
         state.req_data = data;
        }
    },
    actions:{
    pathTo({commit},to){
     commit('pathTo',to);
    },
        updateServiceType({commit},service){
        commit('updateServiceType',service);
        },
        updateTrack({commit},track){
        commit('updateTrack',track);
        },
        updateFuelBalance({commit},fuel){
        commit('updateFuelBalance',fuel);
        },
        updateProviderType({commit},provider){
        commit('updateProviderType',provider);
        },
        updateSupplier({commit},supplier){
        commit('updateSupplier',supplier);
        },
        updateMachine({commit},machine){
        commit('updateMachine',machine);
        },
        updateJobcard({commit},job){
        commit('updateJobcard',job);
        },
        updateExpense({commit},expense){
        commit('updateExpense',expense);
        },
        updateFuel({commit},fuel){
        commit('updateFuel',fuel);
        },
        updateFuelSupplier({commit},supplier){
        commit('updateFuelSupplier',supplier);
        },
        updateChecklist({commit},checklist){
        commit('updateChecklist',checklist);
        },
        updateAssignChecklist({commit},assign){
        commit('updateAssignChecklist',assign);
        },
        updateExternalServices({commit},service){
        commit('updateExternalServices',service);
        },
        listJobReports({commit},jobs){
        commit('listJobReports',jobs);
        },
        listFuelReports({commit},fuel){
        commit('listFuelReports',fuel);
        },
        updatePart({commit},part){
        commit('updatePart',part);
        },
        updateCategory({commit},category){
        commit('updateCategory',category);
        },
        updateUser({commit},user){
        commit('updateUser',user);
        },
        updateMechanic({commit},mechanic){
        commit('updateMechanic',mechanic);
        },
        updateJobType({commit},job_type){
        commit('updateJobType',job_type);
        },
        updateCustomer({commit},customer){
        commit('updateCustomer',customer);
        },
        updateJobcardCategory({commit},category){
        commit('updateJobcardCategory',category);
        },
        updateFuelType({commit},type){
        commit('updateFuelType',type);
        },
        updateRequisition({commit},rq){
        commit('updateRequisition',rq);
        },
        updateTool({commit},tool){
        commit('updateTool',tool);
        },
        listKMHRReports({commit},km){
        commit('listKMHRReports',km);
        },
        listDieselReports({commit},diesel){
         commit('listDieselReports',diesel);
        },
        listconsumptionReports({commit},dept){
          commit('listconsumptionReports',dept);
        },
        listPartnersReports({commit},partner){
         commit('listPartnersReports',partner);
        },
        getPeriod({commit},dates){
        commit('getPeriod',dates);
        },
        updateFuelCategory({commit},category){
            commit('updateFuelCategory',category);
        },
        updateCharge({commit},charge){
            commit('updateCharge',charge);
        },
        reqFormData({commit},data){
        commit('reqFormData',data);
        }
    }
})
