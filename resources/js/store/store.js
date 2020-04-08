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
        req_data:{},
        all_charges:{},
        all_my_parts:{},
        all_my_customers:{},
        all_my_vehicles:{},
        all_my_uoms:{},
        all_my_pricelists:{},
        all_my_reqs:{},
        all_my_job_types:{},
        all_my_jobcards:{},
        all_my_charges:{},
        all_my_users:{},
        all_my_customer_types:{},
        all_my_fuels:{},
        all_my_projects:{},
        all_my_accounts:{},
        all_my_job_categories:{},
        all_my_service_types:{},
        all_my_tracks:{},
        all_my_categories:[],
        all_my_mechanics:{},
        get_users:{},
        all_my_stk_groups:{},
        all_my_expenses:{},
        p_lists:{},
        loading:false,
        pagination: {
            descending: true,
            page: 1,
            rowsPerPage: 5,
            sortBy: 'id',
            totalItems: 0,
            rowsPerPageItems: [1, 2, 4, 8, 16]
        },
        items:[]
    },

    mutations:{
     pathTo(state, to) {
     state.path_to = to;
     },
        _setItems (state, { items, totalItems }) {
            state.items = items
            Vue.set(state.pagination, 'totalItems', totalItems)
        },
        getAllUsers(state,data){
            state.get_users = data;
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
        },
        updateCharges(state,charge){
         state.all_charges = charge;
        },
        my_parts(state,data){
         state.all_my_parts = data;
        },
        my_customers(state,data){
            state.all_my_customers = data;
        },
        my_vehicles(state,data){
         state.all_my_vehicles = data;
        },
        my_uoms(state,data){
         state.all_my_uoms = data;
        },
        my_pricelists(state,data){
         state.all_my_pricelists = data;
        },
        my_reqs(state,data){
         state.all_my_reqs = data;
        },
        my_job_types(state,data){
         state.all_my_job_types = data;
        },
        my_jobcards(state,data){
         state.all_my_jobcards = data;
        },
        my_charges(state,data){
         state.all_my_charges = data;
        },
        my_users(state,data){
         state.all_my_users = data;
        },
        my_customer_types(state,data){
         state.all_my_customer_types = data;
        },
        my_fuels(state,data){
         state.all_my_fuels = data;
        },
        my_projects(state,data){
         state.all_my_projects = data;
        },
        my_accounts(state,data){
         state.all_my_accounts = data;
        },
        my_job_categories(state,data){
         state.all_my_job_categories = data;
        },
        my_service_types(state,data){
         state.all_my_service_types = data;
        },
        my_tracks(state,data){
         state.all_my_tracks = data;
        },
        my_categories(state,data){
         state.all_my_categories = data;
        },
        my_mechanics(state,data){
         state.all_my_mechanics = data;
        },
        my_stk_groups(state,data){
         state.all_my_stk_groups = data;
        },
        my_expenses(state,data){
         state.all_my_expenses = data;
        }
    },
    getters:{
        loading (state) {
            return state.loading
        },
        pagination (state) {
            return state.pagination
        },
        items (state) {
            return state.items
        },
      all_parts(state){
      return state.all_my_parts;
      },
      all_pricelists(state){
      return state.all_my_pricelists;
      },
        all_reqs(state){
        return state.all_my_reqs;
        },
        all_users(state){
        return state.all_my_users;
        },
        all_customers(state){
        return state.all_my_customers;
        },
        all_accounts(state){
        return state.all_my_accounts;
        },
        all_units(state){
        return state.all_my_uoms;
        },
        all_vehicles(state){
        return state.all_my_vehicles;
        },
        all_stk_groups(state){
        return state.all_my_stk_groups;
        },
        all_projects(state){
        return state.all_my_projects;
        },
        all_jobs(state){
            return state.all_my_jobcards;
        },
        all_categories(state){
            return state.all_my_categories;
        },
        all_mechanics(state){
            return state.all_my_mechanics;
        },
        all_tracks(state){
            return state.all_my_tracks;
        },
        all_customer_types(state){
            return state.all_my_customer_types;
        },
        all_service_types(state){
            return state.all_my_service_types;
        },
        all_job_categories(state){
            return state.all_my_job_categories;
        },
        all_job_types(state){
          return state.all_my_job_types;
        },
        all_fuels(state){
            return state.all_my_fuels;
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
        },
        updateCharges({commit},charge){
        commit('updateCharges',charge);
        },
        my_parts({commit}){
        axios.get('parts')
            .then(res => {
                commit('my_parts',res.data);
            })
        },
        my_customers({commit},data){
        axios.get('customers')
            .then(res => {
             commit('my_customers',res.data);
            })
        },
        my_vehicles({commit}){
            axios.get('machines')
                .then(res => {
                    commit('my_vehicles',res.data);
                })
        },
        my_uoms({commit}){
        axios.get('uom')
            .then(res => {
                commit('my_uoms',res.data);
            })
        },
        my_pricelists({commit}){
        axios.get('price-list')
            .then(res => {
                console.log('walla')
              commit('my_pricelists',res.data);
            }).catch(e => console.log(e))
        },
        my_reqs({commit}){
        axios.get('requisitions')
            .then(res => {
                commit('my_reqs',res.data);
            })
            .catch(error => Exception.handle(error))
        },
        my_job_types({commit}){
        axios.get('job-types')
            .then(res => {
                commit('my_job_types',res.data);
            })
            .catch(error => Exception.handle(error))
        },
        my_jobcards({commit}){
        axios.get('job-card')
            .then(res => {
                commit('my_jobcards',res.data);
            })
        },
        my_charges({commit},data){
        commit('my_charges',data);
        },
        my_users({commit}){
        axios.get('users')
            .then(res => {
             commit('my_users',res.data);
            })
        },
        my_customer_types({commit}){
        axios.get('customer-types')
            .then(res => {
                commit('my_customer_types',res.data);
            })
        },
        my_fuels({commit}){
        axios.get('fuel')
            .then(res => {
             commit('my_fuels',res.data);
            })
        },
        my_projects({commit}){
        axios.get('asset-category')
            .then(res => {
                commit('my_projects',res.data);
            })
        },
        my_accounts({commit},data){
        axios.get('accounts')
            .then(res => {
                commit('my_accounts',res.data);
            })
        },
        my_job_categories({commit}){
        axios.get('jobcard-category')
            .then(res => {
                commit('my_job_categories',res.data);
            })
            .catch(error => Exception.handle(error))
        },
        my_service_types({commit}){
        axios.get('service-types')
            .then(res => {
                commit('my_service_types',res.data);
            })
            .catch(error => Exception.handle(error))
        },
        my_tracks({commit}){
        axios.get('track-by')
            .then(res => {
                commit('my_tracks',res.data);
            })
            .catch(error => Exception.handle(error))
        },
        my_categories({commit}){
        axios.get('categories')
            .then(res => {
                commit('my_categories',res.data);
            })
            .catch(error => Exception.handle(error))
        },
        my_mechanics({commit}){
        axios.get('mechanics')
            .then(res => {
                commit('my_mechanics',res.data);
            })
            .catch(error => Exception.handle(error))
        },
        my_stk_groups({commit},data){
        axios.get('stk-groups')
            .then(res => {
                commit('my_stk_groups',res.data);
            })
        },
        my_expenses({commit},data){
        commit('my_expenses',data);
        }

    }
})
