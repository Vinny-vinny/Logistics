<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Datatable test</h3>
                </div>
                <div class="box-body">
      <vuetable ref="vuetable"
      api-url="https://vuetable.ratiw.net/api/users"
      :fields="fields"
      pagination-path=""
      @vuetable:pagination-data="onPaginationData"
    >
    <template slot="actions" slot-scope="props">
    <div class="table-button-container">
        <button class="ui button" @click="editRow(props.rowData)"><i class="fa fa-edit"></i> Edit</button>&nbsp;&nbsp;
        <button class="ui basic red button" @click="deleteRow(props.rowData)"><i class="fa fa-remove"></i> Delete</button>&nbsp;&nbsp;
    </div>
</template>
    </vuetable>
    <vuetable-pagination ref="pagination"
      @vuetable-pagination:change-page="onChangePage"
    ></vuetable-pagination>
                </div>
            </div>
        </section>

    </div>
</template>
<script>
import Vuetable from 'vuetable-2';
import VuetablePagination from 'vuetable-2'
    export default {
       
        data(){
            return {
                fields: ['name', 'email','birthdate','nickname','gender','__slot:actions']
                }
        },
       
        methods:{
      onPaginationData (paginationData) {
      this.$refs.pagination.setPaginationData(paginationData)
    },
    onChangePage (page) {
      this.$refs.vuetable.changePage(page)
    },
    editRow(rowData){
      alert("You clicked edit on"+ JSON.stringify(rowData))
    },
    deleteRow(rowData){
      alert("You clicked delete on"+ JSON.stringify(rowData))
    }
        },
         components:{
            Vuetable,
            //VuetablePagination
   'vuetable-pagination': Vuetable.VuetablePagination
      },
    }
</script>

<style scoped>

</style>
